<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

	class BIS2BIS_Marcascategoria_Model_Category {

		public function toOptionArray() {

			$collection = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('name');
	       
			$category_array = array();

			foreach($collection as $category){
				if($category->getName() != ''){
					$category_array[] = array( 'label' => $category->getName(), 'value' => $category->getId() );
				}
			}

	        return $category_array;
	    }
	}