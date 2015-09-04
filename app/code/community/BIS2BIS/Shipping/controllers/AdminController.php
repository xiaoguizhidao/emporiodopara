<?php

 class BIS2BIS_Shipping_AdminController extends Mage_Adminhtml_Controller_Action {

    // Executa a compilação de novas transportadoras
    public function compilationAction(){
        $tables = Mage::getModel('bis2ship/shippingtables')->getCollection()->addFieldToFilter('active', 1); // tabelas ativadas
        if($tables->getSize() > 0 ){
            // realiza a compilação
            $compilation = Mage::getModel('bis2ship/compilation');
            $response = $compilation->doExe(); // executa
            if($response){
                Mage::getSingleton('core/session')->addSuccess('Compilação realizada com sucesso');
                $this->_redirect('*/*/manageTables');
            }else{
                Mage::getSingleton('core/session')->addSuccess('Erro ao efetuar a compilação');
                $this->_redirect('*/*/manageTables');
            }
        }else{
            Mage::getSingleton('core/session')->addSuccess('Nenhuma tabela ativada!');
            $this->_redirect('*/*/manageTables');
        }
    }
 
    public function manageTablesAction(){
         $this->_initAction()->_addContent($this->getLayout()->createBlock('bis2ship/Adminhtml_Shipping'));
         $this->renderLayout();
    }

    public function deleteTableAction(){
        $params = $this->getRequest()->getParams();
        $table_id = $params['table_id'];
        $table = Mage::getModel('bis2ship/shippingtables')->load($table_id);
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $write->exec('DROP TABLE ' . $table->getTableName());
        $recipe = Mage::getModel('bis2ship/recipe')->getCollection()->addFieldToFilter('table_id', $table_id)->getFirstItem();
        $recipe->delete();
        $table->delete();
        Mage::getSingleton('core/session')->addSuccess('Tabela deletada com sucesso!');
        $this->_redirect('*/*/manageTables');
    }
    

    public function registerTableAction(){
         Mage::getSingleton('core/session')->addNotice('Preencher campos sem espaço / acento / caracteres especiais');
         $this->_initAction()->_addContent($this->getLayout()->createBlock('bis2ship/Registertable'));
         $this->renderLayout();
    }

    // Cria a tabela no banco de dados
    public function saveRecipeAction(){
       $params = $this->getRequest()->getParams();
       $table = $params['table'];
       $recipe_id = $params['recipe_id'];
       $compose_recipe = $params['compose_recipe'];
       if($recipe_id == 0){
            $managerecipe = Mage::getModel('bis2ship/recipe');
       }else{
            $managerecipe = Mage::getModel('bis2ship/recipe')->load($recipe_id);
       }

       $managerecipe->setData('table_id', $table);
       $managerecipe->setData('recipe', serialize($compose_recipe));
       $managerecipe->save();
       Mage::getSingleton('core/session')->addSuccess('Fórmula criada com sucesso');
       $this->_redirect('*/*/manageTables');
    }

    // Ativa a tabela de transportadora
    public function activeTableAction(){
        $params = $this->getRequest()->getParams();
        $id = $params['id'];
        $table_model = Mage::getModel('bis2ship/shippingtables')->load($id);
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $my_query = 'SELECT * FROM ' . $table_model->getTableName() . ';';
        $results = $read->fetchAll($my_query);
        $count_results = count($results);
        $my_query = 'SELECT * FROM shipping_recipes WHERE table_id = ' . $id . ';';
        $results = $read->fetchAll($my_query);
        $count_recipes = count($results);
        if($count_results > 0 && $count_recipes > 0){
            $table_model->setActive(1);
            $table_model->save();
            Mage::getSingleton('core/session')->addSuccess('Tabela ativada com sucesso');
            $this->_redirect('*/*/registerTable/id/'.$id);
        }else{
            if($count_results > 0 && $count_recipes == 0 ){
               Mage::getSingleton('core/session')->addError('Não há fórmula cadastrada nessa transportadora');
            }else if($count_results == 0 && $count_recipes > 0 ){
               Mage::getSingleton('core/session')->addError('Não há dados preenchidos nessa transportadora');
            }else{
                Mage::getSingleton('core/session')->addError('Não há fórmula cadastrada nem dados preenchidos nessa transportadora');
            }
            $this->_redirect('*/*/registerTable/id/'.$id);
        }

    }

    //Desativa a tabela
    public function deactivateTableAction(){
        $params = $this->getRequest()->getParams();
        $id = $params['id'];
        $table_model = Mage::getModel('bis2ship/shippingtables')->load($id);
        $table_model->setActive(0);
        $table_model->save();
        Mage::getSingleton('core/session')->addSuccess('Tabela desativada com sucesso');
        $this->_redirect('*/*/registerTable/id/'.$id);
    }

    // Cria a tabela no banco de dados
    public function saveRegisterTableAction(){
        $params = $this->getRequest()->getParams();
        $id = $params['id'];

        if($id > 0){ // caso o id seja maior que 0 ele carrega o registro
            $shipping_tables = Mage::getModel('bis2ship/shippingtables')->load($id);
        }else{ // se não instancia uma model nova 
            $shipping_tables = Mage::getModel('bis2ship/shippingtables');
        }

        $hidden_fields = $params['hidden'];

        $carrier = $params['carrier'];
        
        $cubado = $params['cubado'];

        $excedente = $params['excedente'];

        $titulo = $params['titulo'];

        $nf_estado = $params['nf_estado'];

        if($id == 0){
            $_sql =  'CREATE TABLE ' . str_replace( ' ', '_', $carrier ) . ' ( ';
            $_sql .=  'id INT(11) AUTO_INCREMENT PRIMARY KEY,';
          
            foreach($hidden_fields as $field){
                $exp_field = explode('#', $field);
                $_sql .= ' ' . str_replace(' ', '_',  $exp_field[0] ) . ' ';
                if($exp_field[1] == 1)
                    $_sql .= ' INT(11), ';
                if($exp_field[1] == 2)
                    $_sql .= ' DECIMAL(12,4), ';
                if($exp_field[1] == 3)
                    $_sql .= ' VARCHAR(55), ';
            }

            $_sql .= 'peso_inicial DECIMAL(12,4), ';
            $_sql .= 'peso_final DECIMAL(12,4), ';
            $_sql .= 'cep_inicial INT(8) ZEROFILL, ';
            $_sql .= 'cep_final INT(8) ZEROFILL, ';
            $_sql .= 'valor_excedente DECIMAL (12,4),';
            $_sql .= 'prazo_entrega VARCHAR(100), ';
            $_sql .= 'nf_estado DECIMAL(5,2) ';
            $_sql .= ');';

            $write = Mage::getSingleton('core/resource')->getConnection('core_write');

            $write->exec($_sql);
        }else{
            $campos_adicionais = $params['adicional'];
            foreach($campos_adicionais as $adicional){
                 $exp_field = explode('#', $adicional);
                $_sql = 'ALTER TABLE ' . $shipping_tables->getTableName() . ' ';
                $_sql .= 'ADD COLUMN ' . str_replace(' ', '_' , $exp_field[0]) . ' ';
               
                if($exp_field[1] == 1)
                    $_sql .= ' INT(11) ; ';
                if($exp_field[1] == 2)
                    $_sql .= ' DECIMAL(12,4); ';
                if($exp_field[1] == 3)
                    $_sql .= ' VARCHAR(155); ';

                $write = Mage::getSingleton('core/resource')->getConnection('core_write');

                $write->exec($_sql);
            }
        }

        $serialized_fields = serialize($hidden_fields);

        if($id <= 0){
            $shipping_tables->setData('table_name', str_replace( ' ', '_', $carrier ) );
            $shipping_tables->setData('titulo', $titulo);
            $shipping_tables->setData('create', $_sql);
        }
        $shipping_tables->setData('fields', $serialized_fields);
        $shipping_tables->setData('active', 0);
        $shipping_tables->setData('cubado', $cubado);
        $shipping_tables->setData('excedente', $excedente);
        $shipping_tables->setData('nf_estado', $nf_estado);
        $shipping_tables->save();

        Mage::getSingleton('core/session')->addSuccess('Tabela criada com sucesso');
        $this->_redirect('*/*/manageTables');
    }


    public function saveFillTableAction(){
        $params = $this->getRequest()->getParams();

        $data = $params['data'];
        $table_id = $params['table_id'];
        $data = json_decode($data);

        $new_data = (json_decode($data));

        $table_model = Mage::getModel('bis2ship/shippingtables')->load($table_id);
        $fields = unserialize($table_model->getData('fields'));
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $_sql = 'TRUNCATE TABLE ' . $table_model->getTableName() . ';';
        $write->exec($_sql);
        foreach($new_data as $dta){
            $my_sql = 'INSERT INTO ' . $table_model->getTableName() . ' ( '  ;
            foreach($fields as $field){
                $field = explode('#', $field);
                $field = $field[0];
                $field = str_replace(' ', '_', $field);
                $my_sql .= $field . ', ';
            }

            $dta->nf_estado = number_format($dta->nf_estado, 2);

            if($dta->nf_estado != null){
                $my_sql .= ' peso_inicial, peso_final, cep_inicial, cep_final, valor_excedente, prazo_entrega, nf_estado ) VALUES ( ';
            }else{
                $my_sql .= ' peso_inicial, peso_final, cep_inicial, cep_final, valor_excedente, prazo_entrega ) VALUES ( ';
            }

            foreach($fields as $field){
                $field = explode('#', $field);
                $type = $field[1];
                $field = $field[0];
                $field = str_replace(' ', '_', $field);
                if($type == 3){
                    $my_sql .= '"'. $dta->$field.'", ';
                }else if($type == 2){ // Decimal
                    $my_sql .=  str_replace(',' , '.', $dta->$field) .', ';
                }else{
                    $my_sql .=  $dta->$field.', ';
                }
            }
            if($dta->nf_estado != null){
                $my_sql .= str_replace(',', '.', $dta->peso_inicial) . ', ' . str_replace(',', '.', $dta->peso_final). ', ' . $dta->cep_inicial . ', ' . $dta->cep_final . ' , ' . str_replace(',', '.', $dta->valor_excedente ) . ', "'. $dta->prazo_entrega .'"'.', '. $dta->nf_estado .');';
            }else{
                $my_sql .= str_replace(',', '.', $dta->peso_inicial) . ', ' . str_replace(',', '.', $dta->peso_final). ', ' . $dta->cep_inicial . ', ' . $dta->cep_final . ' , ' . str_replace(',', '.', $dta->valor_excedente ) . ', "'. $dta->prazo_entrega .'");';
            }
            
            //echo $my_sql; die;
            $write->exec($my_sql);
        }

        Mage::getSingleton('core/session')->addSuccess('Tabela salva com sucesso');
        $this->_redirect('*/*/fillTable/id/' . $table_id);
    
    }

    public function getfieldsAction(){
        $params = $this->getRequest()->getParams();
        $table = $params['table'];
        $datatable = Mage::getModel('bis2ship/shippingtables')->getCollection()->addFieldToFilter('table', $table)->getFirstItem();
        print_r(unserialize($datatable->getData('fields')));    
    }

    public function manageRecipeAction(){
         $this->_initAction()->_addContent($this->getLayout()->createBlock('bis2ship/Managerecipe'));
         $this->renderLayout();
    }

    public function fillTableAction(){
         $this->_initAction()->_addContent($this->getLayout()->createBlock('bis2ship/Filltable'));
         $this->renderLayout();
    }

    public function _initAction() {
        $this->loadLayout()->_addBreadcrumb(Mage::helper("bis2ship")->__("OneStepCheckout"), Mage::helper("bis2ship")->__("OneStepCheckout"));
        return $this;
    }
 }
 
?>
