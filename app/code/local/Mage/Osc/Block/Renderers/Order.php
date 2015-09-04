<?php
class Mage_Osc_Block_Renderers_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		if($value == null){
			return '<div style=" text-align : center; line-height: 30px; background : red; color : #FFF; width : 100%; height : 30px;" >Não foi gerado o pedido.</div>';
		}else{
			return '<div style=" text-align : center; line-height: 30px; background : green; color : #FFF; width : 100%; height : 30px;" >'.$value.'</div>';
		}
	}
}
?>