<?php
$installer = $this;
$installer->startSetup();
$installer->run("
 
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `deposito` VARCHAR( 255 );
ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `deposito` VARCHAR( 255 );
 
");
$installer->endSetup();