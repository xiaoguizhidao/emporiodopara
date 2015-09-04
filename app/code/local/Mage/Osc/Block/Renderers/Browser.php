<?php
class Mage_Osc_Block_Renderers_Browser extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		if(strpos($value, 'MSIE')){
			if(strpos($value, 'MSIE 10.0')){
				return '<div style=" text-align : center; line-height: 30px; background : #0066CC; color : #FFF; width : 100%; height : 30px;" >Internet Explorer 10</div>';
			}elseif (strpos($value, 'MSIE 9.0')) {
				return '<div style=" text-align : center; line-height: 30px; background : #0066CC; color : #FFF; width : 100%; height : 30px;" >Internet Explorer 9</div>';
			}elseif (strpos($value, 'MSIE 8.0')) {
				return '<div style=" text-align : center; line-height: 30px; background : #0066CC; color : #FFF; width : 100%; height : 30px;" >Internet Explorer 8</div>';
			}elseif (strpos($value, 'MSIE 7.0')) {
				return '<div style=" text-align : center; line-height: 30px; background : #0066CC; color : #FFF; width : 100%; height : 30px;" >Internet Explorer 7</div>';
			}else{
				return '<div style=" text-align : center; line-height: 30px; background : #0066CC; color : #FFF; width : 100%; height : 30px;" >Internet Explorer</div>';
			}
		}elseif(strpos($value, 'Firefox')){
			return '<div style=" text-align : center; line-height: 30px; background : #CC6600; color : #FFF; width : 100%; height : 30px;" >Firefox</div>';
		}elseif(strpos($value, 'Chrome')){
			return '<div style=" text-align : center; line-height: 30px; background : #99CC99; color : #FFF; width : 100%; height : 30px;" >Chrome / Safari.</div>';
		}
		else{
			return $value;
		}
	}
}
?>