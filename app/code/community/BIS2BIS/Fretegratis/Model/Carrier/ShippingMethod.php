<?php
 
class BIS2BIS_Fretegratis_Model_Carrier_ShippingMethod extends Mage_Shipping_Model_Carrier_Abstract
{
  
  protected $_code = 'fretegratis';
 
  public function collectRates(Mage_Shipping_Model_Rate_Request $request)
  {

      // Verifica se esta habilitado tanto pelo cliente ou pelo administrator
    	if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active') || !Mage::getStoreConfig('gerfrete/gerfrete_group/ativa_frete')) {
    		return false;
    	}

      //pega os produtos do carrinho e soma, não funcionou pegando diretamente da sessão. Caso efetue um teste e funcione favor solicitar a alteração do módulo
      $items = Mage::getSingleton("checkout/session")->getQuote()->getAllItems();
      $preco = 0;
      foreach($items as $item)
      {
          $qty = $item->getQty();
          $preco += $item->getPrice() * $qty;
      }

      // pega o model e seta título, método, etc
    	$result = Mage::getModel('shipping/rate_result');     
      $method = Mage::getModel('shipping/rate_result_method');      
      $method->setCarrier($this->_code);
      $method->setCarrierTitle(Mage::getStoreConfig('gerfrete/gerfrete_group/title_frete'));
      $method->setMethod($this->_code);
      $method->setMethodTitle(Mage::getStoreConfig('gerfrete/gerfrete_group/title_frete'));

      // seta valores
      $method->setCost(0);
      $method->setPrice(0);
      $result->append($method);
      
      // retorna caso seja maior ou igual ao valor setado na administração
      if($preco >= Mage::getStoreConfig('gerfrete/gerfrete_group/valor_frete'))
          return $result;
  }

}