<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

	class BIS2BIS_Marcascategoria_Block_Renderers_Active extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

		public function render(Varien_Object $row){
			$value =  $row->getData($this->getColumn()->getIndex());

			switch ($value) {
				case 0:
					return 'Desabilitado';
					break;

				case 1:
					return 'Habilitado';
					break;
			}
		}
	}