<?php
class Mage_Osc_Block_Renderers_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		if($value){
			return Mage::getModel('customer/customer')->load($value)->getName();
		}
		return 'Sem Cliente';
	}
}
?>