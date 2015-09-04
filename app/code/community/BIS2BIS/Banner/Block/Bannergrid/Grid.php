<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Grid
 *
 * @author Intel
 */
class BIS2BIS_Banner_Block_Bannergrid_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct(){
        parent::__construct();
        $this->setId('gridBannergrid');
    }

    protected function _prepareCollection(){
       

        $collection = Mage::getModel('banner/banner')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
	    return $this;
    }

    protected function _prepareColumns()
    {

        $this->addColumn("active", array(
           "header" => "active",
           "sortable" => true,
           "index" => "active",
           'renderer' => 'BIS2BIS_Banner_Model_Active'
        ));


        $this->addColumn("image", array(
           "header" => "image",
           "sortable" => true,
           "index" => "image",
           'renderer' => 'BIS2BIS_Banner_Model_Thumbnail'
        ));


        $this->addColumn("id", array(
           "header" => "ID",
           "sortable" => true,
           "index" => "id" 
        ));

        $this->addColumn('name', array(
            'header'    => $this->__('Name'),
            'sortable'  => true,
            'index'     => 'name'
        ));
        
        $this->addColumn('link', array(
            'header'    => $this->__('Link'),
            'sortable'  => true,
            'index'     => 'link'
        ));
        
        $this->addColumn('type', array(
           'header' => Mage::helper("catalog")->__('Tipo'),
           'sortable'  => true,
           'index'     => 'type',
           'renderer' => 'BIS2BIS_Banner_Model_TypeRenderer'
        ));

        $this->addColumn('first_date', array(
           'header' => Mage::helper("catalog")->__('Data Inicial'),
           'sortable'  => true,
           'index'     => 'first_date',
           'type' => 'date'
        ));

        $this->addColumn('final_date', array(
           'header' => Mage::helper("catalog")->__('Data Final'),
           'sortable'  => true,
           'index'     => 'final_date',
           'type' => 'date'
        ));

        $this->addColumn('category', array(
           'header' => Mage::helper("catalog")->__('Categoria'),
           'sortable'  => true,
           'index'     => 'category',
           'renderer' => 'BIS2BIS_Banner_Model_Category'
        ));

        $this->addColumn('ordem', array(
           'header' => Mage::helper("catalog")->__('Ordem'),
           'sortable'  => true,
           'index'     => 'ordem'
        ));
        
        echo ($this->getButtonHtml("Novo Banner ", "setLocation('" . $this->getUrl('*/*/register', array('id' => "")) . "')", "scalable add "));

        $this->addExportType('*/*/exportOrdersCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportOrdersExcel', Mage::helper('reports')->__('Excel'));

        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}

?>
