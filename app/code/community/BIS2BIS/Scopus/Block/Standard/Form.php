<?php

class BIS2BIS_Scopus_Block_Standard_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
    }
    
    protected function _toHtml(){

        ?>

            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery("#p_method_scopus").click(function(){
                        jQuery("#payment_form_scopus li input").first().click();
                    });
                });
            </script>
            <style type="text/css">
                #payment_form_scopus li    { padding: 0 0 0 25px; }
            </style>

        <?php
       
        $_code = $this->getMethodCode();
        $config = Mage::getStoreConfig('payment/scopus/scopus_tipo');
        
        $htmlString = '<fieldset class="form-list">';
        $htmlString .= '<ul id="payment_form_' . $_code . '" style="display:none;">';
        
        if(strstr($config,'boleto'))
            // $htmlString .= '<li> <input onClick="onestepcheckout.sendpaymentformdata();" type="radio" name="payment[cc_type]" value="boleto" /> Boleto Bancário</li>';
            $htmlString .= '<li> <input type="radio" name="payment[cc_type]" value="boleto" /> Boleto Bancário</li>';
            
        if(strstr($config,'transferencia'))
            // $htmlString .= '<li> <input onClick="onestepcheckout.sendpaymentformdata();" type="radio" name="payment[cc_type]" value="transferencia" /> Transferência </li>';
            $htmlString .= '<li> <input type="radio" name="payment[cc_type]" value="transferencia" /> Transferência </li>';

        /* $htmlString  .= '<li>
            '.Mage::getStoreConfig('payment/scopus/msg').'
            <input type="hidden" name="payment[cc_type]" value="boleto"  /> </li>'; */
            
        $htmlString .= '</ul>';
        $htmlString .= '</fieldset>';
        
        return($htmlString);
    }
}
