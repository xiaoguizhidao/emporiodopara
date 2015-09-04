<?php
/**
############################################################################################
############################################################################################
########  BIS2BIS - COMÉRCIO ELETRÔNICO                                                    #
########  Módulo : Banner                                                                  #
########  Desenvolvedor : Guilherme Costa                                                  #
########  Email : guilherme.costa@bis2bis.com.br                                           #
########  Descrição : SQL de criação do Banco                         					   #
############################################################################################
############################################################################################
*/

$installer = $this;
$installer->startSetup();
$installer->run("

CREATE TABLE IF NOT EXISTS banner(
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100),
	link VARCHAR(150),
	type INT(11),
	first_date DATE,
	final_date DATE,
	category INT,
	image VARCHAR(150),
	active INT(11),
	ordem INT(11)
);

CREATE TABLE IF NOT EXISTS banner_config (
  id int(11) NOT NULL AUTO_INCREMENT,
  active int(11) DEFAULT NULL,
  url varchar(145) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO banner_config ( active, url ) VALUES ( 1, 'http://www.url.com.br');

");
?>
