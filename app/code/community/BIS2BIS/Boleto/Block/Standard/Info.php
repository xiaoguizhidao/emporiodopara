<?php 

    class BIS2BIS_Boleto_Block_Standard_Info extends Mage_Payment_Block_Info {

        protected function _construct()
        {
            parent::_construct();
        }
        
        protected function _toHtml(){
            $order = Mage::registry('current_order');
            $html = $this->getMethod()->getTitle() . "<br/>";
            $html .= "<b>" . Mage::getModel('boleto/standard')->getNomeBanco() . "</b><br/>";
            if($order){
                if(Mage::app()->getRequest()->getRouteName() == 'adminhtml'){
                    if($order->getBoletoVencimento()){
                        $boletoVencimento = $order->getBoletoVencimento();
                    }else{
                        $strtotime = strtotime($order->getCreatedAt());
                        $boletoVencimento = date('d/m/Y', $strtotime + ($this->getConfig('vencimento') * 86400));
                    }
                    $html .= "Data de Vencimento<br/>";
                    if($order->getStatus() == $this->getConfig('order_status')){
                        $html .= $this->getScript();
                        $html .= "<input type='text' id='boleto_vencimento' name='boleto_vencimento' placeholder='DD/MM/YYY' data-order='" . $order->getId() . "' value='" . $boletoVencimento . "'></input>";
                        $html .= "<button type='button' onclick='alterarVencimento()'>Alterar</button><br/>";
                        $html .= "<div id='mensagem_erro' style='display:none;font-weight:bold;color:#FF0000;'></div>";
                        $html .= "<a href='" . Mage::getUrl('boleto/standard/adminView/order_id/' . $order->getId()) . "' class='scalable' target='_blank'>" . $this->__('Imprimir Boleto') . "</a>";
                    }else{
                        $html .= "<strong>" . $boletoVencimento . "</strong>";
                    }
                    return $html;
                }
                if($order->getStatus() == $this->getConfig('order_status') && $this->getConfig('segundaviafront')){
                    $html .= "<a href='" . Mage::getUrl('boleto/standard/view/order_id/' . $order->getId()) . "' class='scalable' target='_blank'>" . $this->__('Imprimir Boleto') . "</a>";
                }
            }
            return $html;
        }

        private function getScript(){
            return "<script type='text/javascript'>
                    function alterarVencimento(){
                        var boletoVencimento = jQuery('input#boleto_vencimento');
                        var mensagemErro = jQuery('div#mensagem_erro');
                        jQuery.ajax({
                            url: '" . Mage::getUrl('boleto/standard/alterarDataVencimento') . "',
                            data: { order_id: boletoVencimento.data('order'), boleto_vencimento: boletoVencimento.val() },
                            type: 'GET',
                            beforeSend: function(){
                                boletoVencimento.attr('disabled', 'disabled');
                                mensagemErro.hide();
                            }, 
                            success: function(response){
                                boletoVencimento.removeAttr('disabled');
                                if(response != null){
                                    mensagemErro.html(response);
                                    mensagemErro.show();
                                }
                            }
                        });
                    }
                </script>
            ";
        }

        private function getConfig($info){
            return Mage::getStoreConfig('payment/boleto_bancario/' . $info);
        }

    }

?>