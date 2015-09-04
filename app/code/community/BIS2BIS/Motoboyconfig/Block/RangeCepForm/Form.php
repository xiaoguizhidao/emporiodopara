<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminPage
 *
 * @author Intel
 */
class BIS2BIS_Motoboyconfig_Block_RangeCepForm_Form extends Mage_Adminhtml_Block_Widget_Form {

    
    
      protected function _prepareForm(){
    	
    	$dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
	    
        $form = new Varien_Data_Form(array(
            "id" => "edit_form",
    	    "action" => Mage::helper("adminhtml")->getUrl("*/*/save"),
    	    "method" => "post",
        ));


	    $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper("motoboyconfig")->__("Cadastro de Faixa de Cep - Frete Grátis")
        ));
        
        $fieldset->addField('cepinicial', 'text', array(
            'name'      => 'cepinicial',
            'label'     => Mage::helper('motoboyconfig')->__('Cep Inicial'),
            'required'  => true
        ));
        
        $fieldset->addField('cepfinal', 'text', array(
            'name'      => 'cepfinal',
            'label'     => Mage::helper('motoboyconfig')->__('Cep Final'),
            'required'  => true
        ));
        
        $fieldset->addField('valor_pedido', 'text', array(
            'name'      => 'valor_pedido',
            'label'     => Mage::helper('motoboyconfig')->__('Valor Frete'),
            'required'  => true
        ));
        
        $fieldset->addField('prazo', 'text', array(
            'name'      => 'prazo',
            'label'     => Mage::helper('motoboyconfig')->__('Prazo'),
            'required'  => true,
            'value'     => Mage::registry("prazo")
        ));
        
        
        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();

    }
    
 

}

?>
