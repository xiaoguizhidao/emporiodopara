<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	CREATE TABLE `cores` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `nome` varchar(100) DEFAULT NULL,
	  `cor` varchar(100) DEFAULT NULL,
	  `imagem` varchar(100) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	);
");
?>
