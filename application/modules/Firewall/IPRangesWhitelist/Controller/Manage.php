<?php

class Firewall_IPRangesWhitelist_Controller_Manage extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $dbTableName = 'whitelist_ipranges';
    //Protect from unauthorized access
    public function __construct(){
        $this->aGroupAccess = array(1,2);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
      $this->oSmarty->display('Manage.tpl');
    }

    public function get()
    {
        $oManage = $oManage = new Firewall_IPRangesWhitelist_Model_Manage;
        $oManage->get();
    }

    public function create()
    {
        //Model constructor expects $oInput to be object
        $oManage = new Firewall_IPRangesWhitelist_Model_Manage(json_decode($_POST['models']));

        if(!$oManage->create()){
            exit($oManage->sErrors);
        }

        exit($oManage->sOutput);
    }

    public function update()
    {
        $oManage = new Firewall_IPRangesWhitelist_Model_Manage(json_decode($_POST['models']));
        $oManage->oInput = json_decode($_POST['models']);
        $oManage->update();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    public function destroy()
    {
        $oManage = new Firewall_IPRangesWhitelist_Model_Manage(json_decode($_POST['models']));
        $oManage->oInput = json_decode($_POST['models']);
        $oManage->destroy();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    public function reinitialize()
    {
        $oManage = new Firewall_IPRangesWhitelist_Model_Manage();

        if($oManage->reinitialize() == true){
            exit('ok');
        } else {
            exit('An Error Occured while reinitializing!');
        }
    }

    public function flushCache()
    {
        $oManage = new Firewall_IPRangesWhitelist_Model_Manage(NULL);
        $oManage->deleteCache();
    }
}
