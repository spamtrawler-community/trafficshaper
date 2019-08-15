<?php
class Admin_Auth_Controller_Logout extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        //Exclude from Maintenance
        $this->bExcludeFromMaintenance = TRUE;

        parent::__construct();
    }

    public function index(){
        $this->logout();
    }

    public function logout()
    {
        $oSession = new SpamTrawler_Session();
        $oSession->destroy();

        //Remove Admin Cache Entry
        SpamTrawler::$Registry['oCache']->remove(sha1(SpamTrawler::$Registry['visitordetails']['ip']));

        header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Login'));
        exit();
    }
}
