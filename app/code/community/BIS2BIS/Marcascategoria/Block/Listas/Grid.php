<?php

/**
 * Description of Grid
 *
 * @author Intel
 */

	class BIS2BIS_Marcascategoria_Block_Listas_Grid extends Mage_Adminhtml_Block_Widget_Grid
	{

	    public function __construct(){
	        parent::__construct();
	    	
	        $this->setId('listasgrid');

	    }


	    protected function _prepareCollection(){
	        $collection = Mage::getModel('marcascategoria/marcas')->getCollection();
	        $this->setCollection($collection);
	        parent::_prepareCollection();
		    return $this;
	    }

	    protected function _prepareColumns(){
	    	        
	        $this->addColumn('id',
	            array(
	                'header'=> $this->__('ID'),
	                'align' =>'left',
	                'width' => '50px',
	                'index' => 'id'
	            )
	        );

	        $this->addColumn('active',
	            array(
	                'header'=> $this->__('Habilitado'),
	                'align' =>'left',
	                'width' => '50px',
	                'index' => 'active',
	                'renderer' => 'BIS2BIS_Marcascategoria_Block_Renderers_Active'
	            )
	        );

	        $this->addColumn('name',
	            array(
	                'header'=> $this->__('Name'),
	                'align' =>'left',
	                'width' => '50px',
	                'index' => 'name',
	                'value' => Mage::registry('name')
	            )
	        );

	        $this->addColumn('category',
	            array(
	                'header'=> $this->__('Categoria'),
	                'align' =>'left',
	                'width' => '50px',
	                'index' => 'category',
	                'renderer' => 'BIS2BIS_Marcascategoria_Model_Renderers_Category'
	            )
	        );

	        $this->addColumn('marcas',
	            array(
	                'header'=> $this->__('Marcas'),
	                'align' =>'left',
	                'width' => '50px',
	                'index' => 'marcas',
	                'renderer' => 'BIS2BIS_Marcascategoria_Model_Renderers_Marca'
	            )
	        );
        	
  	    	return parent::_prepareColumns();
	    }

	    public function getRowUrl($row){
	        return $this->getUrl('*/*/editarlista', array('id' => $row->getId()));
	    }



	}