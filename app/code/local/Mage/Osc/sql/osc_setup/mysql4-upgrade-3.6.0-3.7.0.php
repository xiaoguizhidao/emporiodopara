<?php

$installer = $this;

$installer->startSetup();

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$sql = "ALTER TABLE  `checkout_config_data` ADD  `coupon` BOOLEAN NOT NULL;";

$write->exec($sql);

$sql = "ALTER TABLE sales_flat_quote_address ADD reference VARCHAR(100);";

$write->exec($sql);

$sql = "ALTER TABLE sales_flat_order_address ADD reference VARCHAR(100);";

$write->exec($sql);

$sql = "ALTER TABLE checkout_status MODIFY entity_id INT NOT NULL;";

$write->exec($sql);

$sql = "ALTER TABLE checkout_status DROP PRIMARY KEY;";

$write->exec($sql);

$sql = "ALTER TABLE `checkout_status` ADD `ID` INT NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`ID`);";

$write->exec($sql);

?>
