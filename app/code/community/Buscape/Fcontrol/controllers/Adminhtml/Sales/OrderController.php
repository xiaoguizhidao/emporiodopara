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

include_once("Mage/Adminhtml/controllers/Sales/OrderController.php");

class Buscape_Fcontrol_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{

    const BLOCK_SALES_ORDER_FRAME = 'Buscape_Fcontrol_Block_Adminhtml_Sales_Order_View_Info';
    
    public function viewAction()
    {
        if(version_compare(Mage::getVersion(), '1.4.0', '>'))
        {
            $this->_title($this->__('Sales'))->_title($this->__('Orders'));
        }
        
        if($order = $this->_initOrder()) {
            
            $this->_initAction();
            
            if(version_compare(Mage::getVersion(), '1.4.0', '>'))
            {
                $this->_title(sprintf("#%s", $order->getRealOrderId()));
            }
            
            switch(Mage::helper('fcontrol')->getConfig('type_service')) {
                case Buscape_Fcontrol_Model_Api::FRAME:
                    $block = $this->getLayout()->createBlock(
                        self::BLOCK_SALES_ORDER_FRAME,
                        'fcontrol.sales.order.view.info',
                        array(
                            'template'  => 'buscape/fcontrol/sales/order/view/info.phtml',
                            'before'    => '-'
                        )
                    );

                    $this->getLayout()->getBlock('content')->insert($block);
                break;
                case Buscape_Fcontrol_Model_Api::FILA_LOJISTA_UNICO:
                case Buscape_Fcontrol_Model_Api::FILA_LOJISTA_PASSAGENS:
                case Buscape_Fcontrol_Model_Api::FILA_VARIOS_LOJISTAS:
                    if($order->getId()) {
                        Mage::getModel('fcontrol/observer')->queueOrder($order);
                    }
                break;
            }
            
            $this->renderLayout();
        }
    }
    
    public function sendlistAction()
    {
        if($orderList = $this->getRequest()->getPost('order_ids')) {
            
            try
            {
                foreach($orderList as $orderId)
                {
                    $order = Mage::getModel('sales/order')->load($orderId);
                    
                    if(Mage_Sales_Model_Order::STATE_NEW === $order->getState()) {
                        
                        Mage::getModel('fcontrol/observer')->queueOrder($order);
                        
                        $this->_getSession()->addSuccess($this->__('Pedido(s) enviado(s) para o FControl.'));
                    } else {
                        $this->_getSession()->addNotice($this->__('Enviado somente o(s) novo(s) pedidos.'));
                    }
                }
            } catch(Mage_Core_Exception $e) {
                $this->_getSession()->addError($this->__('Não foi possível enviar o(s) Pedido(s) para o FControl.'));
            }
        }
        
        $this->_redirect('*/*/');
    }
    
    public function capturelistAction()
    {
        // return frame;
        
        $data = array();
        
        $count = (!is_null($this->_getSession()->getCountCaptureList())) ? $this->_getSession()->getCountCaptureList() : 0;
       
        $count += 1;
        
        $this->_getSession()->setCountCaptureList($count);
        
        if($count == 1) {
            $data[$count] = Mage::getSingleton('core/date')->date('Y-m-d H:i:s');
            $this->_getSession()->setCaptureList($data);
            
        } elseif($count >= 2) {
            
            $anterior = $this->_getSession()->getCaptureList();
            
            $diff = abs(strtotime(Mage::getSingleton('core/date')->date('Y-m-d H:i:s')) - strtotime($anterior[1]));
            
            $years   = floor($diff / (365*60*60*24)); 

            $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
            
            $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 

            $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 

            $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 

            if($minuts <= 4) {
                $this->_getSession()->addNotice($this->__('Aguarde alguns instantes para a próxima sincronização com o FControl.'));
                return $this->_redirect('*/*/');
            } else {
                $this->_getSession()->setCountCaptureList(null);
                $this->_getSession()->setCaptureList(null);
            }
        }
        
        try
        {
            $list = Mage::getModel('fcontrol/observer')->captureList();
            
            if($list) {
            
                Mage::getModel('fcontrol/observer')->captureOrder($list);
                
                $this->_getSession()->addSuccess($this->__('Sincronização com o FControl foi realizada.'));
                
                $this->_getSession()->setCountCaptureList(null);
                $this->_getSession()->setCaptureList(null);
            }
        } catch(Mage_Core_Exception $e) {
            $this->_getSession()->addError($this->__('Não foi possível sincronizar com o FControl.'));
            
            $this->_getSession()->setCountCaptureList(null);
            $this->_getSession()->setCaptureList(null);
        }
        
        $this->_redirect('*/*/');
    }
    
    /**
     * Acl check for admin
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        parent::_isAllowed();
        
        $action = strtolower($this->getRequest()->getActionName());
        
        switch ($action) {
            case 'sendlist':
                $aclResource = 'sales/order/actions/sendlist';
                
                return Mage::getSingleton('admin/session')->isAllowed($aclResource);
            break;
            case 'capturelist':
                $aclResource = 'sales/order/actions/capturelist';
                
                return Mage::getSingleton('admin/session')->isAllowed($aclResource);
            break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('sales/order');
            break;    
        }
    }
}