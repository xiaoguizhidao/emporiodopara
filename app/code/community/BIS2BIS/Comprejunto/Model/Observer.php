<?php
class BIS2BIS_Comprejunto_Model_Observer extends Mage_Core_Model_Abstract
{
    public function comprejuntoCartUpdateBefore($observer)
    {
    }  

	public function addDescontoCompreJunto($observer){
		/*$event = $observer->getEvent();
		$product = $event->getProduct();
		echo $product->getData('created_at');
		die;
		
		Mage::getSingleton('core/session')->setData(
			'comprejunto', 
				array(
					$product->getId() => $product->getData('created_at')
				)
		);
		
	$session = Mage::getSingleton('core/session')->getData('comprejunto');*/
		
	}
	
}
