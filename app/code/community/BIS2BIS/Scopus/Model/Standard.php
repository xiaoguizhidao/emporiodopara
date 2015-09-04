<?php

class BIS2BIS_Scopus_Model_Standard extends Mage_Payment_Model_Method_Abstract
{

    protected $_code = 'scopus';
    protected $_isGateway               = true;
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = false;
    protected $_canVoid                 = true;
    protected $_canUseInternal          = true;
    protected $_canUseCheckout          = true;
	protected $_formBlockType = 'scopus/standard_form';
	protected $_infoBlockType = 'scopus/standard_info';
    protected $_allowCurrencyCode = array('BRL');
	
	public function __construct(){	
		// Verificando se o ambiente de produção, caso seja de teste verifica se o usuário logado é o mesmo cadastrado no admin para liberar o método de pagamento
		$ambiente = Mage::getStoreConfig('payment/scopus/ambiente');
		$email = Mage::getStoreConfig('payment/scopus/emailteste');
		
		if($ambiente == 'liberado'){
			$this->_canUseCheckout = true;
		} elseif(($ambiente == 'teste' || $ambiente == 'producao') && Mage::getModel('customer/session')->getCustomer()->getEmail() != $email){
			$this->_canUseCheckout = false;
		}
		
    }
	
	public function getUrlScopus($ambiente, $tipo){
		if($tipo == 'boleto'){
			$url = 	array(
						'teste' => 'http://mupteste.comercioeletronico.com.br/paymethods/boleto/model5/prepara_pagto.asp?',
						'producao' => 'http://mup.comercioeletronico.com.br/paymethods/boleto/model5/prepara_pagto.asp?',
						'liberado' => 'http://mup.comercioeletronico.com.br/paymethods/boleto/model5/prepara_pagto.asp?'
					);
		} elseif($tipo == 'transferencia'){
			$url = 	array(
						'teste' => 'http://mupteste.comercioeletronico.com.br/paymethods/transfer/model1/prepara_pagto.asp?',
						'producao' => 'http://mup.comercioeletronico.com.br/paymethods/transfer/model1/prepara_pagto.asp?',
						'liberado' => 'http://mup.comercioeletronico.com.br/paymethods/transfer/model1/prepara_pagto.asp?'
					);
		}
					
		return $url[$ambiente];
	}
	
	// Pega a sigla do estado de acordo com o ID
    public function getEstados($region_id){
        
		$estados = array(
						485 => 'AC',
						486 => 'AL',
						487 => 'AP',
						488 => 'AM',
						489 => 'BA',
						490 => 'CE',
						491 => 'ES',
						492 => 'GO',
						493 => 'MA',
						494 => 'MT',
						495 => 'MS',
						496 => 'MG',
						497 => 'PA',
						498 => 'PB',
						499 => 'PR',
						500 => 'PE',
						501 => 'PI',
						502 => 'RJ',
						503 => 'RN',
						504 => 'RS',
						505 => 'RO',
						506 => 'RR',
						507 => 'SC',
						508 => 'SP',
						509 => 'SE',
						510 => 'TO',
						511 => 'DF',
						);

		return $estados[$region_id];
    }
	
    // Cria as opções de pagamento como Boleto, Tranferencia, etc - No momento o módulo esta utilizando somente Boleto.
    public function toOptionArray(){
        $opcoes = array();
        $retorno = array();
        $opcoes[] = array('boleto' => 'Boleto Bancário');
		$opcoes[] = array('transferencia' => 'Transferência Online');
        
        for($i=0; $i<sizeof($opcoes); $i++){
            foreach($opcoes[$i] AS $chave => $valor)
                $retorno[] = array('value' => $chave, 'label' => $valor);
        }
        
        return $retorno;
    }


}