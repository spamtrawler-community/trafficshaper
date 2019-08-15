<?php
class Admin_Auth_Controller_Login extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $sUsername;
    private $sPassword;

    public function __construct()
    {
        //Exclude from Maintenance
        $this->bExcludeFromMaintenance = TRUE;

        parent::__construct();
    }

    public function index()
    {
        if (isset($_SESSION['sUsername'])) {
            header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Dashboard/View'));
            exit();
        }

        if (isset($_SESSION['AUTH_ERROR'])) {
            $this->oSmarty->assign('error', $_SESSION['AUTH_ERROR']);
            unset($_SESSION['AUTH_ERROR']);
        } else {
            $this->oSmarty->assign('error', array());
        }

        $this->oSmarty->display('Login.tpl');
        exit();
    }

    public function login()
    {
        $oSession = new SpamTrawler_Session();
        if (!isset($_POST['token']) || !$oSession->checkToken($_POST['token'])) {
            exit('Direct Access Denied!');
        }

        $this->sUsername = $_POST['username'];
        $this->sPassword = $_POST['password'];

        $oAuth = new Admin_Auth_Model_Login();
        $oAuth->sUsername = $this->sUsername;
        $oAuth->sPassword = $this->sPassword;

        $mValidation = $oAuth->validate();

        if ($mValidation !== TRUE) {
            header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Login'));
            exit();
        }

        if ($oAuth->login() === FALSE) {
            header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Login'));
            exit();
        }
    }
}
