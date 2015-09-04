<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to suporte.developer@buscape-inc.com so we can send you a copy immediately.
 *
 * @category   Buscape
 * @package    Buscape_Fcontrol
 * @copyright  Copyright (c) 2010 Buscap? Company (http://www.buscapecompany.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
if (version_compare(Mage::getVersion(), '1.3.3', '<=')) {

    class Buscape_Fcontrol_Block_Adminhtml_Sales_Order_View_Info extends Mage_Adminhtml_Block_Sales_Order_Abstract {
        /*
         * @Buscape_Fcontrol_Block_Adminhtml_Sales_Order_View_Info
         *
         * Implementa??o somente para o uso de Frame
         */

        public function getScore() {
            if ($this->getOrder() && Mage::getModel('fcontrol/adapter_payment')->validate($this->getOrder()->getPayment())) {
                Mage::getModel('fcontrol/observer')->frameOrder($this, true);
            } else {
                $texto_fail = 'Este pedido não precisa ou não possui suporte para a Análise do Fcontrol';

                echo utf8_decode($texto_fail);
            }
        }

    }

} else {

    class Buscape_Fcontrol_Block_Adminhtml_Sales_Order_View_Info extends Mage_Adminhtml_Block_Sales_Order_View_Info {
        /*
         * @Buscape_Fcontrol_Block_Adminhtml_Sales_Order_View_Info
         *
         * Implementa??o somente para o uso de Frame
         *
         * @todo: incluir a possibilidade de escolher se determinado
         *
         */

        public function getScore() {
            if ($this->getOrder() && Mage::getModel('fcontrol/adapter_payment')->validate($this->getOrder()->getPayment())) {
                Mage::getModel('fcontrol/observer')->frameOrder($this, true);
            } else {
                echo 'Este pedido n?o precisa ou n?o possui suporte para an?lise no FControl.';
            }
        }

    }

}