 <?php

$installer = $this;

$installer->startSetup();

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$sql = "
DROP TABLE IF EXISTS checkout_config_data;
";

$write->exec($sql);

$sql = "
CREATE TABLE `checkout_config_data` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `checkout` int(11) DEFAULT NULL,
	  `https` int(11) DEFAULT NULL,
	  `oschttpsurl` varchar(255) DEFAULT NULL,
	  `oscloginhttpsurl` varchar(255) DEFAULT NULL,
	  `term` int(11) DEFAULT NULL,
	  `text_term` longtext,
	  `version` varchar(100),
	  PRIMARY KEY (`id`)
);
";

$write->exec($sql);

$sql = "

INSERT INTO `checkout_config_data`
(
`id`,
`checkout`,
`https`,
`oschttpsurl`,
`oscloginhttpsurl`,
`term`,
`text_term`,
`version`
)
VALUES
(
	1,
	2,
	'https://www.urlaqui.com.br',
	'https://www.urlaqui.com.br/osc',
	'https://www.urlaqui.com.br/osc/login',
	2,
	'Term',
	'3.5'
); 

";

$write->exec($sql);

$sql = "DROP TABLE IF EXISTS `checkout_status`;";

$write->exec($sql);

$sql = "CREATE TABLE `checkout_status` (
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `browser` varchar(155) DEFAULT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `clickedfo` int(11) DEFAULT NULL,
  `payment_method` varchar(155) DEFAULT NULL,
  PRIMARY KEY (`entity_id`)
);";

 $write->exec($sql);

?>