<?php

/*
 * Chamada do One Step Checkout
 */
/*
 *
 * @author Guilherme
 */

require_once 'Mage/Checkout/controllers/OnepageController.php';

class Mage_Osc_OnepageController extends Mage_Checkout_OnepageController{
    
    public function indexAction(){
        $this->validateMinimumAmountOsc();
    	$osc = Mage::getModel('osc/osc')->load(1);
		if($osc->getCheckout() == 2){ 
		 	if($osc->getHttps() == 1) 
		 		$this->_redirectUrl($osc->getOscloginhttpsurl());
		 	else 
		 		$this->_redirect('osc/onepage/login'); 
		 	return; 
  	    }else{
  	    	if (!Mage::helper('checkout')->canOnepageCheckout()) {
	            Mage::getSingleton('checkout/session')->addError($this->__('The onepage checkout is disabled.'));
	            $this->_redirect('checkout/cart');
	            return;
	        }
	        $quote = $this->getOnepage()->getQuote();
	        if (!$quote->hasItems() || $quote->getHasError()) {
	            $this->_redirect('checkout/cart');
	            return;
	        }
	        if (!$quote->validateMinimumAmount()) {
	            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
	            Mage::getSingleton('checkout/session')->addError($error);
	            $this->_redirect('checkout/cart');
	            return;
	        }
	        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
	        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));
	        $this->getOnepage()->initCheckout();
	        $this->loadLayout();
	        $this->_initLayoutMessages('customer/session');
	        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
	        $this->renderLayout();
  	    }
	}


	// Retorna o quote da sessão
    public function getQuote() {
        return Mage::getSingleton('checkout/session')->getQuote();
    }


	// Realiza o login na página 
	public function loginAction(){
        $this->validateMinimumAmountOsc();
		if(Mage::getModel('customer/session')->isLoggedIn()){
			$osc_model = Mage::getModel('osc/osc')->load(1);
			if($osc_model->getHttps() == 1){
				$this->_redirectUrl($osc_model->getOschttpsurl());
			}else{
				$this->_redirect('osc/onepage/checkout');	
			}
		}
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
	}

	public function checkoutAction(){
        $this->validateMinimumAmountOsc();
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();

        $payments = Mage::getSingleton('payment/config')->getActiveMethods();
		$payMethods = array();

		foreach ($payments as $paymentCode=>$paymentModel) 
		{
			if($paymentCode != 'googlecheckout' && $paymentCode != 'paypal_mep' && $paymentCode != 'paypal_billing_agreement'){
				 $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
		   		 $payMethods[$paymentCode] = $paymentTitle;
			}
		   
		}
		
		// limpa os métodos de pagamento
		$this->getQuote()->getPayment()->setMethod('');
	    $this->getQuote()->collectTotals();
	    $this->getQuote()->save();

	    $osc = Mage::getModel('osc/osc');
		
		// Conta os métodos 
		if(count($payMethods) == 1){
			$dados['method'] = $paymentCode;
			$osc->salvarPagamento($dados);
		}

		$metodos_de_envio = $this->getQuote()->getShippingAddress()->getShippingRatesCollection();

		if(count($metodos_de_envio) == 1){
			foreach($metodos_de_envio as $metodo_envio) $metodo = $metodo_envio->getData('code');
			$osc->salvarEnvio($metodo);
		}

        /*

		*/
	}


    /**
    Recupera model do Checkout
    */
    public function getOnestep() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    protected function validateMinimumAmountOsc(){
        if(!Mage::getSingleton('checkout/cart')->getQuote()->validateMinimumAmount()){
            $this->_redirect('checkout/cart');
            return;
        }
    }
}