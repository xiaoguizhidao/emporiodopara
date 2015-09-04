<?php
/*
@autor Guilherme Costa
@email guilherme.costa@bis2bis.com.br
@company Bis2Bis Comércio Eletrônico
*/

class Mage_Osc_OnestepcheckoutController extends Mage_Checkout_Controller_Action {
    
    /**
    Retorna Model do One Step Checkout
    */
    public function getOscModel(){  
        return Mage::getModel('osc/osc');
    }

    /**
    Recupera model do Checkout
    */
    public function getOnestep() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    /**
    Retorna sessão do cliente
    */
    protected function _getSession() {
        return Mage::getSingleton('customer/session');
    }


    /**
    Valida o email do cliente 
    */
    public function validateEmailAction(){
        $email = $this->getRequest()->getParam('email');
        $customer_coll = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('email')->addFieldToFilter('email', $email);
        $validate = false;
        if($customer_coll->getSize() == 0){
            $validate = true;
        }
        echo $validate;     
     }


    /**
    Efetua o login no sistema
    */
    public function loginAction() {
        $login = $this->getRequest()->getParam('login');
        $pass = $this->getRequest()->getParam('password');
        $return_session = '';
        $session = $this->_getSession();
        if (!empty($login) && !empty($pass)) {
            try {
                $session->login($login, $pass);
                if ($session->getCustomer()->getIsJustConfirmed()) {
                    $this->_welcomeCustomer($session->getCustomer(), true);
                }
                $return_session = 'ok';
            } catch (Mage_Core_Exception $e) {
                $mensagem = '';
                switch ($e->getCode()) {
                    case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                        $mensagem = 'Email ainda não confirmado';
                        break;
                    case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                        $mensagem = 'Email ou senha inválida';
                        break;
                    default:
                        $mensagem = $e->getMessage();
                        break;
                }
                $session->setUsername($login);
                $return_session =  $mensagem;
            } catch (Exception $e) {
            }
        } else {
            $return_session =  'Digite login e senha';
        }
        $osc_model = Mage::getModel('osc/osc')->load(1);
        if($return_session != 'ok'){
            Mage::getSingleton('core/session')->setOscloginerror($return_session);
            if($osc_model->getHttps() == 1){
                $this->_redirectUrl($osc_model->getOscloginhttpsurl());
            }else{
                $this->_redirect('osc/onepage/login');  
            }
        }
        else{
            if($osc_model->getHttps() == 1){
                $this->_redirectUrl($osc_model->getOschttpsurl());
            }else{
                $this->_redirect('osc/onepage/checkout');   
            }       
        }
    }


    /**
    Retorna CEP da BIS2BIS
    */
    public function preencherEnderecoAction(){
        $params = $this->getRequest()->getParams();
        $cep = $params['cep'];
        $resultado = @file_get_contents('http://webservice.bis2bis.com.br/web_cep.php?cep='.urlencode($cep).'&formato=json'); 
        echo $resultado;
    }    
    
     
    /**
    Valida o CPF/CNPJ do cliente
    */
    public function validateTaxvatAction(){
        $taxvat = $this->getRequest()->getParam('taxvat');
        $taxvat_replaced = str_replace('.', '', $taxvat);
        $taxvat_replaced = str_replace('/', '', $taxvat_replaced);
        $taxvat_replaced = str_replace('-', '', $taxvat_replaced);
        $customer_coll = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('taxvat')->addFieldToFilter('taxvat', $taxvat);
        $customer_coll_replace = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('taxvat')->addFieldToFilter('taxvat', $taxvat_replaced);
        if($customer_coll->getSize() == 0 && $customer_coll_replace->getSize() == 0){
            echo true;
        }else{
            echo false;
        }
     }

     public function atualizarEnderecoAction(){

        $billing = $this->getRequest()->getParam('billing');
        $shipping = $this->getRequest()->getParam('shipping');

        $use_for_shipping = $billing['use_for_shipping'] == 'on' ? 1 : 0 ;
        if($use_for_shipping){
            $this->salvarBilling($billing);
        }else{
            $this->salvarBilling($billing);
            $this->salvarShipping($shipping);
        }

        $this->getQuote()->collectTotals();
        $this->getQuote()->save();

        //renderiza os blocos
        $blocos_renderizados = array(
            'shipping'          => $this->renderShipping(),
            'payment'           => $this->renderPayment(),
            'review'            => $this->renderReview() 
        );
       
        echo json_encode($blocos_renderizados);

     }

    //Mage::getSingleton('checkout/session')->getQuote()->setBillingAddress(Mage::getSingleton('sales/quote_address')->importCustomerAddress($customAddress));

    /**
     Retorna quote da Sessão
     */
    public function getQuote() {
        return Mage::getSingleton('checkout/session')->getQuote();
    }

    /**
    Funções do carrinho de Compras
    */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }


    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getCartQuote()
    {
        return $this->_getCart()->getQuote();
    }


    /** 
    Action que verifica se o login está logado 
    */
    public function isloggedAction() {
        echo Mage::getSingleton('customer/session')->isLoggedIn();
    }


    // Valida se o método de pagamento existe
    public function validarPagamentoAction(){

        $params = $this->getRequest()->getParams();

        $this->getQuote()->getPayment()->setMethod('');
        $this->getQuote()->collectTotals();
        $this->getQuote()->save();

        $data = $params['payment'];

        		
        try{
	   		$result = Mage::getModel('osc/osc')->salvarPagamento($data);
        }
        catch(Mage_Payment_Exception $e){
        	$result['error'] = $e->getMessage();
        }

        $result['renderer'] = array(
            'review' => $this->renderReview()
        );

        $payment = false;
        if($this->getQuote()->getPayment()->getMethod() != '')
            if($result['error'])
                $payment = false;
            else
                $payment = true;
        else
            $payment =  false;

        $result['validacao_pagamento'] = $payment;

        echo  json_encode($result);
    }


    // Valida se o método de envio existe
    public function validarEnvioAction(){
        $shipping = false;
        if($this->getQuote()->getShippingAddress()->getShippingMethod() != '')
            $shipping = true;
        else
            $shipping = false;

        echo $shipping;
    }

    /**
    Retorna método de envio
    */
    public function hasshippingmethodAction(){
        if($this->getQuote()->getShippingAddress()->getShippingMethod() != '')
            echo true;
        else
            echo false;
    }

    /**
    Retorna método de pagamento
    */
    public function haspaymentmethodAction(){
        if($this->getQuote()->getPayment()->getMethod() != '')
            echo true;
        else
            echo false;
    }

    /**
     Retorna método de envio selecionado
     */
    public function getShippingMethodAction() {
        echo $this->getQuote()->getShippingAddress()->getShippingMethod();
    }


    /**
     Retorna método de pagamento
     */
    public function getPaymentMethodAction() {
        echo $this->getQuote()->getPayment()->getMethod();
    }

    /**
    Registra tentativa de fechamento de pedido
    */
    public function registerClick(){
        Mage::getModel('osc/status')->call('click');
    }

    /**1
    Metodo principal para salvar pedido
    */
    public function salvarPedidoAction(){

        if(!$this->getQuote()->validateMinimumAmount()){
            $error = Mage::getSingleton('checkout/session')->getErrorValidateMinimumAmount();
            if(empty($error))
                $resposta['error'] = Mage::getStoreConfig('sales/minimum_order/description');
            else
                $resposta['error'] = $error;

            echo json_encode($resposta);
            return;
        }

        $this->registerClick();
        $params = $this->getRequest()->getParams();
        $endereco_billing  = $params['billing'];
        $endereco_shipping = $params['shipping'];

        $esta_logado = Mage::getModel('customer/session')->isLoggedIn();
        if($esta_logado){
             /**
            ========================================================================================
            =================================CLIENTES JÁ REGISTRADOS================================
            ========================================================================================
            */
            $cliente = Mage::getModel('customer/session')->getCustomer();
            if($endereco_billing['use_for_shipping'] == 'on' ){
                if($endereco_billing['saveaddress'] == 'on' && $endereco_billing['address_id'] == 0 ){
                     $endereco_billing = $this->getQuote()->getBillingAddress(); 
                     $this->salvarEndereco($endereco_billing->getData(), $cliente->getId(), 3);
                }
            }else{
                if($endereco_billing['saveaddress'] == 'on' && $endereco_billing['address_id'] == 0 ){
                     $endereco_billing = $this->getQuote()->getBillingAddress(); 
                     $this->salvarEndereco($endereco_billing->getData(), $cliente->getId(), 1);
                }
                if($endereco_shipping['saveaddress'] == 'on' && $endereco_billing['address_id'] == 0 ){
                    $endereco_shipping = $this->getQuote()->getShippingAddress();
                    $this->salvarEndereco($endereco_shipping->getData(), $cliente->getId(), 2);
                }
            }
            
            /**
            ========================================================================================
            =================================CLIENTES JÁ REGISTRADOS================================
            ========================================================================================
            */
        }else{
            /**
            ========================================================================================
            ==================================== NOVOS CLIENTES ====================================
            ========================================================================================
            */
            $cliente = Mage::getModel('customer/customer');

            try{
                $tipopessoa = $endereco_billing['tipopessoa'];
                $firstname  = $endereco_billing['firstname'];
                $lastname   = $endereco_billing['lastname'];
                $pass       = $endereco_billing['pass'];
                $email      = $endereco_billing['email'];
                $gender     = $endereco_billing['gender'];
                $dob        = $endereco_billing['dob'];
                $newsletter = $endereco_billing['newsletter'];

                if($tipopessoa == 'pf'){
                    $taxvat     = $endereco_billing['cpf'];  // cpf 
                }
                elseif($tipopessoa == 'pj'){
                    $taxvat     = $endereco_billing['cnpj']; // cnpj
                    $razaosocial = $endereco_billing['razaosocial'];
                    $ie = $endereco_billing['ie'];
                    $ieisento = $endereco_billing['ieisento'] == 'on' ? 0 : 1;
                    $firstname  = $razaosocial;
                    $lastname  = ' - ';
                    $endereco_billing['firstname'] = $razaosocial;
                    $endereco_billing['lastname'] = ' - ';
                }

                $cliente->setWebsiteId(Mage::app()->getWebsite()->getId());
            
                $cliente->loadByEmail($email);

                $documento_validacao = str_replace('.', '', $taxvat);

                $documento_validacao = str_replace('/', '', $documento_validacao);
                
                $documento_validacao = str_replace('-', '', $documento_validacao);
                
                $clientes_colecao = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('taxvat')->addFieldToFilter('taxvat', $taxvat);
                
                $clientes_colecao_subs = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('taxvat')->addFieldToFilter('taxvat', $documento_validacao);
                
                if($clientes_colecao->getSize() == 1 || $clientes_colecao_subs->getSize() == 1){
                    $resposta['error'] = 'CPF/CNPJ já cadastrado!';
                    echo json_encode($resposta);
                    return;
                }

                // ====== validações ==========


                // ====== validações ==========

                if(!$cliente->getId()){
                    if($newsletter == 'on'){
                        $cliente->setIsSubscribed(1);
                    }
                    $cliente->setEmail($email);
                    $cliente->setFirstname($firstname);
                    $cliente->setLastname($lastname);
                    $cliente->setPassword($pass);
                    $cliente->setTipopessoa($tipopessoa);
                    $cliente->setTaxvat($taxvat);
                    if($tipopessoa == 'pj'){
                        $cliente->setIe($ie);
                        $cliente->setIeisento($ieisento);
                    }else if($tipopessoa == 'pf'){
                        $cliente->setGender($gender);
                        $cliente->setDob($dob);
                    }
                    $cliente->setConfirmation(null); // sem a necessidade de confirmação de e-mail
                    $cliente->save();
                    $cliente->sendNewAccountEmail();
                    Mage::getSingleton('customer/session')->loginById($cliente->getId()); // loga o cliente na sessão

                }else{
                    $resposta['error'] = 'E-mail já existe!';
                    echo json_encode($resposta);
                    return;
                }
            }catch(Exception $ex){
                $id_erro = $this->getOscModel()->bugtracker($ex->getMessage());
                $resposta['error'] = 'ERRO #' . $id_erro . ' Erro ao obter informações do cliente e salvar';
                echo json_encode($resposta);
                return;
            }
            if($endereco_billing['use_for_shipping'] == 'on'){
                $this->salvarEndereco($endereco_billing, $cliente->getId(), 3);
            }else{
                $this->salvarEndereco($endereco_billing, $cliente->getId(), 1);
                $this->salvarEndereco($endereco_shipping, $cliente->getId(), 2);
            }
            /**
            ========================================================================================
            ==================================== NOVOS CLIENTES ====================================
            ========================================================================================
            */

        }

        try{
            $this->getQuote()->setCustomer($cliente);
            $this->getQuote()->collectTotals();
            $this->getQuote()->save();
            $this->getOnestep()->getQuote()->setCustomer($cliente);
            $this->getOnestep()->saveOrder();
            

            $metodo_pagamento = $this->getQuote()->getPayment()->getMethod();
            
            if($metodo_pagamento == 'pagamentodigital_standard' ||  $metodo_pagamento == 'pagseguro_standard'){
                $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
                $pedido = Mage::getModel('sales/order')->load($orderId);
                $pedido->sendNewOrderEmail();
            }

            //$this->getOnestep()->getQuote()->setIsActive(false);
            //$this->getOnestep()->getQuote()->save();
            
            Mage::getSingleton('checkout/type_onepage')->getQuote()->setIsActive(false);
            Mage::getSingleton('checkout/type_onepage')->getQuote()->save();
            Mage::getSingleton('checkout/type_onepage')->getCheckout()->clear();
            
            Mage::getModel('osc/status')->call('finish');
            $resposta['success'] = true;
            $resposta['redirect'] = $this->getOnestep()->getCheckout()->getRedirectUrl();

            echo json_encode($resposta);
        }catch(Exception $ex){
             $id_erro = $this->getOscModel()->bugtracker($ex->getMessage()); // id do erro
             $resposta['error'] = ' ERRO #' . $id_erro . ' Erro ao salvar o pedido - Contate o suporte.';
             echo json_encode($resposta);
             return;
        }


    }

    // salva o endereco do cliente
    public function salvarEndereco($dados, $id_cliente, $tipo){
        $endereco_cliente   = Mage::getModel('customer/address');

        $endereco_cliente->setData($dados)->setCustomerId($id_cliente);
        try {
            if($tipo == 1){
                $endereco_cliente->setIsDefaultBilling(1);
                $endereco_cliente->save();
                $this->getQuote()->setBillingAddress(Mage::getSingleton('sales/quote_address')->importCustomerAddress($endereco_cliente));
            }else if($tipo == 2){
                $endereco_cliente->setIsDefaultShipping(1);
                $endereco_cliente->save();
                $this->getQuote()->setShippingAddress(Mage::getSingleton('sales/quote_address')->importCustomerAddress($endereco_cliente));
            }else if($tipo == 3){
                $endereco_cliente->setIsDefaultBilling(1);
                $endereco_cliente->setIsDefaultShipping(1);
                $endereco_cliente->save();
                $this->getQuote()->setBillingAddress(Mage::getSingleton('sales/quote_address')->importCustomerAddress($endereco_cliente));
            }


        }catch (Exception $ex) {
            $id_erro = $this->getOscModel()->bugtracker($ex->getMessage());
            $resposta['error'] = 'ERRO #' . $id_erro. ' Erro ao salvar endereço';
            echo json_encode($resposta);
            return;
        }
    }

    public function shippingAction() {
        print_r($this->getOnestep()->getQuote()->getShippingAddress()->getData());
    }

    public function billingAction(){
         print_r($this->getOnestep()->getQuote()->getBillingAddress()->getData());
    }

    /**
    Atualiza o método de pagamento com formulário
    */
    public function atualizarMetodoPagamentoFormularioAction() {
        $params = $this->getRequest()->getParams();
        $metodo = $params['metodo'];
        
        Mage::getSingleton('core/session')->setMetodoPagamento($metodo);

        $this->getQuote()->getPayment()->setMethod($metodo)->save();
        $this->getQuote()->collectTotals();
        $this->getQuote()->save();

        $result['renderer'] = array(
            'review' => $this->renderReview(),
            'shipping' => $this->renderShipping()
        );

        echo json_encode($result);

    }

    /**
    Atualiza o método de pagamento
    */
    public function atualizarMetodoPagamentoAction() {
        $params = $this->getRequest()->getParams();
       
        $data = $params['payment'];
       
        Mage::getSingleton('core/session')->setMetodoPagamento($data['method']);
		$result = Mage::getModel('osc/osc')->salvarPagamento($data);
        


        $result['renderer'] = array(
            'review' => $this->renderReview(),
            'shipping' => $this->renderShipping()
        );

        echo json_encode($result);
    }

    public function addressesinfoAction() {
        $customer_logged = Mage::getSingleton('customer/session');
        echo json_encode(array('logged' => $customer_logged->isLoggedIn(), 'number_address' => count($customer_logged->getCustomer()->getAddresses())));
    }


    /** 
    Aplicar cupom de desconto
    */
    public function aplicarCupomAction(){
 
        if (!$this->_getCart()->getQuote()->getItemsCount()) {
            $this->_goBack();
            return;
        }

        $couponCode = (string) $this->getRequest()->getParam('coupon_code');

        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $oldCouponCode = $this->_getCartQuote()->getCouponCode();

        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            $this->_goBack();
            return;
        }

        try {
            $this->_getCartQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getCartQuote()->setCouponCode(strlen($couponCode) ? $couponCode : '')
            ->collectTotals()
            ->save();
            if ($couponCode) {
                if ($couponCode == $this->_getCartQuote()->getCouponCode()) {
                    $tipo = true;
                    $msg =  $this->__('Coupon code "%s" was applied.', Mage::helper('core')->htmlEscape($couponCode));
                }
                else {
                    $tipo = false;
                    $msg =  $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->htmlEscape($couponCode));
                }
            } else {
                    $tipo = false;
                    $msg  =  $this->__('Coupon code was canceled.');
            }

        } catch (Mage_Core_Exception $e) {
            $tipo = false;
            $msg =  $e->getMessage();
        } catch (Exception $e) {
            $tipo = false;
            $msg = $this->__('Cannot apply the coupon code.');
            Mage::logException($e);
        }

        // Resposta do arquivo jSon
        $resposta_json = array( 
            'tipo' => $tipo,
            'msg' => $msg,
            'review' => $this->renderReview()
        );

        echo json_encode($resposta_json);

    }


    /**
    Renderiza review e retorna via json/ajax
    */
    public function renderreviewAction() {
        $blocks_rendered = array(
            'review' => $this->renderReview()
        );
        echo json_encode($blocks_rendered);
    }

    /**
    Renderiza o bloco de review 
    */
    public function renderReview() {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('osc_onepage_checkout');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getBlock('review')->toHtml();
        return $output;
    }

    /**
    Renderiza o bloco de método de pagamentos 
    */
    public function renderPayment() {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('osc_onepage_checkout');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getBlock('payment_methods')->toHtml();
        return $output;
    }

    /**
    Renderiza o bloco de método de envio
    */
    public function renderShipping() {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('osc_onepage_checkout');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getBlock('shipping_methods')->toHtml();
        return $output;
    }

    /**
    Essa action atualiza o método de pagamento e renderiza o layout
    Ele deverá ser chamado por Ajax
    */
    public function atualizarMetodoEnvioAction() {
        $data = $this->getRequest()->getParam('method');
        $shipping_method = $this->getQuote()->getShippingAddress()->getData('shipping_method');
        $shipping_amount = $this->getQuote()->getShippingAddress()->getData('shipping_amount');

        $this->getOnestep()->saveShippingMethod($data);
        $this->getQuote()->collectTotals();
        $this->getQuote()->save();

        $new_shipping_amount = $this->getQuote()->getShippingAddress()->getData('shipping_amount');

        if(($data == $shipping_method) && ($new_shipping_amount == $shipping_amount)){
            $remove_payment = false;
        }else{
            $remove_payment = true;
        }
        
        $blocks_rendered = array(
            'payment' => $this->renderPayment(),
            'review'  => $this->renderReview(),
            'remove_payment' => $remove_payment
        );
        echo json_encode($blocks_rendered);
    }

    /*
    Salva os dados do endereço de envio temporariamente.
    */
    public function salvarShipping($dados){
        $endereco_shipping = $this->getQuote()->getShippingAddress();
        if(!empty($dados['address_id']) && $dados['address_id'] != 0 ){
            $endereco_cliente_id = $dados['address_id'];
            $endereco_cliente = Mage::getModel('customer/address')->load($endereco_cliente_id);
            if ($endereco_cliente->getId()) {
                if ($endereco_cliente->getCustomerId() != $this->getQuote()->getCustomerId()) {
                    return array('error' => 1,
                        'message' => $this->_helper->__('Customer Address is not valid.')
                    );
                }
                $endereco_shipping->importCustomerAddress($endereco_cliente)->setSaveInAddressBook(0); // não salva no banco
            }
        }else{
            $endereco_shipping->unsetData('customer_address_id');
            unset($dados['address_id']); 
            $endereco_shipping->addData($dados);
        }
        $use_for_shipping = $dados['use_for_shipping'] == 'on' ? 1 : 0;
        $endereco_shipping->implodeStreetAddress();
        $endereco_shipping->setSameAsBilling($use_for_shipping); // indica que é o0
        $endereco_shipping->setCollectShippingRates(true);
        $endereco_shipping->save();
    }



    /*
    Salva os dados do endereço de cobrança temporariamente
    */
    public function salvarBilling($dados){
        $endereco_billing = $this->getQuote()->getBillingAddress();

        if($dados['tipopessoa'] == 'pj'){
            $dados['firstname'] = $dados['razaosocial'];
            $dados['lastname'] = ' - ';
        }

        if(!empty($dados['address_id']) && $dados['address_id'] != 0){
            $endereco_cliente_id = $dados['address_id'];
            $endereco_cliente = Mage::getModel('customer/address')->load($endereco_cliente_id);
            if ($endereco_cliente->getId()) {
                if ($endereco_cliente->getCustomerId() != $this->getQuote()->getCustomerId()) {
                    return array('error' => 1,
                        'message' => $this->_helper->__('Customer Address is not valid.')
                    );
                }
                $endereco_billing->importCustomerAddress($endereco_cliente)->setSaveInAddressBook(0); // não salva no banco
            }
        }else{
            $endereco_billing->unsetData('customer_address_id');

            unset($dados['address_id']);

            $endereco_billing->addData($dados);
        }
        
        $endereco_billing->implodeStreetAddress();

        $endereco_billing->save();

        $use_for_shipping = $dados['use_for_shipping'] == 'on' ? 1 : 0;

        if($use_for_shipping){
            $this->salvarShipping($dados);
        }

    }







}

?>
