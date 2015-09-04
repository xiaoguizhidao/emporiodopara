<?php

/**
 * 
 *
 * @author Helton Hayashi
 */

$installer = $this;
$installer->startSetup();
$installer->run("


DROP TABLE IF EXISTS marcas, marcas_config;

CREATE TABLE IF NOT EXISTS `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `marcas` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category` (`category`)
);

CREATE TABLE IF NOT EXISTS `marcas_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL,
  `attribute_name` varchar(50) NOT NULL,
  `https` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `marcas_config` (`id`, `active`, `attribute_name`, `https`) VALUES (1, 0, 'marcas', '');

");
?>
