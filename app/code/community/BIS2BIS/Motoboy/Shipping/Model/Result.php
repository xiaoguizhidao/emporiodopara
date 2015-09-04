<?php

/**
 * Mauro Miyauti
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL).
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Transportadora
 * @package    Transportadora_Shipping
 * @copyright  Copyright (c) 2009 Mauro Miyauti [ msmiyauti@gmail.com ]
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Mototaxi_Shipping_Model_Result extends Varien_Data_Collection_Db {

    protected $_shipTable;
    protected $_countryTable;
    protected $_regionTable;
    public $cep;
    public $peso;
    public $uf;

    protected function _construct() {
        
    }

    public function inicializar() {
        parent::__construct(Mage::getSingleton('core/resource')->getConnection('mtshipping_read'));
    }

    public function getUfFromCep($destino) {
        $sql = "select * from correios_regiao_uf 
				WHERE cep_inicial <= $destino  
				AND cep_final >= $destino";

        $r = $this->getSelect()
                ->getAdapter()
                ->fetchAll($sql);


        if (count($r) > 0) {
            $regiaoDestino = $r[0]["uf"];
        } else {
            $regiaoDestino = "";
        }

        return $regiaoDestino;
    }

}

?>