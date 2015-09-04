<?php  

class BIS2BIS_Cores_Block_Adminhtml_Lista  extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct(){
        $this->_blockGroup = 'cores';
        $this->_controller = 'Adminhtml_Lista';
        $this->_headerText = Mage::helper('cores')->__('Lista de Cores');
        parent::__construct();

        $this->_addButton('adicionar_cor', array(
            'label'     => Mage::helper('cores')->__('Adicionar Cor'),
            'onclick' => "setLocation('{$this->getUrl('*/*/cadastrar')}')",
            'class'     => 'download'
        ), 0, 100, 'header', 'header');

        $this->_addButton('sincronizaar_atributo', array(
            'label'     => Mage::helper('cores')->__('Sincronizar com atributo (Novas instalações)'),
            'onclick' => "setLocation('{$this->getUrl('*/*/sincronizar')}')"
        ), 0, 100, 'header', 'header');


        $this->_removeButton('add');
    }

}

?>