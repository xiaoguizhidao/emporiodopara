<?php
/*
 * My Render Extension
 */
class BIS2BIS_Comprejunto_Block_Tipodesconto extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    
    public function render(Varien_Object $row)
    {
		$tipodesconto = $row->getData('tipodesconto');
		$out = '';
		
		if($tipodesconto == 'porcent')
			$out = 'Porcentagem (%)';
		else
			$out = 'Valor Fixo (R$)';
		
		return $out;

    }
    
}