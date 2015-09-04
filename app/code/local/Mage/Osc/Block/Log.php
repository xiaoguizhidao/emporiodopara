<?php
class Mage_Osc_Block_Log extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'osc';
        $this->_controller = 'log';
        $this->_headerText = $this->__('Gerenciamento de Log');

        $this->_removeButton('add'); // remove Add test button
        
        parent::__construct();
    }
}