<?php
class User_Auth_Controller_Auth  extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $sUsername;
    private $sPassword;

    public function index()
    {
        $this->oSmarty->assign('name', 'george smith');

        if($_SESSION['AUTH_ERROR']){
            $this->oSmarty->assign('error', $_SESSION['AUTH_ERROR']);
            unset($_SESSION['AUTH_ERROR']);
        } else {
            $this->oSmarty->assign('error', array());
        }

        $this->oSmarty->display('Auth.tpl');
        exit();
    }

    public function login(){
        $oSession = new SpamTrawler_Session();
        if(!isset($_POST['token']) || !$oSession->checkToken($_POST['token'])){
            exit('Direct Access Denied!');
        }

        $this->sUsername = $_POST['username'];
        $this->sPassword = $_POST['password'];

        $oAuth = new User_Auth_Model_Auth();
        $oAuth->sUsername = $this->sUsername;
        $oAuth->sPassword = $this->sPassword;

        $mValidation = $oAuth->validate();

        if($mValidation !== TRUE){
            header('Location: ' . SpamTrawler_Url::MakeFriendly('User/Auth/Auth/view'));
            exit();
        }

        if($oAuth->login() === FALSE){
            header('Location: ' . SpamTrawler_Url::MakeFriendly('User/Auth/Auth/view'));
            exit();
        }
    }

    public function logout()
    {
       $oSession = new SpamTrawler_Session();
       $oSession->destroy();
        header('Location: ' . SpamTrawler_Url::MakeFriendly('User/Auth/Auth/view'));
        exit();
    }
}
