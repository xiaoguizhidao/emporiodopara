<?php

	class BIS2BIS_Marcascategoria_Block_Gerenciarmarcas_Form extends Mage_Adminhtml_Block_Widget_Form {
		

	    protected function _prepareForm(){
	    	//$dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
		    
	        $form = new Varien_Data_Form(array(
	            'id' => 'edit_form',
	      	    'action' => Mage::helper('adminhtml')->getUrl('*/*/savemarca'),
	      	    'method' => 'post',
	      	    'enctype' => 'multipart/form-data'
	        ));




	      	$fieldset = $form->addFieldset('base_fieldset', array(
	            'legend' => Mage::helper('marcascategoria')->__('Cadastrar Marca')
	        ));


			$fieldset->addField('id', 'hidden', array(
	            'label'     => Mage::helper('marcascategoria')->__('Id'),
	            'name'      => 'id',
	            'value' => Mage::registry('id')
	        ));

			$fieldset->addField('name', 'text', array(
				'label'     => Mage::helper('marcascategoria')->__('Name'),
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'name',
				'value'		=> Mage::registry('name'),
				'tabindex' => 1
			));

			$fieldset->addField('sort_order', 'text', array(
				'label'     => Mage::helper('marcascategoria')->__('Posição'),
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'sort_order',
				'value'		=> Mage::registry('sort_order'),
				'tabindex' => 2
			));

			$fieldset->addField('image', 'image', array(
				'label'     => Mage::helper('marcascategoria')->__('Imagem'),
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'image',
				'value'		=> Mage::registry('image'),
				'tabindex'  => 3
	        ));

			$fieldset->addField('link', 'text', array(
				'label'     => Mage::helper('marcascategoria')->__('Link'),
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'link',
				'value'		=> Mage::registry('link'),
				'tabindex' => 4
			));



	        $form->setUseContainer(true);
	        $this->setForm($form);
	        
	        return parent::_prepareForm();

	    }

		
	}