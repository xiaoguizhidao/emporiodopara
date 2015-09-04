<?php

class BIS2BIS_Gmerchant_AdminController extends Mage_Adminhtml_Controller_Action {
  
	//efetua análise do cliente
    public function attrAction(){
        Mage::getSingleton('core/session')->addNotice('Caso a loja possua algum atributo em específico, configurar nessa tela inserindo o Código do Atributo (attribute_code) para seu respectivo campo do Google Shopping');
        $this->_initAction()->_addContent($this->getLayout()->createBlock('gmerchant/Formcontainer'));
        $this->renderLayout();
    }

    public function categoriasAction(){
        $this->_initAction()->_setActiveMenu('gmerchant')->_addContent($this->getLayout()->createBlock('gmerchant/categorias'));
        $this->renderLayout();
    }

    // salvar xml
    public function saveAction(){
        $params = $this->getRequest()->getParams();

        $gtin =  $params['gtin'];
        $mpn =  $params['mpn'];
        $brand = $params['brand'];
        $tipoprodutos = $params['tipoprodutos'];
        $gmerchant = Mage::getModel('gmerchant/gmerchant')->load(1);
        $gmerchant->setGtin($gtin);
        $gmerchant->setMpn($mpn);
        $gmerchant->setBrand($brand);
        $gmerchant->setTipoprodutos($tipoprodutos);
        $gmerchant->setTipopreco($params['tipopreco']);
        $gmerchant->setDesconto($params['desconto']);
        $gmerchant->setParcelamento($params['parcelamento']);
        $gmerchant->setQtyParcelas($params['qty_parcelas']);
        $gmerchant->setValorMin($params['valor_min']);

        $gmerchant->save();
        Mage::getSingleton('core/session')->addSuccess('Atributos salvo com sucesso');
        $this->_redirect('*/*/attr');
    }

    //efetua análise do cliente
    public function updateAction(){
        Mage::getSingleton('core/session')->addNotice('Configure os atributos personalizáveis do Google Merchant');
        $this->_initAction()->_addContent($this->getLayout()->createBlock('gmerchant/Update'));
        $this->renderLayout();
    }

    // realiza a atualização parcial
    public function doTurnXMLAction(){
        $params = $this->getRequest()->getParams();
        $turn = ($params['turn']);
        $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')->addAttributeToFilter('googlemerchant', 1)->setPageSize(200)->setCurPage($turn);


       // die(Mage::getModel('gmerchant/xml')->getGoogleMerchant()->getTipoprodutos());


        //die($collection->getSelect());
        
        foreach($collection as $product){


            switch (Mage::getModel('gmerchant/xml')->getGoogleMerchant()->getTipoprodutos()) {
                ###################### configuraveis ####################
                case 'configuraveis':

                        if($product->getTypeId() == 'simple' && count(Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId())) <= 0) {
                              if ($product->getVisibility() == 4) {
                                $xml_string .= Mage::getModel('gmerchant/xml')->createItemXMLSimple($product);
                              }
                        }elseif($product->getTypeId() == 'configurable'){
                            $xml_string .= Mage::getModel('gmerchant/xml')->createItemXMLConfigurable($product);
                        }


                    break;
                ###################### configuraveis ####################

                ###################### SIMPLES ####################
                case 'simples':
                        if($product->getTypeId() == 'simple') {
                            $xml_string .= Mage::getModel('gmerchant/xml')->createItemXMLSimple($product);
                        }
                    break;
                ###################### SIMPLES ####################


                ###################### TODOS OS PRODUTOS ####################
                case 'todos':

                        if($product->getTypeId() == 'simple') {
                            $xml_string .= Mage::getModel('gmerchant/xml')->createItemXMLSimple($product);
                        }elseif($product->getTypeId() == 'configurable'){
                            $xml_string .= Mage::getModel('gmerchant/xml')->createItemXMLConfigurable($product);
                        }elseif($product->getTypeId() == 'bundle'){
                            $xml_string .= Mage::getModel('gmerchant/xml')->createItemXMLBundle($product);
                        }



                    break;
                ###################### TODOS OS PRODUTOS ####################
            }

        }
        
        $myDir = Mage::getBaseDir('media') . DS . 'googlemerchant';
        
        if (!is_dir($myDir)) { mkdir($myDir); chmod($myDir, 0777); }

        $myFile = Mage::getBaseDir('media') . DS . 'googlemerchant' . DS . 'item'.$turn.'.xml';

        $handle = fopen($myFile, 'w'); // abre ou cria
        fwrite($handle, ''); // limpa  
        fwrite($handle, $xml_string);

        fclose($handle); // fecha
        echo 'ok';
    }


    public function doGeneralXMLAction(){
         $xml_string = '';
         $xml_googlemerchant = Mage::getModel('gmerchant/xml');

         $xml_string = $xml_googlemerchant->createHeaderXML();

         if ($handle = opendir(Mage::getBaseDir('media') . DS . 'googlemerchant')) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {
                if(strpos($entry, 'item') !== false){
                    $myFile = Mage::getBaseDir('media') . DS . 'googlemerchant' . DS . $entry;
                    $new_handle = fopen($myFile, "c+");
                    $xml_string .= fread($new_handle, filesize($myFile));
                    fclose($new_handle);
                    unlink($myFile);
                }
            }
            closedir($handle);
        }

        $xml_string .= $xml_googlemerchant->createFooterXML();
        $myFile = Mage::getBaseDir('media') . DS . 'googlemerchant' . DS . 'googlemerchant.xml';
        $handle = fopen($myFile, 'w'); // abre ou cria
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml_string);
        $xml_string = $dom->saveXML();
        fwrite($handle, ''); // limpa  
        fwrite($handle, $xml_string);
        fclose($handle); // fecha
        // aqui é finalizado o rodapé do XML;
    }

    public function _initAction() {
        $this->loadLayout()->_addBreadcrumb(Mage::helper('gmerchant')->__('Google Shopping API'), Mage::helper('gmerchant')->__('Google Shopping API'));
        return $this;
    }

}
 

?>