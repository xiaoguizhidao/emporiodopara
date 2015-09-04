<?php 

$installer = $this;

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$installer->run("
    ALTER TABLE  `googleshopping` ADD  `tipopreco` VARCHAR(15) NOT NULL;
    ALTER TABLE  `googleshopping` ADD  `desconto` INT(2) NOT NULL;
    ALTER TABLE  `googleshopping` ADD  `parcelamento` INT(1) NOT NULL;
    ALTER TABLE  `googleshopping` ADD  `qty_parcelas` INT(1) NOT NULL;
    ALTER TABLE  `googleshopping` ADD  `valor_min` INT(1) NOT NULL;

    UPDATE `googleshopping` SET `tipopreco` = 'precofinal', `parcelamento` = 0 WHERE `entity_id` = 1;
");

$installer->endSetup();