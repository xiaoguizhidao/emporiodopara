<?php

class BIS2BIS_Banner_Model_Thumbnail extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		$url =  $this->getBaseUrl() . $value ;
		$url =str_replace('/index.php', '', $url);


        $type = $url;
        $type = substr($type,(strlen($type)-3),strlen($type));
        
        if($type == 'swf'){
        	$url = $this->getBaseUrl() . 'skin/adminhtml/default/default/images/banner/flash.png';
        	$url = str_replace('/index.php', '', $url);
			$img = '<center><img  src="'.$url.'" /> </center>';
        }else{
        	if($value != ''){
				$img = '<center><img width="100"  src="'.$url.'" /> </center>';
			}else{
				$url = 'http://bis2bis.com.br/imgs/logo.png';
				$img = '<center><img  src="'.$url.'" /> </center>';
			}
        }


	

		return $img;
	}
}
?>