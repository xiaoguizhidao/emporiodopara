<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to suporte.developer@buscape-inc.com so we can send you a copy immediately.
 *
 * @category   Buscape
 * @package    Buscape_Fcontrol
 * @copyright  Copyright (c) 2010 BuscapÃ© Company (http://www.buscapecompany.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Buscape_Fcontrol_Model_Api extends Buscape_Fcontrol_Model_Api_Abstract
{
    
    protected $_urlFrame = 'https://secure.fcontrol.com.br/validatorframe/validatorframe.aspx';
    
    
   
    
    public function analisaFrame()
    {
    	function retira_acentos($texto){
    		return strtr($texto, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ", "aaaaeeiooouucAAAAEEIOOOUUC");
    	}
    	
     	$url = $this->_urlFrame;
        
        $uf = retira_acentos($this->compradorEstado);
               
        $uf = strtolower($uf);
        
        $uf = str_replace(' ', '_', $uf);
        
        $_state_sigla = array(
        		'acre' => 'AC',
        		'alagoas' => 'AL',
        		'amapa' => 'AP',
        		'amazonas' => 'AM',
        		'bahia' => 'BA',
        		'ceara' => 'CE',
        		'distrito_federal' => 'DF',
        		'espirito_santo' => 'ES',
        		'goias' => 'GO',
        		'maranhao' => 'MA',
        		'mato_grosso' => 'MT',
        		'mato_grosso_do_sul' => 'MS',
        		'minas_gerais' => 'MG',
        		'para' => 'PA',
        		'paraiba' => 'PB',
        		'parana' => 'PR',
        		'pernambuco' => 'PE',
        		'piaui' => 'PI',
        		'rio_de_janeiro' => 'RJ',
        		'rio_grande_do_norte' => 'RN',
        		'rio_grande_do_sul' => 'RS',
        		'rondonia' => 'RO',
        		'roraima' => 'RR',
        		'santa_catarina' => 'SC',
        		'sao_paulo' => 'SP',
        		'sergipe' => 'SE',
        		'tocatins' => 'TO'
        );
        
        $uf= $_state_sigla[$uf];
       
        
        $url .= '?login=' . $this->getUser();
        $url .= '&senha=' . $this->getPassword();
        $url .= '&nomeComprador=' . $this->compradorNome;
        $url .= '&ruaComprador=' . $this->compradorRua;
        $url .= '&numeroComprador=' . $this->compradorNumero;
		$url .= '&bairroComprador=' . $this->compradorBairro; //Atualizacao
		$url .= '&complementoComprador='. $this->compradorComplemento; //Atualizacao
        $url .= '&cidadeComprador=' . $this->compradorCidade;
        $url .= '&ufComprador=' . $uf;
        $url .= '&paisComprador=' . $this->compradorPais;
        $url .= '&cepComprador=' . $this->compradorCep;
        $url .= '&dddComprador=' . $this->compradorDddTelefone1;
        $url .= '&telefoneComprador=' . $this->compradorTelefone1;
        $url .= '&dddCelularComprador=' . $this->compradorDddCelular; // Atualizacao
        $url .= '&celularComprador=' . $this->compradorCelular; // Atualizacao 
		$url .= '&dddComprador2=' . $this->compradorDddCelular; // Atualizacao
        $url .= '&telefoneComprador2=' . $this->compradorCelular; // Atualizacao
        $url .= '&cpfComprador=' . $this->compradorCpfCnpj;
        $url .= '&emailComprador=' . $this->compradorEmail;
        $url .= '&nomeEntrega=' . $this->entregaNome;
        $url .= '&ruaEntrega=' . $this->entregaRua;
        $url .= '&numeroEntrega=' . $this->entregaNumero;
        $url .= '&cidadeEntrega=' . $this->entregaCidade;
		$url .= '&complementoEntrega=' . $this->entregaComplemento; // Atualizacao
		$url .= '&bairroEntrega=' . $this->entregaBairro; // Atualizacao
        $url .= '&ufEntrega=' . $uf;
        $url .= '&paisEntrega=' . $this->entregaPais;
        $url .= '&cepEntrega=' . $this->entregaCep;
        $url .= '&dddEntrega=' . $this->entregaDddTelefone1;
        $url .= '&telefoneEntrega=' . $this->entregaTelefone1;
        $url .= '&dddCelularEntrega=' . $this->entregaDddCelular; // Atualizacao
        $url .= '&celularEntrega=' . $this->entregaCelular; // Atualizacao
        $url .= '&dddEntrega2=' .  $this->entregaDddCelular; // Atualizacao
        $url .= '&telefoneEntrega2=' . $this->entregaCelular; // Atualizacao
        $url .= '&ip=' . $this->compradorIp;				// Atualizacao
        $url .= '&codigoPedido=' . $this->codigoPedido;
        $url .= '&quantidadeItensDistintos=' . $this->itensDistintos;
        $url .= '&quantidadeTotalItens=' . $this->itensTotal;
        $url .= '&valorTotalCompra=' . ($this->valorTotalCompra * 100);
        $url .= '&dataCompra=' . $this->dataCompra;
        $url .= '&metodoPagamentos=' . $this->metodoPagamento;
        $url .= '&numeroParcelasPagamentos=' . $this->numeroParcelas;
        $url .= '&valorPagamentos=' . ($this->valorPedido * 100);

        		
		$url = retira_acentos($url);
		
        
       /* $datetime = new Datetime();
        
        $datetime->setTimezone('America/Sao_Paulo');
        
        $name = $datetime->format("Y-m-d");
        
        $data = var_export(parse_url($url), true);
        
        Mage::log($datetime->format("h:m:s").'-'.$data."\n--------------------------------------------------", null, $name.'-fcontrol.log', true);
       */
        echo '<iframe height="115" frameborder="0" width="300" src="'. $url .'"></iframe>';    	
    }
    
    /* Proposta */
    public function analisaFrame2()
    {
        try {
            $client = new Zend_Http_Client();
            
            $url = $this->_urlFrame;
            
            $client->setUri($this->_urlFrame); // verify if exist problem with https, if exist please change for http
            
            $client->resetParameters();
            
            $client->setParameterGet('login', $this->getUser());
            $client->setParameterGet('senha', $this->getPassword());
            $client->setParameterGet('nomeComprador', $this->compradorNome);
            $client->setParameterGet('ruaComprador', $this->compradorRua);
            $client->setParameterGet('numeroComprador', $this->compradorNumero);
            $client->setParameterGet('cidadeComprador', $this->compradorCidade);
            $client->setParameterGet('ufComprador', $this->compradorEstado);
            $client->setParameterGet('paisComprador', $this->compradorPais);
            $client->setParameterGet('cepComprador', $this->compradorCep);
            $client->setParameterGet('cpfComprador', $this->compradorCpfCnpj);
            $client->setParameterGet('emailComprador', $this->compradorEmail);
            $client->setParameterGet('nomeEntrega', $this->entregaNome);
            $client->setParameterGet('ruaEntrega', $this->entregaRua);
            $client->setParameterGet('numeroEntrega', $this->entregaNumero);
            $client->setParameterGet('cidadeEntrega', $this->entregaCidade);
            $client->setParameterGet('ufEntrega', $this->entregaEstado);
            $client->setParameterGet('paisEntrega', $this->entregaPais);
            $client->setParameterGet('cepEntrega', $this->entregaCep);
            $client->setParameterGet('dddEntrega', $this->entregaDddTelefone1);
            $client->setParameterGet('telefoneEntrega', $this->entregaTelefone1);
            $client->setParameterGet('codigoPedido', $this->codigoPedido);
            $client->setParameterGet('quantidadeItensDistintos', $this->itensDistintos);
            $client->setParameterGet('quantidadeTotalItens', $this->itensTotal);
            $client->setParameterGet('valorTotalCompra', ($this->valorTotalCompra * 100));
            $client->setParameterGet('dataCompra', $this->dataCompra);
            $client->setParameterGet('metodoPagamentos', $this->metodoPagamento);
            $client->setParameterGet('numeroParcelasPagamentos', $this->numeroParcelas);
            $client->setParameterGet('valorPagamentos', ($this->valorPedido * 100));
            
            $content = $client->request();
            
            $body = $content->getBody();
           
            $images = array();
            
            preg_match_all('/(img|src)\=(\"|\')[^\"\'\>]+/i', $body, $media);
            
            preg_match_all('/BACKGROUND-IMAGE:[\s]*url\(.*.gif\);/', $body, $background_images);
            
            $data_images = preg_replace('/(img|src)(\"|\'|\=\"|\=\')(.*)/i', "$3", $media[0]);
            
            // images
            foreach($data_images as $url)
            {
                $body = preg_replace("~$url~", "https://secure.fcontrol.com.br/validatorframe/{$url}", $body);
            }
            
            foreach($background_images[0] as $background)
            {
                $background = str_replace("BACKGROUND-IMAGE: url(", "", $background);
                $background = str_replace(");", "", $background);
                $body = preg_replace("~$background~", "https://secure.fcontrol.com.br/validatorframe/{$background}", $body);
            }
            
            echo $body;
            
        } catch(Exception $e) {
            return;
        }
    }
}