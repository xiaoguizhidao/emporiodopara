<?php
class BIS2BIS_Comprejunto_IndexController extends Mage_Adminhtml_Controller_Action{
    protected function _initAction($ids = null){
            $this->loadLayout($ids);
            return $this;
    }

    public function indexAction(){
            $this->geraGrid();
    }
	
	public function autocompleteAction(){
		die('teste');
	}
	
    public function formAction(){
            $par_id	= $this->getRequest()->getParam('id',-1);

            $modeloForm = Mage::getModel('comprejunto/comprejunto');
            $modeloForm->setData('id',$par_id);

            switch($par_id){
                    case -1:
                            $this->_redirect('*/*/');
                            break;

                    case 0:
                            Mage::register('action','Cadastro');
                            break;

                    default:
                            Mage::register('action','Edit');
                            Mage::register('modeloForm',$modeloForm);
                            break;
            }

            $this->geraFormulario();
    }

    public function deleteAction(){
            $par_id = $this->getRequest()->getParam('id');

            $modelo = Mage::getModel('comprejunto/comprejunto');
            $modelo->setData('id',$par_id);

            try{
                $modelo->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess('Operação Realizada com Sucesso!');
                $this->_redirect('*/*/index');

            }catch(Exception $ex){
                Mage::getSingleton('adminhtml/session')->addError('Erro ao Excluir. '.$ex->getMessage());
                $this->_redirect('*/*/edit',array('id' => $par_id));
            }
    }

    public function saveAction(){
		
            $dados = $this->getRequest()->getPost();
			
            $modelo = Mage::getModel('comprejunto/comprejunto');
            $modelo->setData($dados);

            if($this->getRequest()->getParam('id',0) != 0)
                    $modelo->setId($this->getRequest()->getParam('id'));

            try{
                $modelo->save();
                Mage::getSingleton('adminhtml/session')->addSuccess('Operação Realizada com Sucesso!');
                $this->_redirect('*/*/index');
            }catch(Exception $ex){
                Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($dados);
                $this->_redirect('*/*/form', array('id' => $this->getRequest()->getParam('id')));
            }
    }

    public function validateAction(){
            $resposta = new Varien_Object();
            $resposta->setError(false);
            $this->getResponse()->setBody($resposta->toJson());
    }

    private function geraFormulario(){
            $this->_initAction()->_setActiveMenu('comprejunto')->_addContent($this->getLayout()->createBlock('BIS2BIS_Comprejunto/Form'));
            $this->renderLayout();
    }

    private function geraGrid(){
            $this->_initAction()->_setActiveMenu('comprejunto')->_addContent($this->getLayout()->createBlock('BIS2BIS_Comprejunto/Grid'));
            $this->renderLayout();
    }

}
?>
