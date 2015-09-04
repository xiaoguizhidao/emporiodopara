<?php

class BIS2BIS_Scopus_Block_Standard_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $standard = Mage::getModel('bradesco/standard');

        $form = new Varien_Data_Form();
         
        $scopusTipo = $standard->getScopusTipo();
        
        $form->setAction($standard->getBradescoUrl($bradesco_tipo))
            ->setId('bradesco_standard_checkout')
            ->setName('bradesco_standard_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
        $orderId =  Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);              
        $bradesco = Mage::getModel('bradesco/bradesco')->setUrlBradesco($order->getId(),$standard->getBradescoUrl($bradesco_tipo));
        foreach ($standard->getStandardCheckoutFormFields() as $field=>$value) {
            $form->addField($field, 'hidden', array('name'=>$field, 'value'=>($value)) );
        }
        $html  = '<html>';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; " /></head>';
        $html .= '<body>';
        $html .= $this->__('Você será redirecionado para o Bradesco em alguns instantes.');
        $html .=  $form->toHtml();
        $html .= '<script type="text/javascript">document.getElementById("bradesco_standard_checkout").submit();</script>';
        $html .= '</body></html>';
        // echo '<pre>';
        // print_r($form->toHtml());
        // die();
        return $html;
    }
}