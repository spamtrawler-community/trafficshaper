<?php
class User_ResetPassword_Controller_ResetPassword extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index(){
        $this->oSmarty->display('ResetPassword.tpl');
    }
}
