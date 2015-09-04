<?php
/**
############################################################################################
############################################################################################
########  BIS2BIS - COMÉRCIO ELETRÔNICO                                                    #
########  Módulo : Banner                                                                  #
########  Desenvolvedor : Guilherme Costa                                                  #
########  Email : guilherme.costa@bis2bis.com.br                                           #
########  Descrição : Controller do Administrador do Módulo de Banner                      #
############################################################################################
############################################################################################
*/

class BIS2BIS_Banner_AdminController extends Mage_Adminhtml_Controller_Action {


    // Controller para chamar o grid de banners
    public function gridAction(){
        $this->_initAction()->_addContent($this->getLayout()->createBlock('banner/Bannergrid'));
        $this->renderLayout();
    }

    // Controller para chamar o form de cadastro
    public function registerAction(){
        Mage::register('type_crud', 'register');
        $this->_initAction()->_addContent($this->getLayout()->createBlock('banner/Registerformcontainer'));
        $this->renderLayout();
    }

    // Controller para chamar o form de cadastro
    public function configAction(){
        $bannerconfig = Mage::getModel('banner/bannerconfig')->load(1);

        Mage::register('active', $bannerconfig->getActive());
        Mage::register('url', $bannerconfig->getUrl());
        $this->_initAction()->_addContent($this->getLayout()->createBlock('banner/Configformcontainer'));
        $this->renderLayout();
    }

        // Controller para chamar o form de cadastro
    public function saveconfigAction(){
        $bannerconfig = Mage::getModel('banner/bannerconfig')->load(1);
        $params = $this->getRequest()->getParams();
        $bannerconfig->setActive($params['active']);

        $bannerconfig->setUrl($params['url']);
        $bannerconfig->save();
         Mage::getSingleton('adminhtml/session')->addSuccess('Operação Realizada com Sucesso !');
        $this->_redirect('*/*/config');
    }


        // Controller para chamar o grid de banners
    public function editAction(){
        $params = $this->getRequest()->getParams();
        $id = $params['id'];
        $banner = Mage::getModel('banner/banner')->load($id);
        Mage::register('type_crud', 'edit');
        Mage::register('id', $id);
        Mage::register('active', $banner->getData('active'));
        Mage::register('name', $banner->getData('name'));
        Mage::register('link', $banner->getData('link'));
        Mage::register('type', $banner->getData('type'));
        Mage::register('category', $banner->getData('category'));
        Mage::register('first_date', $banner->getData('first_date'));
        Mage::register('final_date', $banner->getData('final_date'));
        Mage::register('ordem', $banner->getData('ordem'));
        $this->_initAction()->_addContent($this->getLayout()->createBlock('banner/Registerformcontainer'));
        $this->renderLayout();
    }


    public function _initAction() {
        $this->loadLayout()->_addBreadcrumb(Mage::helper('banner')->__('Banner'), Mage::helper('banner')->__('banner'));
        return $this;
    }

    public function deleteAction(){
        $params = $this->getRequest()->getParams();
        $id = $params['id'];
        $banner = Mage::getModel('banner/banner')->load($id);
        $banner->deleteImage($banner->getData('image'));
        $banner->delete();
        Mage::getSingleton('adminhtml/session')->addSuccess('Operação Realizada com Sucesso !');
        $this->_redirect('*/*/grid');
    }


    public function saveAction(){
        $params = $this->getRequest()->getParams();

        $files = $_FILES;

        $_active = $params['active'];
        $_name = $params['name'];
        $_link = $params['link'];
        $_type = $params['type'];
        $_first_date = $params['first_date'];
        $_final_date = $params['final_date'];
        $_id = 0;

        if(array_key_exists('id', $params)){
            $_id = $params['id'];
            $banner = Mage::getModel('banner/banner')->load($_id);
            $old_image = $banner->getData('image');

        }else{
            $banner = Mage::getModel('banner/banner');
        }

        if(array_key_exists('category', $params)){
            $_category = $params['category'];
            $_category = $_category[0];
        }else{
            $_category = '';
        }

        $_order = $params['ordem'];

        $banner->setData('active', $_active);
        $banner->setData('name', $_name);
        $banner->setData('link', $_link);

        $banner->setData('type', $_type);
        if($_first_date != ''){
            $date = new Zend_Date($_first_date);
            $banner->setData('first_date', $date->get('YYYY-MM-dd'));
        }
        if($_final_date != ''){
            $date = new Zend_Date($_final_date);
            $banner->setData('final_date', $date->get('YYYY-MM-dd'));
        }

        $banner->setData('category', $_category);
        $banner->setData('ordem', $_order);

        // Gravação de imagem
        $path = Mage::getBaseDir('media') . DS . 'banner';

        if(!is_dir($path)){
            mkdir($path);
            chmod($path, 0777);
        }

        if( ( $_id != 0 &&  $files['image']['name'] != '' ) || $_id == 0 ){

            if($_id != 0)
            Mage::getModel('banner/banner')->deleteImage($old_image);

            if(is_file($path . DS . $files['image']['name'])){
                $files['image']['name'] = 'ex_'. $files['image']['name'];
            }

            if($files['image']['name']){
                $banner->setData('image', 'media'. '/' . 'banner' . '/' . $files['image']['name']);
            }else{
                $banner->setData('image', '');
            }
            try{
                $uploader = new Varien_File_Uploader('image');

                $allow_mime_type = array("image/jpeg", "image/pjpeg", "image/jpeg", "image/pjpeg", "image/png", "application/x-shockwave-flash", "image/gif");
                if (!$uploader->checkMimeType($allow_mime_type)){
                    Mage::throwException("Arquivo inválido.");
                }

                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save($path, $files['image']['name']);
                $data['datafile'] = $files['image']['name'];

                $banner->save();
                Mage::getSingleton('adminhtml/session')->addSuccess('Operação Realizada com Sucesso !');
                $this->_redirect('*/*/grid');

            } catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect("*/*/register");
            }
        } else {
            $banner->save();
            Mage::getSingleton('adminhtml/session')->addSuccess('Operação Realizada com Sucesso !');
            $this->_redirect('*/*/grid');
        }
    }
}
