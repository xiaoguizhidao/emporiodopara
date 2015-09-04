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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    PagSeguro
 * @copyright  Copyright (c) 2008 WebLibre (http://www.weblibre.com.br) - Guilherme Dutra (godutra@gmail.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 *
 * PagSeguro Checkout Module
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class PagSeguro_Model_Standard extends Mage_Payment_Model_Method_Abstract {
    //changing the payment to different from cc payment type and pagseguro payment type
    const PAYMENT_TYPE_AUTH = 'AUTHORIZATION';
    const PAYMENT_TYPE_SALE = 'SALE';

    protected $_code = 'pagseguro_standard';
    protected $_formBlockType = 'pagseguro/standard_form';
    protected $_allowCurrencyCode = array('BRL');

//    public function savePagseguro($vendedorEmail, $transacaoID, $referencia, $extras, $tipoFrete, $valorFrete, $anotacao, $dataTransacao, $tipoPagamento, $statusTransacao, $parcelas, $numItens) {
//        $write = Mage::getSingleton('core/resource')->getConnection('catalog_write');
//        if (!$write->fetchOne("SELECT * FROM `atacadol_01`.`pagSeguro` where Referencia = " . $referencia)) {
//            $write->query("INSERT INTO `atacadol_01`.`pagSeguro`(`VendedorEmail`,`TransacaoID`, `Referencia`, `Extras`, `TipoFrete`,
//                                            `ValorFrete`, `Anotacao`, `DataTransacao`, `TipoPagamento`, `StatusTransacao`, `Parcelas`, `NumItens`)
//                                            VALUES (" . $vendedorEmail . "," . $transacaoID . "," . $referencia . ", " . $extras . "," . $tipoFrete . ", " . $valorFrete . "," . $anotacao . ", " . $dataTransacao . "," . $tipoPagamento . "," . $statusTransacao . "," . $parcelas . "," . $numItens . ");");
//        }
//    }

    /**
     * Get pagseguro session namespace
     *
     * @return PagSeguro_Model_Session
     */
    public function getSession() {
        return Mage::getSingleton('pagseguro/session');
    }

    /**
     * Get checkout session namespace
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout() {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current quote
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote() {
        return $this->getCheckout()->getQuote();
    }

    /**
     * Using internal pages for input payment data
     *
     * @return bool
     */
    public function canUseInternal() {
        return false;
    }

    /**
     * Using for multiple shipping address
     *
     * @return bool
     */
    public function canUseForMultishipping() {
        return false;
    }

    public function createFormBlock($name) {
        $block = $this->getLayout()->createBlock('pagseguro/standard_form', $name)
                ->setMethod('pagseguro_standard')
                ->setPayment($this->getPayment())
                ->setTemplate('PagSeguro/standard/form.phtml');

        return $block;
    }

    /* validate the currency code is avaialable to use for pagseguro or not */

    public function validate() {
        parent::validate();
        $currency_code = $this->getQuote()->getBaseCurrencyCode();
        if (!in_array($currency_code, $this->_allowCurrencyCode)) {
            Mage::throwException(Mage::helper('pagseguro')->__('Selected currency code (' . $currency_code . ') is not compatabile with PagSeguro'));
        }
        return $this;
    }

    public function onOrderValidate(Mage_Sales_Model_Order_Payment $payment) {
        return $this;
    }

    public function onInvoiceCreate(Mage_Sales_Model_Invoice_Payment $payment) {
        
    }

    public function canCapture() {
        return true;
    }

    public function getOrderPlaceRedirectUrl() {
        return Mage::getUrl('pagseguro/standard/redirect', array('_secure' => true));
    }

    function getNumEndereco($endereco) {
        $numEndereco = '';

        //procura por vÃ­rgula ou traÃ§o para achar o final do logradouro
        $posSeparador = $this->getPosSeparador($endereco, false);
        if ($posSeparador !== false) {
            $numEndereco = trim(substr($endereco, $posSeparador + 1));
        }

        //procura por vÃ­rgula, traÃ§o ou espaÃ§o para achar o final do nÃºmero da residÃªncia
        $posComplemento = $this->getPosSeparador($numEndereco, true);
        if ($posComplemento !== false) {
            $numEndereco = trim(substr($numEndereco, 0, $posComplemento));
        }

        if ($numEndereco == '') {
            $numEndereco = '?';
        }

        return($numEndereco);
    }

    function getPosSeparador($endereco, $procuraEspaco = false) {
        $posSeparador = strpos($endereco, ',');
        if ($posSeparador === false) {
            $posSeparador = strpos($endereco, '-');
        }

        if ($procuraEspaco) {
            if ($posSeparador === false) {
                $posSeparador = strrpos($endereco, ' ');
            }
        }

        return($posSeparador);
    }

    public function getStandardCheckoutFormFields()
    {
    
        $sales = Mage::getModel('sales/order')->loadByIncrementId(Mage::getModel('checkout/session')->getData('last_real_order_id'));
        $dados = $sales->getShippingAddress();
        $endereco = explode("\n", $dados->getOrigData('street'));
        $region = Mage::getModel('pagseguro/pagseguro')->recuperaRegion($dados->getOrigData('region_id'));
        $currency_code = $this->getQuote()->getBaseCurrencyCode();
        $cep = substr(eregi_replace("[^0-9]", "", $dados->getOrigData('postcode')) . '00000000', 0, 8);

		
        // removes non numeric characters from the telephone field, and trims to 8 chars long.
        
        $cust_ddd = '00';    	
        $cust_telephone = eregi_replace ("[^0-9]", "",$dados->getOrigData('telephone'));
        $st = strlen($cust_telephone)-8;
        if ($st>0) { // in case this string is longer than 8 characters (PagSeguro's settings)
            $cust_ddd = substr($cust_telephone, 0, 2);
	    $cust_telephone = substr($cust_telephone, $st, 8);
        }

       	$sArr = array(
            'email_cobranca'    =>  $this->getConfigData('emailID'),
            'Tipo'              => "CP",
            'Moeda'             => "BRL",
    	    'ref_transacao'     =>$this->getCheckout()->getLastRealOrderId(),
            'cliente_nome'      => $dados->getOrigData('firstname') . ' ' . $dados->getOrigData('lastname'),
            'cliente_cep'       => $cep,
            'cliente_end'       => $endereco[0],
            'cliente_num'       => isset($endereco[1]) ? $endereco[1] : "",
            'cliente_compl'     => isset($endereco[2]) ? $endereco[2] : "",
            'cliente_bairro'    => isset($endereco[3]) ? $endereco[3] : "",
            'cliente_cidade'    => $dados->getOrigData('city'),
            'cliente_uf'        => $region[0]['code'],
            'cliente_pais'      => $dados->getOrigData('country_id'),
            'cliente_ddd'       => $cust_ddd,
            'cliente_tel'       => $cust_telephone,
            'cliente_email'     =>$dados->getOrigData('email'),
        );
		$cont = 0;					
		$items = $sales->getAllItems();
                
         
		//'ITENS'. print_r($items) . "<br />";
		if ($items) {
            $i = 1;
            foreach($items as $item) {
                
				$valor = ($item->getData('price') - ($item->getDiscountAmount()/ (int) $item->getQtyOrdered()))*100;
                if($valor>0){
					$sArr = array_merge($sArr, array(
						'item_descr_'.$i   => $item->getName(),
						'item_id_'.$i      => $item->getSku(),
						'item_quant_'.$i   =>  (int) $item->getQtyOrdered(),
						'item_valor_'.$i   => $valor,
					));
					if($cont==0){
						$cont = (int) $item->getQtyOrdered();
					}

				if ($this->getConfigData('calculo_frete') == 1) {
					//para o PagSeguro calcular o frete, tem que passar via Post o peso arredondado, senão dá erro
					if ($this->getConfigData('medida_peso') == 'kg') {
						$item_peso = round($item->getWeight() * 1000);	
					}
					else {
						$item_peso = round($item->getWeight());
					}
					
							$sArr = array_merge($sArr, array('item_peso_'.$i    => $item_peso,
						//'item_frete_'.$i    => 0,
					));
				}

                    if($item->getBaseTaxAmount()>0){
                        $sArr = array_merge($sArr, array('tax_'.$i => sprintf('%.2f',$item->getBaseTaxAmount()),));
                    }
                    $i++;
			    }
            }
        }
        //$transaciton_type = $this->getConfigData('transaction_type');
       // $totalArr = $a->getTotals();
        $shipping = sprintf('%.2f', $sales->getShippingAddress()->getOrder()->getData('shipping_amount'));
		//$shipping = $this->getQuote()->getShippingAddress()->getBaseShippingAmount();
		//$formatado = number_format($shipping,2,',','');
		//print_r($formatado);
	if ($this->getConfigData('calculo_frete') == 0) { //não calcula frete pelo PagSeguro
			//passa o valor do frete total em uma única variavel para o pagseguro, utilizado junto com o modulo de correio
        	//$sArr = array_merge($sArr, array('item_frete_1' => str_replace(".", ",", ($formatado*100)/$item->getQty())));
			
        	$sArr = array_merge($sArr, array('item_frete_1' => ceil(($shipping*100)/($cont))));
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
            $debug = Mage::getModel('Pagseguro/api_debug')
                    ->setApiEndpoint($this->getPagseguroUrl())
                    ->setRequestBody($sReq)
                    ->save();
        }	
        return $rArr;
    }

    //  define a url do pagseguro
    public function getPagSeguroUrl() {
        $url = 'https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx';
        return $url;
    }

    public function getDebug() {
        return Mage::getStoreConfig('pagseguro/wps/debug_flag');
    }

    public function ipnPostSubmit() {
        $sReq = '';
        foreach ($this->getIpnFormData() as $k => $v) {
            $sReq .= '&' . $k . '=' . urlencode(stripslashes($v));
        }
        //append ipn commdn
        $sReq .= "&cmd=_notify-validate";
        $sReq = substr($sReq, 1);

        if ($this->getDebug()) {
            $debug = Mage::getModel('pagseguro/api_debug')
                    ->setApiEndpoint($this->getPagSeguroUrl())
                    ->setRequestBody($sReq)
                    ->save();
        }
        $http = new Varien_Http_Adapter_Curl();
        $http->write(Zend_Http_Client::POST, $this->getPagSeguroUrl(), '1.1', array(), $sReq);
        $response = $http->read();
        $response = preg_split('/^\r?$/m', $response, 2);
        $response = trim($response[1]);
        if ($this->getDebug()) {
            $debug->setResponseBody($response)->save();
        }

        //when verified need to convert order into invoice
        $id = $this->getIpnFormData('invoice');
        $order = Mage::getModel('sales/order');
        $order->loadByIncrementId($id);

        if ($response == 'VERIFIED') {
            if (!$order->getId()) {
                /*
                 * need to have logic when there is no order with the order id from pagseguro
                 */
            } else {

                if ($this->getIpnFormData('mc_gross') != $order->getGrandTotal()) {
                    //when grand total does not equal, need to have some logic to take care
                    $order->addStatusToHistory(
                            $order->getStatus(), //continue setting current order status
                            Mage::helper('pagseguro')->__('Order total amount does not match pagseguro gross total amount')
                    );
                } else {
                    /*
                      //quote id
                      $quote_id = $order->getQuoteId();
                      //the customer close the browser or going back after submitting payment
                      //so the quote is still in session and need to clear the session
                      //and send email
                      if ($this->getQuote() && $this->getQuote()->getId()==$quote_id) {
                      $this->getCheckout()->clear();
                      $order->sendNewOrderEmail();
                      }
                     */
                    /*
                      if payer_status=verified ==> transaction in sale mode
                      if transactin in sale mode, we need to create an invoice
                      otherwise transaction in authorization mode
                     */
                    if ($this->getIpnFormData('payment_status') == 'Completed') {
                        if (!$order->canInvoice()) {
                            //when order cannot create invoice, need to have some logic to take care
                            $order->addStatusToHistory(
                                    $order->getStatus(), //continue setting current order status
                                    Mage::helper('pagseguro')->__('Error in creating an invoice')
                            );
                        } else {
                            //need to save transaction id
                            $order->getPayment()->setTransactionId($this->getIpnFormData('txn_id'));
                            //need to convert from order into invoice
                            $invoice = $order->prepareInvoice();
                            $invoice->register()->capture();
                            Mage::getModel('core/resource_transaction')
                                    ->addObject($invoice)
                                    ->addObject($invoice->getOrder())
                                    ->save();
                            $order->addStatusToHistory(
                                    'processing', //update order status to processing after creating an invoice
                                    Mage::helper('pagseguro')->__('Invoice ' . $invoice->getIncrementId() . ' was created')
                            );
                        }
                    } else {
                        $order->addStatusToHistory(
                                $order->getStatus(), Mage::helper('pagseguro')->__('Received IPN verification'));
                    }
                }//else amount the same and there is order obj
                //there are status added to order
                $order->save();
            }
        } else {
            /*
              Canceled_Reversal
              Completed
              Denied
              Expired
              Failed
              Pending
              Processed
              Refunded
              Reversed
              Voided
             */
            $payment_status = $this->getIpnFormData('payment_status');
            $comment = $payment_status;
            if ($payment_status == 'Pending') {
                $comment .= ' - ' . $this->getIpnFormData('pending_reason');
            } elseif (($payment_status == 'Reversed') || ($payment_status == 'Refunded')) {
                $comment .= ' - ' . $this->getIpnFormData('reason_code');
            }
            //response error
            if (!$order->getId()) {
                /*
                 * need to have logic when there is no order with the order id from pagseguro
                 */
            } else {
                $order->addStatusToHistory(
                        $order->getStatus(), //continue setting current order status
                        Mage::helper('pagseguro')->__('PagSeguro IPN Invalid.' . $comment)
                );
                $order->save();
            }
        }
    }
   

}
