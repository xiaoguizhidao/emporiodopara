<?php
/**
############################################################################################
############################################################################################
########  BIS2BIS - COMÉRCIO ELETRÔNICO                                                    #
########  Módulo : Banner                                                                  #
########  Desenvolvedor : Guilherme Costa                                                  #
########  Email : guilherme.costa@bis2bis.com.br                                           #
########  Descrição : Container do Form do módulo de Banner                                #
############################################################################################
############################################################################################
*/

class BIS2BIS_Banner_Block_Configformcontainer extends Mage_Adminhtml_Block_Widget_Form_Container {

    protected function _prepareLayout() {
        $this->setChild('form', $this->getLayout()->createBlock('banner/Configform'));
        return parent::_prepareLayout();
    }

    public function __construct() {
        $this->_objectId = 'id';
        $this->_blockGroup = 'banner';
        $this->_controller = 'admin';
        parent::__construct();
        $this->_updateButton('save', 'label', 'Salvar Configurações');
    }

    public function getHeaderText() {
        return 'Configuração de Banner';
    }

    public function getSaveUrl() {
        return $this->getUrl('*/' . $this->_controller . '/saveconfig', array('_current' => true, 'back' => null));
    }

}

?>
