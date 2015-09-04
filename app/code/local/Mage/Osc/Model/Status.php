<?php

    class Mage_Osc_Model_Status extends Mage_Core_Model_Abstract{

        private $db;

        protected function _construct(){
            $this->db = new Varien_Data_Collection_Db();
            $this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('osc_write'));
            $this->_init('osc/status');
        }

        /**
        Realiza a inserção de status de iteração na base de dados do checkout
        */
        public function call($metodo){

            $quote_id = Mage::getSingleton('checkout/session')->getQuote()->getId(); // Recebe o quote id

            
            if($metodo == 'click'){
                $status = Mage::getModel('osc/status')->getCollection()->addFieldToFilter('quote_id', $quote_id)->getFirstItem();
                $status->setData('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
                $status->setData('browser', $_SERVER['HTTP_USER_AGENT']);
                $clicked_count = $status->getData('clickedfo');
                $clicked_count++;
                $status->setData('clickedfo', $clicked_count);
                $status->save();
            }else if($metodo == 'finish'){
                 $status = Mage::getModel('osc/status')->getCollection()->addFieldToFilter('quote_id', $quote_id)->getFirstItem();
                 $order_id = Mage::getSingleton('checkout/session')->getLastRealOrderId();
                 $status->setData('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
                 $status->setData('order_id', $order_id);
                 $status->setData('browser', $_SERVER['HTTP_USER_AGENT']);
                 $status->save();
            }else if($metodo == 'payment'){
                $status = Mage::getModel('osc/status')->getCollection()->addFieldToFilter('quote_id', $quote_id)->getFirstItem();
                $status->setData('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
                $status->setData('payment_method', Mage::getSingleton('checkout/session')->getQuote()->getPayment()->getMethod());
                $status->setData('browser', $_SERVER['HTTP_USER_AGENT']);
                $status->save();
            }else if($metodo == 'start'){
                $status = Mage::getModel('osc/status')->getCollection()->addFieldToFilter('quote_id', $quote_id)->getFirstItem();
                $status->setData('quote_id', $quote_id);
                $status->setData('browser', $_SERVER['HTTP_USER_AGENT']);
                $status->setData('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
                $status->save();
            }
        }

        private function registerStart($quote_id){
            $status = Mage::getModel('osc/status');
            $status->setData('quote_id', $quote_id);
            $status->setData('browser', $_SERVER['HTTP_USER_AGENT']);
            $status->setData('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
            $status->save();

            $cookie_period = 2592000; // 30 dias de cookie
            // set cookie
            Mage::getModel('core/cookie')->set('cart-table-id', $status->getId(), $cookie_period); // id do numero do registro da tabela
            Mage::getModel('core/cookie')->set('cart-store-id', $quote_id, $cookie_period); // id do carrinho do usuario
        }

        private function registerClick(){

        }

        private function registerPayment(){

        }
        /*
        private function registerFinish(){
            $status = Mage::getModel('osc/status')->load(Mage::getModel('core/cookie')->get('cart-table-id'));

            $order_id = Mage::getSingleton('checkout/session')->getLastRealOrderId();
            $status->setData('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
            $status->setData('order_id', $order_id);
            $status->setData('browser', $_SERVER['HTTP_USER_AGENT']);
            $status->save();

            Mage::getModel('core/cookie')->delete('cart-table-id');
            Mage::getModel('core/cookie')->delete('cart-store-id');
        }*/

        private function removeItemStatus($id_cart_status){
            $status = Mage::getModel('osc/status')->load($id_cart_status);
            $status->delete();
            return true;
        }
    }