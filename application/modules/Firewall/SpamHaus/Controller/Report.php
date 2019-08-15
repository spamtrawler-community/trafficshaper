<?php

class Firewall_StopForumSpam_Controller_Report{
    public $aParams = NULL;
    public $sIP;
    public $sEmail;

    public function __construct($sParams){
        $this->$sIP = SpamTrawler_VisitorDetails_IP_IP::get();
        $this->$sEmail = SpamTrawler_VisitorDetails_Email_Email::get();
    }

    public function filter()
    {
        $oStopForumSpam = new Firewall_StopForumSpam_Helper_API();

        $bReport = $oStopForumSpam->SubmitReport("vehicle271", "113.116.60.187", "http://pastebin.com/HL9aC5UC", "vehicle271@163.com");

        if(!$bReport){
            return false;
        }
        return true;
    }
}
