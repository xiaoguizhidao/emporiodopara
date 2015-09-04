<?php

class BIS2BIS_Freteproduto_IndexController extends Mage_Core_Controller_Front_Action{
	
	
	//grava carrinho atual do cliente
	function keepcart(){
		$cartHelper = Mage::helper('checkout/cart');
        $items = $cartHelper->getCart()->getItems();  // recupera items do atual carrinho
		Mage::getSingleton("core/session")->setKeepcart($items);
	}
	
	
	function indexAction(){
			
		$params = $this->getRequest()->getParams();
		$keys = array_keys($params);
		$product_id = $params['product_id'];
		$qty 		= $params['qty'];
		$postcode   = $params['postcode'];
		if($qty == ''){ $qty = 1; }
		
		$_quote = $this->_getQuote(); // Instancia o Quote
		$_cart = $this->_getCart();
		$country   = 'BR';
        $_quote->getShippingAddress()->setCountryId($country)->setPostcode($postcode);
		$_product = Mage::getModel('catalog/product')->load($product_id);
		
		$option_arr = array(); 
		
		if(count($_product->getOptions()) > 0 ) {
				 foreach($_product->getOptions() as $_opt){
					$option_arr[($_opt->getData('option_id'))] ;
					foreach($_opt->getValues() as $ovalues){
						$option_arr[($_opt->getData('option_id'))]  = ($ovalues->getData('option_type_id'));
					}
				}
		}
		
		$options = array();
		$options['product'] = $_product->getId();

		if(count($option_arr) > 0 ){
			$options['options'] = $option_arr;
		}
		
		$options['qty'] = $qty;
		
		if($_product->getData('type_id') == 'simple'){
			try {
				$options_obj =  new Varien_Object($options);
				$_quote->addProduct($_product, $options_obj); // , array('qty' => $qty));
			}
			catch (Exception $ex) {
		    	echo $ex->getMessage(); 
			}
		}elseif($_product->getData('type_id') == 'configurable'){
			$attributes = array();
			foreach($keys as $key){
				if(strpos($key, 'attr') === 0){
					$attributes[] = array(
						'code' =>   Mage::getModel("catalog/entity_attribute")->load(str_replace('attr', '', $key))->getData("attribute_code"),
						'value' => $params[$key]
					);
				}				
			}
			$configurable = Mage::getModel('catalog/product')->load($product_id);
  			$product_collection = $configurable->getTypeInstance()->getUsedProductCollection()->addAttributeToSelect('*');
			foreach($attributes as $attr){
				$product_collection->addFieldToFilter($attr['code'], $attr['value']);
			}
			$_product = $product_collection->getFirstItem(); 
			
			try {
				 $_quote->addProduct($_product); // , array('qty' => $qty));
			}
			catch (Exception $ex) {
			}
		}elseif($_product->getData('type_id') == 'bundle'){
			
		}elseif($_product->getData('type_id') == 'grouped'){
			
		}
		
		$itemToUpdate = $_quote->getItemByProduct($_product);
		$itemToUpdate->setQty($qty);
		$_quote->getShippingAddress()->collectTotals();
		$_quote->getShippingAddress()->setCollectShippingRates(true);
		$_quote->getShippingAddress()->collectShippingRates();
		$shipping_address = $_quote->getShippingAddress();
		$_collect = $shipping_address->getGroupedAllShippingRates();
		
		$arr_results = array();
		
		foreach ($_collect as $code => $_rates){
			foreach ($_rates as $_rate){
				$error_message = $_rate->getData('error_message');
				if(isset($error_message))
					$arr_results[] = '<div class="div-shipping" ><span class="error-shipping" >' . $_rate->getData('carrier_title') . ' - ' . $error_message . '</span></div>';
				else
					$arr_results[] =  $_rate->getMethodTitle()  . ' - ' . Mage::helper('core')->currency($_rate->getPrice());
			}
		}
		
		echo json_encode($arr_results);
	}

    public function _getQuote(){
        return Mage::getModel('sales/quote');
    }
	
    public function _getCart(){
        return Mage::getSingleton('checkout/cart');
    }
	
	
}

 ?>