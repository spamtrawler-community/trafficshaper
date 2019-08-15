<?php

class Admin_Maintenance_Controller_Cache extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct()
    {
        $this->aGroupAccess = array(1,2);
        //Exclude from Maintenance
        $this->bExcludeFromMaintenance = TRUE;

        parent::__construct();
    }

    public function index()
    {
        exit('Inavlid Function!');
    }

    public function Field()
    {
        if (isset($_GET['service']) && isset($_GET['field'])) {
            $this->oSmarty->assign('gridheader', $_GET['header']);
            $this->oSmarty->assign('service', $_GET['service']);
            $this->oSmarty->assign('field', $_GET['field']);
            $this->oSmarty->display('Field.tpl');
        } else {
            exit('Inavlid Request!');
        }
    }

    public function getField()
    {
        if (isset($_GET['field'])) {
            header('Access-Control-Allow-Origin: *');
            header('Content-type: application/json; charset=utf-8');
            $oTable = new SpamTrawler_Db_Tables_CacheVisitors();

            $result = $oTable->getByField($_GET['field']);

            exit(json_encode(array("data" => $result, "total" => count($result))));
        }
        else {
            exit('Inavlid Request!');
        }
    }

    public function getUserAgents()
    {
            header('Access-Control-Allow-Origin: *');
            header('Content-type: application/json; charset=utf-8');
            $oTable = new SpamTrawler_Db_Tables_CacheUseragents();

            $result = $oTable->getUseragents();

            exit(json_encode(array("data" => $result, "total" => count($result))));
    }

    public function getReferrer()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');
        $oTable = new SpamTrawler_Db_Tables_CacheReferrer();

        $result = $oTable->getReferrer();

        exit(json_encode(array("data" => $result, "total" => count($result))));
    }

    public function getUrls()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');
        $oTable = new SpamTrawler_Db_Tables_CacheUrls();

        $result = $oTable->getUrls();

        exit(json_encode(array("data" => $result, "total" => count($result))));
    }

    public function getEmails()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');
        $oTable = new SpamTrawler_Db_Tables_CacheEmails();

        $result = $oTable->getEmails();

        exit(json_encode(array("data" => $result, "total" => count($result))));
    }

    public function getUsernames()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');
        $oTable = new SpamTrawler_Db_Tables_CacheUsernames();

        $result = $oTable->getUsernames();

        exit(json_encode(array("data" => $result, "total" => count($result))));
    }

    public function getHostnames()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');
        $oTable = new SpamTrawler_Db_Tables_CacheHostnames();

        $result = $oTable->getHostnames();

        exit(json_encode(array("data" => $result, "total" => count($result))));
    }

    public function getASN()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');
        $oTable = new SpamTrawler_Db_Tables_CacheASN();

        $result = $oTable->get();

        exit(json_encode(array("data" => $result, "total" => count($result))));
    }

    public function getASNOrgs()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');
        $oTable = new SpamTrawler_Db_Tables_CacheASNOrgs();

        $result = $oTable->get();

        exit(json_encode(array("data" => $result, "total" => count($result))));
    }



    public function clearCache()
    {
        $oTable = new SpamTrawler_Db_Tables_CacheVisitors();
        exit($oTable->reInitialize());
    }

    public function normalizeCache()
    {
        $oTable = new SpamTrawler_Db_Tables_CacheVisitors();
        exit($oTable->normalize());
    }
}
