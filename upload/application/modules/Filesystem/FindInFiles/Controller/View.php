<?php

class Filesystem_FindInFiles_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        $this->oSmarty->display('View.tpl');
    }

    public function find(){
        $aScanOptions = $_GET;
        $oScanner = new Filesystem_FindInFiles_Model_Finder();
        $oScanner->findInFiles($aScanOptions);
    }
}
