<?php
class BIS2BIS_Deposito_Model_Bancos
{
    public function toOptionArray()
    {
    	// usado para mostrar as opções no admin
        return array(
            array('value'=>'bb', 'label'=>Mage::helper('adminhtml')->__('Banco do Brasil')),
            array('value'=>'itau', 'label'=>Mage::helper('adminhtml')->__('Banco Itaú')),
            array('value'=>'sant', 'label'=>Mage::helper('adminhtml')->__('Banco Santander')),
            array('value'=>'brad', 'label'=>Mage::helper('adminhtml')->__('Bradesco')),
            array('value'=>'caix', 'label'=>Mage::helper('adminhtml')->__('Caixa Econômica')),
            array('value'=>'hsbc', 'label'=>Mage::helper('adminhtml')->__('HSBC Bank Brasil')),
            array('value'=>'sicr', 'label'=>Mage::helper('adminhtml')->__('Sicredi')),
        );
    }

    public function getBancos($banco){
    	// usado para mostrar as opções no frontend para o usuário selecionar o banco desejado, é o mesmo array da função 'toOptionArray'n pode ser copiado aqui para baixo

        $bancos = array(
            array('value'=>'bb', 'label'=>Mage::helper('adminhtml')->__('Banco do Brasil')),
            array('value'=>'itau', 'label'=>Mage::helper('adminhtml')->__('Banco Itaú')),
            array('value'=>'sant', 'label'=>Mage::helper('adminhtml')->__('Banco Santander')),
            array('value'=>'brad', 'label'=>Mage::helper('adminhtml')->__('Bradesco')),
            array('value'=>'caix', 'label'=>Mage::helper('adminhtml')->__('Caixa Econômica')),
            array('value'=>'hsbc', 'label'=>Mage::helper('adminhtml')->__('HSBC Bank Brasil')),
            array('value'=>'sicr', 'label'=>Mage::helper('adminhtml')->__('Sicredi')),
        );

        foreach($bancos as $seleciona){
            if($seleciona['value'] == $banco)
                $label = $seleciona['label'];
        }

        return $label;
    }

}