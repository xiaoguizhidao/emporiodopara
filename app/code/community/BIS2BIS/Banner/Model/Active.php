<?php

class BIS2BIS_Banner_Model_Active extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		if($value == 0){
			return 'Habilitado';
		}else if($value == 1){
			return 'Desabilitado';
		}
	}
}
?>