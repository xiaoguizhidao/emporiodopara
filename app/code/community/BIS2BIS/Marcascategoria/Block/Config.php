<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

	class BIS2BIS_Marcascategoria_Block_Config extends Mage_Adminhtml_Block_Widget_Form_Container {
	    
	    public function __construct() {
	        $this->_objectId = 'id';
	        $this->_blockGroup = 'marcascategoria';
	        $this->_controller = 'config';
	        parent::__construct();
	    }
	    
	    protected function _prepareLayout() {
	        $this->setChild('form', $this->getLayout()->createBlock('marcascategoria/Config_Form'));
	        return parent::_prepareLayout();
	    }

	    public function getHeaderText() {
	        return 'Configuração Marcas por Categoria';
	    }

	}