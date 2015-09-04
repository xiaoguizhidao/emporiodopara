<?php
class BIS2BIS_Comprejunto_Model_Sales_Quote_Item extends Mage_Sales_Model_Quote_Item
{
	public function calcRowTotal()
	{
		$qty        = $this->getTotalQty();
		$total      = $this->getCalculationPrice();
		$baseTotal  = $this->getBaseCalculationPrice();
		
		/* Adicionando taxa de presente */		
		$product_ids  = array();
		$product_qtys = array();
		
		$products = $this->getQuote()->getAllVisibleItems();		
		foreach ($products as $_products){
			$product_ids[]  = $_products->getProductId();
			$product_qtys[$_products->getProductId()] = $_products->getQty();
		}
		
		// Cálculo de desconto com porcentagem ou valor fixo
		if($this->getTipoDesconto() == 'porcent'){
			$porcentagem = $this->getComprejuntoAmount();
			$desconto = ($porcentagem/100)*$total;
		} elseif($this->getTipoDesconto() == 'fixo'){
			$desconto = $this->getComprejuntoAmount()/2;
		} else {
			$desconto = 0;
		}
		
		// Verifica se existe o produto casado dentro do carrinho e aplica o desconto, caso false seta como desativado esse produto
		if(in_array($this->getComprejuntoIds(),$product_ids)){
			$total -= $desconto;
		} else {
			$this->setComprejuntoIds('desativado');
			$this->setTipoDesconto('desativado');
			$this->setComprejuntoAmount('desativado');
			$this->setBaseComprejuntoAmount('desativado');
		}
		
		/*  Caso seja desativado aplica a regra normal do carrinho, caso contrário é feita verificação dos 
			produtos e aplicando o desconto somente nos produtos casados e filtrando também a quantidade de um mesmo produto
			adicionado várias vezes.
		*/
		if($qty > 1 && $this->getComprejuntoIds() == 'desativado'){
			$total *= $qty;
		} elseif($qty > 1 && in_array($this->getComprejuntoIds(),$product_ids)){
			
			if(isset($product_qtys[$this->getComprejuntoIds()]) && $product_qtys[$this->getComprejuntoIds()] == $qty){
				$total = $total*$qty;
			} else {
				// echo $product_qtys[$this->getComprejuntoIds()].' - '.$qty.'<br>';
				if(isset($product_qtys[$this->getComprejuntoIds()]) && $qty > $product_qtys[$this->getComprejuntoIds()]){
					$calc_qty = $qty-$product_qtys[$this->getComprejuntoIds()];
					$qty -= $calc_qty;
					$total = ($total*$qty)+($this->getCalculationPrice() * $calc_qty);
				} else {
					// echo $total;
					$total = $total*$qty;
					
				}
			}
		}
		
		$this->setRowTotal($this->getStore()->roundPrice($total));
		$this->setBaseRowTotal($this->getStore()->roundPrice($baseTotal));
		return $this;
	}
}
