<?php
class BIS2BIS_Shipping_Block_Adminhtml_Shipping_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
         
        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('shipping_grid_table');
    }
     
    protected function _getCollectionClass()
    {
        // This is the model we are using for the grid
        return 'bis2ship/shippingtables_collection';
    }
     
    protected function _prepareCollection()
    {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
         
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {

        $this->addColumn('status',
            array(
                'header'=> $this->__('Status'),
                'align' =>'center',
                'width' => '10px',
                'index' => 'entity_id',
                'renderer'  => 'BIS2BIS_Shipping_Block_Renderers_Status',
            )
        );

        // Add the columns that should appear in the grid
        $this->addColumn('entity_id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'entity_id'
            )
        );


        $this->addColumn('titulo',
            array(
                'header'=> $this->__('Título da Tabela'),
                'align' =>'right',
                'width' => '200px',
                'align' =>'center',
                'index' => 'titulo'
            )
        );

         $this->addColumn('actions',
            array(
                'header'=> $this->__('Ações'),
                'align' =>'center',
                'width' => '200px',
                'index' => 'entity_id',
                'renderer'  => 'BIS2BIS_Shipping_Block_Renderers_Action',
            )
        );
         
    
         
        return parent::_prepareColumns();
    }
     

}