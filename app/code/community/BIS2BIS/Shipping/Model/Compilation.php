<?php 	
class BIS2BIS_Shipping_Model_Compilation {


	public function getHeaderXML(){
		$xml_string .= '<?xml version="1.0" encoding="UTF-8" ?>';
		$xml_string .= '<config>';
		$xml_string .= '<sections>';
		$xml_string .= '<carriers>';
		$xml_string .= '<groups>';
		return $xml_string;
	}


	public function getFooterXML(){
		$xml_string .= '</groups>';
		$xml_string .='</carriers>';
		$xml_string .= '</sections>';
		$xml_string .= '</config>';
		return $xml_string;
	}


	public function doExe(){
		$xml_string .= $this->getHeaderXML();
		$tables = Mage::getModel('bis2ship/shippingtables')->getCollection()->addFieldToFilter('active', 1);
		foreach($tables as $table){
			$xml_string .= '<'.$table->getTableName().'_custom_standard translate="label" module="shipping">';
			$xml_string .= '<label>'. ucfirst($table->getTableName()) .'</label>';
			$xml_string .='<frontend_type>text</frontend_type>';
			$xml_string .='<sort_order>99</sort_order>';
			$xml_string .= '<show_in_default>1</show_in_default>';
			$xml_string .= '<show_in_website>1</show_in_website>';
			$xml_string .= '<show_in_store>1</show_in_store>';
			$xml_string .= '<fields>';
			$xml_string .=  '<active translate="label">';
			$xml_string .= '<label>Enabled</label>';
			$xml_string .= '<frontend_type>select</frontend_type>';
			$xml_string .=  '<source_model>adminhtml/system_config_source_yesno</source_model>';
			$xml_string .=  '<sort_order>1</sort_order>';
			$xml_string .=  '<show_in_default>1</show_in_default>';
			$xml_string .=  '<show_in_website>1</show_in_website>';
			$xml_string .=  '<show_in_store>1</show_in_store>';
			$xml_string .=  '</active>';
				$xml_string .=  '<title translate="label">';
                $xml_string .=  '<label>Title</label>';
                $xml_string .=  '<frontend_type>text</frontend_type>';
                $xml_string .=  '<sort_order>2</sort_order>';
                $xml_string .=  '<show_in_default>1</show_in_default>';
                $xml_string .=  '<show_in_website>1</show_in_website>';
                $xml_string .=  '<show_in_store>1</show_in_store>';
            $xml_string .=  '</title>';
				$xml_string .=  '<sallowspecific translate="label">';
                $xml_string .=  '<label>Ship to Applicable Countries</label>';
                $xml_string .=  '<frontend_type>select</frontend_type>';
                $xml_string .=  '<sort_order>90</sort_order>';
                $xml_string .=  '<frontend_class>shipping-applicable-country</frontend_class>';
                $xml_string .=  '<source_model>adminhtml/system_config_source_shipping_allspecificcountries</source_model>';
                $xml_string .=  '<show_in_default>1</show_in_default>';
                $xml_string .=  '<show_in_website>1</show_in_website>';
                $xml_string .=  '<show_in_store>0</show_in_store>';
            $xml_string .=  '</sallowspecific>';
            $xml_string .=  '<specificcountry translate="label">';
			$xml_string .=  '<label>Ship to Specific Countries</label>';
			$xml_string .=  '<frontend_type>multiselect</frontend_type>';
			$xml_string .=  '<sort_order>91</sort_order>';
			$xml_string .=  '<source_model>adminhtml/system_config_source_country</source_model>';
			$xml_string .=  '<show_in_default>1</show_in_default>';
			$xml_string .=  '<show_in_website>1</show_in_website>';
			$xml_string .=  '<show_in_store>0</show_in_store>';
			$xml_string .=  '<can_be_empty>1</can_be_empty>';
			$xml_string .=  '</specificcountry>';
			$xml_string .=  '</fields>';
			$xml_string .=  '</'.$table->getTableName().'_custom_standard>';
		}

		$xml_string .= $this->getFooterXML();



  	    $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml_string);



        $xml_string = $dom->saveXML();

		
		$file =  Mage::getBaseDir() . DS . 'app' . DS . 'code'. DS . 'community'. DS . 'BIS2BIS' . DS . 'Shipping' . DS . 'etc' . DS . 'system.xml';
	
		$handle = fopen($file, 'w'); // abre ou cria
		fwrite($handle, ''); // limpa  
        fwrite($handle, $xml_string);
        fclose($handle); // fecha
        Mage::app()->cleanCache();

        return true;

	}



}

 ?>