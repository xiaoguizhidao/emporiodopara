 <?php

$installer = $this;

$installer->startSetup();

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$sql = "
CREATE TABLE `checkout_log` (
	`entity_id` int(11) NOT NULL AUTO_INCREMENT,
	`customer_id` int(11) DEFAULT NULL,
	`payment_method` int(11) DEFAULT NULL,
	`quote_id` int(11),
	`message` varchar(255),
	PRIMARY KEY (`entity_id`)
);
";

$write->exec($sql);
?>
