<?php
class BIS2BIS_Cores_Block_Adminhtml_Cadastro extends Mage_Adminhtml_Block_Widget_Form_Container{
    
	protected function _prepareLayout(){
		$this->setChild('form',$this->getLayout()->createBlock('cores/Adminhtml_Cadastro_Form'));
		
		Mage::app()->getLayout()->getBlock('head')->addJs('cores/spectrum.js');
		Mage::app()->getLayout()->getBlock('head')->addItem('skin_css', 'images/cores/spectrum.css');
		
		return parent::_prepareLayout();
	} 
	
	public function __construct(){
		$this->_objectId = 'id';
		$this->_blockGroup = 'cores';
		$this->_controller = 'adminhtml_cadastro';
		parent::__construct();
		$this->_updateButton('save','label','Salvar Configurações');
	}

	public function getHeaderText(){
    	return 'Cadastro de cores';
	}

	/// essa função salva a url
    public function getDeleteUrl(){
   		return $this->getUrl('*/admin/deletar', array('_current'=>true, 'back'=>null));
    }

    //
    public function getBackUrl(){
    	return $this->getUrl('*/admin/lista', array('_current'=>true, 'back'=>null));
    }
    
    
}