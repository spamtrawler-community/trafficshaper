<?php

class Firewall_ParameterBlacklist_Controller_Import extends SpamTrawler_BaseClasses_Modules_Controller {
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        $this->oSmarty->display('Import.tpl');
    }

    public function import()
    {
        $oImport = new Firewall_ParameterBlacklist_Model_Import();
        $oImport->doImport();
    }
}
