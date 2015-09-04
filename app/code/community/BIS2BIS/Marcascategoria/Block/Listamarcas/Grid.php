<?php

/**
 * Description of Grid
 *
 * @author Intel
 */

	class BIS2BIS_Marcascategoria_Block_Listamarcas_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{

	    public function __construct(){
	        parent::__construct();
	        $this->setId('listamarcasgrid');
	    }

	    protected function _prepareCollection(){

	    	$attribute_id = Mage::getModel('marcascategoria/marcas')->getAttributeMarcaId();

			$collection = Mage::getResourceModel('eav/entity_attribute_option_collection')->addFilter('attribute_id', $attribute_id);

            $collection->getSelect()
             	->join(
	    	 		array('values' => 'eav_attribute_option_value'),
	    	 		'values.option_id = main_table.option_id and values.store_id = '.Mage::app()->getStore()->getStoreId() ,
	    	 		array('values.value as value')
	    	 	)
            ;
    	
	        $this->setCollection($collection);
	        //parent::_prepareCollection();
		    return $this;
	    }

	    protected function _prepareColumns(){

	        $this->addColumn('image',
	            array(
	                'header'=> $this->__('Imagem'),
	                'align' =>'center',
	                'width' => '50px',
	                'index' => 'image',
	                'renderer' => 'BIS2BIS_Marcascategoria_Block_Renderers_Image'
	            )
	        );

	      	$this->addColumn('sort_order',
	            array(
	                'header'=> $this->__('Position'),
	                'align' =>'left',
	                'width' => '50px',
	                'index' => 'sort_order'
	            )
	        );

	       	$this->addColumn('value',
	            array(
	                'header'=> $this->__('Name'),
	                'align' =>'left',
	                'width' => '50px',
	                'index' => 'value'
	            )
	        );

	       	$this->addColumn('thumb',
	            array(
	                'header'=> $this->__('link'),
	                'align' =>'left',
	                'width' => '50px',
	                'index' => 'thumb'
	            )
	        );

	        
        	
  	    	return parent::_prepareColumns();
	    }

	    public function getRowUrl($row){
	        return $this->getUrl('*/*/editarmarca', array('id' => $row->getId()));
	    }
	}