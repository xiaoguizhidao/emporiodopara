<?php

class Mage_Osc_Model_Log extends Mage_Core_Model_Abstract{

	private $db;
	 
    protected function _construct(){
    	$this->db = new Varien_Data_Collection_Db();
    	$this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('osc_write'));
        $this->_init('osc/log');
    }


	
}

?>