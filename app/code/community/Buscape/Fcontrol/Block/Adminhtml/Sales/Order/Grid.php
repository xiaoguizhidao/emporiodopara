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
 * @copyright  Copyright (c) 2010 Buscapé Company (http://www.buscapecompany.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Buscape_Fcontrol_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    
    /*
     * @Buscape_Fcontrol_Block_Adminhtml_Sales_Order_View_Info
     * 
     * Implementação somente para o uso de Frame
     */
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
        
        if(intval(Mage::helper('fcontrol')->getConfig('type_service')) !== Buscape_Fcontrol_Model_Api::FRAME) {
            
            $this->getMassactionBlock()->addItem('sendlist_order', array(
                 'label'=> Mage::helper('sales')->__('Enfileirar no F-Control'),
                 'url'  => $this->getUrl('*/sales_order/sendlist'),
            ));
        }
    }
}