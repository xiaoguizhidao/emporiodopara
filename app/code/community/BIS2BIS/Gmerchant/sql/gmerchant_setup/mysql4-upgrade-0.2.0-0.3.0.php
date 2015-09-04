<?php 

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS `googleshopping_categorias` (
      `id` INT(10) NOT NULL AUTO_INCREMENT,
      `name` TEXT NULL,
      PRIMARY KEY (`id`)
    );
");

$installer->endSetup();