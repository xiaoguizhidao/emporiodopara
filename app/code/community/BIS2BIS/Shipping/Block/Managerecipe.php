<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class BIS2BIS_Shipping_Block_Managerecipe extends Mage_Adminhtml_Block_Widget_Form{

    public function __construct()
    {
            parent::__construct();
            $this->setTemplate('bis2bis/shipping/managerecipe.phtml');
    }

}
?>
