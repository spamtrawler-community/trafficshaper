<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 16/06/14
 * Time: 12:15
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class Filesystem_FindFilesByCTime_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller
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
        $oScanner = new Filesystem_FindFilesByCTime_Model_Finder();
        $oScanner->find_files($aScanOptions);
    }
}