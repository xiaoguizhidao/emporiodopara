<?php 

class BIS2BIS_Gmerchant_Model_Categorias extends Mage_Core_Model_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->_init('gmerchant/categorias');
    }

}
