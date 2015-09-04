<?php

	class BIS2BIS_Boleto_Model_Status {
		
	    public function toOptionArray(){
	    	$orderStatus = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
	    	$i = 0;
	    	foreach($orderStatus as $status){
	    		$data[$i]['value'] = $status['status'];
	    		$data[$i]['label'] = Mage::helper('adminhtml')->__($status['label']);
	    		$i++;
	    	}
	    	return $data;
	    }

	}

?>