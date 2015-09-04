<?php

class BIS2BIS_ClearsaleStart_Model_Environment
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('clearsalestart')->__('Homologação')),
            array('value' => 0, 'label'=>Mage::helper('clearsalestart')->__('Produção')),
        );
    }

}
