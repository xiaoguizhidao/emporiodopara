<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class BIS2BIS_Shipping_Block_Managetables extends Mage_Adminhtml_Block_Widget_Form{

    public function __construct()
    {
            parent::__construct();
            $this->setTemplate('bis2bis/shipping/managetables.phtml');
    }

}
?>
