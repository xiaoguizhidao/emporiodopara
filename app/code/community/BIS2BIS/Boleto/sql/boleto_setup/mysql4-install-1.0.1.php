<?php
	$installer = $this;
	$installer->startSetup();
	$installer->run("ALTER TABLE `{$installer->getTable('sales_flat_order')}` ADD `boleto_vencimento` VARCHAR(255);");
	$installer->endSetup();
?>