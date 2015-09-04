<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CheckoutAdmin
 *
 * @author Intel
 */
class Mage_Osc_Block_CheckoutAdmin extends Mage_Adminhtml_Block_Widget_Form_Container {

    protected function _prepareLayout() {
        //$formFilho = Mage::registry('action');
        $this->setChild('form', $this->getLayout()->createBlock('osc/CheckoutForm'));
        return parent::_prepareLayout();
    }

    public function __construct() {
        $this->_objectId = 'id';
        $this->_blockGroup = 'osc';
        $this->_controller = 'admin';
        parent::__construct();
        $this->_updateButton('save', 'label', 'Salvar Configurações');
    }


    public function getHeaderText() {
        return 'Configuração Checkout de Pedido';
    }


    public function getSaveUrl() {
        return $this->getUrl('*/' . $this->_controller . '/save', array('_current' => true, 'back' => null));
    }

}

?>
