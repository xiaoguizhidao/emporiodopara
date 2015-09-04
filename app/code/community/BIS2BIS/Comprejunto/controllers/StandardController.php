<?php
class BIS2BIS_Comprejunto_StandardController extends Mage_Core_Controller_Front_Action{

    public function indexAction(){
        die('teste index');
    }
	
	public function addcartAction(){
		$params	 	= $this->getRequest()->getParams();
		$cart	 	= Mage::getModel("checkout/cart");
		$produtos	= array();
		$desconto   = 0;
		
		if($params['produtosimples']){
			foreach($params['produtosimples'] as $prod){
				$produtos[] = $prod;
				$cart->addProduct($prod, 1); 
			}
		} 
		
		if($params['configuravel']){
			
			foreach($params['configuravel'] as $id_prod => $valores) {
				
				$super_attribute = array();
				
				foreach($valores as $key => $valor){
					$super_attribute[$key] = $valor;
				}
				
				$parametros = array(
					'product' => $id_prod,
					'super_attribute' => $super_attribute,
					'qty' => 1,
				);
				
				$produtos[] = $id_prod;
				$cart->addProduct($id_prod, $parametros);
			}
		}
		
		$desconto 	  = $params['desconto'];
		$tipodesconto = $params['tipodesconto'];
		$items 		  = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
		
		$verifica = false;
		foreach($items as $item) {
			if($item->getProductId() == $produtos[0] && $item->getComprejuntoIds() != '' && $item->getComprejuntoIds() != 'desativado' && $item->getComprejuntoIds() != $produtos[1]){
				$verifica = true;
			}
			
			if($item->getProductId() == $produtos[1] && $item->getComprejuntoIds() != '' && $item->getComprejuntoIds() != 'desativado' && $item->getComprejuntoIds() != $produtos[0]){
				$verifica = true;
			}
		}
		if(!$verifica){
			$cart->save();
			$datatime = date('Y-m-d H:i:s');
			
			if($produtos[0] && $produtos[1]){		
				foreach($items as $item) {
					if($item->getProductId() == $produtos[0] && $item->getUpdatedAt() == $datatime){
						$item->setComprejuntoIds($produtos[1]);
						$item->setTipoDesconto($tipodesconto);
						$item->setComprejuntoAmount($desconto);
						$item->setBaseComprejuntoAmount($desconto);
						$item->save();
					} elseif($item->getProductId() == $produtos[1] && $item->getUpdatedAt() == $datatime){
						$item->setComprejuntoIds($produtos[0]);
						$item->setTipoDesconto($tipodesconto);
						$item->setComprejuntoAmount($desconto);
						$item->setBaseComprejuntoAmount($desconto);
						$item->save();
					}
				}
			}
			
			Mage::getSingleton('core/session')->addSuccess('Produtos com desconto adicionados com sucesso.');
		} else {
			Mage::getSingleton('core/session')->addNotice('Não foi possível adicionar os produtos ao seu carrinho de compras pois já existem produtos com desconto.');
		}
		$this->_redirect('checkout/cart/');
		
	}
	
	// Passa o produto configurável e o id do atributo desejado, a função verifica os atributos vinculados e retorna o preço de acréscimo
	public function getprecochangeAction($product,$attr_id){
		$attrs = $product->getTypeInstance(true)->getConfigurableAttributes($product);
		foreach ($attrs as $attribute) {
			$productAttribute = $attribute->getProductAttribute();
			$prices = $attribute->getPrices();
			foreach ($prices as $value) {
				if(isset($value['value_index']) && $attr_id == $value['value_index']){
					$preco_change = $value['pricing_value'];
				}
			}
		}
		
		return $preco_change;
	}
	
	// Ao selecionar o segundo atributo, geralmente "tamanho" retorna o valor de acréscimo dessa opção
	public function updateprecoAction(){
		$product_id = $this->getRequest()->getParam('product_id');
		$attr_id = $this->getRequest()->getParam('attr_id');
		
		if(!empty($product_id)){
			$product = Mage::getModel('catalog/product')->load($product_id);				
			echo number_format($this->getprecochangeAction($product,$attr_id),2,'.','');
		}
	}
	
	// Retorna valor do próximo atributo (Cores)
	public function getnextfirstAction(){
		$params = $this->getRequest()->getParams();
		$attr_id = $params['attr_id'];
		$attr_code = $params['attr_code'];
		$next_attribute = $params['next_attribute'];
		$product = Mage::getModel('catalog/product')->load($params['product_id']);
		$_products = $product->getTypeInstance()->getUsedProductCollection()->addAttributeToSelect('*')->addFieldToFilter($attr_code, $attr_id);
		Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($_products); 
		$_array_prds = array();
		
		$block = Mage::getBlockSingleton('catalog/product_list');
		
		foreach($_products as $_product){
			$img = (string)$block->helper('catalog/image')->init($_product, 'small_image')->resize(150);

			$_array_prds[] = array(
				'prod_pai_id'    => $params['product_id'],
				'option_id'      => $_product->getData($next_attribute),
				'attribute_text' => $_product->getAttributeText($next_attribute),
				'imagem' 		 => $img,
				'preco_change'   => number_format($this->getprecochangeAction($product,$attr_id),2,'.','')
									
			);
		}
		
		echo json_encode($_array_prds);
	}
	
}
?>

