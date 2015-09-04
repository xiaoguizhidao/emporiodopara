<?php

class BIS2BIS_Gmerchant_Model_Mysql4_Gmerchant_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('gmerchant/gmerchant');
    }
}
