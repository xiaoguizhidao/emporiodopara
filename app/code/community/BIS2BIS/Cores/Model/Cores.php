<?php

class BIS2BIS_Cores_Model_Cores extends Mage_Core_Model_Abstract{

	private $db;
	 
    protected function _construct(){
    	$this->db = new Varien_Data_Collection_Db();
    	$this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('cores_write'));
        $this->_init('cores/cores');
    }

    // verifica se o atributo existe por nome ou não
    public function existe($nome){
        $cores = Mage::getModel('cores/cores')->getCollection();
        $cores->addFieldToFilter('nome', $nome);
        if($cores->getSize() > 0){
            return true;
        }
        return false;
    }

    // retorna todas opções
    public function obterOpcoes(){
        $atributo = Mage::getStoreConfig('cores/config/codigo_cor'); 
        $attribute_code=Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $atributo);
        $attributeInfo = Mage::getModel('eav/entity_attribute')->load($attribute_code);
        $attribute_table = Mage::getModel('eav/entity_attribute_source_table')->setAttribute($attributeInfo);
        $options = $attribute_table->getAllOptions(false);
        return $options;
    }

    // obtem o  id da opção
    public function obterIdOpcao($label){
        $opcoes = $this->obterOpcoes();
        foreach($opcoes as $opcao){
            if($opcao['label'] == $label){
                return $opcao['value']; break;
            }
        }
    }

    // deleta opção do atributo
    public function deletarAtributo($id_opcao){
        $attribute_code = Mage::getStoreConfig('cores/config/codigo_cor'); 
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product',$attribute_code);
        $options = $attribute->getSource()->getAllOptions();
        $option_id = $id_opcao;
        $options['delete'][$option_id] = true; 
        $options['value'][$option_id] = true;
        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
        $setup->addAttributeOption($options);
    }

    // em caso de edição de atributo
    public function editarAtributo($id_opcao, $nome){
        $attr_model = Mage::getModel('catalog/resource_eav_attribute');
        $attr_model->load($this->obterIdCor()); // atributo de cor
        $data = array();
        $values = array(
            $id_opcao => array(
                0 => $nome,    //0 is current store id, Apple is the new label for the option
                1 => $nome
            )
        );
        $data['option']['value'] = $values;
        $attr_model->addData($data);
        try {
            return $attr_model->save();
        } catch (Exception $e) {
            return false;
        }
    }

    // salva o atributo na base de atributos
    public function salvarAtributo($valor){
        if($this->obterIdOpcao($valor)){
            return false; 
        }
        $attribute_code = Mage::getStoreConfig('cores/config/codigo_cor'); 
        $attribute_model = Mage::getModel('eav/entity_attribute');
        //$attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;
        $attribute_code = $attribute_model->getIdByCode('catalog_product', $attribute_code);
        $attribute = $attribute_model->load($attribute_code);
        //$attribute_table = $attribute_options_model->setAttribute($attribute);
        //$options = $attribute_options_model->getAllOptions(false);
        $value['option'] = array($valor,$valor);
        $result = array('value' => $value);
        $attribute->setData('option',$result);
        return $attribute->save();
    }

    public function obterIdCor(){
        $cod_atributo                = Mage::getStoreConfig('cores/config/codigo_cor'); 
        $attribute_model             = Mage::getModel('eav/entity_attribute');
        $attribute_code              = $attribute_model->getIdByCode('catalog_product', $cod_atributo);
        $opcao_atributo              = $attribute_model->load($attribute_code);
        
        return $opcao_atributo->getData('attribute_id');
    }

    //Retorna a cor ou imagem referente a label da Cor
    public function obterCor($nome){
        $cores = Mage::getModel('cores/cores')->getCollection();
        $cor = $cores->addFieldToFilter('nome', $nome)->getFirstItem();

        if($cor->getImagem()){
            return $cor->getImagem();
        }else{
            return $cor->getCor();
        }
    }

    //Retorna a label da cor, atraves do id da cor
    public function getColorLabelByColorId($id){

        $atributo = Mage::getStoreConfig('cores/config/codigo_cor');
        $attribute_code=Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $atributo);
        $attributeInfo = Mage::getModel('eav/entity_attribute')->load($attribute_code);
        $attribute_table = Mage::getModel('eav/entity_attribute_source_table')->setAttribute($attributeInfo);

        $label = $attribute_table->getOptionText($id);
        return $label;
    }
    

	
}

?>