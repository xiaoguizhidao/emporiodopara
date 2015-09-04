<?php
class BIS2BIS_Gmerchant_Model_Mysql4_Categorias extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('gmerchant/categorias', 'id');
    }
}
