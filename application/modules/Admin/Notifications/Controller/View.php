<?php

class Admin_Notifications_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller {
    public function __construct(){
        //Set group access to admin only
        $this->aGroupAccess = array(1,2);

        //Exclude from maintenance
        $this->bExcludeFromMaintenance = TRUE;

        parent::__construct();
    }

    public function index(){
            $this->oSmarty->display('Notifications.tpl');
    }

    public function get(){
        $oManage = new Admin_Notifications_Model_Manage;
        $oManage->get();
    }

    public function checkNew(){
        $oManage = new Admin_Notifications_Model_Manage;
        $count = $oManage->checkNew();
        exit((string) $count);
    }

    public function destroy(){
        $oManage = new Admin_Notifications_Model_Manage(json_decode($_POST['models']));
        $oManage->oInput = json_decode($_POST['models']);
        $oManage->destroy();

        if(null !== $oManage->sErrors){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }
}
