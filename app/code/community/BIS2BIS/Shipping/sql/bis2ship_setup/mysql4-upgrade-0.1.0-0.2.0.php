 <?php

$installer = $this;

$installer->startSetup();

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$sql = "ALTER TABLE shipping_tables ADD cubado INT(11);";

$write->exec($sql);

$sql = "ALTER TABLE shipping_tables ADD excedente INT(11);";

$write->exec($sql);

$sql = "ALTER TABLE shipping_tables ADD titulo VARCHAR(100);";

$write->exec($sql);

?>