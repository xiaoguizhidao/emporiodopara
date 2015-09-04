<?php
class BIS2BIS_Gmerchant_Block_Categorias_Cadastro extends Mage_Adminhtml_Block_Widget_Form{

    protected function _prepareForm(){

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'name' => 'edit_form',
            'action' => $this->getData('action'),
            'method' => 'post'
        ));

        $boxInfo = $form->addFieldset('description',array('legend' => 'Informações'));

        $ponteiro = fopen ("http://www.google.com/basepages/producttype/taxonomy.pt-BR.txt","r");
        $html     = array();
        while (!feof ($ponteiro)) {
            $linha = fgets($ponteiro,4096);
            if(!strripos($linha,'Google_Product_Taxonomy_Version')){
                $html[] = array('value'=>$linha,'label'=>$linha);
            }
            //$html .= "<input type='checkbox' name='name' value='".$linha."'> ".$linha."<br>";
        }
        fclose ($ponteiro);

        $boxInfo->addField('name', 'checkboxes', array(
            'label'     => $this->__('Selecione as categorias desejadas:'),
            'name'      => 'name[]',
            'value'     => '2',
            'values'    => $html,
            'disabled'  => false,
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}