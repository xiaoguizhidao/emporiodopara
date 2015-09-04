<?php

class BIS2BIS_Scopus_StandardController extends Mage_Core_Controller_Front_Action
{
    public function indexAction() {     
    }
	
	public function confirmaAction(){
		die('NULL Conf');
	}
	
	public function umAction(){
		die('NULL um');
	}
	
	public function doisAction(){
		die('NULL dois');
	}
	
	public function tresAction(){
		die('NULL tres');
	}
	
	public function quatroAction(){
		die('NULL quatro');
	}
	
	public function falhaAction(){
		print_r($_GET); die;
	}
	
	public function transferenciaAction(){
	    // Pega o tipo de transação se é boleto e pega o número do pedido carregando o mesmo para utilização das infos
		$tipo = $this->getRequest()->getParam('transId');
		$id_order = $this->getRequest()->getParam('numOrder');
		
		$order = Mage::getModel('sales/order')->loadByIncrementId($id_order);

		// Pegando os produtos e quantidade comprada
		$items = $order->getAllItems();
		$itemcount = count($items);
		
		// Iniciando XML dos produtos
		$html = "<BEGIN_ORDER_DESCRIPTION>";
		$html .= "<orderid>=(".$order->getIncrementId().")";

		// Aplicar desconto em cada produto no carrinho se houver
		$grandTotal = $order->getGrandTotal();
		$freteTotal = $order->getBaseShippingAmount();
		$grandTotalSemDesconto = 0;
		$qtyProducts = 0;
		foreach($items as $item){
			if($item->getPrice() > 0){
				$grandTotalSemDesconto += $item->getPrice() * intval($item->getQtyOrdered());
				$qtyProducts++;
			}
		}
		if($qtyProducts == 0){
			$qtyProducts = 1;
		}
		$grandTotalSemDesconto += $freteTotal;
		$desconto = $grandTotalSemDesconto - $grandTotal;
		$descontoPorProduto = floatval($desconto / $qtyProducts);
		
		// Loop nos produtos preenchendo as Tags
		foreach ($items as $itemId => $item){
			if($item->getPrice() > 0){
				$qty = intval($item->getData('qty_ordered'));
				$valorProduto = ($item->getPrice() * $qty) - $descontoPorProduto;
				$valorProduto = number_format($valorProduto, 2, '', '.');
				
				$html .= "
					<descritivo>=(".$item->getName().")
					<quantidade>=(".$qty.")
					<unidade>=(un)
					<valor>=(".$valorProduto.")
				";
			}
		}

		// Verifica se o frete é maior que 0, pois o Bradesco mostra erro caso mande essa tag nula ou valor 0.
		if($freteTotal > 0){
			$html .= "
				<adicional>=(Frete)
				<valorAdicional>=(".number_format($freteTotal, 2, '', '').")
			";
		}

		// Fim da relação dos produtos
		$html .= "<END_ORDER_DESCRIPTION>";
	
		// Verificando se esta em ambiente de teste, produção ou liberado para venda
		if(Mage::getStoreConfig('payment/scopus/ambiente') == 'teste'){
			$agencia = '0001';
			$conta = '0000001';
			$assinatura = "7B94A66D138FFD2AEBFBE3D8E0BDBD02700A5B793F017B0C8B40252C3680F9CD05C7EEB1A395C84916425DC318F7F07518D416194FB077AA47A8DBD2B35B2E4C376A23385FDD9B0AA3E7D0712B373B9EFD11028ADFC763B7EAD4A3E70FC8BE4A3CF4DA804E692A6173F4B81F568D7A9A38F663106149A34409B3C48147FBAA12";
		} else {
			$agencia = Mage::getStoreConfig('payment/scopus/agencia');
			$conta = Mage::getStoreConfig('payment/scopus/conta');
			$assinatura = Mage::getStoreConfig('payment/scopus/assinatura_transf');
		}
		
		
		$endereco = $order->getBillingAddress()->__toArray();  
		$cep = str_replace('-','',$endereco['postcode']);
		
		// Tratando UF sacado para mostrar somente a sigla, caso venha vazio ou venha o nome do estado completo.
		if(empty($endereco["region"]) || (strlen($endereco["region"]) > 2)){
			$estados = Mage::getModel('scopus/standard');
			$estado = $estados->getEstados($endereco["region_id"]);
		} else {
			$estado = $endereco["region"];
		}
			
		$venc = Mage::getStoreConfig('payment/scopus/vencimento');
		$cpf = str_replace(array('-', '.'),'',$order->getCustomerTaxvat());
		$nome = str_replace(array('(', ')'),'',$endereco["firstname"]);
		$shopfacil = Mage::getStoreConfig('payment/scopus/shopfacil');
		
		$html .= "
			<BEGIN_TRANSFER_DESCRIPTION>
			<NUMEROAGENCIA>=(".$agencia.")
			<NUMEROCONTA>=(".$conta.")
			<ASSINATURA>=(".$assinatura.")
			<END_TRANSFER_DESCRIPTION>
		";
				
		$session = Mage::getSingleton('checkout/session');
    	$session->setPaypalStandardQuoteId($session->getQuoteId());
        $this->getResponse()->setBody($html);
        $session->unsQuoteId();
		print_r($html); die();
		echo $html.' - teste'; die;
	}

	public function boletoAction(){
		
		// Pega o tipo de transação se é boleto e pega o número do pedido carregando o mesmo para utilização das infos
		$tipo = $_POST["transId"];
		$id_order = $_POST["numOrder"];
		$order = Mage::getModel('sales/order')->loadByIncrementId($id_order);

		// Pegando os produtos e quantidade comprada
		$items = $order->getAllItems();
		$itemcount = count($items);
		
		// Iniciando XML dos produtos
		$html = "<BEGIN_ORDER_DESCRIPTION>";
		$html .= "<orderid>=(".$order->getIncrementId().")";
		
		// Loop nos produtos preenchendo as Tags
		foreach ($items as $itemId => $item){
			if($item->getPrice() > 0){
				$preco = number_format($item->getPrice(),2,'','.');
				$qty = intval($item->getData('qty_ordered'));
				$frete = number_format($order->getData('base_shipping_amount'),2,'','');
				
				$html .= "
					<descritivo>=(".$item->getName().")
					<quantidade>=(".$qty.")
					<unidade>=(un)
					<valor>=(".$qty*$preco.")
				";
			}
		}

		// Verifica se o frete é maior que 0, pois o Bradesco mostra erro caso mande essa tag nula ou valor 0.
		if($frete > 0){
			$html .= "
				<adicional>=(Frete)
				<valorAdicional>=(".$frete.")
			";
		}

		// Fim da relação dos produtos
		$html .= "<END_ORDER_DESCRIPTION>";
			
		// Verificando se esta em ambiente de teste, produção ou liberado para venda
		if(Mage::getStoreConfig('payment/scopus/ambiente') == 'teste'){
			$agencia = '0001';
			$conta = 1234567;
			$assinatura = "233542AD8CA027BA56B63C2E5A530029F68AACD5E152234BFA1446836220CAA53BD3EA92B296CA94A313E4E438AD64C1E4CF2CBAD6C67DAA00DE7AC2C907A99979A5AB53BFEF1FD6DD3D3A24B278536929F7F747907F7F922C6C0F3553F8C6E29D68E1F6E0CA2566C46C63A2DD65AFF7DF4802FBF4811CA58619B33989B8DDF8";
		} else {
			$agencia = Mage::getStoreConfig('payment/scopus/agencia');
			$conta = Mage::getStoreConfig('payment/scopus/conta');
			$assinatura = Mage::getStoreConfig('payment/scopus/assinatura_boleto');
		}
		
		$endereco = $order->getBillingAddress()->__toArray();  
		$cep = str_replace('-','',$endereco['postcode']);
		
		// Tratando UF sacado para mostrar somente a sigla, caso venha vazio ou venha o nome do estado completo.
		if(empty($endereco["region"]) || (strlen($endereco["region"]) > 2)){
			$estados = Mage::getModel('scopus/standard');
			$estado = $estados->getEstados($endereco["region_id"]);
		} else {
			$estado = $endereco["region"];
		}
			
		$venc = Mage::getStoreConfig('payment/scopus/vencimento');
		$cpf = str_replace(array('/','-', '.'),'',$order->getCustomerTaxvat());
		$nome = str_replace(array('(', ')'),'',$endereco["firstname"]);
		$shopfacil = Mage::getStoreConfig('payment/scopus/shopfacil');
		
		$html .= "
			<BEGIN_BOLETO_DESCRIPTION>
			<CEDENTE>=(".Mage::getStoreConfig('payment/scopus/cedente').")
			<BANCO>=(".Mage::getStoreConfig('payment/scopus/banco').")
			<NUMEROAGENCIA>=(".$agencia.")
			<NUMEROCONTA>=(".$conta.")
			<ASSINATURA>=(".$assinatura.")
			<DATAEMISSAO>=(".date("d/m/Y").")
			<DATAPROCESSAMENTO>=(".date("d/m/Y").")
			<DATAVENCIMENTO>=(".date("d/m/Y", time() + ($venc * 86400)).")
			
			<NOMESACADO>=(".$nome.")
			<ENDERECOSACADO>=(".$endereco["street"].")
			<CIDADESACADO>=(".$endereco["city"].")
			<UFSACADO>=(".trim($estado).")
			<CEPSACADO>=(".trim($cep).")
			<CPFSACADO>=(".$cpf.")
			
			<NUMEROPEDIDO>=(".$order->getIncrementId().")
			<VALORDOCUMENTOFORMATADO>=(R$ ".number_format($order->getGrandTotal(),2,",","").")
			<SHOPPINGID>=(".$shopfacil.")
			<NUMDOC>=(".$order->getIncrementId().")
			<END_BOLETO_DESCRIPTION>
		";

		$session = Mage::getSingleton('checkout/session');
        $session->setPaypalStandardQuoteId($session->getQuoteId());
        $this->getResponse()->setBody($html);
        $session->unsQuoteId();
        
	}

    public function redirectAction(){
        $session = Mage::getSingleton('checkout/session');
        $session->setPaypalStandardQuoteId($session->getQuoteId());
        die($session->getQuoteId());
        $this->getResponse()->setBody($this->getLayout()->createBlock('scopus/standard_redirect')->toHtml());
        $session->unsQuoteId();
    }
}