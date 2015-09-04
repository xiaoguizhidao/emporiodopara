<?php

class BIS2BIS_Banner_Model_Category extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		return Mage::getModel('catalog/category')->load($value)->getName();
	}
}
?>