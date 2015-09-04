<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Participants
 *
 * @author Intel
 */
class BIS2BIS_Motoboyconfig_Block_RangeCep extends Mage_Adminhtml_Block_Widget_Grid_Container {

     public function __construct()
    {
        $this->_blockGroup = "motoboyconfig";
        $this->_controller = 'RangeCep';
        $this->_headerText = Mage::helper('motoboyconfig')->__('Faixa de Cep - Motoboy');
        parent::__construct();
        $this->_removeButton('add');
    }
    
    
}

?>
