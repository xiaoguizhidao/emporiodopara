<?php
/**
 * Indexa - Pagamento Digital Payment Module
 *
 * @title      Magento -> Custom Payment Module for Pagamento Digital (Brazil)
 * @category   Payment Gateway
 * @package    Indexa_PagamentoDigital
 * @author     Gabriel Zamprogna -> desenvolvimento [at] indexainternet [dot] com  [dot] br
 * @copyright  Copyright (c) 2009 Indexa
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Indexa_PagamentoDigital_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
    //changing the payment to different from cc payment type and indexa_pagamentodigital payment type
    const PAYMENT_TYPE_AUTH = 'AUTHORIZATION';
    const PAYMENT_TYPE_SALE = 'SALE';

    protected $_code  = 'pagamentodigital_standard';
    protected $_formBlockType = 'pagamentodigital/standard_form';
    protected $_allowCurrencyCode = array('BRL');

     /**
     * Get pagamentodigital session namespace
     *
     * @return Indexa_PagamentoDigital_Model_Session
     */
    public function getSession()
    {
        return Mage::getSingleton('pagamentodigital/session');
    }

    /**
     * Get checkout session namespace
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current quote
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    /**
     * Using internal pages for input payment data
     *
     * @return bool
     */
    public function canUseInternal()
    {
        return false;
    }

    /**
     * Using for multiple shipping address
     *
     * @return bool
     */
    public function canUseForMultishipping()
    {
        return false;
    }

    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('pagamentodigital/standard_form', $name)
            ->setMethod('pagamentodigital_standard')
            ->setPayment($this->getPayment())
            ->setTemplate('pagamentodigital/standard/form.phtml'); // @todo Indexa - antes tava Indexa_PagamentoDigital

        return $block;
    }

    /*validate the currency code is avaialable to use for indexa_pagamentodigital or not*/
	// @todo: Indexa - ver se esta funcao esta rodando e funcional
    public function validate()
    {
        parent::validate();
        $currency_code = $this->getQuote()->getBaseCurrencyCode();
        if (!in_array($currency_code,$this->_allowCurrencyCode)) {
            Mage::throwException(Mage::helper('pagamentodigital')->__('Selected currency code ('.$currency_code.') is not compatabile with Pagamento Digital'));
        }
        return $this;
    }

    // @todo: Indexa - ver se esta funcao eh necessaria
	public function onOrderValidate(Mage_Sales_Model_Order_Payment $payment)
    {
       return $this;
    }

    // @todo: Indexa - ver se esta funcao eh necessaria
	public function onInvoiceCreate(Mage_Sales_Model_Invoice_Payment $payment)
    {

    }

    public function canCapture()
    {
        return true;
    }

    public function getOrderPlaceRedirectUrl()
    {
          return Mage::getUrl('pagamentodigital/standard/redirect', array('_secure' => true));
    }

	function getNumEndereco($endereco) {
    	$numEndereco = '';

    	//procura por vírgula ou traço para achar o final do logradouro
    	$posSeparador = $this->getPosSeparador($endereco, false);
    	if ($posSeparador !== false)
		    $numEndereco = trim(substr($endereco, $posSeparador + 1));

    	//procura por vírgula, traço ou espaço para achar o final do número da residência
      	$posComplemento = $this->getPosSeparador($numEndereco, true);
		if ($posComplemento !== false)
		    $numEndereco = trim(substr($numEndereco, 0, $posComplemento));

		if ($numEndereco == '')
		    $numEndereco = '?';

		return($numEndereco);
	}

	function getPosSeparador($endereco, $procuraEspaco = false) {
		$posSeparador = strpos($endereco, ',');
		if ($posSeparador === false)
			$posSeparador = strpos($endereco, '-');

		if ($procuraEspaco)
			if ($posSeparador === false)
				$posSeparador = strrpos($endereco, ' ');

		return($posSeparador);
	}

	public function getStandardCheckoutFormFields() {

		// @todo: Indexa: Verifica se é um produto virtual
		$order_id = Mage::getModel('checkout/session')->getLastOrderId();
		$_order = Mage::getModel('sales/order')->load($order_id);

		if ($this->getQuote()->getIsVirtual())
			$a = $_order->getBillingAddress();
		else
			$a = $_order->getShippingAddress();

       	$currency_code = $this->getQuote()->getBaseCurrencyCode();
		$postal_code = substr(eregi_replace ("[^0-9]", "", $a->getPostcode()).'00000000',0,8);

      	$sArr = array(
            'email_loja'        =>  $this->getConfigData('emailID'),
            'tipo_integracao'   => "PAD",
    	    'id_pedido'     	=> $order_id,
            'nome'      		=> $a->getFirstname().' '.$a->getLastname(),
            'cep'       		=> $postal_code,
            'endereco'       	=> $a->getStreet(1),
            'complemento'     	=> $a->getStreet(2),
            'bairro'    		=> "",
            'cidade'    		=> $a->getCity(),
            'estado'        	=> $a->getState(),
            'pais'      		=> $a->getCountry(),
            'telefone'       	=> $a->getTelephone(),
            'email'    			=> $a->getEmail(),
        );

		$items = $_order->getAllItems();
		$valorProduto = 0;
		$desconto = 0;
        if ($items)
        {
            $i = 1;
            foreach($items as $item)
            {
            	if ($item->getParentItem()) continue;
            	
			    // @todo: Indexa - Testar se os descontos funcionam
                //$valorProduto = ($item->getBaseCalculationPrice() - $item->getBaseDiscountAmount());
                $desconto += $item->getData("base_discount_amount");
          
				$valorProduto = ($item->getData("base_price"));
				$valorProduto = str_replace(',', '.', $valorProduto);

				$sArr = array_merge($sArr, array(
                    'produto_descricao_'.$i   => $item->getName(),
                    'produto_codigo_'.$i      => $item->getSku(),
                    'produto_qtde_'.$i   => $item->getData("qty_ordered"),
                    'produto_valor_'.$i   => $valorProduto,
			    ));
			
			    // @todo Indexa - caso utilize imposto
//	            if($item->getBaseTaxAmount()>0){
//	                $sArr = array_merge($sArr, array(
//	                'tax_'.$i      => sprintf('%.2f',$item->getBaseTaxAmount()),
//	                ));
//	            }
	            
            	$i++;
            }
        }
		$sArr = array_merge($sArr, array(
                    'desconto'   => $desconto, 
			    ));
        //$transaciton_type = $this->getConfigData('transaction_type');
        $totalArr = $a->getTotals();
        $shipping = sprintf('%.2f', $_order->getData('shipping_amount'));
		$shipping = str_replace(',', '.', $shipping);

		//passa o valor do frete total em uma única variavel para o pagamentodigital
    	$sArr = array_merge($sArr, array('frete' => $shipping));

		//passa a URL para o Pagamento Digital retornar à loja
		if ($this->getConfigData('retorno') != '') {
	    	$sArr = array_merge($sArr, array('url_retorno' => $this->getConfigData('retorno')));
	    	$sArr = array_merge($sArr, array('redirect' => 'true'));
		}

        $sReq = '';
        $rArr = array();
        foreach ($sArr as $k=>$v) {
            /*
            replacing & char with and. otherwise it will break the post
            */
            $value =  str_replace("&","and",$v);
            $rArr[$k] =  $value;
            $sReq .= '&'.$k.'='.$value;
        }

        if ($this->getDebug() && $sReq) {
            $sReq = substr($sReq, 1);
            $debug = Mage::getModel('pagamentodigital/api_debug')
                    ->setApiEndpoint($this->getPagamentoDigitalUrl())
                    ->setRequestBody($sReq)
                    ->save();
        }

        return $rArr;
    }

    //  define a url do pagamentodigital
    public function getPagamentoDigitalUrl()
    {
         $url='https://www.pagamentodigital.com.br/checkout/pay/';
         return $url;
    }

    // @todo Indexa: esta funcao eh inutil
	public function getDebug()
    {
        return Mage::getStoreConfig('pagamentodigital/wps/debug_flag');
    }
}
