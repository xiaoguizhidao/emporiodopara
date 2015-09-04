<?php
class  Mage_Osc_Block_Status_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    
    public function __construct()
    {
        parent::__construct();
         
        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('status_grid_table');
    }
     
    protected function _getCollectionClass()
    {
        // This is the model we are using for the grid
        return 'osc/status_collection';
    }
     
    protected function _prepareCollection()
    {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->setOrder('ID','DESC');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {
        // Add the columns that should appear in the grid
        $this->addColumn('ID',
            array(
                'header'=> $this->__('ID'),
                'width' => '50px',
                'index' => 'ID'
            )
        );


        $this->addColumn('browser',
            array(
                'header'=> $this->__('Browser Data'),
                'width' => '50px',
                'index' => 'browser',
                'renderer' => 'Mage_Osc_Block_Renderers_Browser'
            )
        );


        $this->addColumn('quote_id',
            array(
                'header'=> $this->__('ID do Carrinho'),
                'width' => '50px',
                'index' => 'quote_id'
            )
        );


        $this->addColumn('order_id',
            array(
                'header'=> $this->__('ID do Pedido'),
                'width' => '50px',
                'index' => 'order_id',
                'renderer' => 'Mage_Osc_Block_Renderers_Order'
            )
        );


        $this->addColumn('customer_id',
            array(
                'header'=> $this->__('Cliente'),
                'width' => '50px',
                'index' => 'customer_id',
                'renderer' => 'Mage_Osc_Block_Renderers_Customer'
            )
        );


        $this->addColumn('clickedfo',
            array(
                'header'=> $this->__('Quantidade de Cliques'),
                'width' => '50px',
                'index' => 'clickedfo'
            )
        );


        $this->addColumn('payment_method',
            array(
                'header'=> $this->__('Método de Pagamento'),
                'width' => '50px',
                'index' => 'payment_method'
            )
        );


        return parent::_prepareColumns();
    }
     

}