<?php

class Install_Setup_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller
{
    public function index()
    {
        $aDbDrivers = array();
        if(function_exists('mysqli_connect')) {
            $aDbDrivers[] = array('mysqli', 'MySQLi');
        }

        if(extension_loaded('pdo_mysql')){
            $aDbDrivers[] = array('pdo_mysql', 'PDO MySQL');
        }

        $this->oSmarty->assign('DbDrivers', $aDbDrivers);
        //$this->oSmarty->debug = true;
        $this->oSmarty->display('Setup.tpl');
    }
}
