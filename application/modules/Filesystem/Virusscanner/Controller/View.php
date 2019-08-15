<?php

class Filesystem_Virusscanner_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        $this->oSmarty->assign('scanpath', $_SERVER['DOCUMENT_ROOT']);
        $this->oSmarty->display(TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Filesystem' . DIRECTORY_SEPARATOR .'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template . DIRECTORY_SEPARATOR . 'View.tpl');
    }

    public function scan(){
        ob_start();
        echo 'Virus scan initiated...<br /><br />A notification will be created once the scan is complete!<br /><br />You can leave this page in the meantime...<br />';

// get the size of the output
        $size = ob_get_length();

// send headers to tell the browser to close the connection
        header("Content-Length: $size");
        header("Content-Encoding: none\r\n");
        header('Connection: close');


// flush all output
        ob_end_flush();
        ob_flush();
        flush();
        session_write_close();

        $aScanOptions = $_POST;
        $oScanner = new Filesystem_Virusscanner_Model_Scanner();
        $oScanner->scan($aScanOptions);
    }
}
