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
class BIS2BIS_Fs_Shipping_Model_PostMethods {

    public function toOptionArray() {
        return array(
            array('value' => 0, 'label' => Mage::helper('adminhtml')->__('Frete Grátis Por Valor do Pedido'))
        );
    }

}