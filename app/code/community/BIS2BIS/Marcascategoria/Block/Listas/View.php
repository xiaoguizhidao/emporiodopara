<?php

	class BIS2BIS_Marcascategoria_Block_Listas_View extends Mage_Core_Block_Template {


		public function getAllMarcas(){
			$marcas = Mage::getModel('marcascategoria/marcas')->getMarcasOptions();

			$marcas_model = Mage::getModel('marcascategoria/config')->load(1);
			$attribute_name = $marcas_model->getData('attribute_name');

			if ($marcas_model->getData('active') == 1) {
				$http = 'https://';
			} else {
				$http = 'http://';
			}
			
			if ($attribute_name) {

				foreach ($marcas as $key => $marca) {
					$marcas[$key]['image'] = Mage::getModel('marcascategoria/marcas')->getImagemmarca($marca['value']);
					
						$marcas[$key]['url'] = Mage::getModel('marcascategoria/marcas')->getMarcaLink($marca['value']);
					
				}
				return $marcas;
			}
		}

		public function getListaMarcas($id = null){
			if ($id) {
				$collection = Mage::getModel('marcascategoria/marcas')->getCollection();

				$marcas_model = Mage::getModel('marcascategoria/config')->load(1);
				$attribute_name = $marcas_model->getData('attribute_name');
				$active = $marcas_model->getData('active');

				$active = $collection->addFieldToFilter('category', $id)->getFirstItem()->getData('active');

				if ($active) {
					
					if ($marcas_model->getData('active') == 1) {
						$http = $marcas_model->getData('https');
					} else {
						$http = $this->getBaseUrl();
					}

					if ($attribute_name) {

						$marcas = $collection->addFieldToFilter('category', $id)->getFirstItem()->getData('marcas');

						if ($marcas) {
							$marcas = explode(',', $marcas);
							$marcasobj = array();
							foreach ($marcas as $key => $marca) {
								$marcasobj[$key]['id'] = $marca;
								$marcasobj[$key]['image'] = Mage::getModel('marcascategoria/marcas')->getImagemmarca($marca);
								$marcasobj[$key]['name'] = Mage::getModel('marcascategoria/marcas')->getMarcaName($marca);
								$marcasobj[$key]['url'] = Mage::getModel('marcascategoria/marcas')->getMarcaLink($marca);
							}
						}
						
						return $marcasobj;
					}
				}
			}
		}
	}