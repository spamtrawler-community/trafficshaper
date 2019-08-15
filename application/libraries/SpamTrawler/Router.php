<?php

/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 16/06/14
 * Time: 22:29
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Router
{
    private $sModule = null;
    private $sSubModule = null;
    private $sController = null;
    private $sMethod = null;
    private $sRoute = null;

    public function __construct()
    {
        if(file_exists(TRAWLER_PATH_ROOT . DIRECTORY_SEPARATOR . 'autologin.php')){
            exit('<h1>Autologin script present!</h1><br /><strong>Please follow the steps below:</strong><br /><ul><li>Remove the file ' . TRAWLER_PATH_ROOT . DIRECTORY_SEPARATOR . 'autologin.php</li><li>Refresh your browser</li></ul>');
        }

        $oSession = new SpamTrawler_Session();
        $oSession->start();

        $this->route();
    }

    private function route($sRoute = null)
    {
        if (isset($_GET['trawlerdevmode'])) {
            $_SESSION['devmode'] = true;
        }

        //Add get request parameter to SpamTrawler Registry
        SpamTrawler::$Registry['requestparams'] = json_decode(json_encode($_GET), FALSE);

        //Parse URL
        if (is_null($sRoute)) {
            $this->parseURL();
        }

        $sController = $this->sModule . '_' . $this->sSubModule . '_Controller_' . $this->sController;

        //Prepare Template Engine
        $this->prepareTemplateEngine();

        if (!empty($this->sSubModule) && !empty($this->sController) && class_exists($sController)) {
            $oController = new $sController;
            if (is_null($this->sMethod) || empty($this->sMethod)) {
                $sAction = 'index';
            } else {
                $sAction = $this->sMethod;
            }

            if (method_exists($oController, $sAction)) {
                $oController->$sAction();
                exit();
            } else {
                $this->Error('not_found');
            }

        } else {
            $this->Error('not_found');
        }
    }

    private function parseURL()
    {
        if (SpamTrawler::$Config->core->friendlyurl != 0) {
            $requestURI = explode('?', $_SERVER['REQUEST_URI']);
            $requestURI = explode('/', $requestURI[0]);
        } else {
            if (isset(SpamTrawler::$Registry['requestparams']['do']) && !empty(SpamTrawler::$Registry['requestparams']['do'])) {
                $requestURI = explode('/', SpamTrawler::$Registry['requestparams']['do']);
            } else {
                exit('Invalid Route!');
            }
        }

        $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

        for ($i = 0; $i < sizeof($scriptName); $i++) {
            if ($requestURI[$i] == $scriptName[$i]) {
                unset($requestURI[$i]);
            }
        }

        $command = array_values($requestURI);

        //Set Path Vars
        if (isset($command[0])) {
            $this->sModule = $command[0];
        }

        if (isset($command[1])) {
            $this->sSubModule = $command[1];
        }
        if (isset($command[2])) {
            $this->sController = $command[2];
        }
        if (isset($command[3])) {
            $this->sMethod = $command[3];
        }
    }

    private function prepareTemplateEngine()
    {
        $sCoreTemplate = SpamTrawler::$Config->core->template;
        $sTemplatePath = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $this->sModule . DIRECTORY_SEPARATOR . $this->sSubModule . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template;

        //Get template engine
        $oSmarty = SpamTrawler::getSmarty();
        $oSmarty->setTemplateDir($sTemplatePath);
        $oSmarty->assign('token', $_SESSION['sToken']);
        $oSmarty->assign('core_template', $sCoreTemplate);

        if (isset($_SESSION['devmode'])) {
            $oSmarty->assign('devmode', true);
        }

        $sCoreLanguage = SpamTrawler::$Config->core->language;

        if (!file_exists(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $sCoreLanguage . '.ini')) {
            $sCoreLanguage = 'en_EN';
        }
        $oSmarty->assign('language', parse_ini_file(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $sCoreLanguage . '.ini', true));

        return true;
    }

    private function defaultRoutes()
    {
        $aRoutes = array(
            'Admin' => '/Dashboard/View',
            'Install' => '/Setup/View',
            'Update' => '/Setup/View',
            'Firewall' => '/Core/Filter',
        );

        if (array_key_exists($this->sModule, $aRoutes)) {
            //Set Path Vars
            $aRouteParts = array_filter(explode('/', $aRoutes[$this->sModule]));

            $this->sSubModule = $aRouteParts[1];
            $this->sController = $aRouteParts[2];

            if (isset($aRouteParts[3])) {
                $this->sMethod = $aRouteParts[3];
            }

            if (class_exists($this->sModule . '_' . $this->sSubModule . '_Controller_' . $this->sController)) {
                $this->route(true);
                //header('location: ' . dirname($_SERVER['PHP_SELF']) . '/' .  $this->sModule . $aRoutes[$this->sModule]);
                return true;
            }
        }
        return false;
    }

    private function Error($sError)
    {
        if ($this->defaultRoutes() === false) {
            switch ($sError) {
                case 'not_found':
                    header("HTTP/1.0 404 Not Found");
                    exit('Invalid Route!');
                    break;
            }
        }
    }
}