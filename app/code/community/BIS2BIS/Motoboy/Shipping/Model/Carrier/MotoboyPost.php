<?php

//ini_set("allow_url_fopen", 1); 
/**
 * Mauro Miyauti
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL).
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Transportadora
 * @package    Transportadora_Shipping
 * @copyright  Copyright (c) 2009 Mauro Miyauti [ msmiyauti@gmail.com ]
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/*
 * Observações sobre a instalação
 *
 * O Array $shipping_methods contém todos os códigos dos serviços dos correios seguidos de seus
 * nomes e prazos de entrega, prazos esses que foram ajustados de acordo com a necessidade do
 * desenvolvedor, caso ache necessário, é só modificá-los.
 * 
 * O restante das configurações pode ser feita acessando a área administrativa de sua loja.
 */



class BIS2BIS_Motoboy_Shipping_Model_Carrier_MotoboyPost extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'motoboy_standard';
    protected $_result = null;

    public function getAllowedMethods() {
        return array($this->_code => $this->getConfigData('title'));
    }

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        $result = Mage::getModel('shipping/rate_result');
        $error = Mage::getModel('shipping/rate_result_error');
        $error->setCarrier($this->_code);

        $cepdest = ($request->getDestPostcode());
        $cepdest = str_replace('-', '', $cepdest);

        $value = ($request->getPackageValue());
        $method = Mage::getModel('shipping/rate_result_method');
        $method->setCarrier($this->_code);

        $collection = Mage::getModel('motoboyconfig/motoboyconfig')->getCollection();
        $collection->addFieldToFilter('cepinicial',
                        array('lteq' => $cepdest)
        );

        $collection->addFieldToFilter('cepfinal',
                        array('gteq' => $cepdest)
        );

        $frete = $collection->getFirstItem();
        if($frete->getData('valor_pedido')){
            $valor = $frete->getData('valor_pedido');
            $prazo = $frete->getData('prazo');
            $method->setMethodTitle($this->getConfigData('name') . ' - <font color="red"> ' .    $prazo . ' dias para entrega </font>') ;
            $method->setPrice($valor);
            $method->setCost($valor);
            $result->append($method);
        }

       
        return $result;
    }

}
