<?php

    class BIS2BIS_Boleto_Block_Standard_Form extends Mage_Payment_Block_Form {
        
        protected function _construct(){
            parent::_construct();
        }
        
        protected function _toHtml(){
            $html = "<fieldset class='form-list'>";
                $html .= "<ul id='payment_form_boleto_bancario' style='display:none'>";
                    $html .= "<li>";
                        $html .= "<p><strong>" . Mage::getModel('boleto/standard')->getNomeBanco() . "</strong></p>";
                        $html .= "<p>" . $this->getConfig('mensagem_form') . "</p>";
                    $html .= "</li>";
                $html .= "</ul>";
            $html .= "</fieldset>";
            return $html;
        }

        private function getConfig($info){
            return Mage::getStoreConfig('payment/boleto_bancario/' . $info);
        }

    }

?>