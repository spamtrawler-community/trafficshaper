<?php

/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 19/06/14
 * Time: 14:58
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
abstract class SpamTrawler_BaseClasses_Modules_Controller
{

    protected $oSmarty;

    protected $aGroupAccess = array();

    protected $bExcludeFromMaintenance = false;


    abstract protected function index();


    public function __construct()
    {
        if ($this->bExcludeFromMaintenance === FALSE) {

            $this->maintenance();

        }

        $this->oSmarty = SpamTrawler::getSmarty();


        //Required to replace ? with & for subsequent parameters

        $sUrlGlue = (SpamTrawler::$Config->core->friendlyurl === 1 ? '?' : '&');

        $this->oSmarty->assign('urlparameterglue', $sUrlGlue);


        $this->oSmarty->assign('sysurl', TRAWLER_URL_ROOT);

        $this->oSmarty->assign('ressourceurl', TRAWLER_URL_ROOT . 'static/templates/' . SpamTrawler::$Config->core->template);


        if (SpamTrawler::$Config->core->friendlyurl != 0) {

            SpamTrawler::$Registry['sLinkUrl'] = TRAWLER_URL_ROOT;
            //Required on some servers . '/'
            SpamTrawler::$Registry['sLinkUrl'] = str_replace('\\', '/', SpamTrawler::$Registry['sLinkUrl']);
            SpamTrawler::$Registry['sLinkUrl'] = str_replace('//', '/', SpamTrawler::$Registry['sLinkUrl']);

            $this->oSmarty->assign('linkurl', SpamTrawler::$Registry['sLinkUrl']);

        } else {

            SpamTrawler::$Registry['sLinkUrl'] = TRAWLER_URL_ROOT . basename($_SERVER['SCRIPT_NAME']) . '?do=';

            $this->oSmarty->assign('linkurl', SpamTrawler::$Registry['sLinkUrl']);

        }

        //Check UserGroup access permissions

        if (!empty($this->aGroupAccess)) {

            //Two Factor Authentication

            if (SpamTrawler::$Registry['settings_core']['admin_auth_method'] != 'UsernamePassword' && $_SESSION['twofactor'] == 'true' && !$_SESSION['TwoFactorAuthStatus']) {

                header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Logout'));

                exit();

            } else if (!$_SESSION['iUsergroup']) {

                header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Login'));

                exit();

            } else if (!in_array($_SESSION['iUsergroup'], $this->aGroupAccess)) {

                header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/AccessDenied/View'));

                exit();

            }

        }

    }


    public function __call($method, $args)

    {

        $this->index();

    }


    private function maintenance()
    {
        if (isset(SpamTrawler::$Registry['settings_core']['maintenance_status']) && SpamTrawler::$Registry['settings_core']['maintenance_status'] == '1') {

            switch (SpamTrawler::$Registry['settings_core']['maintenance_action']) {

                case 'exitmessage':

                    header(SpamTrawler_Http_StatusCodes::httpHeaderFor(503));

                    exit(SpamTrawler::$Registry['settings_core']['maintenance_exit_message']);

                case 'redirect':

                    header('Location: ' . SpamTrawler::$Registry['settings_core']['maintenance_redirection_target']);

                    exit();

                default:

                    header(SpamTrawler_Http_StatusCodes::httpHeaderFor(503));

            }

        }

    }
}