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
	'https://www.urlaqui.com.br/osc/onepage/checkout',
	'https://www.urlaqui.com.br/osc/onepage/login',
	2,
	'Term',
	'3.7'
); 

";

$write->exec($sql);

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$attrcodetipopessoa = 'tipopessoa';
$attrcodeie = 'ie';
$attrcodeieisento = 'ieisento';
$attrcoderazaosocial = 'razaosocial';
$attrcodereference = 'reference';

$settings = array (
  'position' => 1,
  'is_required' => 0
);

$setup->addAttribute('1', $attrcodeie, $settings);
$setup->addAttribute('1', $attrcodeieisento, $settings);
$setup->addAttribute('1', $attrcoderazaosocial, $settings);
$setup->addAttribute('1', $attrcodetipopessoa, $settings);
$setup->addAttribute('2', $attrcodereference, $settings);


?>


