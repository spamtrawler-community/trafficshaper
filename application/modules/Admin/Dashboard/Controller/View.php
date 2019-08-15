<?php

class Admin_Dashboard_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array(1,2);
        //Exclude from Maintenance
        $this->bExcludeFromMaintenance = TRUE;

        parent::__construct();
    }

    public function index()
    {
        //Get Server Load Average currently only for Linux servers
        if(function_exists('sys_getloadavg')){
            $aServerLoad = sys_getloadavg();

            $this->oSmarty->assign('serverload', implode(' | ', $aServerLoad));
        }

        $oNumCachedVisitors = new SpamTrawler_Db_Tables_CacheVisitors();
        $iNumCachedVisitors = $oNumCachedVisitors->getCount();

        $oVisitorsToday = new SpamTrawler_Db_Tables_CacheVisitors();
        $iVisitorsToday = $oVisitorsToday->getMaxHourToday();

        /*
        if($iVisitorsToday < 100){
            $iVisitorsToday = ceil($iVisitorsToday / 10) * 10;
        } elseif($iVisitorsToday < 1000){
            $iVisitorsToday = ceil($iVisitorsToday / 100) * 100;
        } elseif($iVisitorsToday < 10000){
            $iVisitorsToday = ceil($iVisitorsToday / 1000) * 1000;
        } elseif($iVisitorsToday < 100000){
            $iVisitorsToday = ceil($iVisitorsToday / 10000) * 10000;
        } else {
            $iVisitorsToday = ceil($iVisitorsToday / 100000) * 100000;
        }
        */

        $iVisitorsToday = ceil($iVisitorsToday / 10) * 10;

        $this->oSmarty->assign('maxvisitorshour', $iVisitorsToday);
        $this->oSmarty->assign('totalvisitors', $iNumCachedVisitors);
        $this->oSmarty->display('Dashboard.tpl');
    }

    public function update(){
        exit('Update Method!');
    }
}
