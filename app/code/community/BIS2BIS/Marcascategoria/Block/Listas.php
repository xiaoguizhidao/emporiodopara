<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

	class BIS2BIS_Marcascategoria_Block_Listas extends Mage_Adminhtml_Block_Widget_Grid_Container {

	    public function __construct() {
	        $this->_blockGroup = 'marcascategoria';
	        $this->_controller = 'listas';
	        $this->_headerText = Mage::helper('marcascategoria')->__('Listas de Marcas');


	        //print_r(get_class_methods($this)); die;
	        parent::__construct();

			
			$this->_addButton('adicionar', array(
	            'label'     => Mage::helper('marcascategoria')->__('Criar Lista'),
	      		'onclick' => "setLocation('{$this->getUrl('*/*/criarlista')}')"
	        ));

	        $this->_removeButton('add');
	    }   
	}