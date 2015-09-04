<?php
class Mage_Osc_Helper_Data extends Mage_Core_Helper_Abstract{
	
	
	// remove caracteres de separação de um cpf-cnpj
	function replace($value){
		$value = str_replace('/', '', $value);
		$value = str_replace('.', '', $value);
		$value = str_replace('-', '', $value);
		return $value;
	}

	// VERIFICA CPF
    function validaCPF($cpf) {

    	  $cpf = $this->replace($cpf);
	      
	      $soma = 0;
	       
	      if (strlen($cpf) <> 11)
	         return false;
	       
	      // Verifica 1º digito      
	      for ($i = 0; $i < 9; $i++) {         
	         $soma += (($i+1) * $cpf[$i]);
	      }
	 
	      $d1 = ($soma % 11);
	       
	      if ($d1 == 10) {
	         $d1 = 0;
	      }
	       
	      $soma = 0;
	       
	      // Verifica 2º digito
	      for ($i = 9, $j = 0; $i > 0; $i--, $j++) {
	         $soma += ($i * $cpf[$j]);
	      }
	       
	      $d2 = ($soma % 11);
	       
	      if ($d2 == 10) {
	         $d2 = 0;
	      }      
	       
	      if ($d1 == $cpf[9] && $d2 == $cpf[10]) {
	         return true;
	      }
	      else {
	         return false;
	      }
    }
    
   // VERFICA CNPJ
   function validaCNPJ($cnpj) {

   		  $cnpj = $this->replace($cnpj);
    
	      if (strlen($cnpj) <> 14)
	         return false;
	 
	      $soma = 0;
	       
	      $soma += ($cnpj[0] * 5);
	      $soma += ($cnpj[1] * 4);
	      $soma += ($cnpj[2] * 3);
	      $soma += ($cnpj[3] * 2);
	      $soma += ($cnpj[4] * 9);
	      $soma += ($cnpj[5] * 8);
	      $soma += ($cnpj[6] * 7);
	      $soma += ($cnpj[7] * 6);
	      $soma += ($cnpj[8] * 5);
	      $soma += ($cnpj[9] * 4);
	      $soma += ($cnpj[10] * 3);
	      $soma += ($cnpj[11] * 2);
	 
	      $d1 = $soma % 11;
	      $d1 = $d1 < 2 ? 0 : 11 - $d1;
	 
	      $soma = 0;
	      $soma += ($cnpj[0] * 6);
	      $soma += ($cnpj[1] * 5);
	      $soma += ($cnpj[2] * 4);
	      $soma += ($cnpj[3] * 3);
	      $soma += ($cnpj[4] * 2);
	      $soma += ($cnpj[5] * 9);
	      $soma += ($cnpj[6] * 8);
	      $soma += ($cnpj[7] * 7);
	      $soma += ($cnpj[8] * 6);
	      $soma += ($cnpj[9] * 5);
	      $soma += ($cnpj[10] * 4);
	      $soma += ($cnpj[11] * 3);
	      $soma += ($cnpj[12] * 2);
	       
	      $d2 = $soma % 11;
	      $d2 = $d2 < 2 ? 0 : 11 - $d2;
	       
	      if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
	         return true;
	      }
	      else {
	         return false;
	      }
   }


}