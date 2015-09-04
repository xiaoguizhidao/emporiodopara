<?php

class BIS2BIS_ClearsaleStart_Model_Mysql4_Payment extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('clearsalestart/payment', 'entity_id');
    }
}
