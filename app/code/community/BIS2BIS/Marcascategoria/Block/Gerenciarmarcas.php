<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

	class BIS2BIS_Marcascategoria_Block_Gerenciarmarcas extends Mage_Adminhtml_Block_Widget_Form_Container {

	    public function __construct() {
	        $this->_objectId = 'id';
	        $this->_blockGroup = 'gerenciarmarcas';
	        $this->_controller = 'admin';
	        parent::__construct();

	        $this->_removeButton('delete');

	       	$this->_addButton('delete', array(
	            'label'     => Mage::helper('marcascategoria')->__('Excluir'),
	            'onclick'   => 'setLocation(\'' . $this->getUrl('*/*/excluirmarca' , array('id' => $this->getRequest()->getParam('id'))) . '\')',
	            'class'     => 'delete',
	        ),-1,1);

	        
	    }
	    protected function _prepareLayout() {
	        $this->setChild('form', $this->getLayout()->createBlock('marcascategoria/Gerenciarmarcas_Form'));
	        return parent::_prepareLayout();
	    }

	    public function getHeaderText() {
	        return 'Cadastrar nova Marca';
	    }
	}