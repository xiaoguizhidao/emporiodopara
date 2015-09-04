<?php
class BIS2BIS_Deposito_Block_Info_Deposito extends Mage_Payment_Block_Info
{
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $info = $this->getInfo();
        $transport = new Varien_Object();
        $transport = parent::_prepareSpecificInformation($transport);

        switch($info->getDeposito()){
            case 'Banco Itaú':
                $codeposito = "itau";
                break;
            case 'Banco do Brasil':
                $codeposito = "bb";
                break;
            case 'Banco Santander':
                $codeposito = "sant";
                break;
            case 'Bradesco':
                $codeposito = "brad";
                break;
            case 'Caixa Econômica':
                $codeposito = "caix";
                break;
            case 'HSBC Bank Brasil':
                $codeposito = "hsbc";
                break;
            case 'Sicredi':
                $codeposito = "sicr";
                break;
            default:
                $codeposito = "";
                break;
        };

        $transport->addData(array(
            Mage::helper('payment')->__('Banco Selecionado') => $info->getDeposito()
        ));

        echo $info->getDeposito();

        if($codeposito != ""){
            $transport->addData(array(
                Mage::helper('payment')->__('Informações') => Mage::getStoreConfig('payment/deposito/'.$codeposito.'_bancos')
            ));
        }else{
            $transport->addData(array(
                Mage::helper('payment')->__('Informações') => "Não foi possível recuperar a instância do pagamento"
            ));
        }

        $transport->addData(array(
            Mage::helper('payment')->__('Aviso') => $htmlInfo
        ));

        return $transport;
    }
        public function toPdf()
    {
        $this->setTemplate('deposito/infopdf.phtml');
        return $this->toHtml();
    }
}