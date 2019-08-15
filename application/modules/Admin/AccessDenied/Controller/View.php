<?php

class Admin_AccessDenied_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array();
        //Exclude from Maintenance
        $this->bExcludeFromMaintenance = TRUE;

        parent::__construct();
    }

    public function index()
    {
        if(!$_SESSION['iUsergroup']){
            header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Login'));
            exit();
        }
        $this->oSmarty->display('Group.tpl');
    }
}
