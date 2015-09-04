<?php
/*
{
	Formulario de Cores
	Guiherme Costa
}
*/

class BIS2BIS_Cores_Block_Adminhtml_Cadastro_Form extends Mage_Adminhtml_Block_Widget_Form {
	
	protected function _prepareForm(){

		$dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => Mage::helper('adminhtml')->getUrl('*/*/salvar'),
			'method' => 'post',
            'enctype' => 'multipart/form-data'
		));


		$fieldset = $form->addFieldset('base_fieldset', array(
			 'legend' => Mage::helper('cores')->__('Cadastro de cores')
		));


		$fieldset->addField('id', 'hidden', array(
	          'value' => Mage::registry('id'),
	          'name'      => 'id'
        ));
		

		if(!Mage::registry('imagem')){

			$fieldset->addField('cor', 'color', array(
	          'label'     => Mage::helper('cores')->__('Cor'),
	          'value' => Mage::registry('cor'),
	          'name'      => 'cor'
       		));

			$fieldset->addField('imagem', 'file', array(
	          'label'     => Mage::helper('cores')->__('Imagem'),
	          'value' => Mage::registry('imagem'),
	          'name'      => 'imagem'
	        ));

	        $fieldset->addField('nome', 'text', array(
	          'label'     => Mage::helper('cores')->__('Nome'),
	          'required'  => true,
	          'value' => Mage::registry('nome'),
	          'name'      => 'nome',
	          'after_element_html' => '<small>Nome da cor (Ex: Verde)</small>'
        	));

		}else{
			 $fieldset->addField('nome', 'text', array(
	          'label'     => Mage::helper('cores')->__('Nome'),
	          'required'  => true,
	          'value' => Mage::registry('nome'),
	          'name'      => 'nome',
	          'after_element_html' => '<br/><br/> <img src="'.Mage::helper('cores')->getBaseDir() . Mage::registry('imagem') . '" />'
        	));

		    $fieldset->addField('remover_imagem', 'checkbox', array(
	          'label'     => Mage::helper('cores')->__('Remover imagem?'),
	          'name'      => 'remover_imagem',
	          'tabindex' => 1
	        ));
		}





		$form->setUseContainer(true);
		$this->setForm($form);
		return parent::_prepareForm();
	}
	
 

}

?>
