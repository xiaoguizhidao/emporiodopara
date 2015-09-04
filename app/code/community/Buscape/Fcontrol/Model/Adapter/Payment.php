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
class Buscape_Fcontrol_Model_Adapter_Payment extends Varien_Object {

    protected static $_service = array(
        'ccsave'                    => true,
        'checkmo' 					=> false,
        'free' 						=> true,
        'purchaseorder' 			=> true,
        'authorizenet_directpost'	=> false,
        'authorizenet' 				=> false,
        'googlecheckout' 			=> false,
        'paypal_standard' 			=> false,
        'paypal_express' 			=> false,
        'paypal_direct' 			=> false,
        'paypaluk_direct' 			=> false,
        'paypaluk_express' 			=> false,
        'verisign' 					=> false,
        'payflow_link' 				=> false,
        'hosted_pro'				=> false,
        'paypal_billing_agreement' 	=> false,
        'pagamentodigital_standard' => false,
        'pagseguro_standard' 		=> false,
        'pagseguro' 				=> false,
        'brunoassarisse_pagseguro' 	=> false,
        'cobredireto' 				=> true,
        'pagamentodigital_geral' 	=> true,
        'pagamentodigital_vista' 	=> false,
        'pagamentodigital_prazo' 	=> true,
        'dineromail_standard' 		=> false,
		'brunoassarisse_pagseguro' 	=> true,
        'banco' 					=> true,
        'BoletoBancario_standard' 	=> false,
        'payments_cielowebservice' 	=> true,
        'boleto_bancario'           => true,
        'cielo'                     => true,
		'ipgcore'                   => true
    );
    /* Verifica se a forma de pagamento pode ser utilizada com o FControl */

     public function validate($payment = null)
    {
    //  $payment->getData('additional_data');
        
        
        if(in_array($payment->getMethod(), self::$_service)) {
            if(self::$_service[$payment->getMethod()]) {
                
                if($payment->getMethod()=="ipgcore") {
                    
                   if($payment->getData('additional_data') == self::$_additional_data){
                        
                        return false;
                    }else{
                        return true;
                    }
                    
                }  else {
                    return true;
                }
                
            }
        }
        
        return false;
    }
	
	

    public function filter($payment = null, Buscape_Fcontrol_Model_Api_Abstract $api = null) {
        if ($api && in_array($payment->getMethod(), self::$_service)) {

            switch ($payment->getMethod()) {
                case 'ccsave':

                    if (intval(Mage::helper('fcontrol')->getConfig('type_service')) === Buscape_Fcontrol_Model_Api::FRAME) {
                        $api->metodoPagamento = 1;
                    } else {
                        $api->metodoPagamento = 'CartaoCredito';
                    }

                    $api->valorPedido = number_format($payment->getAmountOrdered(), 2, ".", "");

                    $api->nomeBancoEmissor = $payment->getCcType();

                    $api->numeroCartao = $payment->getCcNumber();

                    $api->dataValidadeCartao = $payment->getCcExpMonth() . '/' . $payment->getCcExpYear();

                    $api->nomeTitularCartao = $payment->getCcOwner();

                    $api->quatroUltimosDigitosCartao = $payment->getCcLast4();
                    break;
                case 'checkmo':

                    if (intval(Mage::helper('fcontrol')->getConfig('type_service')) === Buscape_Fcontrol_Model_Api::FRAME) {
                        $api->metodoPagamento = 15;
                    } else {
                        $api->metodoPagamento = 'Deposito';
                    }

                    $api->valorPedido = number_format($payment->getAmountOrdered(), 2, ".", "");
                    break;
                case 'pagamentodigital_geral':
                case 'pagamentodigital_vista':
                case 'pagamentodigital_prazo':
                case 'dineromail_standard':

                    if (intval(Mage::helper('fcontrol')->getConfig('type_service')) === Buscape_Fcontrol_Model_Api::FRAME) {
                        $api->metodoPagamento = 10;
                    } else {
                        $api->metodoPagamento = 'PagamentoEntrega';
                    }

                    $api->valorPedido = number_format($payment->getAmountOrdered(), 2, ".", "");
                    break;
                case 'free':
                case 'purchaseorder':

                    if (intval(Mage::helper('fcontrol')->getConfig('type_service')) === Buscape_Fcontrol_Model_Api::FRAME) {
                        $api->metodoPagamento = 18;
                    } else {
                        $api->metodoPagamento = 'ValePresente';
                    }

                    $api->valorPedido = number_format($payment->getAmountOrdered(), 2, ".", "");
                    break;
                default:

                    if (intval(Mage::helper('fcontrol')->getConfig('type_service')) === Buscape_Fcontrol_Model_Api::FRAME) {
                        $api->metodoPagamento = 1;
                    } else {
                        $api->metodoPagamento = 'CartaoCredito';
                    }

                    $api->valorPedido = number_format($payment->getAmountOrdered(), 2, ".", "");
                    break;
            }
        }

        $api->numeroParcelas = 1;

        return $api;
    }

}