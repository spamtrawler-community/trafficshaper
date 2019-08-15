<?php

class Firewall_AntiWebSpam_Controller_Manage extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
      $this->oSmarty->display('Manage.tpl');
    }

    public function get()
    {
        $oManage = $oManage = new Firewall_AntiWebSpam_Model_Manage;
        $oManage->get();
    }

    public function create()
    {
        //Model constructor expects $oInput to be object
        $oManage = new Firewall_AntiWebSpam_Model_Manage(json_decode($_POST['models']));

        if(!$oManage->create()){
            exit($oManage->sErrors);
        }

        exit($oManage->sOutput);
    }

    public function update()
    {
        $oManage = new Firewall_AntiWebSpam_Model_Manage(json_decode($_POST['models']));
        $oManage->oInput = json_decode($_POST['models']);
        $oManage->update();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    public function destroy()
    {
        $oManage = new Firewall_AntiWebSpam_Model_Manage(json_decode($_POST['models']));
        $oManage->oInput = json_decode($_POST['models']);
        $oManage->destroy();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    public function flushCache()
    {
        $oManage = new Firewall_AntiWebSpam_Model_Manage(NULL);
        $oManage->deleteCache();
    }
}
