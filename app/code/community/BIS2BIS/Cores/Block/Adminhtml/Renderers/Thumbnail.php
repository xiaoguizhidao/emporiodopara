<?php
class BIS2BIS_Cores_Block_Adminhtml_Renderers_Thumbnail  extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    
    public function render(Varien_Object $row){
        $value =  $row->getData($this->getColumn()->getIndex());
        $cor = Mage::getModel('cores/cores')->load($value);
        if($cor->getImagem()){
        	return '<img width="60px" src="'. Mage::helper('cores')->getBaseDir()  .   $cor->getImagem() .  '" />';
        }else{
        	return '<div style=" width : 100px; height : 20px; background : '.$cor->getCor() .'"></div>';
    	}
    }
    
    
}
?>