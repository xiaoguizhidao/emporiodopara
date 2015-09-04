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

	require_once('lib/boleto_ultimate/html2pdf/html2pdf.class.php');

	class BIS2BIS_BoletoUltimate_StandardController extends Mage_Core_Controller_Front_Action
	{

		public function indexAction(){
			$order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
			$order->sendNewOrderEmail();
			
			$this->_redirect('boleto_ultimate/standard/success');
		}

		public function successAction()
	    {

			$this->loadLayout();
			$this->getLayout()->getBlock('head')->setTitle($this->__('Sucesso ao finalizar a compra'));
			$this->renderLayout();
	    }

	    public function viewAction()
	    {
			$order_id = (int) $this->getRequest()->getParam('order_id');
			if(!$order_id || !$order = Mage::getModel('sales/order')->load($order_id)){
				$this->_forward('noRoute');
				return false;
			}
			$standard = Mage::getModel('boleto_ultimate/standard');
			if($standard->isAvailablePrint($order->getStatus())){
				try {
					Mage::register('current_order', $order);
					$dadosboleto = $standard->prepareValues($order);
					foreach($dadosboleto as $key => $value){
						$dadosboleto[$key] = utf8_decode($value);
					}
					$additional_data = unserialize($order->getPayment()->getAdditionalData());
		    		$code_banco = $additional_data['code_banco'];
					$path = BP . DS . 'lib' . DS . 'boleto_ultimate' . DS . 'layouts' . DS;
					ob_start();
						require_once($path . 'funcoes_' . $code_banco . '.php');
						require_once($path . 'layout_' . $code_banco . '.php');
					$content = ob_get_clean();
					ob_end_clean();
				    $html2pdf = new HTML2PDF('P', 'A4', 'pt', false, 'ISO-8859-1', array(15, 5, 5, 8));
		    		$html2pdf->pdf->setFont('arial');
				    $html2pdf->WriteHTML($content);
				    $html2pdf->Output(time() . '.pdf');
				    exit;
				} catch (Exception $e) {
					Mage::log($order->getIncrementId() . ' => ' . $e->getMessage(), null, 'boleto_ultimate_view.log', true);
					$this->_forward('noRoute');
					return false;
				}
			}
			$this->_forward('noRoute');
			return false;
		}

		public function enviarBoletoEmailAction()
		{
			$order_id = (int) $this->getRequest()->getParam('order_id');
			if(!$order_id || !$order = Mage::getModel('sales/order')->load($order_id)){
				$this->_forward('noRoute');
				return false;
			}
			$standard = Mage::getModel('boleto_ultimate/standard');
			try {
				$billing_address = $order->getBillingAddress();
				$email = Mage::getModel('boleto_ultimate/email');
				$email->sendEmail(
				    'boleto_ultimate_email_template',
				    array(
				    	'name' 	=> Mage::app()->getStore()->getName(),
				    	'email' => Mage::getStoreConfig('trans_email/ident_sales/email')
				    ),
				    $billing_address->getEmail(),
				    $billing_address->getFirstname() . ' ' . $billing_address->getLastname(),
				    $standard->getConfig('subject_send'),
				    array(
				    	'name_to' 	 		 => $billing_address->getFirstname(),
						'store_name'		 => Mage::app()->getStore()->getName(),
						'order_increment_id' => $order->getIncrementId(),
						'url_segunda_via' 	 => Mage::getUrl('boleto_ultimate/standard/view/order_id/' . $order->getId()),
						'url_print'			 => Mage::getUrl('sales/order/print/order_id/' . $order->getId()),
						'url_imagem'		 => Mage::getBaseUrl() . 'media/boleto_ultimate/boleto_ultimate.png'
				    )
				);
				$data = array(
					'mensagem'  => 'Email enviado com sucesso!',
					'resultado' => 'success'
				);
			} catch (Exception $e) {
				Mage::log($order->getIncrementId() . ' => ' . $e->getMessage(), null, 'boleto_ultimate_send_email.log', true);
				$data = array(
					'mensagem'  => $e->getMessage(),
					'resultado' => 'error'
				);
			}
			echo json_encode($data);
		}

		public function alterarDataVencimentoAction()
		{
			$params = Mage::app()->getRequest()->getParams();
			$order_id = (int) $params['order_id'];
			$vencimento = $params['vencimento'];
			if(!$order_id || !$order = Mage::getModel('sales/order')->load($order_id)){
				$this->_forward('noRoute');
				return false;
			}
			$dd_mm_yyyy = explode('/', $vencimento);
			if(!checkdate($dd_mm_yyyy[1], $dd_mm_yyyy[0], $dd_mm_yyyy[2])){
				$data = array(
					'vencimento' => $vencimento,
					'mensagem'	 => 'Data de vencimento inválida!',
					'resultado'	 => 'error'
				);
			}else{
				$additional_data = unserialize($order->getPayment()->getAdditionalData());
				try{
					$additional_data['data_vencimento'] = $vencimento;
					$order->getPayment()
						->setAdditionalData(serialize($additional_data))
						->save();
					$data = array(
						'vencimento' => $vencimento,
						'mensagem'	 => 'Data de vencimento alterada com sucesso!'
					);
				}catch(Exception $e){
					Mage::log($order->getIncrementId() . ' => ' . $e->getMessage(), null, 'boleto_ultimate_vencimento.log', true);
					$data = array(
						'vencimento' => $additional_data['data_vencimento'],
						'mensagem'   => $e->getMessage(),
						'resultado'  => 'success'
					);
				}
			}
			echo json_encode($data);
		}

	}

?>
