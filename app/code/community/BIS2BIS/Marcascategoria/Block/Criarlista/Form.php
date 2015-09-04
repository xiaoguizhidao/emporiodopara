<?php
	class BIS2BIS_Marcascategoria_Block_Criarlista_Form extends Mage_Adminhtml_Block_Widget_Form {

	protected function _prepareForm(){

		$dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => Mage::helper('adminhtml')->getUrl('*/*/salvarlista'),
			'method' => 'post'
		));

		$fieldset = $form->addFieldset('base_fieldset', array(
			'legend' => Mage::helper('marcascategoria')->__('Cadastrar nova lista de marcas')
		));

		$fieldset->addField('id', 'hidden', array(
            'label'     => Mage::helper('marcascategoria')->__('Id'),
            'name'      => 'id',
            'value' => Mage::registry('id')
        ));

		$fieldset->addField('active', 'select', array(
			'label'     => Mage::helper('marcascategoria')->__('Habilitado'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'active',
			'value' 	=> Mage::registry('active'),
			'values' => array(array('label' => 'Habilitado', 'value' => 1), 
							  array('label' => 'Desabilitado', 'value' => 0))
		));

		$fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('marcascategoria')->__('Nome da Lista'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
            'tabindex' => 1,
            'value' => Mage::registry('name')
        ));

		$fieldset->addField('category', 'select', array(
			'label'     => Mage::helper('marcascategoria')->__('Categoria'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'category',
			'value'		=> Mage::registry('category'),
			'values' => Mage::getModel('marcascategoria/category')->toOptionArray()
		));

		$fieldset->addField('marcas', 'multiselect', array(
			'label'     => Mage::helper('marcascategoria')->__('Marcas'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'marcasId',
			'value'  => Mage::registry('marcas'),
			'values' => Mage::getModel('marcascategoria/marcas')->getMarcasOptions()
        ));

		$form->setUseContainer(true);
		$this->setForm($form);
		return parent::_prepareForm();
	}
}