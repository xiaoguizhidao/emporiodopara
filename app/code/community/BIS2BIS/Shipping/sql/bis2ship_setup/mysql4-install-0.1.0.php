<?php

$installer = $this;
$installer->startSetup();

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$sql = "
DROP TABLE IF EXISTS `shipping_tables`;
";

$write->exec($sql);

$sql = "
CREATE TABLE `shipping_tables` (
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(45) DEFAULT NULL,
  `create` longtext,
  `fields` longtext,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`entity_id`)
); 
";

$write->exec($sql);

$sql = " 
DROP TABLE IF EXISTS `shipping_recipes`;
";

$write->exec($sql);

$sql = "
CREATE TABLE `shipping_recipes` (
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) DEFAULT NULL,
  `recipe` longtext,
  PRIMARY KEY (`entity_id`)
);
";

$write->exec($sql);


?>


