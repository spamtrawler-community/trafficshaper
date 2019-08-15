<?php

class Filesystem_Virusscanner_Model_Scanner
{
    public function __construct(){
    }

    public function scan($aScanOptions){
        try{
            ignore_user_abort(true);
            set_time_limit(3600);
        } catch(Exception $e) {
            //Do nothing
        }

        //add custom directory scan
        $scanPath = $_SERVER['DOCUMENT_ROOT'];
        if(isset($aScanOptions['options']['scanpath'])) {
            if(file_exists($aScanOptions['options']['scanpath'])){
                $scanPath = escapeshellarg($aScanOptions['options']['scanpath']);
            } else {
                exit('Path does not exist!');
            }
        }
        //end add custom directory scan

        //log path
        $logPath = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'Virusscanner.log';
        //end log path

        //handle infected
        $filePath = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'infected' . DIRECTORY_SEPARATOR;

        if(isset($aScanOptions['copyinfected']) && $aScanOptions['copyinfected'] == '1') {
            $copyInfected = '--copy='.$filePath;
        } else {
            $copyInfected = '';
        }
        //end handle infected
        //advanced scan options
        $blockEncrypted = '';
        $blockBroken = '';
        $detectPUA = '';

        if(isset($aScanOptions['markencrypted']) && $aScanOptions['markencrypted'] == '1') {
            $blockEncrypted = '--block-encrypted';
        }
        if(isset($aScanOptions['markbroken']) &&  $aScanOptions['markbroken'] == '1') {
            $blockBroken = '--detect-broken';
        }
        if(isset($aScanOptions['detectpua']) && $aScanOptions['detectpua'] == '1') {
            $detectPUA = '--detect-pua';
        }
        //end advanced scan options
        exec("clamscan -r $scanPath -i $$blockEncrypted $blockBroken $detectPUA --log=$logPath $copyInfected", $output);

        $sScanResult = 'Path: ' . $scanPath . '<br /><br />';
        foreach ($output as $key => $value) {
            $sScanResult .= $value. '<br />';
        }

        $oNotifications = new Admin_Notifications_Model_Manage();
        $oNotifications->add('Virusscan', str_replace('<br />', "\r\n", $sScanResult));
    }

    public function validate(){}
}
