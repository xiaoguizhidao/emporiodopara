<?php
class BIS2BIS_RelatoriosVendas_OrderController extends Mage_Adminhtml_Controller_Action{
	
	public function geraretiquetasAction(){
        $orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
        if (!empty($orderIds)) {
            $flag = true;
        	$pdf = Mage::getModel('relatoriosvendas/relatorios_pdf_etiquetas')->getPdf($orderIds);
        }
        if ($flag) {
            return $this->_prepareDownloadResponse(
                'etiquetas'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(),
                'application/pdf'
        	);
        } else {
            $this->_getSession()->addError($this->__('There are no printable documents related to selected orders.'));
            $this->_redirect('*/*/');
        }
        $this->_redirect('*/*/');
    }
    
}