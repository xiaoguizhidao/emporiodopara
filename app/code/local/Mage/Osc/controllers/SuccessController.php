<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SuccessController
 *
 * @author Intel
 */
class Mage_Osc_SuccessController extends Mage_Core_Controller_Front_Action {
    
    //put your code here
    public function indexAction(){
        /*
        $session =  Mage::getSingleton('checkout/type_onepage')->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }
        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();
        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
            $this->_redirect('checkout/cart');
            return;
        }*/
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Sucesso ao finalizar a compra'));
        $this->renderLayout();
    }
    
}
?>
