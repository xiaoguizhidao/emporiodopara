<?php

class Mage_Osc_Model_Osc extends Mage_Core_Model_Abstract{

	private $db;
	 
    protected function _construct(){
    	$this->db = new Varien_Data_Collection_Db();
    	$this->db->__construct(Mage::getSingleton('core/resource')->getConnection('osc_write'));
        $this->_init('osc/osc');
    }

    /*Retorna a versão atual do Checkout*/
    public function getOscVersion(){
    	$osc_model = Mage::getModel('osc/osc')->load(1);
    	return $osc_model->getVersion();
    }

    /**
    Salva o pagamento na sessão
    */
    public function salvarPagamento($dados){
        try {
            $result = $this->getOnestep()->savePayment($dados);
            Mage::getModel('osc/status')->call('payment');
        } catch (Mage_Payment_Exception $e) {
            if ($e->getFields()) {
                $result['fields'] = $e->getFields();
            }
            $result['error'] = $e->getMessage();
        } catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            $result['error'] = $this->__('Unable to set Payment Method.');
        }

        return $result;
    }

    /**
    Salva o envio na sessão
    */
    public function salvarEnvio($metodo){
    	$this->getOnestep()->saveShippingMethod($metodo);
        $this->getQuote()->collectTotals();
        $this->getQuote()->save();
        $this->getQuote()->collectTotals();
        $this->getQuote()->save();
    }


	/**
	Retorna diretório de regiões nativas !!!!!!!!!!!! SÓ FUNCIONA PARA 1.5 !!!!!!!!!!
	*/
	public function getDirectoryRegions(){
		$region_collection = Mage::getModel('directory/region')->getCollection()->addFieldtoFilter('country_id', 'BR');
		$states = "<option value=''>Selecione um estado...</option>";
		foreach($region_collection as $reg){
			$states .= "<option id='".$reg['default_name']."' name='". $reg["code"] ."' value='".$reg['region_id']."'  >". $reg['default_name'] ."</option>";
		}
		return $states;
	}
	
	
	/**
	Bug Tracker
	*/
	public function bugtracker($msg){
		$checkout_log = Mage::getModel('osc/log');
		$checkout_log->setBrowser($_SERVER['HTTP_USER_AGENT']);
		$checkout_log->setMessage($msg);
		$quote = Mage::getSingleton('checkout/session')->getQuote();
		$checkout_log->setQuoteId($quote->getId());
		$payment_method = $quote->getPayment()->getMethod();
		$checkout_log->setPaymentMethod($payment_method);
		$customer_id = Mage::getModel('customer/session')->getCustomer()->getId();
		$checkout_log->setCustomerId($customer_id);
		$checkout_log->save();
		return $checkout_log->getId(); // retorna o ID do erro
	}

	 /**
    Recupera model do Checkout
    */
    public function getOnestep() {
        return Mage::getSingleton('checkout/type_onepage');
    }


    // Retorna o quote da sessão
    public function getQuote() {
        return Mage::getSingleton('checkout/session')->getQuote();
    }


	/**
	Retorna dados do endereço
	*/
	public function getAddressData($address_id){
        $customer_address = Mage::getModel('customer/address')->load($address_id);
        return $customer_address->getData();
    } 

	/**
	Retorna quantidade de endereços que o cliente tem
	*/
	public function getCountAddress($customer_id){
		return count(Mage::getModel('customer/customer')->load($customer_id)->getAddresses());
	}

	/**
	Retorna endereços do cliente
	*/
	public function getAddressesSelect($customer_id, $scope){
		$customer = Mage::getModel('customer/customer')->load($customer_id);
		$_string_options  = ''; // variável para armazenar as informações dos endereços
		if(count($customer->getAddresses()) > 0){
			$default_id = 0;
			if($scope == 'billing'){
				$default_billing_address = $customer->getDefaultBillingAddress();
				if(method_exists($default_billing_address, 'getData')){
					$default_id = $default_billing_address->getData('entity_id');	
				}
			}else if($scope == 'shipping'){
				$default_shipping_address = $customer->getDefaultShippingAddress();
				if(method_exists($default_shipping_address, 'getData')){
					$default_id = $default_shipping_address->getData('entity_id');
				}
			}

			foreach($customer->getAddresses() as $address){
				$info_data = $address->getData();
				if(array_key_exists('region_id', $info_data)){
					$resource = new Mage_Core_Model_Resource();
					$read = $resource->getConnection('core_read');
					$select = $read->select()->from('directory_country_region')->where('region_id = ?', $info_data['region_id']);
					$r = $read->fetchAll($select);
	       			$result_set = $r[0];
	       			$region = $result_set['default_name'];
				}else{
					$region = "";
				}
				$_string_options .= "<option "; 

				if($address->getData('entity_id') == $default_id ){
					$_string_options .= "selected='selected'";
				}

				$_string_options .= "value='". $info_data['entity_id'] . "' > ". $info_data['firstname'] . " " . $info_data['lastname']  .",
				" . $info_data['street'] . ", " . $info_data['postcode'] . ", " . $info_data['city'] .", " . $region . " </option> \n";
			}
			$_string_options .= "<option value='0'> Novo Endereço </option>";
			return $_string_options;
		}else{
			return null;
		}
	}

	
}

?>