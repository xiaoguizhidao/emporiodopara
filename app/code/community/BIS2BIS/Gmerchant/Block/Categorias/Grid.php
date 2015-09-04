<?php
class BIS2BIS_Gmerchant_Block_Categorias_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct(){
        parent::__construct();

        $this->setId('id');
        $this->setDefaultSort('name');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setTemplate('gmerchant/grid.phtml');
    }

    protected function _prepareCollection(){
        $collection = Mage::getModel('gmerchant/categorias')->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns(){

        $this->addColumn('id',array(
            'header' => 'ID',
            'sortable' => true,
            'width' => '40px',
            'index' => 'id'
        ));

        $this->addColumn('name',array(
            'header' => 'Nome',
            'sortable' => true,
            'index' => 'name'
        ));

        parent::_prepareColumns();
    }

    public function getRowUrl($row){
        return $this->getUrl('*/*/form', array('id' => $row->getId()));
    }

    public function addNewButton(){
        return $this->getButtonHtml(
            'Criar Categorias',
            "setLocation('".$this->getUrl('*/*/form', array('id'=>0))."')",
            "scalable add"
        );
    }

}