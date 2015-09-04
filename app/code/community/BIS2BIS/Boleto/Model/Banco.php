<?php

	class BIS2BIS_Boleto_Model_Banco {
		
	    public function toOptionArray(){
	        return array(
			    array('value' => 'bb', 'label' => Mage::helper('adminhtml')->__('Banco do Brasil')),
				array('value' => 'bradesco', 'label' => Mage::helper('adminhtml')->__('Bradesco')),
				array('value' => 'cef', 'label' => Mage::helper('adminhtml')->__('Caixa Economica Federal')),
				array('value' => 'cef_sinco', 'label' => Mage::helper('adminhtml')->__('Caixa Economica Federal Sinco')),
				array('value' => 'cef_sigcb', 'label' => Mage::helper('adminhtml')->__('Caixa Economica Federal Sigcb')),
				array('value' => 'hsbc', 'label' => Mage::helper('adminhtml')->__('HSBC')),
				array('value' => 'itau', 'label' => Mage::helper('adminhtml')->__('Itau')),
	            array('value' => 'santander_banespa', 'label' => Mage::helper('adminhtml')->__('Santander')),
	            array('value' => 'sudameris', 'label' => Mage::helper('adminhtml')->__('Sudameris')),
	        );
	    }

	}

?>