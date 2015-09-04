<?php
$installer = $this;
$installer->startSetup();
$installer->run("
			
		ALTER TABLE  `".$this->getTable('sales/quote_item')."` ADD  `comprejunto_amount` DECIMAL( 12, 4 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/quote_item')."` ADD  `base_comprejunto_amount` DECIMAL( 12, 4 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/quote_item')."` ADD  `comprejunto_ids` VARCHAR( 100 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/quote_item')."` ADD  `tipo_desconto` VARCHAR( 100 ) NOT NULL;
			
		ALTER TABLE  `".$this->getTable('sales/order_item')."` ADD  `comprejunto_amount` DECIMAL( 12, 4 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order_item')."` ADD  `base_comprejunto_amount` DECIMAL( 12, 4 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order_item')."` ADD  `comprejunto_ids` VARCHAR( 100 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order_item')."` ADD  `tipo_desconto` VARCHAR( 100 ) NOT NULL;
		
		CREATE TABLE comprejunto (id int not null auto_increment, id_prods varchar(100), tipodesconto varchar(100), desconto int(10), primary key(id));

    ");
	
$installer->endSetup();