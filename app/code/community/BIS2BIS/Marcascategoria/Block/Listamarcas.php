<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

	class BIS2BIS_Marcascategoria_Block_Listamarcas extends Mage_Adminhtml_Block_Widget_Grid_Container {

	    public function __construct() {
	        $this->_blockGroup = 'marcascategoria';
	        $this->_controller = 'listamarcas';
	        $this->_headerText = Mage::helper('marcascategoria')->__('Gerenciar Marcas');

	        parent::__construct();
			
			$this->_addButton('adicionar', array(
	            'label'     => Mage::helper('marcascategoria')->__('Cadastrar Marca'),
	      		'onclick' => "setLocation('{$this->getUrl('*/*/gerenciarmarcas')}')"
	        ));

	        $this->_removeButton('add');
	    }   
	}