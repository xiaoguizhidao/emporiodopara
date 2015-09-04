<?php
class BIS2BIS_Deposito_Block_Form_Deposito extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('deposito/form/deposito.phtml');
    }
    
}