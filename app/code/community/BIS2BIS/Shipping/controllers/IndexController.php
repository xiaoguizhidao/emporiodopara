<?php
/*
▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
▬▬▬▬▬▬▬▬▬▬▬▬▬▬ Módulo : Frete                                     ▬▬▬▬▬▬▬▬▬▬▬▬▬▬
▬▬▬▬▬▬▬▬▬▬▬▬▬▬ Desenvolvedor : Guilherme costa                    ▬▬▬▬▬▬▬▬▬▬▬▬▬▬
▬▬▬▬▬▬▬▬▬▬▬▬▬▬ E-mail : guilherme.costa@bis2bis.com.br            ▬▬▬▬▬▬▬▬▬▬▬▬▬▬
▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
*/

 class BIS2BIS_Shipping_IndexController extends Mage_Core_Controller_Front_Action {
 
  
    public function validateTableAction(){
        $params = $this->getRequest()->getParams();
        $table = $params['table'];
        $size = Mage::getModel('bis2ship/shippingtables')->getCollection()->addFieldToFilter(
          'table_name', $table
        )->getSize();
        if($size > 0)
          echo '1';
        else
          echo '0';
    }


    // Constrói uma tabela editável
    public function buildeditabletableAction(){


        $params = $this->getRequest()->getParams();
           
        $id = $params['id'];
      
        $table_model = Mage::getModel('bis2ship/shippingtables')->load($id);
          
        $fields = unserialize($table_model->getData('fields'));

        $new_fields_array = array();

        $new_fields_array[] = array(
            'id' => 'cep_inicial',
            'name' => 'CEP Inicial',
            'field' => 'cep_inicial',
            'width' => 120,
            'cssClass' => 'cell-title',
            'editor' => 'Slick.Editors.Integer',
            'validator' => 'requiredFieldValidator'
        );

        $new_fields_array[] = array(
            'id' => 'cep_final',
            'name' => 'CEP Final',
            'field' => 'cep_final',
            'width' => 120,
            'cssClass' => 'cell-title',
            'editor' => 'Slick.Editors.Integer',
            'validator' => 'requiredFieldValidator'
        );

         $new_fields_array[] = array(
            'id' => 'peso_inicial',
            'name' => 'Peso Inicial',
            'field' => 'peso_inicial',
            'width' => 120,
            'cssClass' => 'cell-title',
            'editor' => 'Slick.Editors.Text',
            'validator' => 'requiredFieldValidator',
            'formatter' => 'Slick.Formatters.Decimal'
        );


        $new_fields_array[] = array(
            'id' => 'peso_final',
            'name' => 'Peso Final',
            'field' => 'peso_final',
            'width' => 120,
            'cssClass' => 'cell-title',
            'editor' => 'Slick.Editors.Text',
            'validator' => 'requiredFieldValidator',
            'formatter' => 'Slick.Formatters.Decimal'
        );

        // novo campo 
        $new_fields_array[] = array(
            'id' => 'valor_excedente',
            'name' => 'Valor Excedente',
            'field' => 'valor_excedente',
            'width' => 120,
            'cssClass' => 'cell-title',
            'editor' => 'Slick.Editors.Text',
            'validator' => 'requiredFieldValidator',
            'formatter' => 'Slick.Formatters.Decimal'
        );

        // novo campo 
        $new_fields_array[] = array(
            'id' => 'prazo_entrega',
            'name' => 'Prazo de Entrega',
            'field' => 'prazo_entrega',
            'width' => 120,
            'cssClass' => 'cell-title',
            'editor' => 'Slick.Editors.Text'
        );

        if ($table_model->getNfEstado()) {
            // novo campo 
            $new_fields_array[] = array(
                'id' => 'nf_estado',
                'name' => 'NF Estado %',
                'field' => 'nf_estado',
                'width' => 120,
                'cssClass' => 'cell-title',
                'editor' => 'Slick.Editors.Text',
                'validator' => 'requiredFieldValidator',
                'formatter' => 'Slick.Formatters.Decimal'
            );
        }

        foreach($fields as $field){
            $exploded_field = explode('#', $field);
  
            $type = $exploded_field[1];
            $field_name = $exploded_field[0];
            if($type == 1){
                $new_fields_array[] = array(
                    'id' => $field_name,
                    'name' =>  ucfirst($field_name),
                    'field' => str_replace(' ', '_', $field_name),
                    'width' => 120,
                    'cssClass' => 'cell-title',
                    'editor' => 'Slick.Editors.Integer',
                    'validator' => 'requiredFieldValidator'
                );
            }else{
                if($type == 2){

                    $new_fields_array[] = array(
                        'id' => $field_name,
                        'name' =>  ucfirst($field_name),
                        'field' => str_replace(' ', '_', $field_name),
                        'width' => 120,
                        'cssClass' => 'cell-title',
                        'editor' => 'Slick.Editors.Text',
                        'validator' => 'requiredFieldValidator',
                        'formatter' => 'Slick.Formatters.Decimal'
                    );
                }else{
                    $new_fields_array[] = array(
                        'id' => $field_name,
                        'name' =>  ucfirst($field_name),
                        'field' => str_replace(' ', '_', $field_name),
                        'width' => 120,
                        'cssClass' => 'cell-title',
                        'editor' => 'Slick.Editors.Text',
                        'validator' => 'requiredFieldValidator'
                    );
                }
                
            }

        }

        echo json_encode($new_fields_array);


    }


    // Retorna dados da tabela
    public function gettabledataAction(){

        $params = $this->getRequest()->getParams();
        $table_id = $params['id'];   
        $table_model = Mage::getModel('bis2ship/shippingtables')->load($table_id);

        $table_name = $table_model->getTableName(); 
        $fields = unserialize($table_model->getData('fields'));
        $my_query = ' SELECT   cep_inicial, cep_final, peso_inicial, peso_final, valor_excedente, prazo_entrega, nf_estado, ';
        foreach($fields as $field){
            $c++; 
            $field = explode('#', $field);
            $field = $field[0];
            $field = str_replace(' ', '_', $field);
            $my_query .= $field;
            if($c < count($fields)){
                $my_query .= ', ';
            }
        }
        $my_query .= ' FROM ' . $table_name . ';'; 

        $read = Mage::getSingleton('core/resource')->getConnection('core_read');

        $results = $read->fetchAll($my_query);

        echo json_encode($results);
    }


    public function getfieldsAction(){
        $params = $this->getRequest()->getParams();
        $table = $params['table'];
        $datatable = Mage::getModel('bis2ship/shippingtables')->getCollection()->addFieldToFilter(
        	'table_name', $table
		)->getFirstItem();

        $fields = (unserialize($datatable->getData('fields')));
      	$_return = array();
      	foreach($fields as $field){
      		$_field = explode('#', $field);
      		$_return[] = $_field[0];
      	}

        if ($datatable->getData('nf_estado') == 1) {
            $_return[] = 'nf_estado';   
        }



      	echo json_encode($_return);
    }

    // retorna todas os valores das fórmulas.
    public function getrecipesAction(){
        $params = $this->getRequest()->getParams();
        $table_id = $params['table_id'];
        $recipes = Mage::getModel('bis2ship/recipe')->getCollection()->addFieldToFilter('table_id', $table_id)->getFirstItem();
        $recipe_unserialized = json_encode(unserialize($recipes->getRecipe()));
        echo $recipe_unserialized;
    } 

 }
 
?>
