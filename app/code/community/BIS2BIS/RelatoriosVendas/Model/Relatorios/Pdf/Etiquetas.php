<?php
class BIS2BIS_RelatoriosVendas_Model_Relatorios_Pdf_Etiquetas extends Mage_Sales_Model_Order_Pdf_Abstract
{
	function insertOrder(&$page, $obj, $putOrderId = true)
	{
		if ($obj instanceof Mage_Sales_Model_Order) {
			$shipment = null;
			$order = $obj;
		} elseif ($obj instanceof Mage_Sales_Model_Order_Shipment) {
			$shipment = $obj;
			$order = $shipment->getOrder();
		}
		$page->setFillColor(new Zend_Pdf_Color_GrayScale(0.0));
		$this->_setFontRegular($page, 7);
		$shippingAddress = $this->_formatAddress($order->getShippingAddress()->format('pdf'));
		$tamanho = $this->y - (count($shippingAddress) * 10);
		$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
		$page->setLineColor(new Zend_Pdf_Color_GrayScale(0.0));
		$page->setLineWidth(0.5);
		$page->drawRectangle(150, $this->y, 420, $tamanho-45); // pedido
		$page->drawLine(150, $this->y-11, 420, $this->y-11);

		$page->setFillColor(new Zend_Pdf_Color_GrayScale(0.0));
		$this->_setFontRegular($page, 8);
		$page->drawText('PEDIDO #'. $order->getRealOrderId(), 250, $this->y-10, 'UTF-8');
		$this->y -= 30;
		$page->setFillColor(new Zend_Pdf_Color_GrayScale(0.0));
		foreach ($shippingAddress as $value){
			if ($value!=='') {
				if(strip_tags(ltrim($value))== 'Brasil'){
					$estado = $this->getEstado($order->getShippingAddress()->getRegionId());
					$page->drawText($estado . ' - ' . strip_tags(ltrim($value)) , 175, $this->y, 'UTF-8');
				}	else
					$page->drawText(strip_tags(ltrim($value)),175, $this->y, 'UTF-8');
				$this->y -=10;
			}
		}
		
		$this->y = $tamanho-70;
	}

	public function getPdf($orderIds = array())
	{
		$this->_beforeGetPdf();
		$this->_initRenderer('invoice');
		$this->y = 800;
		$pdf = new Zend_Pdf();
		$this->_setPdf($pdf);
		$style = new Zend_Pdf_Style();
		$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
		$pdf->pages[] = $page;
		foreach ($orderIds as $orderId) {
			if($this->y < 100){
				$page = $this->newPage();
			}			
			$order = Mage::getModel('sales/order')->load($orderId);
			
			$this->insertOrder($page, $order, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId()));
			}
		return $pdf;
	}
	
	public function getEstado($id){
	 	$resource = new Mage_Core_Model_Resource();
        $read = $resource->getConnection("core_read");
        $select = $read->select()->from('directory_country_region')->where('region_id = ?', $id);
        $r = $read->fetchAll($select);
        if(array_key_exists(0, $r)){
        	$estado = $r[0];
        	if(array_key_exists('default_name', $estado))
        		return  $estado['default_name'];
        }
	}

}
