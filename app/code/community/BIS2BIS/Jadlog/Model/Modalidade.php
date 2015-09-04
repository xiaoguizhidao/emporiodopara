<?php

class BIS2BIS_Jadlog_Model_Modalidade
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => 'Expresso'),
            array('value' => 3, 'label' => '.Package'),
            array('value' => 4, 'label' => 'Rodoviário'),
            array('value' => 5, 'label' => 'Econômico'),
            array('value' => 6, 'label' => 'Doc'),
            array('value' => 7, 'label' => 'Corporate'),
            array('value' => 9, 'label' => '.Com'),
            array('value' => 10, 'label' => 'Internacional'),
            array('value' => 12, 'label' => 'Cargo'),
            array('value' => 14, 'label' => 'Emergêncial')
        );
    }

}
