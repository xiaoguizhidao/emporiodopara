<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
 *
 * @author Intel
 */
class BIS2BIS_Motoboyconfig_AdminController extends Mage_Adminhtml_Controller_Action {

    
    public function _initAction() {
        $this->loadLayout()->_addBreadcrumb(Mage::helper("motoboyconfig")->__("Motoboyconfig"), Mage::helper("motoboyconfig")->__("Motoboyconfig"));
        return $this;
    }
    
    public function saveAction(){
        /* Dados de Requisição */
        $post = $this->getRequest()->getParams();
        $keys = array_keys($post);
        /* Dados do Banco */
        $resource = new Mage_Core_Model_Resource();
        $write = $resource->getConnection("core_write");
        /* Dados de inserção */
        $cepinicial =  $post["cepinicial"];
        $cepfinal = $post["cepfinal"];

        $valor_pedido = $post["valor_pedido"];
        $prazo = $post['prazo'];
        
        $valor_pedido = str_replace(",", ".", $valor_pedido);
        try{
            $write->insert('motoboyconfig', array(
                'cepinicial' => $cepinicial,
                'cepfinal' => $cepfinal,
                'valor_pedido' => $valor_pedido,
                'prazo' => $prazo
            ));
            Mage::getSingleton('adminhtml/session')->addSuccess('Faixa de CEP cadastrada com sucesso!');
            $this->_redirect('*/*/rangecep');
        }catch(Exception $e){
             Mage::getSingleton('adminhtml/session')->addError('Erro nos dados digitados, contate o suporte!');
        }
    }   
    
    public function save_editAction(){
        $post = $this->getRequest()->getParams();
        $keys = array_keys($post);
        $id = $post["id"];
        $cepinicial = $post["cepinicial"];
        $cepfinal = $post["cepfinal"];
        $valor_pedido = $post["valor_pedido"];
        $prazo = $post['prazo'];
        $valor_pedido = str_replace(",", ".", $valor_pedido);
        $resource = new Mage_Core_Model_Resource();
        $write = $resource->getConnection('core_write');
        try{
            $write->update('motoboyconfig', array(
            'cepinicial' => $cepinicial,
            'cepfinal' => $cepfinal,
            'valor_pedido' => $valor_pedido,
            'prazo' => $prazo
            ), $write->quoteInto('id' . '=?', $id));
            $this->_redirect('*/*/rangecep');
            Mage::getSingleton('adminhtml/session')->addSuccess('Faixa de CEP atualizada com sucesso!');
            
        }catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError('Erro nos dados digitados, contate o suporte!');
        }
    }
    
    public function editAction(){
        $id = $this->getRequest()->getParam("id");
        $resource = new Mage_Core_Model_Resource();
        $read = $resource->getConnection('core_read');
        $select = $read->select()->from('motoboyconfig')->where('id = ?', $id);
        $r = $read->fetchAll($select);
        $rs = $r[0];
        Mage::register("ideditado", $id);
        Mage::register("cepinicial", $rs["cepinicial"]);
        Mage::register("cepfinal", $rs["cepfinal"]);
        Mage::register("valor_pedido", $rs["valor_pedido"]);
        Mage::register("prazo", $rs["prazo"]);
        $this->_initAction()->_addContent($this->getLayout()->createBlock('motoboyconfig/RangeCepFormEdit'));
        $this->renderLayout();
    }
    
    public function deleteAction(){
        $post = $this->getRequest()->getParams();
        $id = $post["id"];
        $resource = new Mage_Core_Model_Resource();
        $write = $resource->getConnection('core_write');
        try{
            $write->delete("motoboyconfig", $write->quoteInto("id=?", $id));
            $this->_redirect('*/*/rangecep');
            Mage::getSingleton('adminhtml/session')->addSuccess('Faixa de CEP deletada com sucesso!');
        }catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError('Erro ao deletar Faixa de CEP, contate o suporte!');
        }
    }
    
    public function rangecepAction() {
        $this->_initAction()
                ->_setActiveMenu('parameters/rangecep')
                ->_addBreadcrumb(Mage::helper('motoboyconfig')->__('Faixa de Cep - Motoboy'), Mage::helper('reports')->__('Faixa de Cep - Motoboy'))
                ->_addContent($this->getLayout()->createBlock('motoboyconfig/RangeCep'))
                ->renderLayout();
    }
    
    public function rangecepformAction(){
        $this->_initAction()->_addContent($this->getLayout()->createBlock('motoboyconfig/RangeCepForm'));
        $this->renderLayout();
    }
    
    
    
   

}

?>
