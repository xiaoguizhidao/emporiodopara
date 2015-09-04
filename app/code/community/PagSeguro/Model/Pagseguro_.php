<?php

/**
 * PagSeguro Module Adapter
 */
class PagSeguro_Model_PagSeguro extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->db = new Varien_Data_Collection_Db();
        $this->db->__construct(Mage::getSingleton('core/resource')->getConnection('core_write'));
        $this->_init('pagseguro/pagseguro');
        $this->objPDO = $this->db->getSelect()->getAdapter();
    }

//    public function isExist($idPedido) {
//        try {
//
//            $bd = new Varien_Data_Collection_Db();
//
//            $bd->__construct(Mage::getSingleTon('core/resource')->getConnection('pagseguro_read'));
//
//
//            $r = $bd->getSelect()
//                    ->getAdapter()
//                    ->fetchAll("select * from pagseguro where Referencia = " . $idPedido);
//
//            if (count($r) > 0) {
//                return true;
//            } else {
//                return false;
//            }
//        } catch (Exception $e) {
//            echo 'ERROR =>' . $e->getMessage();
//            die;
//        }
//    }
//
//    public function getOrderId($idPedido) {
//        try {
//
//            $bd = new Varien_Data_Collection_Db();
//
//            $bd->__construct(Mage::getSingleTon('core/resource')->getConnection('pagseguro_read'));
//
//
//            $r = $bd->getSelect()
//                    ->getAdapter()
//                    ->fetchAll("select * from sales_order where increment_id = " . $idPedido);
//
//            if (count($r) > 0) {
//                return $r[0]['entity_id'];
//            } else {
//                return 0;
//            }
//        } catch (Exception $e) {
//            echo 'ERROR =>' . $e->getMessage();
//            die;
//        }
//    }

}

?>
