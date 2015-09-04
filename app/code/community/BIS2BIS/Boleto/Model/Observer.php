<?php

	class BIS2BIS_Boleto_Model_Observer{

		public function sales_order_save_after($observer){
			$order = $observer->getEvent()->getOrder();
			if($order->getPayment()->getMethodInstance()->getCode() == 'boleto_bancario'){
				try{
					$strtotime = strtotime($order->getCreatedAt());
					$dataVencimento = date('d/m/Y', $strtotime + ($this->getConfig('vencimento') * 86400));
					$order->setData('boleto_vencimento', $dataVencimento);
				}catch(Exception $e){ }
			}
		}

		private function getConfig($info){
			return Mage::getStoreConfig('payment/boleto_bancario/' . $info);
		}

	}

?>