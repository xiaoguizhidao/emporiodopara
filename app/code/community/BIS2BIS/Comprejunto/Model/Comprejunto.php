<?php
class BIS2BIS_Comprejunto_Model_Comprejunto extends Mage_Core_Model_Abstract{
    private $db;
    
    protected function _construct(){
    	$this->db = new Varien_Data_Collection_Db();
    	$this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('comprejunto'));
        $this->_init('comprejunto/comprejunto');
    }
}