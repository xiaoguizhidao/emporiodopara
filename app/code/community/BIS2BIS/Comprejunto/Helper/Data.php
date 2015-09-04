<?php
class BIS2BIS_Comprejunto_Helper_Data extends Mage_Core_Helper_Abstract{
	public function getTaxa() {
		return Mage::helper('core')->currency(number_format(Mage::getStoreConfig('comprejunto/config/taxa'), 2, '.', ''), true, false);
	}
}