<?php

/**
 * PagSeguro Module Adapter
 */
class PagSeguro_Model_PagSeguro extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->db = new Varien_Data_Collection_Db();
        $this->db->__construct(Mage::getSingleton('core/resource')->getConnection('core_write'));
        $this->_init('pagseguro/pagseguro');
        $this->objPDO = $this->db->getSelect()->getAdapter();
    }

    /* O nÃºmero de Token gerado no painel de administraÃ§Ã£o no PagSeguro */

    public function getToken() {
        return $this->getConfigData('token');
    }

    /* recupera o email que esta sendo utilizado para acessar o pagseguro */

    public function getEmail() {
        return $this->getConfigData('emailID');
    }

    public function getDadosCartao() {
        return $this->getData('cartao_credito');
    }

    /*  MÃ‰TODOS DO MODULO DO MAURO */

    private $timeout = 20; // Timeout em segundos

    public function notificationPost($token, $post) {
        $postdata = 'Comando=validar&Token=' . $token;
        foreach ($post as $key => $value) {
            $valued = $this->clearStr($value);
            $postdata .= "&$key=$valued";
        }
        return $this->verify($postdata);
    }

    private function clearStr($str) {
        if (!get_magic_quotes_gpc()) {
            $str = addslashes($str);
        }
        return $str;
    }

    private function verify($data) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://pagseguro.uol.com.br/pagseguro-ws/checkout/NPI.jhtml");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = trim(curl_exec($curl));
        curl_close($curl);
        return $result;
    }

    /*  MÃ‰TODOS DO MODULO DO MAURO */

    public function verificaStaus($data) {
        // 2012-01-24 01:17:38

        $str = strpos($data, ' ');
        $dataformatada = substr($data, 0, $str);

        $nextdate = $this->addDayIntoDate($dataformatada, 3);    // Adiciona 3 dias
        $dia = strtotime($nextdate);
        $hj = strtotime(date("Ymd"));

        if ($dia > $hj) {
            $nextdate = date("Ymd");
            $hora = date("H:i");
            $hora_anterior = mktime(date("H") - 2, date("i") - 40, date("s"), date("m"), date("d"), date("Y"));
            $hora = date("H:i", $hora_anterior);
        }

        $backdate = $this->subDayIntoDate($dataformatada, 3);    // Subtrair 3 dias

        $thisyear = substr($nextdate, 0, 4);
        $thismonth = substr($nextdate, 4, 2);
        $thisday = substr($nextdate, 6, 2);

        /* BACKUP - 13/01/2012 
          if($hora){
          $datafinal = $thisyear ."-".$thismonth."-".$thisday. "T" .$hora;
          }else{
          $datafinal = $thisyear ."-".$thismonth."-".$thisday. "T00:00";
          }
         */

        if (isset($hora)) {
            $datafinal = $thisyear . "-" . $thismonth . "-" . $thisday . "T" . $hora;
        } else {
            $datafinal = $thisyear . "-" . $thismonth . "-" . $thisday . "T00:00";
        }

        $thisyear = substr($backdate, 0, 4);
        $thismonth = substr($backdate, 4, 2);
        $thisday = substr($backdate, 6, 2);

        $datainicial = $thisyear . "-" . $thismonth . "-" . $thisday . "T00:00";

        $retorno = file_get_contents("http://ws.pagseguro.uol.com.br/v2/transactions/abandoned?initialDate=" . $datainicial . "&finalDate=" . $datafinal . "&page=1&maxPageResults=100&email=comercial@mulherelastica.com.br&token=CA509954ACBA43539D736C07AA7980B3");
        $retornoXml = simplexml_load_string($retorno);

        $pedidos = array();
        if ($retornoXml->resultsInThisPage > 0) {
            foreach ($retornoXml->transactions->transaction as $a) {
                $pedidos [] = (string) $a->reference;
            }
        }

        return $pedidos;
    }

    public function addDayIntoDate($date, $days) {
        $thisyear = substr($date, 0, 4);
        $thismonth = substr($date, 5, 2);
        $thisday = substr($date, 8, 2);
        $nextdate = mktime(0, 0, 0, $thismonth, $thisday + $days, $thisyear);
        return strftime("%Y%m%d", $nextdate);
    }

    public function subDayIntoDate($date, $days) {
        $thisyear = substr($date, 0, 4);
        $thismonth = substr($date, 5, 2);
        $thisday = substr($date, 8, 2);
        ;
        $nextdate = mktime(0, 0, 0, $thismonth, $thisday - $days, $thisyear);
        return strftime("%Y%m%d", $nextdate);
    }

    public function recuperaRegion($id) {
        $this->objPDO->beginTransaction();

        $sql = $this->objPDO->prepare("SELECT code
                                        FROM `directory_country_region`
                                        WHERE `region_id` = ?");

        $sql->bindValue(1, $id, PDO::PARAM_INT);
        $sql->execute();

        if ($sql->rowCount()) {
            $this->objPDO->commit();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->objPDO->rollBack();
            return array();
        }
    }

//    function httprequest($paEndereco, $paPost) {
// 
//        $sessao_curl = curl_init();
//        curl_setopt($sessao_curl, CURLOPT_URL, $paEndereco);
//        curl_setopt($sessao_curl, CURLOPT_FAILONERROR, true);
//
//        //  CURLOPT_SSL_VERIFYPEER
//        //  verifica a validade do certificado
//        curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYPEER, false);
//
//        //  CURLOPPT_SSL_VERIFYHOST
//        //  verifica se a identidade do servidor bate com aquela informada no certificado
//        curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYHOST, 2);
//
//        //  CURLOPT_SSL_CAINFO
//        //  informa a localizaÃ§Ã£o do certificado para verificaÃ§Ã£o com o peer
//        curl_setopt($sessao_curl, CURLOPT_CAINFO, getcwd() . "/ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.crt");
//        curl_setopt($sessao_curl, CURLOPT_SSLVERSION, 3);
//
//        //  CURLOPT_CONNECTTIMEOUT
//        //  o tempo em segundos de espera para obter uma conexÃ£o
//        curl_setopt($sessao_curl, CURLOPT_CONNECTTIMEOUT, 10);
//
//        //  CURLOPT_TIMEOUT
//        //  o tempo mÃ¡ximo em segundos de espera para a execuÃ§Ã£o da requisiÃ§Ã£o (curl_exec)
//        curl_setopt($sessao_curl, CURLOPT_TIMEOUT, 40);
//
//        //  CURLOPT_RETURNTRANSFER
//        //  TRUE para curl_exec retornar uma string de resultado em caso de sucesso, ao
//        //  invÃ©s de imprimir o resultado na tela. Retorna FALSE se hÃ¡ problemas na requisiÃ§Ã£o
//        curl_setopt($sessao_curl, CURLOPT_RETURNTRANSFER, true);
//
//        curl_setopt($sessao_curl, CURLOPT_POST, true);
//        curl_setopt($sessao_curl, CURLOPT_POSTFIELDS, $paPost);
//
//        $resultado = curl_exec($sessao_curl);
//        
//        curl_close($sessao_curl);
//
//        if ($resultado) {
//            return $resultado;
//        } else {
//            return curl_error($sessao_curl);
//        }
//    }
}

?>
