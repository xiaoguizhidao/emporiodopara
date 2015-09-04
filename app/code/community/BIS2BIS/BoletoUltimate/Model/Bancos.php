<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   design_default
 * @package    Mage
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
?>

<?php

	class BIS2BIS_BoletoUltimate_Model_Bancos
	{
		
	    public function toOptionArray()
	    {
	        return array(
			    array('value' => 'bb', 'label' => Mage::helper('adminhtml')->__('Banco do Brasil')),
				array('value' => 'bradesco', 'label' => Mage::helper('adminhtml')->__('Bradesco')),
				array('value' => 'cef', 'label' => Mage::helper('adminhtml')->__('Caixa Econômica Federal')),
				array('value' => 'cef_sinco', 'label' => Mage::helper('adminhtml')->__('Caixa Econômica Federal Sinco')),
				array('value' => 'cef_sigcb', 'label' => Mage::helper('adminhtml')->__('Caixa Econômica Federal Sigcb')),
				array('value' => 'hsbc', 'label' => Mage::helper('adminhtml')->__('HSBC')),
				array('value' => 'itau', 'label' => Mage::helper('adminhtml')->__('Itaú')),
	            array('value' => 'santander_banespa', 'label' => Mage::helper('adminhtml')->__('Santander Banespa')),
				array('value' => 'sicoob', 'label' => Mage::helper('adminhtml')->__('Sicoob'))
	        );
	    }

	}

?>