<?php

class BIS2BIS_Scopus_Model_Ambiente extends Mage_Payment_Model_Method_Abstract
{
    // Cria as opções de ambiente de testes
    public function toOptionArray(){
        $opcoes = array();
        $retorno = array();
        $opcoes[] = array('teste' => 'Testes');
        $opcoes[] = array('producao' => 'Produção em Homologação');
		$opcoes[] = array('liberado' => 'Liberado p/ Venda');

        for($i=0; $i<sizeof($opcoes); $i++){
            foreach($opcoes[$i] AS $chave => $valor)
                $retorno[] = array('value' => $chave, 'label' => $valor);
        }

        return $retorno;
    }
}