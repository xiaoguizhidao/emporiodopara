<?php 

class BIS2BIS_Cores_FrontController extends Mage_Core_Controller_Front_Action {

	// Retorna informação da cor
	public function selecionarAction(){

		$params = $this->getRequest()->getParams();
		
		// ======== CAMPOS
		$id_produto      = $params['produto_configuravel'];

		$valor_atributo  = $params['valor_atributo'];

		$codigo_atributo = $params['codigo_atributo'];

		$index 			 = $params['index']; 

		$valores_explodidos = explode('#', $valor_atributo);

		$codigos_explodidos = explode('#', $codigo_atributo);

		$array_attributos = array();

		for( $i =0; $i < count($valores_explodidos); $i++){
			$array_attributos[] = array(
				'codigo' => $codigos_explodidos[$i],
				'valor' => $valores_explodidos[$i]
			);
		}

		// ======== CAMPOS

		array_pop($array_attributos); // remove última posição do array

		// ==== Instancia o produto
		$produto = Mage::getModel('catalog/product')->load($id_produto);
		
		// ==== Retorna todos os atributos disponíveis
		$atributos  = $produto->getTypeInstance(true)->getConfigurableAttributesAsArray($produto); 

		// ==== Recebe proximo atributo
		if($proximo_atributo = $atributos[$index+1]['attribute_code']){
			$produto_config = Mage::getModel('catalog/product_type_configurable')->setProduct($produto);
			
			$collection_produtos = $produto_config->getUsedProductCollection();
			$collection_produtos->addAttributeToSelect($proximo_atributo);
			foreach($array_attributos as $atributo){
				$collection_produtos->addAttributeToSelect($atributo['codigo']);
				$collection_produtos->addFieldToFilter($atributo['codigo'], $atributo['valor']);
			}

			Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection_produtos);

			$retorno_array = array();

			foreach($collection_produtos as $produto_simples){
				$proximos_existentes[] = $produto_simples->getData($proximo_atributo);
			}

			$retorno_array['proximos_itens'] = $proximos_existentes;
			
			$retorno_array['proximo_atributo'] = $proximo_atributo;
			
			//print_r($retorno_array); die;
			echo json_encode($retorno_array);

		}

	}
}
?>