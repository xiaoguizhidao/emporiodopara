<?php
class BIS2BIS_Shipping_Block_Adminhtml_Shipping extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'bis2ship';
        $this->_controller = 'adminhtml_shipping';
        $this->_headerText = $this->__('Gerenciamento de Tabelas');

        $this->_addButton('new_button', array(
            'label'     => Mage::helper('bis2ship')->__('Adicionar transportadora'),
      		'onclick' => "setLocation('{$this->getUrl('*/*/registerTable')}')"
        ));
        parent::__construct(); // after ???!?!?!?
        $this->_removeButton('add'); // remove Add test button
    }
}