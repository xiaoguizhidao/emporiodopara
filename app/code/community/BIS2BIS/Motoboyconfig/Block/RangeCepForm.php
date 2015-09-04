<?php
class BIS2BIS_Motoboyconfig_Block_RangeCepForm extends Mage_Adminhtml_Block_Widget_Form_Container{
    
	protected function _prepareLayout(){
		$this->setChild('form',$this->getLayout()->createBlock('motoboyconfig/RangeCepForm_Form'));
		return parent::_prepareLayout();
	} 
	
	public function __construct(){
		$this->_objectId = 'id';
		$this->_blockGroup = 'motoboyconfig';
		$this->_controller = 'rangecepform';
		parent::__construct();
		$this->_updateButton('save','label','Salvar Configurações');
	}
	
            
	public function getHeaderText(){
        return 'Cadastro de Faixa de Cep - Frete Grátis ';
	}
	     
       
    public function getSaveUrl(){
        return $this->getUrl('*/'.$this->_controller.'/save', array('_current'=>true, 'back'=>null));
    }

    public function getBackUrl(){
   	    return $this->getUrl('*/*/rangecep');
	}
    
}