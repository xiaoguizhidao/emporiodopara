<?php
/**

  ░░░░░░░░  ░░  ░░░░░░░░  ░░░░░░░░  ░░░░░░░░  ░░  ░░░░░░░░        
  ░░    ░░  ░░  ░░░             ░░  ░░    ░░  ░░  ░░
  ░░░░░░░░  ░░  ░░░░░░░░  ░░░░░░░░  ░░░░░░░░  ░░  ░░░░░░░░
  ░░    ░░  ░░        ░░  ░░        ░░    ░░  ░░        ░░
  ░░░░░░░░  ░░  ░░░░░░░░  ░░░░░░░░  ░░░░░░░░  ░░  ░░░░░░░░

############################################################################################
############################################################################################
########  BIS2BIS - COMÉRCIO ELETRÔNICO                                                    #
########  Módulo : Banner                                                                  #
########  Desenvolvedor : Guilherme Costa                                                  #
########  Email : guilherme.costa@bis2bis.com.br                                           #
########  Descrição : Form do módulo de Banner                                             #
############################################################################################
############################################################################################

*/

class BIS2BIS_Banner_Block_Registerform extends Mage_Adminhtml_Block_Widget_Form {
  
     protected function _prepareForm(){
    	 
        $dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
	    
        $type = Mage::registry('type_crud');

        $form = new Varien_Data_Form(array(
            "id" => "edit_form",
      	    "action" => Mage::helper("adminhtml")->getUrl("*/*/save"),
      	    "method" => "post",
            "enctype" => "multipart/form-data"
        ));

	      $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper("banner")->__('Cadastro do Banner')
        ));

        $fieldset->addField('id', 'hidden', array(
            'label'     => Mage::helper('banner')->__('Id'),
            'name'      => 'id',
            'value' => Mage::registry('id')
        ));


        $fieldset->addField('active', 'select', array(
            'label'     => Mage::helper('banner')->__('Habilitado'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'active',
            'values' => array('0'=>'Habilitado','1' => 'Desabilitado'  ),
            'tabindex' => 1,
            'value' => Mage::registry('active')
        ));
        
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('banner')->__('Nome do Banner'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
            'after_element_html' => '<small>Aqui deverá ser informado o nome do Banner</small>',
            'tabindex' => 1,
            'value' => Mage::registry('name')
        ));

        $fieldset->addField('link', 'text', array(
            'label'     => Mage::helper('banner')->__('Link do Banner'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'link',
            'after_element_html' => '<small>Aqui deverá ser informado o link do Banner</small>',
            'tabindex' => 1,
            'value' => Mage::registry('link')
        ));
 
        $fieldset->addField('type', 'select', array(
            'label'     => Mage::helper('banner')->__('Tipo'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'type',
            'values' => array(  '1' => 'Topo','2' => 'Esquerda', '3' => 'Direita', '4' => 'Rodapé', '5' => 'Categoria', '6' => 'Conteúdo' ),
            'after_element_html' => '<small>Tipo de exibição do Banner</small>',
            'tabindex' => 1,
            'value' => Mage::registry('type')
        ));

        $fieldset->addField('first_date', 'date', array(
            'label'     => Mage::helper('banner')->__('Data Inicial'),
            'after_element_html' => '<small>Data Inicial do Período de Exibição do Banner</small>',
            'tabindex' => 1,
            'name' => 'first_date',
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
            'value' => Mage::registry('first_date')
        ));

        $fieldset->addField('final_date', 'date', array(
            'label'     => Mage::helper('banner')->__('Data Final'),
            'after_element_html' => '<small>Data Final do Período de Exibição do Banner</small>',
            'tabindex' => 1,
            'name' => 'final_date',
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
            'value' => Mage::registry('final_date')
        ));

        $fieldset->addField('category', 'multiselect', array(
            'label'     => Mage::helper('banner')->__('Categoria'),
            'name'      => 'category',
            'disabled' => true,
            'value'  => '4',
            'values' => Mage::getModel('banner/categories')->toOptionArray(),
            'after_element_html' => '<small> Selecionar apenas se o tipo for <font color="blue">Categoria</font></small>',
            'tabindex' => 1,
            'value' => Mage::registry('category')
        ));

        $fieldset->addField('image', 'file', array(
            'label'     => Mage::helper('banner')->__('Selecione a Imagem ou Arquivo .swf'),
            'name' => 'image'
        ));

        $fieldset->addField('ordem', 'text', array(
            'label'     => Mage::helper('banner')->__('Ordem de Exibição'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'ordem',
            'after_element_html' => '<small>Campo destinado para order de exibição dos banners</small>',
            'tabindex' => 1,
            'value' => Mage::registry('ordem')
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();

    }


    public function _beforeToHtml(){
      ?>
      <script type='text/javascript'>
          jQuery(document).ready(function(){

              if(jQuery('#type').val() == 5){
                 jQuery('#category').removeAttr('disabled');
              }

              jQuery('#type').change(function(){
                 selected = jQuery(this).val();
                 if(selected == 5){
                    jQuery('#category').removeAttr('disabled');
                 }else{
                    jQuery('#category').attr('disabled', true);
                 }
              });
          });
      </script>
      <?php
      return parent::_beforeToHtml();
    }
    
    
    
    
    
    
    
    
    
    

}
?>
