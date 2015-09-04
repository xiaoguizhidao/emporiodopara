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
class BIS2BIS_Motoboyconfig_Block_RangeCep_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct(){
        parent::__construct();
        $this->setId('gridRangeCep');
    }

    protected function _prepareCollection(){
       

        $collection = Mage::getModel('motoboyconfig/motoboyconfig')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
	    return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn("id", array(
           "header" => "ID",
           "sortable" => true,
           "index" => "id" 
        ));

        $this->addColumn('cepinicial', array(
            'header'    => $this->__('CEP Inicial'),
            'sortable'  => true,
            'index'     => 'cepinicial'
        ));
        
        $this->addColumn('cepfinal', array(
            'header'    => $this->__('CEP Final'),
            'sortable'  => true,
            'index'     => 'cepfinal'
        ));
        
        $this->addColumn('valor_pedido', array(
            'header'    => Mage::helper('catalog')->__('Valor Frete'),
            'type'  => 'currency',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'valor_pedido'
        ));
        
        $this->addColumn('prazo', array(
           'header' => Mage::helper("catalog")->__("Prazo"),
           'sortable'  => true,
           'index'     => 'prazo'
        ));
        
        echo ($this->getButtonHtml("Nova Faixa", "setLocation('" . $this->getUrl('*/*/rangecepform', array('id' => "")) . "')", "scalable add "));

        $this->addExportType('*/*/exportOrdersCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportOrdersExcel', Mage::helper('reports')->__('Excel'));

        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}

?>
