<?php
class BIS2BIS_Gmerchant_Block_Categorias_Edit extends Mage_Adminhtml_Block_Widget_Form{
	
	protected function _prepareForm(){
		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'name' => 'edit_form',
			'action' => $this->getData('action'),
			'method' => 'post'
		));

		$boxInfo = $form->addFieldset('description',array('legend' => 'Informações'));

        $boxInfo->addField('name','textarea',array(
            'main' => 'name',
            'disabled' => 'disabled',
            'name' => 'name',
            'label' => 'Categoria',
            'title' => 'Categoria',
            'required' => true
        ));
		
		$form->setUseContainer(true);

		$modelo = Mage::registry('modeloForm');
		$form->addValues($modelo->getData());
		$this->setForm($form);
		
		return parent::_prepareForm();
	}
}