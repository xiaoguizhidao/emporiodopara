<?php 
class BIS2BIS_Gmerchant_Model_Xml extends Mage_Core_Model_Abstract{

	// cria xml para o item, o parâmetro é a model do produto 
	public function createItemXMLSimple($product){
		$xml_string  = '<item>';
		$xml_string .= '<g:id>' . $product->getSku() . '</g:id>';
		$xml_string .= '<g:title>' .  $this->xml_entities($product->getName()) . '</g:title>';
		$xml_string .= '<description>' . $this->xml_entities($product->getName()) . '</description>';

        // Categoria do Produto
        $xml_string .= $this->getCategory($product);

        // Categoria do Google
        $xml_string .= $this->getGoogleCategoria($product);

		$xml_string .= '<link>' . str_replace( 'index.php/', '', Mage::getBaseUrl() . $product->getData('url_path') ) . '</link>';
		$xml_string .= '<g:image_link>' .  Mage::getModel('catalog/product_media_config')->getMediaUrl( $product->getImage())  . '</g:image_link>';
		$xml_string .= '<g:condition>new</g:condition>';
		$xml_string .= '<g:shipping>';
		$xml_string .= 		'<g:country>BR</g:country>';
			   $xml_string .= 	'<g:service>Correios</g:service>';
			   $xml_string .= 	'<g:price>0</g:price>';
		$xml_string .= 		'</g:shipping>';
		$xml_string .= '<g:availability>' . $this->getAvailability($product) . '</g:availability>';

        // Tratando os preços
        $xml_string .= $this->getPricesProduct($product);

        // Tratando o parcelamento
        $xml_string .= $this->tratarParcelamento($product);

        // Valida as tags gtin, mpn e brand
        $xml_string .= $this->validaCodsIdentificadores($product);

        $xml_string .= '</item>';
		return $xml_string;
	}

    // cria xml para o item, o parâmetro é a model do produto
	public function createItemXMLConfigurable($product){
		$xml_string = '<item>';
		$xml_string .=  '<g:id>' . $product->getSku() . '</g:id>';
		$xml_string .= '<g:title>' .  $this->xml_entities($product->getName()) . '</g:title>';
		$xml_string .= '<description>' . $this->xml_entities($product->getName()) . '</description>';

        // Categoria do Produto
        $xml_string .= $this->getCategory($product);

        // Categoria do Google
        $xml_string .= $this->getGoogleCategoria($product);

		$xml_string .= '<link>' . str_replace( 'index.php/', '', Mage::getBaseUrl() . $product->getData('url_path') ) . '</link>';
		$xml_string .= '<g:image_link>' .  Mage::getModel('catalog/product_media_config')->getMediaUrl( $product->getImage())  . '</g:image_link>';
		$xml_string .= '<g:condition>new</g:condition>';
		$xml_string .= '<g:shipping>';
		$xml_string .= 		'<g:country>BR</g:country>';
			   $xml_string .= 	'<g:service>Correios</g:service>';
			   $xml_string .= 	'<g:price>0</g:price>';
		$xml_string .= 		'</g:shipping>';
		$xml_string .= '<g:availability>' . $this->getAvailability($product) . '</g:availability>';

        // Tratando os preços
        $xml_string .= $this->getPricesProduct($product);

        // Tratando o parcelamento
        $xml_string .= $this->tratarParcelamento($product);

        // Valida as tags gtin, mpn e brand
        $xml_string .= $this->validaCodsIdentificadores($product);

		$xml_string .= '</item>';
		return $xml_string;
	}

    // Percorre o produto Bundle para adicionar no XML
	public function createItemXMLBundle($product){
        $bundled = $product;
        $bundled_collection = $bundled->getTypeInstance(true)->getSelectionsCollection(
            $bundled->getTypeInstance(true)->getOptionsIds($bundled), $bundled
        );
        $precoporlaco  = 0;
        $preconormal   = 0;
        $precoespecial = 0;

        foreach($bundled_collection as $produtosimples){
            Mage::getSingleton('core/session', array('name'=>'frontend'));
            $store_id = Mage::app()->getStore()->getId();
            $discounted_price = Mage::getResourceModel('catalogrule/rule')->getRulePrice(
               Mage::app()->getLocale()->storeTimeStamp($store_id),
               // Mage::app()->getStore($store_id)->getWebsiteId(),
               1,
               Mage::getSingleton('customer/session')->getCustomerGroupId(),
               $produtosimples->getId());

            $precoporlaco = $discounted_price * $produtosimples->getData('selection_qty') + $precoporlaco;
            $preconormal += $produtosimples->getPrice() * $produtosimples->getData('selection_qty');

            // Trata os preços do produto simples, compara com o preço de promoção, preço especial e preço normal
            if($produtosimples->getFinalPrice() < $produtosimples->getPrice() && $discounted_price > 0 && $produtosimples->getFinalPrice() < $discounted_price){
                $precoespecial += $produtosimples->getFinalPrice() * $produtosimples->getData('selection_qty');
            }elseif($discounted_price > 0 && $discounted_price < $produtosimples->getPrice()){
                $precoespecial += $discounted_price * $produtosimples->getData('selection_qty');
            }else{
                $precoespecial += $produtosimples->getFinalPrice() * $produtosimples->getData('selection_qty');
            }
        }

        $xml_string = '<item>';
		$xml_string .=  '<g:id>' . $product->getSku() . '</g:id>';
		$xml_string .= '<g:title>' . $product->getName() . ' (pacote) </g:title>';
		$xml_string .= '<description>' . $product->getName() . '</description>';

        // Categoria do Produto
        $xml_string .= $this->getCategory($product);

        // Categoria do Google
        $xml_string .= $this->getGoogleCategoria($product);

		$xml_string .= '<link>' . str_replace( 'index.php/', '', Mage::getBaseUrl() . $product->getData('url_path') ) . '</link>';
		$xml_string .= '<g:image_link>' . Mage::getModel('catalog/product_media_config')->getMediaUrl( $product->getImage())  . '</g:image_link>';
		$xml_string .= '<g:condition>new</g:condition>';
		$xml_string .= '<g:shipping>';
		$xml_string .= 		'<g:country>BR</g:country>';
			   $xml_string .= 	'<g:service>Correios</g:service>';
			   $xml_string .= 	'<g:price>0</g:price>';
		$xml_string .= 		'</g:shipping>';
		$xml_string .= '<g:availability>' . $this->getAvailability($product) . '</g:availability>';

        // Tratando os preços do bundle
        $xml_string .= $this->tratandoPrecosBundle($preconormal,$precoespecial);

        // Tratando o parcelamento
        $xml_string .= $this->tratarParcelamento($product,$precoespecial);

		$xml_string .= '<g:identifier_exists>false</g:identifier_exists>';

		$xml_string .= '</item>';
		return $xml_string;
	}

    function tratandoPrecosBundle($preconormal,$precoespecial){
        $xml_string = '';

        // Trata o tipo de preços escolhido no admin
        switch($this->getGoogleMerchant()->getTipopreco()){

            // Desconto preenchido no admin, caso nao exista desconto é colocado apenas o valor final
            case 'precodesconto':
                $porcentagem = $this->getGoogleMerchant()->getDesconto();
                if($porcentagem > 0){
                    $porcentagem = $porcentagem/100;
                    $precoespecial -= $precoespecial*$porcentagem;
                }
                $xml_string .= '<g:price>'.$precoespecial.'</g:price>';
                break;

            // Insere o "de" e "por" caso o preço final seja menor que o preço normal do produto
            case 'precoespecial':
                if($precoespecial < $preconormal){
                    $xml_string .= '<g:price>'.$preconormal.'</g:price>';
                    $xml_string .= '<g:sale_price>'.$precoespecial.'</g:sale_price>';
                } else {
                    $xml_string .= '<g:price>'.$preconormal.'</g:price>';
                }
                break;

            // Método padrão mostrando apenas o preço final
            default:
                $xml_string .= '<g:price>'.$precoespecial.'</g:price>';
        }

        return $xml_string;
    }

	function xml_entities($string) {
		return str_replace(
			array("&",     "<",    ">",    '"',      "'"),
			array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), 
			$string
		);
	}

	function precoParcelas( $valor_produto ){
	    $valor_parcela = 1;
	    $parcelas      = 5;
	    $total         = '1x de R$ ' . number_format( $valor_produto, 2, ',', '.' );

	    if( $valor_produto <= $valor_parcela ){
	        return $total;
	    }

	    for( $i = 1, $j = 0; $i <= $parcelas; $i++, $j++ ){
	        if( ( $valor_produto / $i ) < $valor_parcela ){
	            break;
	        }
	    }

	    return ' R$ ' . number_format( round( ( $valor_produto / $j ), 2 ), 2, '.', ',' );
	}

	function nParcelas( $valor_produto ){
	    $valor_parcela = 1;
	    $parcelas      = 5;
	    $total         = '1x de R$ ' . number_format( $valor_produto, 2, ',', '.' );

	    if( $valor_produto <= $valor_parcela ){
	        return $total;
	    }

	    for( $i = 1, $j = 0; $i <= $parcelas; $i++, $j++ ){
	        if( ( $valor_produto / $i ) < $valor_parcela ){
	            break;
	        }
	    }

	    return $j;
	}

    public function getGoogleCategoria($product){
        $xml_string = '';
        if($product->getGoogleCategoria()){
            $google_categoria = Mage::getModel('gmerchant/categorias')->load($product->getGoogleCategoria());
            if($google_categoria->getName())
                $xml_string .= '<g:google_product_category><![CDATA['.$google_categoria->getName().']]></g:google_product_category>';
        }

        return $xml_string;
    }

	// gmerchant modulo
	public function getGoogleMerchant(){
		return Mage::getModel('gmerchant/gmerchant')->load(1);
	}

	// cria o cabeçalho do xml
	public function createHeaderXML(){
		$xml_string = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml_string .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">';
		$xml_string .= '<channel>';
		$xml_string .= '<title>' . Mage::app()->getStore()->getGroup()->getName() . '</title>';
		$xml_string .= '<link>'. Mage::getBaseUrl() . '</link>';
		$xml_string .= '<description>Feed de Dados dos Produtos</description>';
		return $xml_string;
	}

	// cria o rodapé do xml
	public function createFooterXML(){
		$xml_string = '</channel>';
		$xml_string .= '</rss>';
		return $xml_string;
	}

    // Inclui somente as tags gtin, mpn e brand se possuir dados válidos. Caso contrário é colocado a tag "identifier_exists"
    public function validaCodsIdentificadores($product){
        $xml_string = '';
        $mpn        = $this->getMpn($product);
        $gtin       = $this->getGtin($product);
        $brand      = $this->getBrand($product);

        // Se não possuir gtin, mpn e brand é colocado a tag "identifier_exists"
        if($mpn == '' && $gtin == '' && $brand == ''){
            $xml_string .= '<g:identifier_exists>false</g:identifier_exists>';
        } else {
            // Insere somente a tag mpn/gtin/brand se possuir dados válidos
            if($mpn != '')
                $xml_string .= '<g:mpn>'.$mpn.'</g:mpn>';
				
            if($gtin != '')
                $xml_string .= '<g:gtin>'.$gtin.'</g:gtin>';
				
            if($brand != '')
                $xml_string .= '<g:brand>'.$brand.'</g:brand>';
        }

        return $xml_string;
    }

    // Valida o tipo do atributo, caso seja select pega por "atributeText" e caso contrário pega por getData
    public function validaTipoAtributo($produto,$attribute_code){
        $attribute = Mage::getSingleton("eav/config")->getAttribute("catalog_product", $attribute_code);

        if($attribute->getData('frontend_input') == 'select')
            $data = $produto->getAttributeText($attribute_code);
        else
            $data = $produto->getData($attribute_code);

        return $data;
    }

	//retorna a mpn
	public function getMpn($produto){
		if($this->getGoogleMerchant()->getMpn())
            return $this->validaTipoAtributo($produto,$this->getGoogleMerchant()->getMpn());
		else
			return '';
	}	

	//retorna gtin
	public function getGtin($produto){
		if($this->getGoogleMerchant()->getGtin())
            return $this->validaTipoAtributo($produto,$this->getGoogleMerchant()->getGtin());
		else
			return '';
	}

	//retorna a marca do produto
	public function getBrand($produto){
		if($this->getGoogleMerchant()->getBrand())
            return $this->validaTipoAtributo($produto,$this->getGoogleMerchant()->getBrand());
		else
            return '';
	}

    public function tratarParcelamento($product,$bundle_valor = 0){
        $xml_string  = '';

        if($this->getGoogleMerchant()->getParcelamento()){

            $qty_parcelas = $this->getGoogleMerchant()->getQtyParcelas();
            $valor_min    = $this->getGoogleMerchant()->getValorMin();

            if($bundle_valor > 0){
                $valor = $bundle_valor;
            } else {
                $valor = $this->getRulePrice($product);
                if($valor > $product->getFinalPrice())
                    $valor = $product->getFinalPrice();
            }

            if($qty_parcelas > 0){
                $valor_parcela = $valor;

                for($ii=1;$ii <= $qty_parcelas; $ii++){
                    $valor_parcela = $valor / $ii;
                    $prox_parc = $valor / ($ii+1);
                    if($prox_parc < $valor_min){
                        break;
                    }
                }

                $xml_string .= '<g:installment>';
                $xml_string .=  '<g:months>'.$ii.'</g:months>';
                $xml_string .=  '<g:amount>R$ '.number_format($valor_parcela,2,',','.').'</g:amount>';
                $xml_string .= '</g:installment>';
            }
        }

        return $xml_string;
    }

    public function getPricesProduct($product){
        $precos['normal']   = $product->getPrice();
        $precos['special']  = $product->getSpecialPrice();
        $precos['promocao'] = $this->getRulePrice($product);
        $precos['final']    = $product->getPrice();
        $xml_string         = '';

        // Tratando o preço especial e o preço da promoção e assim pegando o menor valor e salvando no array precos['final']
        if($precos['special'] > 0 && $precos['special'] < $precos['promocao'])
            $precos['final'] = $precos['special'];
        else
            $precos['final'] = $precos['promocao'];

        // Trata o tipo de preços escolhido no admin
        switch($this->getGoogleMerchant()->getTipopreco()){

            // Desconto preenchido no admin, caso nao exista desconto é colocado apenas o valor final
            case 'precodesconto':
                $porcentagem = $this->getGoogleMerchant()->getDesconto();
                if($porcentagem > 0){
                    $porcentagem = $porcentagem/100;
                    $precos['final'] -= $precos['final']*$porcentagem;
                }
                $xml_string .= '<g:price>'.$precos['final'].'</g:price>';
            break;

            // Insere o "de" e "por" caso o preço final seja menor que o preço normal do produto
            case 'precoespecial':
                if($precos['final'] < $precos['normal']){
                    $xml_string .= '<g:price>'.$precos['normal'].'</g:price>';
                    $xml_string .= '<g:sale_price>'.$precos['final'].'</g:sale_price>';
                } else {
                    $xml_string .= '<g:price>'.$precos['normal'].'</g:price>';
                }
            break;

            // Método padrão mostrando apenas o preço final
            default:
                $xml_string .= '<g:price>'.$precos['final'].'</g:price>';
        }

        return $xml_string;
    }

    // Retorna o preço do produto com desconto das promoções ativas e aplicadas
	public function getRulePrice($product){
		$original_price = $product->getPrice();
		$store_id = 1;
		$discounted_price = Mage::getResourceModel('catalogrule/rule')
            ->getRulePrice(
                Mage::app()->getLocale()->storeTimeStamp($store_id),
                Mage::app()->getStore($store_id)->getWebsiteId(),
                0,
                $product->getId()
            );

		if ($discounted_price === false) {
		    $discounted_price = $original_price;
		}

		return $discounted_price;
	}

	// retorna disponibilidade do produto
	public function getAvailability($produto){
		$produto->load($produto->getId()); // recarrega model para trazer info de estoque
		$avai = $produto->getStockItem()->getIsInStock();
		if($avai == 1){
			return 'in stock';
		}else if($avai == 0){
			return 'out of stock';
		}
	}

	// retorna a categoria do produto
	public function getCategory($product){
		$category_ids = ($product->getCategoryIds());
        $xml_string   = '';
		if(count($category_ids) > 0){
			$category = Mage::getModel('catalog/category')->load($category_ids[count($category_ids)-1]);
            $xml_string .= '<g:product_type><![CDATA['.$category->getName().']]></g:product_type>';
		}

        return $xml_string;
	}

	// função que ficará responsável por criar o XML do Google Merchant
	public function doXML(){
	}

}
