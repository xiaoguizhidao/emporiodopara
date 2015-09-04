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
class BIS2BIS_Cores_Block_Adminhtml_Lista_Grid  extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct(){
        parent::__construct();
        $this->setId('gridLista');
    }

    protected function _prepareCollection(){
        $collection = Mage::getModel('cores/cores')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
	     return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('thumbnail', array(
           'header' => '#',
           'sortable' => true,
           'index' => 'id',
           'renderer' => 'BIS2BIS_Cores_Block_Adminhtml_Renderers_Thumbnail'
        ));

        $this->addColumn('id', array(
           'header' => 'ID',
           'sortable' => true,
           'index' => 'id' 
        ));

        $this->addColumn('nome', array(
           'header' => 'Nome',
           'sortable' => true,
           'index' => 'nome' 
        ));
   
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row){
        return $this->getUrl('*/*/editar', array('id' => $row->getId()));
    }

}

?>
