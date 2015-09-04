<?php
class BIS2BIS_Comprejunto_Block_Form extends Mage_Adminhtml_Block_Widget_Form_Container{
	protected function _prepareLayout(){
		$formFilho = Mage::registry('action');
		$this->setChild('form',$this->getLayout()->createBlock('BIS2BIS_Comprejunto/'.$formFilho));
		return parent::_prepareLayout();
	} 
	
	public function __construct(){
		$this->_objectId = 'id';
		$this->_blockGroup = 'BIS2BIS_Comprejunto';
		$this->_controller = 'index';
		
		parent::__construct();
		
		$this->_updateButton('reset','label','Limpar Formulario');
		$this->_updateButton('back','label','Voltar a Grid');
		
		if(Mage::registry('action') == 'Edit'){
			$this->getEditFormData();
			$this->_updateButton('save','label','Editar');
			$this->_addButton('delete',array(
				'label' => 'Deletar',
				'class' => 'scalable delete',
				'onclick' => "deleteConfirm('Quer realmente excluir ?','{$this->getUrl('*/*/delete/id/'.$this->getRequest()->getParam('id').'')}')"
			));
			            
		}else{
			$this->_updateButton('save','label','Salvar');
		}
	
	}
	
	private function getEditFormData(){
		$modelo = Mage::registry('modeloForm');
		$dados = $modelo->load($this->getRequest()->getParam('id'));
		$modelo->setData($dados[0]);
		$modelo->addData(array('id' => $this->getRequest()->getParam('id')));
	}
	
	public function getHeaderText(){
		return 'Compre Junto ( '.Mage::registry('action').' )';
	}
	
    public function getValidationUrl(){
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    public function getSaveUrl(){
        return $this->getUrl('*/'.$this->_controller.'/save', array('_current'=>true, 'back'=>null));
    }
    
}