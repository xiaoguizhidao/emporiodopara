<?php 

$installer = $this;

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$data=array(

	'type'=>'int',
	'input'=>'boolean',
	'label'=>'Habilitar Google Shopping',
	'global'=>Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
	'is_required'=>'0',
	'is_comparable'=>'0',
	'is_searchable'=>'0',
	'is_unique'=>'0',
	'is_configurable'=>'0',
	'use_defined'=>'0'

);
 
$setup->addAttribute('catalog_product','googlemerchant',$data); 


$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$sql = "
CREATE TABLE IF NOT EXISTS `googleshopping` (
  `gtin` varchar(100) DEFAULT NULL,
  `mpn` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `last` datetime DEFAULT NULL,
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoprodutos` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`entity_id`)
);
";

$write->exec($sql);

$installer->endSetup();

?>