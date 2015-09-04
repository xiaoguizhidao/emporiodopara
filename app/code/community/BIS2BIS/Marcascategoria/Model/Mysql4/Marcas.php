<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

class BIS2BIS_Marcascategoria_Model_Mysql4_Marcas extends Mage_Core_Model_Mysql4_Abstract {
    
    protected function _construct(){
        $this->_init('marcascategoria/marcas', 'id');
        $this->_isPkAutoIncrement = false;
    }
	
}
