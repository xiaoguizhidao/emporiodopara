<?php
class BIS2BIS_ClearsaleStart_AvaliacaoController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		if ($this->getRequest()->isPost() && $this->getRequest()->getParam('orderId')) {
			$orderId = $this->getRequest()->getParam('orderId');

			$helperClearsale = Mage::helper('clearsalestart');
			$session = Mage::getModel('clearsalestart/session');
			$order = Mage::getModel('sales/order')->load($orderId);
			$items = $order->getAllItems();

			$increment_id = $order->getIncrementId();
			$billing_address = $order->getBillingAddress();
			$shipping_address = $order->getShippingAddress();
			$full_date = Mage::helper('clearsalestart')->formatOrderDate($order->getCreatedAt());
			$customer = Mage::getModel('customer/customer')->load($order->getData('customer_id'));
			$region = Mage::getModel('directory/region')->load($billing_address->getData('region_id'));

			$exploded_phone = Mage::helper('clearsalestart')->getExplodedPhone($shipping_address->getData('telephone'));

			$postData = array(
				'CodigoIntegracao' => Mage::getStoreConfig('clearsalestart/config/token'),
				'PedidoID' => $increment_id,
				'Data' => $full_date,
				'Total' => number_format($order->getData('grand_total') , 2, '.', ''),
				'Cobranca_Nome' => $billing_address->getData('firstname') . ' ' . $shipping_address->getData('lastname'),
				'Cobranca_Email' => $billing_address->getData('email'),
				'Cobranca_Documento' => $customer->getData('taxvat'),
				'Cobranca_Logradouro' => $billing_address->getStreet1(),
				'Cobranca_Logradouro_Numero' => $billing_address->getStreet2(),
				'Cobranca_Logradouro_Complemento' => $billing_address->getStreet3(),
				'Cobranca_Bairro' => $billing_address->getStreet4(),
				'Cobranca_Cidade' => $billing_address->getData('city'),
				'Cobranca_Estado' => ($region->getData('code')),
				'Cobranca_CEP' => $billing_address->getData('postcode'),
				'Cobranca_Pais' => 'Bra',
				'Cobranca_DDD_Celular' => is_numeric($exploded_phone['ddd']) ? $exploded_phone['ddd'] : "00",
				'Cobranca_Celular' => is_numeric($exploded_phone['phone']) ? $exploded_phone['phone'] : "00000000",
				'Entrega_Nome' => $shipping_address->getData('firstname') . ' ' . $shipping_address->getData('lastname'),
				'Entrega_Email' => $customer->getData('email'),
				'Entrega_Documento' => $customer->getData('taxvat'),
				'Entrega_Logradouro' => $shipping_address->getStreet1(),
				'Entrega_Logradouro_Numero' => $shipping_address->getStreet2(),
				'Entrega_Logradouro_Complemento' => $shipping_address->getStreet3(),
				'Entrega_Bairro' => $shipping_address->getStreet4(),
				'Entrega_Cidade' => $shipping_address->getData('city'),
				'Entrega_Estado' => ($region->getData('code')),
				'Entrega_CEP' => $shipping_address->getData('postcode'),
				'Entrega_Pais' => 'Bra',
				'Entrega_DDD_Celular' => is_numeric($exploded_phone['ddd']) ? $exploded_phone['ddd'] : "00",
				'Entrega_Celular' => is_numeric($exploded_phone['phone']) ? $exploded_phone['phone'] : "00000000"
			);

			$i = 0;
			foreach($items as $item){
				if($item->getPrice() > 0){
					$i++;
					$postData['Item_ID_'.$i] = $item->getProductId();
					$postData['Item_Nome_'.$i] = $item->getName();
					$postData['Item_Qtd_'.$i] = round($item->getQtyOrdered());
					$postData['Item_Valor_'.$i] =  number_format(( $item->getPrice() - ($item->getPrice() * ($item->getDiscountPercent()/100)) ), 2, '.', '');
					$_categories = Mage::getModel('catalog/product')->load($item->getProductId())->getCategoryIds();
					if(count($_categories) > 0){
						$postData['Item_Categoria_'.$i] = Mage::getModel('catalog/category')->load($_categories[0])->getName();
					} else {
						$postData['Item_Categoria_'.$i] = 'Sem Categoria';
					}
				}
			}

			$payment_method = $order->getPayment()->getData('method');
			$payment_type = $helperClearsale->getPaymentType($payment_method);

			if ($payment_type != -1) {
				$postData['TipoPagamento'] = $payment_type;

				if ($payment_type == 1) {
					$helperClearsale->inserirInfoCartao($orderId, $postData, $payment_method);
				}

				if ($session->getParcelas() != null) { // não está funcionando no momento
					$postData['Parcelas'] = $session->getParcelas();
				} else {
					$postData['Parcelas'] = 1;
				}
			} else {
				$postData['TipoPagamento'] = 14;
				$postData['Parcelas'] = 1;
			}

			$ch = curl_init();

			$fields_string = "";

			foreach($postData as $key=>$value) {
				$fields_string .= $key.'='.$value.'&';
			}

			$fields_string = rtrim($fields_string, '&');

			curl_setopt($ch, CURLOPT_URL, "http://www.clearsale.com.br/start/Entrada/EnviarPedido.aspx");
			curl_setopt($ch, CURLOPT_POST, count($postData));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);
			curl_close ($ch);

			$resultado_clersale = "
				<iframe id=\"iFrameStart\" src=\"http://www.clearsale.com.br/start/Entrada/EnviarPedido.aspx?codigointegracao="
				.Mage::getStoreConfig('clearsalestart/config/token')."&PedidoID=".$increment_id."\"  width=\"280\" height=\"85\" frameborder=\"0\" scrolling=\"no\">
				<p>Seu Browser não suporta iframes</p></iframe>";
			echo $resultado_clersale;
		} else {
			$this->getResponse()->setHeader('Content-type', 'application/json');
			$this->getResponse()->setBody(
				"Request Inválido. Use seu kung fu para descobrir o porquê."
			);
		}
	}
}
