<?php
class BIS2BIS_Cores_AdminController extends Mage_Adminhtml_Controller_Action{



	/**
	Novos métodos Cores
	*/
	public function _initAction() {
		$this->loadLayout()->_addBreadcrumb(Mage::helper('cores')->__('Cores'), Mage::helper('cores')->__('Cores'));
		return $this;
	}

	// salva
	public function salvarAction(){
		$params = $this->getRequest()->getParams();
		$files = $_FILES;
		$id = $params['id'];
		$nome  = $params['nome'];
		$cor   = $params['cor'];

		if(empty($cor)){
			$cor = "#000000";
		}
		
		$cores = Mage::getModel('cores/cores');
		if(!$id){
			$resultado = $cores->salvarAtributo($nome);
		}else{
			$cores->load($id);
			$id_opcao = $cores->obterIdOpcao($cores->getNome());
			$resultado = $cores->editarAtributo($id_opcao, $nome); // edita a cor
		}
		
		if($resultado){
			$imagem = $this->salvarImagem($_FILES);
			$cores->setNome($nome);
			$cores->setCor($cor);
			if($imagem){
				$cores->setImagem($imagem);
			}
			if(array_key_exists('remover_imagem', $params)){
				unlink(Mage::getBaseDir('media') . DS . 'cores' . DS . $cores->getImagem());			
				$cores->setImagem('');
			}
			$cores->save();
			$this->_getSessao()->addSuccess('Cor salva com sucesso');
		}else{
			$this->_getSessao()->addError('Não foi possível salvar a cor');
		}	
		$this->_redirect('*/*/lista');	
	}


	// salva imagens
	public function salvarImagem($_files){
		foreach(array_keys($_files) as $key){
            if(strpos($key, 'imagem') === 0){
               if(isset($_FILES[$key]['name']) && $_FILES[$key]['name'] != ''){
               		 try{       
                        $path = Mage::getBaseDir().DS. 'media' . DS .  'cores' . DS;  //desitnation directory
                        $short_path = 'cores' . DS ;     
                        $fname = $_FILES[$key]['name']; //file name           

                        while(file_exists($path . $fname)){
                            $fname = 'file_' . $fname; 
                        }

                        $fname = str_replace(' ', '', $fname);

                        $file_index = str_replace('upload_file', '', $key);

                        $name_file = $params['name_file'.$file_index];

                        if($name_file == '') $name_file = $fname;

                        $uploader = new Varien_File_Uploader($key); //load class
                        $uploader->setAllowedExtensions(array('jpg','png', 'gif')); //Allowed extension for file
                        $uploader->setAllowCreateFolders(true); //for creating the directory if not exists
                        $uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
                        $uploader->setFilesDispersion(false);
                        $uploader->save($path,utf8_decode($fname)); //save the file on the specified path
                        return $fname;

                    }
                    catch (Exception $e){
                        echo 'Error Message: '.$e->getMessage(); exit;
                    }
               }
            }
       }
       return false;
	}

	// Sincroniza as cores dos atributos com as cores da tabela de cor
	public function sincronizarAction(){	
		$cor = Mage::getModel('cores/cores');
		$opcoes = $cor->obterOpcoes();
		foreach($opcoes as $opcao){
			if(!$cor->existe($opcao['label'])){
				$nova_cor = Mage::getModel('cores/cores');
				$nova_cor->setNome($opcao['label']);
				$nova_cor->save();
			}
		}
		$this->_getSessao()->addSuccess('Atributos selecionados');
		$this->_redirect('*/*/lista');

	}


	// Lista todas as cores
	public function listaAction(){	
		$url_chrome =  str_replace('index.php/', '', Mage::getBaseUrl() . 'skin/adminhtml/default/default/images/cores/chrome.png' ) ;
		// $this->_getSessao()->addNotice('Usar apenas no GOOGLE CHROME ' . '<img src="'.$url_chrome.'" />' );

		$this->_initAction()->_addContent($this->getLayout()->createBlock('cores/Adminhtml_Lista'));
		$this->renderLayout();
	}

	//  tela de cadastro
	public function cadastrarAction(){
		$url_chrome =  str_replace('index.php/', '', Mage::getBaseUrl() . 'skin/adminhtml/default/default/images/cores/chrome.png' ) ;
		// $this->_getSessao()->addNotice('Usar apenas no GOOGLE CHROME ' . '<img src="'.$url_chrome.'" />' );
		$this->_initAction()->_addContent($this->getLayout()->createBlock('cores/Adminhtml_Cadastro'));
		$this->renderLayout();
	}	


	// deletar
	public function deletarAction(){
		$params = $this->getRequest()->getParams();
		$id = $params['id'];
		$cor = Mage::getModel('cores/cores');
		$cor->load($id);
		$imagem = $cor->getImagem();
		$id_opcao = $cor->obterIdOpcao($cor->getNome());
		$deletar_atributo = $cor->deletarAtributo($id_opcao);
		if($imagem){
			unlink(Mage::getBaseDir('media') . DS . 'cores' . DS . $imagem);			
		}
		$cor->delete();
		$this->_getSessao()->addSuccess('Cor removida com sucesso');
		$this->_redirect('*/*/lista');
	}


	//  tela de cadastro
	public function editarAction(){
		$params = $this->getRequest()->getParams();
		$id = $params['id'];
		$cor = Mage::getModel('cores/cores')->load($id);
		Mage::register('id',   $id);
		Mage::register('cor',   $cor->getCor());
		Mage::register('nome',  $cor->getNome());
		Mage::register('imagem',  $cor->getImagem());
		$this->_initAction()->_addContent($this->getLayout()->createBlock('cores/Adminhtml_Cadastro'));
		$this->renderLayout();
	}

	// retorna sessao
	public function _getSessao(){
		return Mage::getSingleton('adminhtml/session');
	}
	
}