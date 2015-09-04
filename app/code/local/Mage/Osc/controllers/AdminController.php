<?php

/*
 * Chamada do One Step Checkout
 */
/*
 *
 * @author Guilherme
 */

class Mage_Osc_AdminController extends Mage_Adminhtml_Controller_Action {
    
   
    /* Action de Configuração */
    public function configAction(){
        $myFile = "http://www.bis2bis.com.br/downloads/osc_version.txt";
        $fh = fopen($myFile, 'r');
        $theData = fread($fh, 200);
        fclose($fh);
        $posicao_um   =  strpos($theData, '[[[[');
        $versao = substr($theData, $posicao_um);
        $versao = str_replace('[[[[', '', $versao);
        $versao = str_replace(']]]]', '', $versao);
        $versao = str_replace(' ', '', $versao);
        $atualizado = version_compare($versao, Mage::getModel('osc/osc')->getOscVersion());
        if($atualizado == 1)
            Mage::getSingleton('adminhtml/session')->addError('Essa versão de checkout possui uma atualização disponível!');
        elseif ($atualizacao == 0 ) 
            Mage::getSingleton('adminhtml/session')->addSuccess('Não há atualizações para esse checkout!');
     	$osc = Mage::getModel('osc/osc')->load(1);
        Mage::register('checkout', $osc->getCheckout());
		Mage::register('https', $osc->getHttps());
		Mage::register('oschttpsurl', $osc->getOschttpsurl());
		Mage::register('oscloginhttpsurl', $osc->getOscloginhttpsurl());
        Mage::register('term', $osc->getTerm());
        Mage::register('text_term', $osc->getTextTerm());
        Mage::register('coupon', $osc->getCoupon());
        $this->_initAction()->_addContent($this->getLayout()->createBlock('osc/CheckoutAdmin'));
        $this->renderLayout();
    }

    
    /*Action de Log */
    public function logAction(){
        $this->_initAction()->_addContent($this->getLayout()->createBlock('osc/Log'));
        $this->renderLayout();
    }
        
    /*Action de Status */
    public function statusAction(){
        $this->_initAction()->_addContent($this->getLayout()->createBlock('osc/Status'));
        $this->renderLayout();
    }
        
    public function _initAction() {
        $this->loadLayout()->_addBreadcrumb(Mage::helper('osc')->__('One Step Checkout'), Mage::helper('osc')->__('One Step Checkout'));
        return $this;
    }
    
    /*Action que efetuará a gravação dos dados digitados no formulário */
    public function saveAction(){
        $params = $this->getRequest()->getParams();
        /* ### - Dados atribuidos do formulário - ### */
        $checkout= $params['checkout'];
        $https = $params['https'];
        $oschttpsurl = $params['oschttpsurl'];
		$oscloginhttpsurl = $params['oscloginhttpsurl'];
        $term = $params['term'];
        $text_term = $params['text_term'];
        $coupon = $params['coupon'];
        /* ### - Dados atribuidos do formulário - ### */
        /* ### - Conexão com o Banco - ### */
     	$osc = Mage::getModel('osc/osc')->load(1);
     	$osc->setCheckout($checkout);
		$osc->setHttps($https);
		$osc->setOschttpsurl($oschttpsurl);
		$osc->setOscloginhttpsurl($oscloginhttpsurl);
        $osc->setTerm($term);
        $osc->setTextTerm($text_term);
        $osc->setCoupon($coupon);
		$osc->save();
     
        Mage::getSingleton('adminhtml/session')->addSuccess('Operação Realizada com Sucesso !');
        $this->_redirect('*/*/config');
    }
    
    
}

?>
