<?php

class Firewall_Satellite_Controller_Manage extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array(1,2);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
      $this->oSmarty->display('Manage.tpl');
    }

    public function resetSatelliteLock(){
        SpamTrawler::$Registry['oCache']->remove('SatelliteLock');
        exit('Reset Successfull!');
    }
}
