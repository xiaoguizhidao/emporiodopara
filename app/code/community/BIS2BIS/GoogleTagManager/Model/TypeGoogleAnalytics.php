<?php

	class BIS2BIS_GoogleTagManager_Model_TypeGoogleAnalytics{

	    public function toOptionArray(){
	        return array(
	            array('value' => 'old_version', 'label' => Mage::helper('adminhtml')->__('Antigo')),
	            array('value' => 'new_version', 'label' => Mage::helper('adminhtml')->__('Novo'))
	        );
	    }

	}

?>