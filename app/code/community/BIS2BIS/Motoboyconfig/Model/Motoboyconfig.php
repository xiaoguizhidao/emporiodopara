<?php
class BIS2BIS_Motoboyconfig_Model_Motoboyconfig extends BIS2BIS_Motoboyconfig_Model_Abstract{
    private $db;
    
    protected function _construct(){
    	$this->db = new Varien_Data_Collection_Db();
    	$this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('motoboyconfig_write'));
        $this->_init('motoboyconfig/motoboyconfig');
    }

   
    
}