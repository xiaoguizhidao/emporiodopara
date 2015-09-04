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

class BIS2BIS_Banner_Block_Configform extends Mage_Adminhtml_Block_Widget_Form {
  
     protected function _prepareForm(){
    	 
        $dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);


        $form = new Varien_Data_Form(array(
            "id" => "edit_form",
      	    "action" => Mage::helper("adminhtml")->getUrl("*/*/saveconfig"),
      	    "method" => "post",
            "enctype" => "multipart/form-data"
        ));

	      $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper("banner")->__('Configuração do Banner')
        ));


        $fieldset->addField('active', 'select', array(
            'label'     => Mage::helper('banner')->__('HTTPS?'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'active',
            'values' => array('0'=>'Habilitado','1' => 'Desabilitado'  ),
            'tabindex' => 1,
            'value' => Mage::registry('active')
        ));
        
        $fieldset->addField('url', 'text', array(
            'label'     => Mage::helper('banner')->__('Nome do Banner'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'url',
            'after_element_html' => '<small>Aqui deverá ser informado a URL Https da loja</small>',
            'tabindex' => 1,
            'value' => Mage::registry('url')
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
