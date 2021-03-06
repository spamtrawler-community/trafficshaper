<?php

class Feeds_Visitors_Controller_ManageUnblocked extends SpamTrawler_BaseClasses_Modules_Controller
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

        //Debug
        //$writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log/debug.log');
        //$logger = new SpamTrawler_Log($writer);
        //$logger->warn("\r\nPOST: " . json_encode($_POST) . "\r\nGET: " . json_encode($_GET));

        $oManage = new Feeds_Visitors_Model_ManageUnblocked();
        exit($oManage->get());
    }

    public function update()
    {
        $oManage = new Feeds_Visitors_Model_ManageUnblocked(json_decode($_POST['models']));
        $oManage->update();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    public function destroy()
    {
        $oManage = new Feeds_Visitors_Model_ManageUnblocked(json_decode($_POST['models']));
        $oManage->destroy();

        if(!is_null($oManage->sErrors)){
            exit($oManage->sErrors);
        }
        exit($oManage->sOutput);
    }

    public function deleteCache()
    {
        SpamTrawler::$Registry['oCache']->remove($this->cacheFileName);
    }
}
