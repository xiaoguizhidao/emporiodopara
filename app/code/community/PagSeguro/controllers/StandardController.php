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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    PagSeguro
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Paypal Standard Checkout Controller
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class PagSeguro_StandardController extends Mage_Core_Controller_Front_Action
{

    
    /**
     * Order instance
     */
    protected $_order;

    /**
     *  Get order
     *
     *  @param    none
     *  @return	  Mage_Sales_Model_Order
     */
    public function getOrder() {
        if ($this->_order == null) {
            
        }
        return $this->_order;
    }

    protected function _expireAjax() {
        if (!Mage::getSingleton('checkout/session')->getQuote()->hasItems()) {
            $this->getResponse()->setHeader('HTTP/1.1', '403 Session Expired');
            exit;
        }
    }

    /**
     * Get singleton with pagseguro strandard order transaction information
     *
     * @return PagSeguro_Model_Standard
     */
    public function getStandard() {
        return Mage::getSingleton('pagseguro/standard');
    }

    /**
     * When a customer chooses Paypal on Checkout/Payment page
     *
     */
    public function redirectAction() {

        $this->getResponse()->setHeader('Content-Type', 'text/html; charset=iso-8859-1');
        $session = Mage::getSingleton('checkout/session');
        $session->setPaypalStandardQuoteId($session->getQuoteId());
        $this->getResponse()->setBody($this->getLayout()->createBlock('pagseguro/standard_redirect')->toHtml());
        $session->unsQuoteId();
    }
    
    public function testeAction(){
    	$order = Mage::getModel('sales/order')->loadByIncrementId('100000013');
    	$order->cancel()->save();
    	$this->_redirect('checkout/cart');
    }

    /**
     * When a customer cancel payment from pagseguro.
     */
    public function cancelAction() {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getPaypalStandardQuoteId(true));

        // cancel order
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId()) {
                $order->cancel()->save();
            }
        }

        $this->_redirect('checkout/cart');
    }
    
    public function autoretornoAction(){
        if($_POST){
            if(array_key_exists('Referencia', $_POST)){
                $order = Mage::getModel('sales/order');
                $idpedido = $_POST['Referencia'];           
                $order->loadByIncrementId($idpedido);
                    if($order->getIncrementId() == $idpedido){
                        $status = $_POST['StatusTransacao'];
                        if($status == 'Aprovado'){
                            $frase = 'Pagseguro enviou automaticamente o status: Transação Aprovada.';
                            $order->setStatus('processing');
                            $order->addStatusToHistory(
                                'processing', 'Pagseguro enviou automaticamente o status: Transação Aprovada.'
                            );
                            $order->save();
                            $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
                            $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                            $invoice->register();
                            $transactionSave = Mage::getModel('core/resource_transaction')
                                ->addObject($invoice)
                                ->addObject($invoice->getOrder());
                            $transactionSave->save();
                        }else if($status == 'Cancelado'){
                            $order->addStatusToHistory(
                                'cancel', 'Cancelado pelo PagSeguro.'
                            );
                            $order->cancel()->save();
                        }
                        if($_POST['Referencia'] == 'Aguardando Pagto'){
                            if($order->getId()){
                                $order->sendNewOrderEmail();
                            }
                            $this->_redirect('osc/success');
                        }
                        
                    }
            }else{
                $this->_redirect('osc/success');
            }
        }else{
            $this->_redirect('osc/success');
        }

    }

    /**
     * when pagseguro returns
     * The order information at this point is in POST
     * variables.  However, you don't want to "process" the order until you
     * get validation from the IPN.
     */
    public function successAction() {
        $session = Mage::getSingleton('checkout/session');
        if ($session) {
            $session->setQuoteId($session->getPaypalStandardQuoteId(true));
            /**
             * set the quote as inactive after back from pagseguro
             */
            Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        }

        $order = Mage::getModel('sales/order');
          if (array_key_exists('Referencia', $_POST)) {
          		$order->loadByIncrementId($_POST['Referencia']);
          }else{
        		$order->load(Mage::getSingleton('checkout/session')->getLastOrderId());  	
          }       

        
    }
    

    /**
     * when pagseguro returns via ipn
     * cannot have any output here
     * validate IPN data
     * if data is valid need to update the database that the user has
     */
    public function ipnAction() {
        if (!$this->getRequest()->isPost()) {
            $this->_redirect('');
            return;
        }

        if ($this->getStandard()->getDebug()) {
            $debug = Mage::getModel('pagseguro/api_debug')
                    ->setApiEndpoint($this->getStandard()->getPaypalUrl())
                    ->setRequestBody(print_r($this->getRequest()->getPost(), 1))
                    ->save();
        }

        $this->getStandard()->setIpnFormData($this->getRequest()->getPost());
        $this->getStandard()->ipnPostSubmit();
    }
    
    public function ajaxemailcobrancaAction() {

        $dados = $this->getRequest()->getParams();
        $customer = Mage::getModel('customer/customer')->load($dados['user_id']);
        $order = Mage::getModel('sales/order')->load($dados['order_id']);

        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        try {
            $mailTemplate = Mage::getModel('core/email_template');
            /* @var $mailTemplate Mage_Core_Model_Email_Template */
            $mailTemplate->setDesignConfig(array('area' => 'frontend'))->sendTransactional(23, 'custom2',$customer->getEmail(), null, array('order' => $order));
            $translate->setTranslateInline(true);
            echo 'sucesso';
        } catch (Exception $e) {
            $translate->setTranslateInline(true);
            Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
            echo 'erro ';
        }
    }

    public function redirectpaymentAction() {
        $this->getResponse()->setBody($this->getLayout()->createBlock('pagseguro/Retornopagseguro')->toHtml());
         //$this->_initAction()->_setActiveMenu('sales/order')->_addContent($this->getLayout()->createBlock('pagseguro/Retornopagseguro'));
         $this->renderLayout();
    }
}
