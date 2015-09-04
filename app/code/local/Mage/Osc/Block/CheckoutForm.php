<?php
# Block de Formulário da página de configuração do módulo do OneStepCheckout
?>
<?php
class Mage_Osc_Block_CheckoutForm extends Mage_Adminhtml_Block_Widget_Form {
  
     protected function _prepareForm(){
    	  $dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
	    
        $form = new Varien_Data_Form(array(
            "id" => "edit_form",
      	    "action" => Mage::helper("adminhtml")->getUrl("*/*/save"),
      	    "method" => "post",
        ));

	      $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper("osc")->__("Configuração Checkout")
        ));
        
        $fieldset->addField('selectck', 'select', array(
          'label'     => Mage::helper('osc')->__('Checkout de Pedido'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'checkout',
          'onclick' => "",
          'onchange' => "",
          'value'  => Mage::registry("checkout"),
          'values' => array( '1' => 'One Page Checkout (Padrão Magento)', '2' => 'One Step Checkout' ),
          'tabindex' => 1
        ));
        
		
	     	$fieldset->addField('select', 'select', array(
          'label'     => Mage::helper('osc')->__('Usar HTTPS?'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'https',
          'onclick' => "",
          'onchange' => "",
          'value'  => Mage::registry("https"),
          'values' => array( '1' => 'Sim', '2' => 'Não' ),
          'tabindex' => 1
        ));
        

		    $fieldset->addField('oscloginhttpsurl', 'text', array(
          'label'     => Mage::helper('osc')->__('Login OSC URL HTTPS'),
          'required'  => true,
          'name'      => 'oscloginhttpsurl',
          'tabindex'  => 1,
          'value'     => Mage::registry("oscloginhttpsurl")
        ));
		
        $fieldset->addField('oschttpsurl', 'text', array(
          'label'     => Mage::helper('osc')->__('URL HTTPS'),
          'required'  => true,
          'name'      => 'oschttpsurl',
          'tabindex'  => 1,
          'value'     => Mage::registry("oschttpsurl")
        ));


        $fieldset->addField('coupon', 'select', array(
          'label'     => Mage::helper('osc')->__('Ativar cupom de desconto?'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'coupon',
          'value'  => Mage::registry('coupon'),
          'values' => array( '1' => 'Sim', '2' => 'Não' ),
          'tabindex' => 1
        ));

        $fieldset->addField('term', 'select', array(
          'label'     => Mage::helper('osc')->__('Ativar temos de compromisso?'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'term',
          'value'  => Mage::registry('term'),
          'values' => array( '1' => 'Sim', '2' => 'Não' ),
          'tabindex' => 1
        ));

        $fieldset->addField('text_term', 'editor', array(
          'label'     => Mage::helper('osc')->__('Texto do Termo'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'text_term',
          'value'     => Mage::registry('text_term'),
          'wysiwyg'   => false,
          'required'  => true,
          'tabindex' => 1
        ));

    
        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();

    }
    
    
    
    
    
    
    
    
    
    

}
?>
