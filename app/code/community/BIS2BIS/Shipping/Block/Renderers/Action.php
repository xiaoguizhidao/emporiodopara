<?php
class BIS2BIS_Shipping_Block_Renderers_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		$retorno = '';
		$table_model = Mage::getModel('bis2ship/shippingtables')->load($value);

		$retorno ='<a  title="Editar Tabela" href="'.  Mage::helper('adminhtml')->getUrl('bis2ship/admin/registerTable/id/'.$value) . '" class="editar_tabela"> <img src="' . $this->getSkinUrl('images/shipping/edit.png') . '" /> </a>';    
		
		$retorno .= '<a  href="'.  Mage::helper('adminhtml')->getUrl('bis2ship/admin/manageRecipe/id/'.$value) . '" title="Editar Fórmula" ><img src="' . $this->getSkinUrl('images/shipping/formula.png') . '" /></a> 
		 	<a href="'.  Mage::helper('adminhtml')->getUrl('bis2ship/admin/fillTable/id/'.$value) . '" title="Editar preenchimento da tabela" ><img src="' . $this->getSkinUrl('images/shipping/table.png') . '" /></a> ';

		return $retorno;

	}
}
?>