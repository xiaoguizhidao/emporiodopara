<?php
class BIS2BIS_ClearsaleStart_Helper_Data extends Mage_Core_Helper_Abstract{

	//adiciona parâmetros na sessão
	public function setParams($tipo_pagamento, $parcelas){
		$session = Mage::getModel('clearsalestart/session');
		$session->setTipoPagamento($tipo_pagamento);
		$session->setParcelas($parcelas);
	}

	// Formata data do Pedido
	public function formatOrderDate($date){
		$create_order_date = new Zend_Date($date);

		$day    = $create_order_date->get(Zend_Date::DAY);
		$month  = $create_order_date->get(Zend_Date::MONTH);
		$year   = $create_order_date->get(Zend_Date::YEAR);

		$hour   = $create_order_date->get(Zend_Date::HOUR);
		$minute = $create_order_date->get(Zend_Date::MINUTE);
		$second = $create_order_date->get(Zend_Date::SECOND);

		$formatted = $year . '-' . $month . '-' . $day . 'T' . $hour . ':' . $minute . ':' . $second;
		return $formatted;
	}

	public function getExplodedPhone($phone){
		$phone = str_replace('(', '', $phone);
		$phone = str_replace(')', '', $phone);
		$phone = str_replace('-', '', $phone);
		$phone = str_replace(' ', '', $phone);
		$phone_exploded = array(substr($phone, 0, 2), substr($phone, 2));
		if(strlen($phone) <= 8)
			return array('ddd' => '00', 'phone' => $phone);
		else
			return array('ddd' => $phone_exploded[0], 'phone' => $phone_exploded[1]);
	}

	public function getPaymentType($methodString){
		$methodString = strtolower($methodString);
		$boleto = array('boletocaixa_bancario', 'boleto_bancario', 'braspagboleto');
		$cartoes = array('ccsave','cc','credito','cartao','redecard','braspagcielo','braspagredecard','braspagbanorte','braspagpagosonline','braspagpayvision','cielo');
		$transferencias_bancarias = array('checkmo', 'transferencia', 'deposito');
		$outros = array('free', 'pagseguro_standard', 'paypal', 'googlecheckout', 'scopus', 'itau_standard', 'PagSeguro');

		if (in_array($methodString, $cartoes)) {
			return 1;
		} else if (in_array($methodString, $boleto)) {
			return 2;
		} else if (in_array($methodString, $transferencias_bancarias)) {
			return 6;
		} else if (in_array($methodString, $outros)) {
			return 14;
		} else if (strcmp($methodString, 'debito') == 0) {
			return 3;
		} else if (strcmp($methodString, 'cheque') == 0) {
			return 8;
		} else if (strcmp($methodString, 'dinheiro') == 0) {
			return 9;
		} else {
			return -1;
		}
	}

	public function inserirInfoCartao($order_id, &$postData, $payment_method) {
		switch ($payment_method) {
			case 'cielo':
				$this->inserirCartaoCielo($order_id, $postData);
				break;

			default:
				Mage::log('Informações de cartão não disponíveis');
				break;
		}
	}

	public function inserirCartaoCielo($order_id, &$postData) {
        if ((strpos(Mage::getConfig()->getModuleConfig('BIS2BIS_Cielo')->version, '1.0.4') !== false) || 
            (strpos(Mage::getConfig()->getModuleConfig('BIS2BIS_Cielo')->version, '1.0.3') !== false)) {
			$compra = Mage::getModel('cielo/compra')->loadByOrderId($order_id);
			try {
					$transacoes = $compra->getTransacao('requisicao_finalizacao');
					$ultima_transacao = array_pop($transacoes);

					$postData['Cartao_Fim'] = $ultima_transacao["ultimos_numeros"];
					$postData['TipoCartao'] = $ultima_transacao["bandeira"];

					return true;

			} catch (Exception $e) {
					if ($e->getCode()==-7) {
							Mage::log("A compra nao resultou em nenhuma transaçao junto a cielo, favor verificar o historico de transações logo abaixo");
					} else {
							Mage::log("Ocorreu um erro ao carregar as transações.");
					}
					return false;
			}
		} else {
			$transaction = Mage::getModel('cielo/cielo')->getLastTransaction($order_id);
			if (array_key_exists('ncartao', $transaction[0]) && array_key_exists('bandeira', $transaction[0])) {
					$postData['Cartao_Fim'] = substr($transaction[0]['ncartao'], -4);
					$postData['TipoCartao'] = $transaction[0]['bandeira'];
					return true;
			} else {
					return false;
			}
		}
	}
}
