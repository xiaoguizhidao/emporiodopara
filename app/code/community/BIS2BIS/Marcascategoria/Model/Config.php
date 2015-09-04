<?php

/**
 * 
 *
 * @author Helton Hayashi
 */
	class BIS2BIS_Marcascategoria_Model_Config extends Mage_Core_Model_Abstract {

		private $db;
	 
	    protected function _construct(){
	    	$this->db = new Varien_Data_Collection_Db();
	    	$this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('marcascategoria_write'));
	        $this->_init('marcascategoria/config');
	    }
	}