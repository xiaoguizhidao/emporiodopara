<?php
class BIS2BIS_Deposito_Model_Deposito extends Mage_Payment_Model_Method_Abstract {
	
    protected $_code = 'deposito';
    protected $_formBlockType = 'deposito/form_deposito';
    protected $_infoBlockType = 'deposito/info_deposito';
    
    public function assignData($data) {
    	if (!($data instanceof Varien_Object)) {
    		$data = new Varien_Object($data);
    	}
    	$info = $this->getInfoInstance();
    	$info->setDeposito($data->getDeposito());
    	return $this;
    }
    
    
    public function validate() {
    	parent::validate();
    
    	$info = $this->getInfoInstance();
    
    	$deposito = $info->getDeposito();
    	if(empty($deposito)){
            Mage::throwException('Selecione um banco');
    	}
    
    	return $this;
    }
}