<?php

class Firewall_TestBlacklist_Model_Manage
{
    private $dbTableName = 'blacklist_test';
    public $oInput = NULL;
    private $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;

    public static function validateSettings($aSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Test Blacklist Status Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['allowcaptcha'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Test Blacklist Allow Captcha Invalid!';
        }

        $sBlockReason = str_replace(' ', '', $aSettings['conf_params']['block_reason']);
        if(!ctype_alnum($sBlockReason)){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Test Blacklist Block Reason Invalid!';
        }

        if($bErrors === true){
            return false;
        }
        return true;
    }
}
