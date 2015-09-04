<?php
class BIS2BIS_Shipping_Block_Renderers_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		$retorno = '';
		$table_model = Mage::getModel('bis2ship/shippingtables')->load($value);
		
		if($table_model->getActive() == 0){
			$retorno .= '<div id="inactive" ><span>Inativo</span></div>';
		}else{
			$retorno .= '<div id="active" ><span>Ativo</span></div>';
		}

		return $retorno;

	}
}
?>