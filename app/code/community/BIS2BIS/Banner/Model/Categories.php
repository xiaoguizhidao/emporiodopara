<?php 
class BIS2BIS_Banner_Model_Categories {


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
 ?>