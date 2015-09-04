<?php 

class BIS2BIS_Ebit_Model_Html  extends Mage_Core_Model_Abstract  {

	public function mostrarBanner(){
		echo Mage::getStoreConfig('ebit/config/script'); 
	}

}

?>