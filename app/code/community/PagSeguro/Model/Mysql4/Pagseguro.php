<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagseguro
 *
 * @author bis2bis
 */
class PagSeguro_Model_Mysql4_PagSeguro extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('pagseguro/pagseguro', 'Referencia');
        $this->_isPkAutoIncrement = false;
    }
}

