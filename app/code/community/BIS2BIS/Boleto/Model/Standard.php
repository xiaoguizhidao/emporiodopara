<?php

	class BIS2BIS_Boleto_Model_Standard extends Mage_Payment_Model_Method_Abstract {

		protected $_code = 'boleto_bancario';
		protected $_canUseCheckout = true;
		protected $_canUseInternal = true;
		protected $_formBlockType = 'boleto/standard_form';
		protected $_infoBlockType = 'boleto/standard_info';

		private $_dataBoleto = array();
	    
	    public function __construct(){    
	        $emHomologacao = $this->getConfig('emhomologacao');
	        $emailHomolagacao = $this->getConfig('emailhomolagacao');
	        if($emHomologacao){
	            $this->_canUseCheckout = true;
	        }elseif($emHomologacao && Mage::getModel('customer/session')->getCustomer()->getEmail() != $emailHomolagacao){
	            $this->_canUseCheckout = false;
	        }
	    }

		public function prepareValues(){
			$order = Mage::registry('current_order');

			// Dados basicos
			$this->setDataBasicos($order);
			
			// Dados opcionais
			$this->setDataOpcionais();

			// Dados personalizados Santander
			$this->setDataSantander();

			// Dados personalizados Bradesco
			$this->setDataBradesco();

			// Dados personalizados Caixa Economica Federal
			$this->setDataCEF();

			// Dados personalizados Caixa Economica Federal Sinco
			$this->setDataCEFSinco();

			// Dados personalizados Caixa Economica Federal Sigcb
			$this->setDataCEFSigcb($order);

			// Dados personalizados Banco do Brasil
			$this->setDataBB();

			// Dados personalizados HSBC
			$this->setDataHSBC();

			// Dados de instrucoes
			$this->setDataInstrucoes();

			// Dados extras
			$this->setDataExtras($order);

			return $this->_prepareValues($order, $this->getDataBoleto());
		}

		private function setDataBasicos($_order){
			$billingAddress = $_order->getBillingAddress();
			$numeroBoleto = str_replace('-', '', $_order->getIncrementId());
			$strtotime = strtotime($_order->getCreatedAt());
			$this->setDataBoleto('logoempresa', $this->getConfig('logoempresa'));
			$this->setDataBoleto('nosso_numero', $numeroBoleto);
			$this->setDataBoleto('numero_documento', $numeroBoleto);
			if($_order->getBoletoVencimento()){
				$this->setDataBoleto('data_vencimento', $_order->getBoletoVencimento());
			}else{
				$this->setDataBoleto('data_vencimento', date('d/m/Y', $strtotime + ($this->getConfig('vencimento') * 86400)));
			}
			$this->setDataBoleto('data_documento', date('d/m/Y', $strtotime));
			$this->setDataBoleto('data_processamento', date('d/m/Y', $strtotime));
			$this->setDataBoleto('valor_boleto', number_format($_order->getGrandTotal() + $this->getConfig('valor_adicional'), 2, ',', ''));
			$this->setDataBoleto('valor_unitario', number_format($_order->getGrandTotal() + $this->getConfig('valor_adicional'), 2, ',', ''));
			$this->setDataBoleto('sacado', $billingAddress->getFirstname() . ' ' . $billingAddress->getLastname());
			$this->setDataBoleto('sacadocpf', $_order->getCustomerTaxvat());
			$this->setDataBoleto('endereco1', implode(' ', $billingAddress->getStreet()));
			$this->setDataBoleto('endereco2', $billingAddress->getCity() . ' - ' . $billingAddress->getRegion() . ' - CEP: ' . $billingAddress->getPostcode());
			$this->setDataBoleto('identificacao', $this->getConfig('identificacao'));
			$this->setDataBoleto('cpf_cnpj', $this->getConfig('cpf_cnpj'));
			$this->setDataBoleto('endereco', $this->getConfig('endereco'));
			$this->setDataBoleto('cidade_uf', $this->getConfig('cidade_uf'));
			$this->setDataBoleto('cedente', $this->getConfig('cedente'));
			$this->setDataBoleto('agencia', $this->getConfig('agencia'));
			$this->setDataBoleto('agencia_dv', $this->getConfig('agencia_dv'));
			$this->setDataBoleto('conta', $this->getConfig('conta'));
			$this->setDataBoleto('conta_dv', $this->getConfig('conta_dv'));
			$this->setDataBoleto('carteira', $this->getConfig('carteira'));
			$this->setDataBoleto('carteira_descricao', $this->getConfig('carteira_descricao'));
		}
		
		private function setDataOpcionais(){
			$this->setDataBoleto('especie', $this->getConfig('especie'));
			$this->setDataBoleto('especie_doc', $this->getConfig('especie_doc'));
			$this->setDataBoleto('aceite', $this->getConfig('aceite'));
			$this->setDataBoleto('quantidade', $this->getConfig('quantidade'));
		}

		private function setDataSantander(){
			$this->setDataBoleto('codigo_cliente', $this->getConfig('codigo_cliente'));
			$this->setDataBoleto('ponto_venda', $this->getConfig('ponto_venda'));
		}

		private function setDataBradesco(){
			$this->setDataBoleto('conta_cedente', $this->getConfig('conta_cedente'));
			$this->setDataBoleto('conta_cedente_dv', $this->getConfig('conta_cedente_dv'));
		}

		private function setDataCEF(){
			$this->setDataBoleto('conta_cedente_caixa', $this->getConfig('conta_cedente_caixa'));
			$this->setDataBoleto('conta_cedente_dv_caixa', $this->getConfig('conta_cedente_dv_caixa'));
			$this->setDataBoleto('inicio_nosso_numero', $this->getConfig('inicio_nosso_numero'));
		}

		private function setDataCEFSinco(){
			$this->setDataBoleto('campo_fixo_obrigatorio', $this->getConfig('campo_fixo_obrigatorio'));
		}

		private function setDataCEFSigcb($_order){
			$numeroBoleto = str_replace('-', '', $_order->getIncrementId());
			$this->setDataBoleto('nosso_numero1', $this->getConfig('nosso_numero1'));
			$this->setDataBoleto('nosso_numero_const1', $this->getConfig('nosso_numero_const1'));
			$this->setDataBoleto('nosso_numero2', $this->getConfig('nosso_numero2'));
			$this->setDataBoleto('nosso_numero_const2', $this->getConfig('nosso_numero_const2'));
			$this->setDataBoleto('nosso_numero3', $numeroBoleto);
		}

		private function setDataBB(){
			$this->setDataBoleto('convenio', $this->getConfig('convenio'));
			$this->setDataBoleto('contrato', $this->getConfig('contrato'));
			$this->setDataBoleto('variacao_carteira', $this->getConfig('variacao_carteira'));
			$this->setDataBoleto('formatacao_convenio', $this->getConfig('formatacao_convenio'));
			$this->setDataBoleto('formatacao_nosso_numero', $this->getConfig('formatacao_nosso_numero'));
		}

		private function setDataHSBC(){
			$this->setDataBoleto('codigo_cedente', $this->getConfig('codigo_cedente'));
		}

		private function setDataInstrucoes(){
			$instrucoes = explode("\n", $this->getConfig('instrucoes_boleto'));
			for($i = 0; $i < 4; $i++){
				$instrucao = isset($instrucoes[$i]) ? $instrucoes[$i] : '';
				$this->setDataBoleto('instrucoes' . ($i + 1), $instrucao);
			}
		}

		private function setDataExtras($_order){
			$info = sprintf($this->getConfig('informacoes'), $_order->getIncrementId());
			$informacoes = explode("\n", $info);
			for($i = 0; $i < 3; $i++){
				$informacao = isset($informacoes[$i]) ? $informacoes[$i] : '';
				$this->setDataBoleto('demonstrativo' . ($i + 1), $informacao);
			}
		}

		public function getNomeBanco(){
            $nomeBanco = $this->getConfig('banconome');
            if($nomeBanco == 'bb') $nomeBanco = 'Banco do Brasil';
            if($nomeBanco == 'bradesco') $nomeBanco = 'Bradesco';
            if($nomeBanco == 'cef') $nomeBanco = 'Caixa Economica Federal';
            if($nomeBanco == 'cef_sinco') $nomeBanco = 'Caixa Economica Federal Sinco';
            if($nomeBanco == 'cef_sigcb') $nomeBanco = 'Caixa Economica Federal Sigcb';
            if($nomeBanco == 'hsbc') $nomeBanco = 'HSBC';
            if($nomeBanco == 'itau') $nomeBanco = 'Itau';
            if($nomeBanco == 'santander_banespa') $nomeBanco = 'Santander';
            if($nomeBanco == 'sudameris') $nomeBanco = 'Sudameris';
            return $nomeBanco;
        }
		
		private function setDataBoleto($index, $value){
			$this->_dataBoleto[$index] = $value;
		}
		
		private function getDataBoleto(){
			return $this->_dataBoleto;
		}

		private function getConfig($info){
			return Mage::getStoreConfig('payment/' . $this->_code . '/' . $info);
		}
		
		protected function _prepareValues(Mage_Sales_Model_Order $order, $values) {
			return $values;
		}
	}

?>