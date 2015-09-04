<?php
/**
 * Pedro Teixeira
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL).
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Correio
 * @package    Correio_Shipping
 * @copyright  Copyright (c) 2008 Pedro Teixeira [ teixeira.pedro@gmail.com ]
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mototaxi_Shipping_Model_RegionFrete
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'0',  'label'=>Mage::helper('adminhtml')->__('Nenhuma')),
            array('value'=>'AC', 'label'=>Mage::helper('adminhtml')->__('Acre')),
            array('value'=>'AL', 'label'=>Mage::helper('adminhtml')->__('Alagoas')),
            array('value'=>'AP', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Amap�'))),
            array('value'=>'AM', 'label'=>Mage::helper('adminhtml')->__('Amazonas')),
            array('value'=>'BA', 'label'=>Mage::helper('adminhtml')->__('Bahia')),
            array('value'=>'CE', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Cear�'))),
            array('value'=>'DF', 'label'=>Mage::helper('adminhtml')->__('Distrito Federal')),
            array('value'=>'ES', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Esp�rito Santo'))),
            array('value'=>'GO', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Goi�s'))),
            array('value'=>'MA', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Maranh�o'))),
            array('value'=>'MT', 'label'=>Mage::helper('adminhtml')->__('Mato Grosso')),
            array('value'=>'MS', 'label'=>Mage::helper('adminhtml')->__('Mato Grosso do Sul')),
            array('value'=>'MG', 'label'=>Mage::helper('adminhtml')->__('Minas Gerais')),
            array('value'=>'PA', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Par�'))),
            array('value'=>'PB', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Para�ba'))),
            array('value'=>'PR', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Paran�'))),
            array('value'=>'PE', 'label'=>Mage::helper('adminhtml')->__('Pernambuco')),
            array('value'=>'PI', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Piau�'))),
            array('value'=>'RJ', 'label'=>Mage::helper('adminhtml')->__('Rio de Janeiro')),
            array('value'=>'RN', 'label'=>Mage::helper('adminhtml')->__('Rio Grande do Norte')),
            array('value'=>'RS', 'label'=>Mage::helper('adminhtml')->__('Rio Grande do Sul')),
            array('value'=>'RO', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('Rond�nia'))),
            array('value'=>'RR', 'label'=>Mage::helper('adminhtml')->__('Roraima')),
            array('value'=>'SC', 'label'=>Mage::helper('adminhtml')->__('Santa Catarina')),
            array('value'=>'SP', 'label'=>Mage::helper('adminhtml')->__(utf8_encode('S�o Paulo'))),
            array('value'=>'SE', 'label'=>Mage::helper('adminhtml')->__('Sergipe')),
            array('value'=>'TO', 'label'=>Mage::helper('adminhtml')->__('Tocantins'))
			
        );
    }

}