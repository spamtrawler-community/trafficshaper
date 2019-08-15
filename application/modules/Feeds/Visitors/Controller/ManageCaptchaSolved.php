<?php

class Feeds_Visitors_Controller_ManageCaptchaSolved extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $dbTableName = 'cache_visitors';
    private $cacheFileName;

    public function __construct()
    {
        $this->aGroupAccess = array(1,2);
        $this->bExcludeFromMaintenance = TRUE;
        //Cache File Name
        $this->cacheFileName = SpamTrawler::$Config->database->table->prefix . '_' . $this->dbTableName;
        parent::__construct();
    }

    public function index()
    {
      $this->oSmarty->display('Manage.tpl');
    }

    public function get()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');

        $oManage = new Feeds_Visitors_Model_ManageCaptchaSolved();
        exit($oManage->get());
    }

    public function update()
    {
        $oManage = new Feeds_Visitors_Model_ManageCaptchaSolved(json_decode($_POST['models']));
        $oManage->update();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }


    public function destroy()
    {
        $oManage = new Feeds_Visitors_Model_ManageCaptchaSolved(json_decode($_POST['models']));
        $oManage->destroy();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    public function getStats()
    {
        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
        $oTable->getStatsJson();
    }

    public function deleteCache()
    {
        SpamTrawler::$Registry['oCache']->remove($this->cacheFileName);
    }
}
