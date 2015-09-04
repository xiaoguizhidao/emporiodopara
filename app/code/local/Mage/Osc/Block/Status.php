<?php
class Mage_Osc_Block_Status extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'osc';
        $this->_controller = 'status';
        $this->_headerText = $this->__('Gerenciamento de Status');

        $this->_removeButton('add'); // remove Add test button
        
        parent::__construct();
    }
}