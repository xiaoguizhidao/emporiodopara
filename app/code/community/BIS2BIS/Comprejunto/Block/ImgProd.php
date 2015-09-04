<?php
/*
 * My Render Extension
 */
class BIS2BIS_Comprejunto_Block_ImgProd extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    
    public function render(Varien_Object $row)
    {
		$ids = explode(',',$row->getData('id_prods'));
		$out = '';
		
		foreach($ids as $key => $prod){
			$product = Mage::getModel('catalog/product')->load($prod);
			
			if($product->getData('thumbnail') != 'no_selection'){
				$url = Mage::getBaseUrl('media') . 'catalog/product' . $product->getData('thumbnail');
				$out .= "<p><img src=". $url ." align='middle' width='60px'/> - ".$product->getName()."</p>";
			} else {
				$out .= '<p><small>Sem Imagem</small> - '.$product->getName().'</p>';
			}
			
			if($key==1)
				continue;
			else
				$out .= '<img src="'.Mage::getBaseUrl('media').'icon_mais.png" width="15px" height="15px" />';
				
			
			
		}
		
		return $out;

    }
    
}