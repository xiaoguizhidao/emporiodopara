<?php

class BIS2BIS_Banner_Model_TypeRenderer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{

		$value =  $row->getData($this->getColumn()->getIndex());
		if($value == 1){
			$text = 'Topo';
			return '<span style="color:#669933; font-weight : bold;">'.$text.'</span>';
		}else if($value == 2){
			$text = 'Esquerda';
			return '<span style="color:#669966; font-weight : bold;">'.$text.'</span>';
		}else if($value == 3){
			$text = 'Direita';
			return '<span style="color:#669999; font-weight : bold;">'.$text.'</span>';
		}else if($value == 4){
			$text = 'Rodapé';
			return '<span style="color:#6699CC; font-weight : bold;">'.$text.'</span>';
		}else if($value == 5){
			$text = 'Categoria';
			return '<span style="color:#6699FF; font-weight : bold;">'.$text.'</span>';
		}else if($value == 6){
			$text = 'Conteúdo';
			return '<span style="color:#6699FF; font-weight : bold;">'.$text.'</span>';
		}



	}
}
?>