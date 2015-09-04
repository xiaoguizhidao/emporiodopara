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

class BIS2BIS_Banner_Model_Bannerconfig extends Mage_Core_Model_Abstract{

	private $db;
	 
    protected function _construct(){
    	$this->db = new Varien_Data_Collection_Db();
    	$this->db->__construct(Mage::getSingleTon('core/resource')->getConnection('banner_write'));
        $this->_init('banner/bannerconfig');
    }

    public function getBaseUrl(){
    	$bannerconfig = Mage::getModel('banner/bannerconfig')->load(1);

    	if($bannerconfig->getActive() == 0){
    		return $bannerconfig->getUrl();
    	}else{
    		return str_replace('/index.php', '', Mage::getBaseUrl()) ;
    	}
    }


	
}

?>