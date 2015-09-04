<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   design_default
 * @package    Mage
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
?>

<?php

	class BIS2BIS_BoletoUltimate_Model_Standard extends Mage_Payment_Model_Method_Abstract
	{

		protected $_code = 'boleto_ultimate';
		protected $_formBlockType = 'boleto_ultimate/form';
		protected $_infoBlockType = 'boleto_ultimate/info';

	    public function canUseCheckout()
	    {
	    	if($this->getConfig('active_homologacao')){
	    		$emails_homolagacao = explode(',', trim($this->getConfig('email_homolagacao')));
	    		foreach ($emails_homolagacao as $key => $email_homolagacao) {
	    			if(Mage::getModel('customer/session')->getCustomer()->getEmail() == $email_homolagacao){
	    				return true;
	    			}
	    		}
	    	}else{
	    		return true;
	    	}
	    	return false;
	    }

	    public function canUseInternal()
	    {
	    	return true;
	    }

	    public function assignData($data)
	    {
			if (!($data instanceof Varien_Object)) {
				$data = new Varien_Object($data);
			}
			$info = $this->getInfoInstance();
			$strtotime = strtotime(date('Y-m-d H:i:s'));
	        $dias_vencimento = (int) $this->getLayoutConfig($data->getCodeBanco(), 'vencimento');
	        $data_vencimento = date('d/m/Y', $strtotime + ($dias_vencimento * 86400));
	        $additional_data = array(
	        	'code_banco'	  => $data->getCodeBanco(),
	        	'data_vencimento' => $data_vencimento
	        );
	        $info->setAdditionalData(serialize($additional_data));
			return $this;
	    }

	    public function validate()
	    {
	    	parent::validate();
	    	$info = $this->getInfoInstance();
	    	$additional_data = unserialize($info->getAdditionalData());
        	$code_banco = $additional_data['code_banco'];
        	if($this->isEmptyString($code_banco)){
				throw new Mage_Payment_Exception('Escolha o Banco');
			}
			return $this;
		}

		public function getOrderPlaceRedirectUrl()
		{
			$this->getCleanQuote();
			return Mage::getUrl('boleto_ultimate/standard/index');
		}

		public function prepareValues(Mage_Sales_Model_Order $order)
	    {
			$billing_address = $order->getBillingAddress();
			$additional_data = unserialize($order->getPayment()->getAdditionalData());
	    	$code_banco = $additional_data['code_banco'];
	    	$data_vencimento = $additional_data['data_vencimento'];
			$numero_boleto = str_replace('-', '', $order->getIncrementId());
			$strtotime = strtotime($order->getCreatedAt());
			$data = array(
				'logoempresa' 		 => $this->getConfig('logoempresa'),
				'nosso_numero' 		 => $numero_boleto,
				'numero_documento' 	 => $numero_boleto,
				'data_vencimento' 	 => $data_vencimento,
				'data_documento'	 => date('d/m/Y', $strtotime),
				'data_processamento' => date('d/m/Y', $strtotime),
				'valor_boleto'		 => number_format($order->getGrandTotal() + $this->getLayoutConfig($code_banco, 'valor_adicional'), 2, ',', ''),
				'valor_unitario'	 => number_format($order->getGrandTotal() + $this->getLayoutConfig($code_banco, 'valor_adicional'), 2, ',', ''),
				'sacado'			 => $billing_address->getFirstname() . ' ' . $billing_address->getLastname(),
				'sacadocpf'			 => $order->getCustomerTaxvat(),
				'endereco1'			 => implode(' ', $billing_address->getStreet()),
				'endereco2'			 => $billing_address->getCity() . ' - ' . $billing_address->getRegion() . ' - CEP: ' . $billing_address->getPostcode(),
				'identificacao'		 => $this->getLayoutConfig($code_banco, 'identificacao'),
				'cpf_cnpj'			 => $this->getLayoutConfig($code_banco, 'cpf_cnpj'),
				'endereco'			 => $this->getLayoutConfig($code_banco, 'endereco'),
				'cidade_uf'			 => $this->getLayoutConfig($code_banco, 'cidade_uf'),
				'cedente'			 => $this->getLayoutConfig($code_banco, 'cedente'),
				'agencia'			 => $this->getLayoutConfig($code_banco, 'agencia'),
				'agencia_dv'		 => $this->getLayoutConfig($code_banco, 'agencia_dv'),
				'conta'			 	 => $this->getLayoutConfig($code_banco, 'conta'),
				'conta_dv'			 => $this->getLayoutConfig($code_banco, 'conta_dv'),
				'carteira'			 => $this->getLayoutConfig($code_banco, 'carteira'),
				'especie' 			 => $this->getLayoutConfig($code_banco, 'especie'),
				'especie_doc' 		 => $this->getLayoutConfig($code_banco, 'especie_doc'),
				'aceite' 			 => $this->getLayoutConfig($code_banco, 'aceite'),
				'quantidade' 		 => $this->getLayoutConfig($code_banco, 'quantidade')
			);
			if($code_banco == 'santander_banespa'){
				$data['ponto_venda'] 		= $this->getLayoutConfig($code_banco, 'ponto_venda');
				$data['carteira_descricao'] = $this->getLayoutConfig($code_banco, 'carteira_descricao');
				$data['codigo_cliente'] 	= $this->getLayoutConfig($code_banco, 'codigo_cliente');
			}
			if($code_banco == 'bradesco'){
				$data['conta_cedente']    = $this->getLayoutConfig($code_banco, 'conta_cedente');
				$data['conta_cedente_dv'] = $this->getLayoutConfig($code_banco, 'conta_cedente_dv');
			}
			if($code_banco == 'cef' || $code_banco == 'cef_sinco' || $code_banco == 'cef_sigcb'){
				$data['conta_cedente_caixa']    = $this->getLayoutConfig($code_banco, 'conta_cedente_caixa');
				$data['conta_cedente_dv_caixa'] = $this->getLayoutConfig($code_banco, 'conta_cedente_dv_caixa');
				$data['inicio_nosso_numero'] 	= $this->getLayoutConfig($code_banco, 'inicio_nosso_numero');
			}
			if($code_banco == 'bb'){
				$data['convenio']    			 = $this->getLayoutConfig($code_banco, 'convenio');
				$data['contrato']				 = $this->getLayoutConfig($code_banco, 'contrato');
				$data['variacao_carteira'] 		 = $this->getLayoutConfig($code_banco, 'variacao_carteira');
				$data['formatacao_convenio'] 	 = $this->getLayoutConfig($code_banco, 'formatacao_convenio');
				$data['formatacao_nosso_numero'] = $this->getLayoutConfig($code_banco, 'formatacao_nosso_numero');
			}
			if($code_banco == 'hsbc'){
				$data['codigo_cedente'] = $this->getLayoutConfig($code_banco, 'codigo_cedente');
			}
			if($code_banco == 'cef_sinco'){
				$data['campo_fixo_obrigatorio'] = $this->getLayoutConfig($code_banco, 'campo_fixo_obrigatorio');
			}
			if($code_banco == 'cef_sigcb'){
				$data['nosso_numero1']    	 = $this->getLayoutConfig($code_banco, 'nosso_numero1');
				$data['nosso_numero_const1'] = $this->getLayoutConfig($code_banco, 'nosso_numero_const1');
				$data['nosso_numero2'] 		 = $this->getLayoutConfig($code_banco, 'nosso_numero2');
				$data['nosso_numero_const2'] = $this->getLayoutConfig($code_banco, 'nosso_numero_const2');
				$data['nosso_numero3'] 		 = $numero_boleto;
			}
			if($code_banco == 'sicoob'){
				$data['convenio'] = $this->getLayoutConfig($code_banco, 'codigo_cedente');
				$data["numero_parcela"] = '001';
			}
			$instrucoes = explode("\n", $this->getLayoutConfig($code_banco, 'instrucoes_boleto'));
			for($i = 0; $i < 4; $i++){
				$instrucao = isset($instrucoes[$i]) ? $instrucoes[$i] : '';
				$data['instrucoes' . ($i + 1)] = $instrucao;
			}
			$info = sprintf($this->getLayoutConfig($code_banco, 'informacoes'), $order->getIncrementId());
			$informacoes = explode("\n", $info);
			for($i = 0; $i < 3; $i++){
				$informacao = isset($informacoes[$i]) ? $informacoes[$i] : '';
				$data['demonstrativo' . ($i + 1)] = $informacao;
			}
			return $data;
		}

		private function isEmptyString($value)
		{
			if(empty($value) || $value == ''){
				return true;
			}
			return false;
		}

		private function getCleanQuote()
		{
			Mage::getSingleton('checkout/type_onepage')->getQuote()->setIsActive(false);
            Mage::getSingleton('checkout/type_onepage')->getQuote()->save();
            Mage::getSingleton('checkout/type_onepage')->getCheckout()->clear();
		}

	    public function getBancosAtivos()
	    {
	    	$bancos_ativos = explode(',', $this->getConfig('bancos_ativos'));
	    	foreach ($bancos_ativos as $key => $code_banco) {
	    		$data[$code_banco] = $this->getNomeBanco($code_banco);
	    	}
	    	if(count($data) == 0){
	    		return array();
	    	}
	    	return $data;
	    }

	    public function isAvailablePrint($_order_status, $is_front = false)
	    {
	    	if($is_front && !$this->getConfig('segunda_via_front')){
	    		return false;
	    	}
    		$available_order_status = explode(',', $this->getConfig('available_order_status'));
	    	foreach ($available_order_status as $key => $order_status) {
	    		if($_order_status == $order_status){
	    			return true;
	    		}
	    	}
	    	return false;
	    }

	    public function isAvailableSendEmail()
	    {
	    	if($this->getConfig('active_send')){
	    		return true;
	    	}
	    	return false;
	    }

	    public function getNomeBanco($code_banco)
	    {
	    	return $this->getLayoutConfig($code_banco, 'name');
	    }

	    public function getLayoutConfig($code_banco, $info)
	    {
	    	return Mage::getStoreConfig('boleto_ultimate/layout_' . $code_banco . '/' . $info);
	    }

		public function getConfig($info)
		{
			return Mage::getStoreConfig('payment/boleto_ultimate/' . $info);
		}

	}

?>