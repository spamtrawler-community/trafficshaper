<?php

class Filesystem_Signatures_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        $this->oSmarty->display('View.tpl');
    }

    public function create(){
        ob_start();
        echo 'Signature creation initiated...<br /><br />A notification will be created once the process has been completed!<br /><br />You can leave this page in the meantime...<br />';

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
        $oScanner = new Filesystem_Signatures_Model_Create();
        $oScanner->CreateChecksums($aScanOptions);
    }

    public function compare(){
        ob_start();
        echo 'Signature comparison initiated...<br /><br />A notification will be created once the process has been completed!<br /><br />You can leave this page in the meantime...<br />';

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
        $oScanner = new Filesystem_Signatures_Model_Compare();
        $oScanner->DeepCompare($aScanOptions);
    }
}
