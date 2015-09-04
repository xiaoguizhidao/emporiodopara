<?php
class BIS2BIS_Shipping_Helper_Data extends Mage_Core_Helper_Abstract{
	
	/*Retorna o tipo de dado do campo*/
	public function getTypeField($id){
		if($id=='1')
			return 'Inteiro';
		else if($id =='2')
			return 'Numérico';
		else if($id =='3')
			return 'Texto';
	}

	function eval_me($mathString) {
	    $mathString = trim($mathString);     // trim white spaces
	    $mathString = preg_replace ('[^0-9\+-\*\/\(\) ]', '', $mathString);    // remove any non-numbers chars; exception for math operators
	    $compute = create_function("", "return (" . $mathString . ");" );
	    return 0 + $compute();
	}
 
	
}