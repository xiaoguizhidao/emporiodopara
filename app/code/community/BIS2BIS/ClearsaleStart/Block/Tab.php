<?php
class BIS2BIS_ClearsaleStart_Block_Tab
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('clearsalestart/tab.phtml');
    }

    public function getTabLabel() {
        return $this->__('ClearsaleStart');
    }

    public function getTabTitle() {
        return $this->__('Gestão de Risco - ClearsaleStart');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getOrder(){
        return Mage::registry('current_order');
    }

}