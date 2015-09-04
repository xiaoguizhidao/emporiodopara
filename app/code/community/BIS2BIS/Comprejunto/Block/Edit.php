<?php
class BIS2BIS_Comprejunto_Block_Edit extends Mage_Adminhtml_Block_Widget_Form{
	
	protected function _prepareForm(){
		
		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'name' => 'edit_form',
			'action' => $this->getData('action'),
			'method' => 'post'
		));

		$boxInfo = $form->addFieldset('description',array('legend' => 'Informações'));

		$boxInfo->addField('desconto','text',array(
			'main' => 'desconto',
			'name' => 'desconto',
			'label' => 'Desconto',
			'title' => 'Desconto',
			'required' => true
		));
		
		$form->setUseContainer(true);
		$modelo = Mage::registry('modeloForm');
		$form->addValues($modelo->getData());
		$this->setForm($form);
		
		return parent::_prepareForm();
	}
}