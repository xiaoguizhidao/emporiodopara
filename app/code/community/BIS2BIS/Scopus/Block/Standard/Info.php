<?php

class BIS2BIS_Scopus_Block_Standard_Info extends Mage_Payment_Block_Info {
    
    public function __construct() {
        parent::__construct();
        $this->setTemplate("scopus/Info.phtml");  
    }
}
