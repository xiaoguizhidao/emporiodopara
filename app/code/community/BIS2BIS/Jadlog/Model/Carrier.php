<?php
class BIS2BIS_Jadlog_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {

	protected $_code 					= 'jadlog';
	protected $_result                  = null;

    public function collectRates(Mage_Shipping_Model_Rate_Request $request){

        $this->_updateFreeMethodQuote($request);

    	// Dados Globais
    	$result = Mage::getModel('shipping/rate_result');
        $error = Mage::getModel('shipping/rate_result_error');
        $error->setCarrier($this->_code);
        $method = Mage::getModel('shipping/rate_result_method');
        $method->setCarrier($this->_code);
        // Dados Globais

        // Dados de CEP
        $cep_destino = str_replace('-', '', $request->getDestPostcode()) ;
        $cep_origem  = str_replace('-', '', Mage::getStoreConfig('shipping/origin/postcode'));
        // Dados de CEP

        // Dados configurados pelo admin da loja
        $usuario    = Mage::getStoreConfig('carriers/jadlog/usuario');
        $senha      = Mage::getStoreConfig('carriers/jadlog/senha');
        $vlr_coleta = Mage::getStoreConfig('carriers/jadlog/valorcoleta');
        $url        = Mage::getStoreConfig('carriers/jadlog/url');
        // Dados configurados pelo admin da loja

        if($url == '') $url = 'http://www.jadlog.com.br:8080/JadlogEdiWs/services/ValorFreteBean?method=valorar';

        if($usuario == '' || $senha == '' || $vlr_coleta == '' || $url == ''){
        	$error = Mage::getModel('shipping/rate_result_error');
        	$error->setCarrier($this->_code);
        	$error->setCarrierTitle($this->getConfigData('name'));
        	$error->setErrorMessage('Configurar dados no admin');
        	$result->append($error);
        	return $result;
        }

        $total_pacote = $request->getPackageValue();
        $modalidade = Mage::getStoreConfig('carriers/jadlog/modalidade');
        $modal_cubagem = $this->getModalCubagem($modalidade);
        $total_peso = $this->getCubagem($request, $modal_cubagem);

        $link = $url . '&';
        $link .= 'vModalidade=' . $modalidade . '&';
        $link .= 'Password=' . $senha . '&';
        $link .= 'vSeguro=N&';
        $link .= 'vVlDec=' . $total_pacote . '&';
        $link .= 'vVlColeta=' . $vlr_coleta . '&';
        $link .= 'vCepOrig=' . $cep_origem . '&';
        $link .= 'vCepDest=' . $cep_destino . '&';
        $link .= 'vPeso=' . $total_peso . '&';
        $link .= 'vFrap=N&';
        $link .= 'vEntrega=D&';
        $link .= 'vCnpj=' . $usuario;

        $ctx = stream_context_create(array('http' => array('timeout' => 10)));
        $xml = file_get_contents($link, false, $ctx);

        // $xml = (file_get_contents($link));

		$retorno = $this->getTagXMLValue('Retorno', $xml);
		$mensagem = $this->getTagXMLValue('Mensagem', $xml);

		if($retorno && $retorno != -1 && $retorno != -2 && $retorno != -3 && $retorno != -99){
			$retorno = str_replace(',', '.', $retorno);
			// ok
			$method->setCarrier($this->_code);
			$method->setCarrierTitle($this->getConfigData('title'));
			$method->setMethodTitle($this->getConfigData('title'));

            if($this->getConfigData('active_free_shipping') && ($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes())){
                $method->setPrice(0);
                $method->setCost(0);
            } else{
                $method->setPrice($retorno);
                $method->setCost($retorno);
            }

			$result->append($method);
		}else{
			// deu erro
			$error = Mage::getModel('shipping/rate_result_error');
			$error->setCarrier($this->_code);
			$error->setCarrierTitle($this->getConfigData('name'));
			$error->setErrorMessage($mensagem);
			$result->append($error);
		}

        return $result;
    }

    public function getTagXMLValue($tag, $xml){
    	$open =   strpos($xml, '&lt;'.$tag.'&gt;');
    	$close =  strpos($xml, '&lt;/'.$tag.'&gt;');
    	$result = substr($xml, $open, ($close-$open) );
    	return str_replace('&lt;'.$tag.'&gt;', '', $result);
    }

    private function getCubagem($request, $modal_cubagem){
        foreach($request->getAllItems() as $item){
            $_product = Mage::getModel('catalog/product')->load($item->getProductId());
            if($_product->getTypeId() == 'simple'){
                $peso_cubico += ($_product->getData('volume_altura') * $_product->getData('volume_largura') * $_product->getData('volume_comprimento')) / $modal_cubagem;
            }
        }
        if($peso_cubico > $request->getPackageWeight()) return $peso_cubico;
        return $request->getPackageWeight();
    }

    private function getModalCubagem($modalidade){
        if($modalidade == 0 || $modalidade == 7 || $modalidade == 9 || $modalidade == 10  || $modalidade == 12) return 6000; // aero
        return 3333; // rodo
    }

    public function getAllowedMethods()
    {
        return array($this->_code => $this->getConfigData('title'));
    }
    
    public function isTrackingAvailable()
    {
    	return true;
    }
    
    /**
     * Retrieve array of tracking info
     *
     *
     * @return Mage_Shipping_Model_Tracking_Result
     */
    public function getTrackingInfo() {
    	//Declara Variaveis -----------------------------------------------------------------------------------------
    	$url 						= 'http://www.jadlog.com.br:8080/JadlogEdiWs/services/TrackingBean?wsdl';
    	$codCliente 				= '13585367000166';
    	$password 					= 'D2s0C1d5';
    	$aux 						= explode(',/', $_SERVER['REQUEST_URI']);
    	$order_id 					= $aux[1];
    	
    	//Instancia Objetos -----------------------------------------------------------------------------------------
    	$_order 					= Mage::getModel('sales/order')->load($order_id);
    	$this->_result 				= Mage::getModel('shipping/tracking_result');
    	$shipmentCollection 		= Mage::getResourceModel('sales/order_shipment_collection')->setOrderFilter($_order)->load();
    	
		//Pega o numero do rastreio ---------------------------------------------------------------------------------    	
    	foreach ($shipmentCollection as $shipment){
    		foreach($shipment->getAllTracks() as $tracknum){
    			$tracknums[]=$tracknum->getNumber();
    		}
    	}
    	$track_number = $tracknums[0];
    	
    	//Soap Jadlog ------------------------------------------------------------------------------------------------
    	$client = new SoapClient($url);
    	$metodo = $client->consultar(array('CodCliente' => $codCliente, 'Password' => $password, 'NDs' => $track_number));
    	$metodo = simplexml_load_string($metodo->consultarReturn);
    	$json = json_encode($metodo, true);
    	$array = json_decode($json, true);
    	
    	//Verifica se array do Soap for vazio (Retorna Erro) ---------------------------------------------------------
    	if($array['Jadlog_Tracking_Consultar']['ND'] == ''){
			$error = Mage::getModel('shipping/tracking_result_error');
	        $error->setTracking($track_number);
	        $error->setCarrier('jadlog');
	        $error->setCarrierTitle($this->getConfigData('title'));
	        $error->setErrorMessage($this->getConfigData('urlerror'));
	        $this->_result->append($error);
	        return $error;

	    //Se tem retorno no array Pega as informações do Soap -------------------------------------------------------
    	}else{
			foreach ($array['Jadlog_Tracking_Consultar']['ND']['Evento'] as $unico){
				$tracking = Mage::getModel('shipping/tracking_result_status');
				$aux = explode(' ', $unico['DataHoraEvento']);
				$delivery_date = $aux[0];
				$delivery_h = $aux[1];
				$locale = new Zend_Locale('pt_BR');
				$date='';
				$date = new Zend_Date($delivery_date, 'dd/MM/YYYY', $locale);
				$relatos = '';
				$relatos['deliverydate'] = $date->toString('YYYY-MM-dd');
				$relatos['deliverytime'] = $delivery_h;
				$relatos['deliverylocation'] = $unico['Observacao'];
				$relatos['activity'] = $unico['Descricao'];
				$progress[] = $relatos;
			}
			
			//Retorna informações para serem montadas no Cliente -----------------------------------------------------
			$track['progressdetail'] = $progress;
			$tracking->setTracking($track_number);
			$tracking->setCarrier('jadlog');
			$tracking->setCarrierTitle($this->getConfigData('title'));
			$tracking->addData($track);
			$this->_result->append($tracking);
	        return $tracking;
    	}
    }
}