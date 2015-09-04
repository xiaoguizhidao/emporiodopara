<?php
class BIS2BIS_Comprejunto_Block_Cadastro extends Mage_Adminhtml_Block_Widget_Form{

	 public function _construct() {
        parent::_construct();
        $this->setTemplate('comprejunto/Comprejunto.phtml');
        return $this;
    }
	
	protected function _prepareForm(){
		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'name' => 'edit_form',
			'action' => $this->getData('action'),
			'method' => 'post'
		));
		
		$boxInfo = $form->addFieldset('description',array('legend' => 'Informações'));

		$form->setUseContainer(true);
		$this->setForm($form);
		
		return parent::_prepareForm();
	}
	
}