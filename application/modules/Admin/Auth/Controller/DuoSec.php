<?php
class Admin_Auth_Controller_DuoSec extends SpamTrawler_BaseClasses_Modules_Controller {
    public function __construct(){
        //Exclude from Maintenance
        $this->bExcludeFromMaintenance = TRUE;

        if(!isset($_SESSION['sUsername']) || !isset($_SESSION['sEmail']) || !isset($_SESSION['iUsergroup']) || $_SESSION['TwoFactorAuthStatus']){
            header('HTTP/1.0 403 Forbidden');
            exit('Invalid Access Attempt!');
        }
            /*
             * This is something uniquely generated by you for your application
             * and is not shared with Duo.
             */
            //define('AKEY', SpamTrawler::$aSettingsCore['conf_params']['duosec_akey']);
            define('AKEY', SpamTrawler::$Registry['settings_core']['duosec_akey']);

            /*
             * IKEY, SKEY, and HOST should come from the Duo Security admin dashboard
             * on the integrations page.
             */
            //define('IKEY', SpamTrawler::$aSettingsCore['conf_params']['duosec_ikey']);
            //define('SKEY', SpamTrawler::$aSettingsCore['conf_params']['duosec_skey']);
            //define('HOST', SpamTrawler::$aSettingsCore['conf_params']['duosec_host']);
            define('IKEY', SpamTrawler::$Registry['settings_core']['duosec_ikey']);
            define('SKEY', SpamTrawler::$Registry['settings_core']['duosec_skey']);
            define('HOST', SpamTrawler::$Registry['settings_core']['duosec_host']);

        parent::__construct();
    }

    public function index(){
        if(isset($_POST['sig_response'])){
           if($this->DuoSecVerify() === TRUE){
               $_SESSION['TwoFactorAuthStatus'] = TRUE;
               header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Dashboard/View'));
           } else {
               exit('Two Factor Authentication Failed!');
           }
        } else {
            $this->oSmarty->assign('DuoSigRequest', $this->DuoSecSigRequest());
            $this->oSmarty->display('DuoSec.tpl');
        }
    }

    public function DuoSecSigRequest(){
        return Admin_Auth_Helper_Duo::signRequest(IKEY, SKEY, AKEY, 'griddie');
    }

    public function DuoSecVerify(){
        /*
         * STEP 3:
         * Once secondary auth has completed you may log in the user
         */
        if(isset($_POST['sig_response'])){ //verify sig response and log in user
            //make sure that verifyResponse does not return NULL
            //if it is NOT NULL then it will return a username
            //you can then set any cookies/session data for that username
            //and complete the login process
            $resp = Admin_Auth_Helper_Duo::verifyResponse(IKEY, SKEY, AKEY, $_POST['sig_response']);
            if($resp != NULL){
                return TRUE;
            }
        }
        return FALSE;
    }
}