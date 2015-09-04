<?php

	class BIS2BIS_Marcascategoria_Model_Renderers_Marca extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

		public function render(Varien_Object $row){

			$value =  $row->getData($this->getColumn()->getIndex());
			$data = explode(',', $value);
			
			// todas as labels
			$options = Mage::getModel("eav/config")->getAttribute("catalog_product" , 'marcas')->getSource()->getAllOptions();

			foreach ($data as $key => $value) {
				foreach ($options as $option) {
					if ($option['value'] == $value) {
						$marcas .= $option['label'] .' ';
					}
				}
			}

			// print_r($marcas); die;

			// echo '<pre>';
			// print_r($options);
			// echo '<br>';
			// print_r($data);
			// echo '</pre>';


			//die;

			return $marcas;
		}
	}