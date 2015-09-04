<?php
class BIS2BIS_Comprejunto_Block_Grid extends Mage_Adminhtml_Block_Widget_Grid{

	public function __construct(){
		parent::__construct();

		$this->setId('id');
                $this->setDefaultSort('id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
		$this->setTemplate('comprejunto/grid.phtml');
	}
	
	protected function _prepareCollection(){
		$collection = Mage::getModel('comprejunto/comprejunto')->getCollection();
        	$this->setCollection($collection);
		
		parent::_prepareCollection();
		return $this;
	}
	
	protected function _prepareColumns(){
			
		$this->addColumn('id_prods',array(
		'header' => 'Produtos Casados',
		'sortable' => false,
		'index' => 'id_prods',
		'renderer' => 'BIS2BIS_Comprejunto_Block_ImgProd',
		));
		
		$this->addColumn('tipodesconto',array(
		'header' => 'Tipo Desconto',
		'sortable' => false,
		'renderer' => 'BIS2BIS_Comprejunto_Block_Tipodesconto',
		'index' => 'tipodesconto'
		));
		
		$this->addColumn('desconto',array(
		'header' => 'Desconto',
		'sortable' => true,
		'index' => 'desconto'
		));
		
		parent::_prepareColumns();
	}
	
	public function addNewButton(){
		return $this->getButtonHtml(
		'Criar Regra',
		"setLocation('".$this->getUrl('*/*/form', array('id'=>0))."')",
		"scalable add"
		);
	}
	
	public function getRowUrl($row){
		return $this->getUrl('*/*/form', array('id' => $row->getId()));
	}
	
}