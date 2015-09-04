 <?php

$installer = $this;

$installer->startSetup();

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$sql = "ALTER TABLE shipping_tables ADD nf_estado INT(11);";

$write->exec($sql);
?>