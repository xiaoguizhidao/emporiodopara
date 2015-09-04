<?php

class BIS2BIS_Gmerchant_CategoriasController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction($ids = null){
        $this->loadLayout($ids);
        return $this;
    }

    public function indexAction(){
        $this->geraGrid();
    }

    private function geraGrid(){
        $this->_initAction()->_setActiveMenu('gmerchant')->_addContent($this->getLayout()->createBlock('gmerchant/categorias_grid'));
        $this->renderLayout();
    }

    public function formAction(){
        $par_id	= $this->getRequest()->getParam('id',-1);

        $modeloForm = Mage::getModel('gmerchant/categorias');
        $modeloForm->setData('id',$par_id);

        switch($par_id){
            case -1:
                $this->_redirect('*/*/');
                break;

            case 0:
                Mage::register('action','categorias_cadastro');
                break;

            default:

                Mage::register('action','categorias_edit');
                Mage::register('modeloForm',$modeloForm);
                break;
        }

        $this->geraFormulario();
    }

    public function validateAction(){
        $resposta = new Varien_Object();
        $resposta->setError(false);
        $this->getResponse()->setBody($resposta->toJson());
    }

    private function geraFormulario(){

        $this->_initAction()->_setActiveMenu('gmerchant')->_addContent($this->getLayout()->createBlock('gmerchant/categorias_form'));
        $this->renderLayout();

    }

    public function saveAction(){
        $dados = $this->getRequest()->getPost();

        try{
            foreach($dados['name'] as $categoria){
                $modelo = Mage::getModel('gmerchant/categorias');
                $modelo->setName($categoria);
                $modelo->save();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess('Operação realizada com sucesso.');
            $this->_redirect('*/*/index');
        }catch(Exception $ex){
            Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($dados);
            $this->_redirect('*/*/form', array('id' => $this->getRequest()->getParam('id')));
        }
    }

    public function deleteAction(){
        $par_id = $this->getRequest()->getParam('id');

        $modelo = Mage::getModel('gmerchant/categorias');
        $modelo->setData('id',$par_id);

        try{
            $modelo->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess('Operação realizada com sucesso.');
            $this->_redirect('*/*/index');

        }catch(Exception $ex){
            Mage::getSingleton('adminhtml/session')->addError('Erro ao Excluir. '.$ex->getMessage());
            $this->_redirect('*/*/edit',array('id' => $par_id));
        }
    }
}