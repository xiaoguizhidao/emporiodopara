<?php
class BIS2BIS_Motoboyconfig_Block_RangeCepFormEdit extends Mage_Adminhtml_Block_Widget_Form_Container{
    
	protected function _prepareLayout(){
		$this->setChild('form',$this->getLayout()->createBlock('motoboyconfig/RangeCepFormEdit_Form'));
		return parent::_prepareLayout();
	} 
	
	public function __construct(){
		$this->_objectId = 'id';
		$this->_blockGroup = 'motoboyconfig';
		$this->_controller = 'edit';
		parent::__construct();
		$this->_updateButton('save','label','Atualizar Configurações');
	}
	
        
	public function getHeaderText(){
        return 'Cadastro de Faixa de Cep - Motoboy ';
	}
        
    public function getSaveUrl(){
	    return $this->getUrl('*/'.$this->_controller.'/save_edit', array('_current'=>true, 'back'=>null));
    }
        

    public function getBackUrl(){
   	    return $this->getUrl('*/*/rangecep');
	}

    public function getDeleteUrl(){
    	return $this->getUrl('*/'. "admin" .  '/delete', array('_current'=>true, 'back'=>null));
    }
    
}