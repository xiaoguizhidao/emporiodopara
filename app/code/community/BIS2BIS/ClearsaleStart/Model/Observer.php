<?php

class BIS2BIS_ClearsaleStart_Model_Observer
{


    /**
     *  Get queue of datas of orders
     *
     *  @return string
     */
    public static function analyseOrder($observer, $analyse = false)
    {
        try {
            $order  = $observer->getEvent()->getOrder();
        } catch(Mage_Core_Exception $e) {

        }
    }



}
