<?php

    class BIS2BIS_Shipping_Model_Source_FreePostMethods
    {

        public function toOptionArray()
        {
            $shipping_tables = Mage::getModel('bis2ship/shippingtables')->getCollection();
            $i = 1;
            foreach($shipping_tables as $shipping){
                $post_methods[$i] = array(
                    'value' => $shipping->getTitulo(),
                    'label' => '[' . $shipping->getTableName() . '] - ' . $shipping->getTitulo()
                );
                $i++;
            }
            return $post_methods;
        }

    }

?>