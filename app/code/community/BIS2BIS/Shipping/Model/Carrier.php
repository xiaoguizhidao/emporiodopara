<?php
    class BIS2BIS_Shipping_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {
        
        protected $_code = 'bis2ship';
     
        public function collectRates(Mage_Shipping_Model_Rate_Request $request){
            
            $result = Mage::getModel('shipping/rate_result');
            $show = true;

            $collection_shipping = Mage::getModel('bis2ship/shippingtables')->getCollection()->addFieldToFilter('active', 1);
            $cep =   $request->getDestPostcode();
            $cep =  str_replace('-', '', $cep);

			$this->_updateFreeMethodQuote($request);
			
            if($cep){

            foreach($collection_shipping as $shipping){


                $recipe_string = '';
                if($show){ // This if condition is just to demonstrate how to return success and error in shipping methods

                    $read = Mage::getSingleton('core/resource')->getConnection('core_read');


                    if($shipping->getCubado() == 1){
                        $full_weight = $this->getPesoCubado($request);
                    }else{
                        $full_weight = $this->getFullWeight($request);
                    }

                    $select = 'SELECT * FROM ' . $shipping->getTableName() . ' WHERE (cep_inicial <= ' . $cep . ' AND cep_final >= ' . $cep . ') AND (peso_inicial <= ' . $full_weight . ' AND peso_final >= ' . $full_weight . ') ;';

                    $result_query = $read->fetchAll($select);


                    $r = $result_query[0];


                    if( $r['peso_final'] == 999999.0000){
                        if($shipping->getExcedente() == 1){
                            $excedent_value = ($full_weight-$r['peso_inicial'])*$r['valor_excedente'];
                        }else{
                            $excedent_value = $full_weight*$r['valor_excedente']; 
                        }
                    }else{
                        $excedent_value = 0;
                    }


                    $all_fields = unserialize($shipping->getFields());

                    $fields_values = array();

                    foreach($all_fields as $field){
                        $field_text = explode('#', $field);
                        $field_text = $field_text[0];
                        $fields_values[$field_text] = $r[$field_text];
                    }

                    if(count($r) > 0 ){
                        $method = Mage::getModel('shipping/rate_result_method');
                        $method->setCarrier($this->_code);
                        $method->setMethod($this->_code.'_'.$shipping->getTableName());
                        $method->setCarrierTitle('Transportadoras'); 
                        $method->setMethodTitle(ucfirst($shipping->getTitulo()) . " - <font color='green'> " . $r['prazo_entrega'] . "</font>" );
                        $recipes = Mage::getModel('bis2ship/recipe')->getCollection()->addFieldToFilter('table_id', $shipping->getId())->getFirstItem();
                        $recipes_array = unserialize($recipes->getRecipe());

                        foreach($recipes_array as $arr_rec){
                            if($arr_rec === 'nf_estado'){
                                $package_value = $request->getPackageValue();
                                $percent = $result_query[0]['nf_estado'];
                                $percent = $percent/100;
                                $new_value = $package_value*$percent;
                                $recipe_string .= $new_value . ' ' ;
                            }else if(strpos($arr_rec, '% NF' ) !== false){
                                $package_value = $request->getPackageValue();
                                $percent = str_replace('% NF', '', $arr_rec);
                                $percent = $percent/100;
                                $new_value = $package_value*$percent;
                                $recipe_string .= $new_value . ' ' ; 
                            }else{
                                $recipe_string .= $arr_rec . ' ' ; 
                            }
                        }

                        foreach(array_keys($fields_values) as $field_value){
                           $recipe_string = str_replace($field_value, $fields_values[$field_value], $recipe_string);
                        }

                        $calculated_result = Mage::helper('bis2ship')->eval_me($recipe_string);
						
						$shipping_price = $calculated_result + $excedent_value;

                        if(($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes()) && $this->getAllowFreeMethod($shipping->getTitulo())){
                            $shipping_price = '0.00';
                        }

                        $method->setPrice($shipping_price);
                        $method->setCost($shipping_price);
                        $result->append($method);
                        
                    }else{ // tenta calculo excedente 

                    }
                    
                }else{
                    $error = Mage::getModel('shipping/rate_result_error');
                    $error->setCarrier($this->_code);
                    $error->setCarrierTitle($this->getConfigData('name'));
                    $error->setErrorMessage($this->getConfigData('specificerrmsg'));
                    $result->append($error);
                }
            }
            }
            return $result;
        }
        public function getAllowedMethods()
        {
            return array('excellence'=>$this->getConfigData('name'));
        }

        public function getFullWeight($request){
            return $request->getPackageWeight();
        }


        public function getPesoCubado($request){
            $todos_produtos = $request->getAllItems();
            foreach($todos_produtos as $item){
                $id = $item->getProductId();

                $produto = Mage::getModel('catalog/product')->load($id);
                $peso =  ($produto->getVolumeAltura()/100) * ($produto->getVolumeComprimento()/100) * ($produto->getVolumeLargura()/100) * 300;

                if($peso > $item->getWeight()){
                   $weight += $peso*$item->getQty();
                }else{
                   $weight += $item->getWeight()*$item->getQty();
                }
            }
            return $weight;
        }
		
		private function getAllowFreeMethod($method_name)
        {
            $allow_free_method = $this->getConfigData('allow_free_method');
            if($allow_free_method === true){
                return true;
            }
            $free_methods = explode(',', $this->getConfigData('free_methods'));
            if(sizeof($free_methods) > 0){
                foreach($free_methods as $key => $free_method){
                    if($free_method == $method_name){
                        return true;
                    }
                }
            }
            return false;
        }
    }