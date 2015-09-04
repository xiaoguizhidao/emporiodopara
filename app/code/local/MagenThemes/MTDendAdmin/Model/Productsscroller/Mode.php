<?php
/*------------------------------------------------------------------------
# APL Solutions and Vision Co., LTD
# ------------------------------------------------------------------------
# Copyright (C) 2008-2010 APL Solutions and Vision Co., LTD. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: APL Solutions and Vision Co., LTD
# Websites: http://www.joomlavision.com/ - http://www.magentheme.com/
-------------------------------------------------------------------------*/ 
class MagenThemes_MTDendAdmin_Model_Productsscroller_Mode
{

    public function toOptionArray()
    {        
        return array(
            array('value'=>'latest', 'label'=>Mage::helper('adminhtml')->__('Latest Product')),
            array('value'=>'bestseller', 'label'=>Mage::helper('adminhtml')->__('Best Seller Product')),
            array('value'=>'mostviewed', 'label'=>Mage::helper('adminhtml')->__('Most Viewed Product')),
            array('value'=>'featured', 'label'=>Mage::helper('adminhtml')->__('Featured Products')),
            array('value'=>'new', 'label'=>Mage::helper('adminhtml')->__('New Product'))
        );
    }

}