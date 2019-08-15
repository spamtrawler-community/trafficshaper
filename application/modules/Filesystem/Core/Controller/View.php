<?php

class Filesystem_Core_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        $this->oSmarty->display(TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Filesystem' . DIRECTORY_SEPARATOR .'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template . DIRECTORY_SEPARATOR . 'View.tpl');
    }
}
