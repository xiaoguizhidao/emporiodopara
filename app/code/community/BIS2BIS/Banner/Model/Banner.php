<?php
/**
############################################################################################
############################################################################################
########  BIS2BIS - COMÉRCIO ELETRÔNICO                                                    #
########  Módulo : Banner                                                                  #
########  Desenvolvedor : Guilherme Costa                                                  #
########  Email : guilherme.costa@bis2bis.com.br                                           #
########  Descrição : Model do módulod de Banner                      					   #
############################################################################################
############################################################################################
*/

class BIS2BIS_Banner_Model_Banner extends Mage_Core_Model_Abstract{

	private $db;
	 
    protected function _construct(){
    	$this->db = new Varien_Data_Collection_Db();
    	$this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('banner_write'));
        $this->_init('banner/banner');
    }

    public function deleteImage($path){
    	$file = (str_replace('media/banner/', '', $path));
    	$_path = Mage::getBaseDir('media') . DS . 'banner' ;
    	chmod($_path, 0777);
    	$file_path = $_path . DS . $file;
        if(is_file($file_path))
    	   unlink($file_path);
    }
	
}

?>