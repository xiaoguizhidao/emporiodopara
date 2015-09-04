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
class BIS2BIS_Banner_Block_Bannergrid extends Mage_Adminhtml_Block_Widget_Grid_Container {

     public function __construct()
    {
        $this->_blockGroup = "banner";
        $this->_controller = 'Bannergrid';
        $this->_headerText = Mage::helper('banner')->__('Lista de Banners');
        parent::__construct();
        $this->_removeButton('add');
    }
    
    
}

?>
