<?php

class Feeds_Visitors_Controller_Charts extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $dbTableName = 'cache_visitors';
    private $cacheFileName;

    public function index()
    {

    }

    public function __construct()
    {
        $this->aGroupAccess = array(1,2);
        $this->bExcludeFromMaintenance = TRUE;
        //Cache File Name
        $this->cacheFileName = SpamTrawler::$Config->database->table->prefix . '_' . $this->dbTableName;
        parent::__construct();
    }

    public function getStatsToday()
    {
        $oTable = new SpamTrawler_Db_Tables_CacheVisitors();
        exit(json_encode($oTable->getStatsToday()));
    }

    public function getStatsByCountry()
    {
        $oTable = new SpamTrawler_Db_Tables_CacheCountryIso();
        exit(json_encode($oTable->getStatsbyCountry()));
    }

    public function getStatsByDevice()
    {
        $oTable = new SpamTrawler_Db_Tables_CacheDeviceTypes();
        exit(json_encode($oTable->getStatsbyDevice()));
    }


    public function getStatsByCountryOld()
    {
        $oTable = new SpamTrawler_Db_Tables_CacheVisitors();
        exit(json_encode($oTable->getStatsByCountryOld()));
    }
}
