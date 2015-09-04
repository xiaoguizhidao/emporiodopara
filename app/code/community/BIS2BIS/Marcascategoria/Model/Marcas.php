<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

	class BIS2BIS_Marcascategoria_Model_Marcas extends Mage_Core_Model_Abstract {

		private $db;
	 
	    protected function _construct(){
	    	$this->db = new Varien_Data_Collection_Db();
	    	$this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('marcascategoria_write'));
	        $this->_init('marcascategoria/marcas');
	    }

	    // pega o id atributo que será usado para marcas através do nome setado no config
	    public function getAttributeMarcaId(){
	    	$attribute_name = Mage::getModel('marcascategoria/config')->load(1)->getData('attribute_name');
	    	$attribute_id = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setCodeFilter($attribute_name)
                ->getFirstItem();
            $attribute_id = $attribute_id['attribute_id'];

            return $attribute_id;
	    }

	    // retorna todas as marcas
		public function getMarcasOptions(){
			$attribute_name = Mage::getModel('marcascategoria/config')->load(1)->getData('attribute_name');
			$attribute_model       		 = Mage::getModel('eav/entity_attribute');
	        $attribute_options_model	 = Mage::getModel('eav/entity_attribute_source_table') ;

	        $attribute_code        		 = $attribute_model->getIdByCode('catalog_product', $attribute_name);
	        $attribute              	 = $attribute_model->load($attribute_code);

	        $attribute_table       		 = $attribute_options_model->setAttribute($attribute);
	        $options               		 = $attribute_options_model->getAllOptions(false);

			return $options;
		}

		// retorna a marca pela id
		public function getMarcaValue($id){
			$options = Mage::getModel('marcascategoria/marcas')->getMarcasOptions();

	        foreach($options as $op){
	           	if ($op['value'] == $id) {
	         		return $op['label'];
	           	}
	        }
		}

		public function getMarcaOptionId($value){
			$options = Mage::getModel('marcascategoria/marcas')->getMarcasOptions();

	        foreach($options as $op){
	           	if ($op['label'] == $value) {
	         		return $op['label'];
	           	}
	        }
		}


		public function getImagemmarca($id = null){
			if ($id) {
				$marca = Mage::getResourceModel('eav/entity_attribute_option')->getAttributeOptionImages();
				return $marca[$id];
			}
		}

		public function getMarcaName($id){

			$marcas = $this->getMarcas();

			foreach ($marcas as $key => $marca) {
				if ($marca['value'] == $id) {
					return $marca['label'];
				}
			}

			return false;
		}

		public function getMarcaLink($id){

			$marca = Mage::getResourceModel('eav/entity_attribute_option')->getAttributeOptionThumbs();

			return $marca[$id];
		}




	}