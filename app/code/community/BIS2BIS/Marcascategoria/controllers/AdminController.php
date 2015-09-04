<?php

/**
 * 
 *
 * @author Helton
 */
	class BIS2BIS_Marcascategoria_AdminController extends Mage_Adminhtml_Controller_Action {


		public function _initAction() {
	        $this->loadLayout()->_addBreadcrumb(Mage::helper('marcascategoria')->__('Marcas Categoria'), Mage::helper('marcascategoria')->__('marcascategoria'));
	        return $this;
	    }

	    public function indexAction(){
	    	$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/listamarcas'));
			$this->renderLayout();
	    }

		/**
		
		 Configuração
		
		**/ 
		// chamar a grid de configuração
		public function configAction(){
			$config = Mage::getModel('marcascategoria/config')->load(1);
			$_id = $config->getData('id');
			$_active = $config->getData('active');
			$_attributename = $config->getData('attribute_name');
			$_https = $config->getData('https');

			Mage::register('id', $_id);
			Mage::register('active', $_active);
			Mage::register('attribute_name', $_attributename);
			Mage::register('https', $_https);

			$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/Config'));
			$this->renderLayout();
		}

		public function salvarConfigAction(){
			
			$params = $this->getRequest()->getParams();
			$config = Mage::getModel('marcascategoria/config')->load(1);
			$config->setData('active', $params['active']);
			$config->setData('attribute_name', $params['attribute_name']);
			$config->setData('https', $params['https']);

			try {
				$config->save();
				Mage::getSingleton('adminhtml/session')->addSuccess('Configurações salvas.');
				$this->_redirect('*/*/config');
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		/**
		
		 Listas de marcas
		
		**/ 
		public function listasAction(){
			$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/Listas'));
			$this->renderLayout();
		}

		// formulário para criar uma lista de marcas e alocar em uma categoria
		public function criarlistaAction(){
			$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/Criarlista'));
			$this->renderLayout();
		}

		public function salvarlistaAction(){
			$params = $this->getRequest()->getParams();

			if (isset($params['id'])) {
				$marcascategoria = Mage::getModel('marcascategoria/marcas')->load($params['id']);
			} else {
				$marcascategoria = Mage::getModel('marcascategoria/marcas');
			}

			$_name = $params['name'];
			$_active = $params['active'];
			$_category = $params['category'];
			$_marcas = implode($params['marcasId'], ',');

			$marcascategoria->setData('name', $_name);
			$marcascategoria->setData('active', $_active);
			$marcascategoria->setData('category', $_category);
			$marcascategoria->setData('marcas', $_marcas);

        	try {
        		$marcascategoria->save();
        		Mage::getSingleton('adminhtml/session')->addSuccess('Lista salva com sucesso!');
				$this->_redirect('*/*/listas');
        	} catch (Exception $e) {
        		Mage::register('active', $active);
        		Mage::register('name', $_name);
        		Mage::register('category', $_category);
        		Mage::register('marcas', $_marcas);

        		Mage::getSingleton('adminhtml/session')->addError('Lista não foi salva! ' . $e->getMessage());
				
        		$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/Criarlista'));
				$this->renderLayout();
        	} 
		}

		public function editarlistaAction(){
			$params = $this->getRequest()->getParams();
        	$id = $params['id'];

        	if ($id) {
        		$lista = Mage::getModel('marcascategoria/marcas')->load($id);

        		Mage::register('id', $lista->getData('id'));
        		Mage::register('active', $lista->getData('active'));
        		Mage::register('name', $lista->getData('name'));
        		Mage::register('category', $lista->getData('category'));
        		Mage::register('marcas', $lista->getData('marcas'));

        		$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/Criarlista'));
				$this->renderLayout();
        	}
		}

		public function deleteAction(){
			$params = $this->getRequest()->getParams();
        	$id = $params['id'];
        	if ($id) {
        		$lista = Mage::getModel('marcascategoria/marcas')->load($id);
        		$lista->delete();
        		Mage::getSingleton('adminhtml/session')->addSuccess('Lista excluida com sucesso!');
				$this->_redirect('*/*/listas');
        	}
		}

		/**
		
		 Marcas Cadastradas (crud)
		
		**/ 

		public function listamarcasAction(){
			$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/Listamarcas'));
			$this->renderLayout();
		}

		// cadastrar
		public function gerenciarmarcasAction(){
			$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/Gerenciarmarcas'));
			$this->renderLayout();
		}

		public function editarmarcaAction(){
			$params = $this->getRequest()->getParams();
        	$id = $params['id'];

        	if ($id) {

        		$option = Mage::getModel('eav/entity_attribute_option')->load($id);
        		$name = Mage::getModel('marcascategoria/marcas')->getMarcaValue($id);

        		Mage::register('id', $id);
        		Mage::register('sort_order', $option->getData('sort_order'));
        		Mage::register('image', $option->getData('image'));
        		Mage::register('link', $option->getData('thumb'));
		        Mage::register('name', $name);
        	}

			$this->_initAction()->_addContent($this->getLayout()->createBlock('marcascategoria/Gerenciarmarcas'));
			$this->renderLayout();
		}


		// Salva a cor no banco de dados
		public function savemarcaAction(){
			$params = $this->getRequest()->getParams();

			$id = $params['id'];
			$name = $params['name'];
			$sort_order = $params['sort_order'];
			$image = $params['image'];
			$link = $params['link'];

			$attribute_marca_id = Mage::getModel('marcascategoria/marcas')->getAttributeMarcaId();
			$attr_model = Mage::getModel('catalog/resource_eav_attribute');
			$attr_model->load($attribute_marca_id);

			$data = array();
			$path = Mage::getBaseDir('media') . DS . 'wysiwyg';

			try {

				if ($id) {// edição
					// exclui imagem caso esteja enviando outra
					$image = Mage::getModel('marcascategoria/marcas')->getImagemmarca($id);

					if ($params['image']['delete'] == 1) {
						$image = strstr($image, 'wysiwyg/');

						$mediaurl = Mage::getBaseDir('media').DS.$image;
						if (is_file($mediaurl)) {
							unlink($mediaurl); // apaga imagem antiga
						}
					}
					//enviando imagem para media/wysiwyg

					if($_FILES['image']['tmp_name']) {

						$uploader = new Varien_File_Uploader('image');
						$allow_mime_type = array("image/jpeg", "image/pjpeg", "image/jpeg", "image/pjpeg", "image/png", "image/gif");
                        if (!$uploader->checkMimeType($allow_mime_type)){
                            Mage::throwException("Arquivo inválido.");
                        }
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
						
						$uploader->save($path, $_FILES['image']['name']);
						$image = 'wysiwyg/'.$_FILES['image']['name'];

					} else {
						$image = $image;
					}

					$data['option'] = 
						array(
							'value' => array( $id => array( 0 => $name )),
							'order' => array( $id => $sort_order ),
							'thumb' => array( $id => $link ),
							'image' => array( $id => $image )
						);

					$attr_model->addData($data);

				} else { // inserção
					//enviando imagem para media/wysiwyg
					if(isset($_FILES['image'])) {
						$uploader = new Varien_File_Uploader('image');
						$allow_mime_type = array("image/jpeg", "image/pjpeg", "image/jpeg", "image/pjpeg", "image/png", "image/gif");
                        if (!$uploader->checkMimeType($allow_mime_type)){
                            Mage::throwException("Arquivo inválido.");
                        }
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
						
						$uploader->save($path, $_FILES['image']['name']);
						$image = 'wysiwyg/'.$_FILES['image']['name'];
					}

					$value['option'] = array($name);
		            $result = array('value' => $value);
		            $attr_model->setData('option',$result);

		            $option_id = Mage::getModel('marcascategoria/marcas')->getMarcaOptionId($name);
					
					$data['option'] = 
						array(
							'value' => array( $option_id => array( 0 => $name )),
							'order' => array( $option_id =>  $sort_order ),
							'thumb' => array( $option_id =>  $link ),
							'image' => array( $option_id => $image )
						);
					$attr_model->addData($data);
				}

				$attr_model->save();
						
				Mage::getSingleton('adminhtml/session')->addSuccess('Attributo salvo com sucesso.');
			 	$this->_redirect('*/*/listamarcas');

			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/gerenciarmarcas');				
			}
		}

		public function excluirmarcaAction($id){
			$params = $this->getRequest()->getParams();
        	$id = $params['id'];

          	if (isset($id)) {
				try {
					$attribute_marca_id = Mage::getModel('marcascategoria/marcas')->getAttributeMarcaId();
					$attr_model = Mage::getModel('catalog/resource_eav_attribute');
					$attr_model->load($attribute_marca_id);

					$image = Mage::getModel('marcascategoria/marcas')->getImagemmarca($id);
					$mediaurl = $path .'/'. $image;
					unlink($mediaurl); // apaga imagem antiga

					$deletes = array(
			            $id => 1
			        );

			        $values = array(
			            $id => array(
			                0=> '',
			                1=> '' //0 is current store id, Apple is the new label for the option
			            )
			        );

					$data['option']['delete'] = $deletes;
					$data['option']['value'] = $values;

					
					$attr_model->addData($data);
					$attr_model->save();

					Mage::getSingleton('adminhtml/session')->addSuccess('Attributo excluído com sucesso.');
				 	$this->_redirect('*/*/listamarcas');
				} catch (Exception $e) {
					
				}

			}
		}
	}