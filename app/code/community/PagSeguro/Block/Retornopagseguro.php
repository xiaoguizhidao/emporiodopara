<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Retornopagseguro
 *
 * @author Mariane
 */
class PagSeguro_Block_Retornopagseguro extends Mage_Core_Block_Template {

    public function _construct() {
        parent::_construct();
        $this->setTemplate('pagseguro/retornoCompra.phtml');
        return $this;
    }
}

?>
