<?php
class BIS2BIS_Cores_Helper_Data extends Mage_Core_Helper_Abstract{

	public function getBaseDir(){
		return str_replace('index.php/', '',  Mage::getBaseUrl() . 'media/cores/');
	}

	// valida se tem em estoque para o primeiro atributo
    //
	public function validarEstoque($produto_configurado, $codigo_atributo, $valor){
		$filhos = $produto_configurado->getTypeInstance()->getUsedProductCollection();
		$filhos->addAttributeToSelect($codigo_atributo);
		$filhos->addFieldToFilter($codigo_atributo, $valor);
		Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($filhos);

		if($filhos->getSize())
			return false;
		else
			return true;
	}

}