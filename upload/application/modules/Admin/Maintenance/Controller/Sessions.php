<?php

class Admin_Maintenance_Controller_Sessions extends SpamTrawler_BaseClasses_Modules_Controller
{
    public $path;

    public function __construct()
    {
        $this->aGroupAccess = array(1);
        //Exclude from Maintenance
        $this->bExcludeFromMaintenance = TRUE;
        $this->path = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'sessions' . DIRECTORY_SEPARATOR;

        parent::__construct();
    }

    public function index()
    {
        exit('Invalid Function!');
    }

    public function clearOld()
    {
        try {
            $interval = strtotime('-24 hours');//files older than 24hours

            foreach (glob($this->path . "*") as $file){
                //delete if older
                if (filemtime($file) <= $interval) unlink($file);
            }
            exit('ok');
        } catch (Exception $e){
            $this->logError($e);
            exit('An Error occured clearing old sessions!');
        }
    }

    public function clearAll()
    {
        try {
            array_map('unlink', (glob($this->path . '*') ? glob($this->path . '*') : array()));
            exit('ok');
        } catch (Exception $e) {
            $this->logError($e);
            exit('An Error occured clearing sessions!');
        }
    }

    private function logError($e){
        //logging error
        $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error.log');
        $logger = new SpamTrawler_Log($writer);
        $logger->warn('Session Deletion Error: ' . $e);
    }
}
