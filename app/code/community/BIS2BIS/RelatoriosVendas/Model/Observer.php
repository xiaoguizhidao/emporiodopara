<?php
class BIS2BIS_RelatoriosVendas_Model_Observer
{
    public function addActions($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction) {
            if ($block->getParentBlock() instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {
                $block->addItem('relatorio_etiquetas', array(
                        'label' => utf8_encode('Relatório para Etiquetas'),
                        'url' => $block->getUrl('relatoriosvendas/order/geraretiquetas'),
                    )
                );
            }
        }
    }
}