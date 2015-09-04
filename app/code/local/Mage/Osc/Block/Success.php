<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Success
 *
 * @author Intel
 */
class Mage_Osc_Block_Success extends Mage_Core_Block_Template {

    //put your code here
    public function _prepareLayout() {
        parent::_prepareLayout();
    }
    
    public function getLastOrderId(){
        $session =  Mage::getSingleton('checkout/type_onepage')->getCheckout();
        $lastOrderId = $session->getLastOrderId();
        return $lastOrderId;
    }
    
    public function getRealOrderId(){
        $order = Mage::getModel('sales/order')->load($this->getLastOrderId());
        return $order->getIncrementId();
    }
    
    public function getLastQuoteId(){
        $session = Mage::getSingleton("checkout/type_onepage")->getCheckout();
        $lastQuoteId = $session->getLaastQuoteId();
        return $lastQuoteId;
    }
    
      
    
}

?>
