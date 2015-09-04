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

class BIS2BIS_Gmerchant_Block_Form extends Mage_Adminhtml_Block_Widget_Form {
  
     protected function _prepareForm(){
    	 
        $dataFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
	    
        $type = Mage::registry('type_crud');

        $gmerchant = Mage::getModel('gmerchant/gmerchant')->load(1);

        $form = new Varien_Data_Form(array(
            "id" => "edit_form",
      	    "action" => Mage::helper("adminhtml")->getUrl("*/*/save"),
      	    "method" => "post",
            "enctype" => "multipart/form-data"
        ));

	      $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('gmerchant')->__('Gerenciamento de Atributos')
        ));

        $fieldset->addField('mpn', 'text', array(
          'label'     => Mage::helper('gmerchant')->__('Mpn'),
          'name'      => 'mpn',
          'after_element_html' => '<small>Esse código identifica, de forma exclusiva, o produto para o fabricante. Em particular, a combinação de marca e MPN especifica claramente um produto.</small>',
          'tabindex' => 1,
          'value' => $gmerchant->getMpn()
        ));

        $fieldset->addField('gtin', 'text', array(
          'label'     => Mage::helper('gmerchant')->__('Gtin/Ean'),
          'name'      => 'gtin',
          'after_element_html' => '<small>Nesse atributo, deve ser incluído o EAN (número global de item comercial) do produto. Esses identificadores incluem o UPC (na América do Norte), o EAN (na Europa), o JAN (no Japão) e o ISBN (para livros).</small>',
          'tabindex' => 1,
          'value' => $gmerchant->getGtin()
        ));
   
        $fieldset->addField('brand', 'text', array(
          'label'     => Mage::helper('gmerchant')->__('Marca'),
          'name'      => 'brand',
          'after_element_html' => '<small>A marca do item (Por padrão é pegado o valor do atributo <b>brand</b></small>',
          'tabindex' => 1,
          'value' => $gmerchant->getBrand()
        ));

        $fieldset->addField('tipoprodutos', 'radios', array(
            'label'     => Mage::helper('gmerchant')->__('Tipo de Produtos'),
            'name'      => 'tipoprodutos',
            'values'    => array(
                 array('value'=>'configuraveis','label'=>' Configuraveis e Simples sem Vinculo '),
                 array('value'=>'simples','label'=>' Somente Simples '),
                 array('value'=>'todos','label'=>' Todos os Produtos'),
            ),
            'value'     =>  $gmerchant->getTipoprodutos(),
            'after_element_html' => '<small> Selecione o tipo de Produtos que ira ser incluido no feed de dados do Google Merchant </small>',
            'tabindex'  => 1
        ));

        $fieldset->addField('tipopreco', 'select', array(
        'label'     => Mage::helper('gmerchant')->__('Tipo do Preço'),
        'name'      => 'tipopreco',
        'values'    => array(
             array('value'=>'precofinal','label'=>'Somente preço final'),
             array('value'=>'precodesconto','label'=>'Preço final + desconto'),
             array('value'=>'precoespecial','label'=>'Preço normal e preço final'),
        ),
        'value'     =>  $gmerchant->getTipopreco(),
        'after_element_html' => '<small> O preço final é o preço especial ou o preço resultante de uma promoção </small>',
        'tabindex'  => 1
        ));

        $fieldset->addField('desconto', 'text', array(
         'label'     => Mage::helper('gmerchant')->__('Desconto (%)'),
         'name'      => 'desconto',
         'after_element_html' => '<small>Porcentagem do desconto (Somente números)</small>',
         'tabindex' => 1,
         'value' => $gmerchant->getDesconto()
        ));

         $fieldset->addField('parcelamento', 'select', array(
             'label'     => Mage::helper('gmerchant')->__('Ativar Parcelamento?'),
             'name'      => 'parcelamento',
             'values'    => array(
                 array('value'=>'1','label'=>'Sim'),
                 array('value'=>'0','label'=>'Não'),
             ),
             'value'     =>  $gmerchant->getParcelamento(),
             'tabindex'  => 1
         ));

         $fieldset->addField('qty_parcelas', 'text', array(
             'label'     => Mage::helper('gmerchant')->__('Quantidade de parcelas'),
             'name'      => 'qty_parcelas',
             'tabindex' => 1,
             'value' => $gmerchant->getQtyParcelas()
         ));

         $fieldset->addField('valor_min', 'text', array(
             'label'     => Mage::helper('gmerchant')->__('Valor Mínimo da Parcela'),
             'name'      => 'valor_min',
             'tabindex' => 1,
             'value' => $gmerchant->getValorMin()
         ));

        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();

    }
}