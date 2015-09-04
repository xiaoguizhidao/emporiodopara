<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

	class BIS2BIS_Marcascategoria_Block_Renderers_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

		public function render(Varien_Object $row){
			$value =  $row->getData($this->getColumn()->getIndex());

			if ($value) {
				
				if (strpos($value, 'http://') !== false) {
					$url  = $value;
				} else {
					$url  = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $value;
				}

				$image = '<img src="'.$url.'" width="20" height="20"/>';
				return $image;
			}
		}
	}