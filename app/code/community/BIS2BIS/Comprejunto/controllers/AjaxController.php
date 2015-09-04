<?php
class BIS2BIS_Comprejunto_AjaxController extends Mage_Adminhtml_Controller_Action{
	
	public function autocompleteAction(){
		
		$query = $this->getRequest()->getParam('query');
		$tipo = $this->getRequest()->getParam('tipo');
		
		$collection = Mage::getModel('catalog/product')->getCollection();
		$collection->addAttributeToFilter(array(array('attribute'=>'name','like'=>"%$query%"),));
		
		foreach($collection as $prod){
			echo '<li id="'.$prod->getId().'" class="ui-widget-content">'.$prod->getName().' | '.$prod->getSku().'</li>';
		}
		
	}
	
	public function saveAction(){
		$dados = $this->getRequest()->getParams();
		$ids = $dados['input_produto1'].','.$dados['input_produto2'];
		$tipodesconto = $dados['tipodesconto'];
		$desconto = $dados['desconto'];
		
		$modelo = Mage::getModel('comprejunto/comprejunto');
		$modelo->setId_prods($ids);
		$modelo->setTipodesconto($tipodesconto);
		$modelo->setDesconto($desconto);
		$modelo->save();
		
		Mage::getSingleton('adminhtml/session')->addSuccess('Regra salva com sucesso.');
        $this->_redirect('*/Index/index');
	}

}
?>
