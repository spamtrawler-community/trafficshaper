<?php

class Firewall_AntiWebSpam_Controller_Report extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct()
    {
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        exit('Not implemented!');
    }
}
