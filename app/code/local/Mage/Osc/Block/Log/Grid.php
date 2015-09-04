<?php
class  Mage_Osc_Block_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        return 'osc/log_collection';
    }
     
    protected function _prepareCollection()
    {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->setOrder('entity_id','DESC');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {

        // Add the columns that should appear in the grid
        $this->addColumn('entity_id',
            array(
                'header'=> $this->__('ID do Erro'),
                'width' => '50px',
                'index' => 'entity_id'
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

        $this->addColumn('customer_id',
            array(
                'header'=> $this->__('Cliente'),
                'width' => '50px',
                'index' => 'customer_id',
                'renderer' => 'Mage_Osc_Block_Renderers_Customer'
            )
        );

        $this->addColumn('payment_method',
            array(
                'header'=> $this->__('Método de Pagamento'),
                'width' => '50px',
                'index' => 'payment_method'
            )
        );


        $this->addColumn('message',
            array(
                'header'=> $this->__('Mensagem'),
                'width' => '50px',
                'index' => 'message'
            )
        );


        return parent::_prepareColumns();
    }
     

}