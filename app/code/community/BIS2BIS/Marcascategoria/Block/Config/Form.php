<?php

	class BIS2BIS_Marcascategoria_Block_Config_Form extends Mage_Adminhtml_Block_Widget_Form {
		

	    protected function _prepareForm(){
	    	$dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
		    
	        $form = new Varien_Data_Form(array(
	            'id' => 'edit_form',
	      	    'action' => Mage::helper('adminhtml')->getUrl('*/*/salvarConfig'),
	      	    'method' => 'post',
	        ));

		      $fieldset = $form->addFieldset('base_fieldset', array(
	            'legend' => Mage::helper('marcascategoria')->__('Configurações')
	        ));

		    $fieldset->addField('id', 'hidden', array(
	            'label'     => Mage::helper('marcascategoria')->__('Id'),
	            'name'      => 'id',
	            'value' => 1
	        ));

	   //      $fieldset->addField('active', 'select', array(
	   //          'label'     => Mage::helper('marcascategoria')->__('HTTPS'),
	            
	   //          'required'  => false,
	   //          'name'      => 'active',
				// 'value'		=> Mage::registry('active'),
				// 'values' => array(array('label' => 'Habilitado', 'value' => 1), 
				// 				  array('label' => 'Desabilitado', 'value' => 0))
	   //      ));

	   //      $fieldset->addField('https', 'text', array(
	   //          'label'     => Mage::helper('marcascategoria')->__('URL HTTPS'),
	   //          'required'  => false,
	   //          'name'		=> 'https',
	   //          'value'		=> Mage::registry('https')
	   //      ));

	        $fieldset->addField('attribute_name', 'text', array(
	         	'label'     => Mage::helper('marcascategoria')->__('Atributo Marcas'),
	         	'class'     => 'required-entry',
	         	'required'  => true,
	         	'name'		=> 'attribute_name',
	         	'after_element_html' => '<small>Atributo de Marca cadastrada</small>',
	            'value'		=> Mage::registry('attribute_name')
	        ));

	        $form->setUseContainer(true);
	        $this->setForm($form);
	        
	        return parent::_prepareForm();

	    }

		
	}