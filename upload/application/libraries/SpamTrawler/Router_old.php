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
        $this->index();
    }

    private function index($sRoute = null){
        //Add get request parameter to SpamTrawler Registry
        SpamTrawler::$Registry->requestparams = json_decode(json_encode($_GET), FALSE);

        $oSession = new SpamTrawler_Session();
        $oSession->start();

        if (SpamTrawler::$Registry->core->friendlyurl != 0) {
            $requestURI = explode('?', $_SERVER['REQUEST_URI']);
            $requestURI = explode('/', $requestURI[0]);
        } else {
            if (isset(SpamTrawler::$Registry->requestparams->do) && !empty(SpamTrawler::$Registry->requestparams->do)) {
                $requestURI = explode('/', SpamTrawler::$Registry->requestparams->do);
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
        if(isset($command[0])){
            $this->sModule = $command[0];
        }

        if(isset($command[1])){
            $this->sSubModule = $command[1];
        }
        if(isset($command[2])){
            $this->sController = $command[2];
        }
        if(isset($command[3])){
            $this->sMethod = $command[3];
        }

        $i = 0;
        $sController = $this->sModule . '_' . $this->sSubModule . '_Controller_' . $this->sController;

        $sCoreTemplate = SpamTrawler::$Registry->core->template;
        $sTemplatePath = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $this->sModule . DIRECTORY_SEPARATOR . $this->sSubModule . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Registry->core->template;

        //Get template engine
        $oSmarty = SpamTrawler::getSmarty();
        $oSmarty->setTemplateDir($sTemplatePath);
        $oSmarty->assign('token', $_SESSION['sToken']);
        $oSmarty->assign('core_template', $sCoreTemplate);

        $sCoreLanguage = SpamTrawler::$Registry->core->language;

        if (!file_exists(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $sCoreLanguage . '.ini')) {
            $sCoreLanguage = 'en_EN';
        }
        $oSmarty->assign('language', parse_ini_file(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $sCoreLanguage . '.ini', true));

        if (class_exists($sController)) {
            $oController = new $sController;
            if (!isset($command[3]) || empty($command[3])) {
                $sAction = 'index';
            } else {
                $sAction = $command[3];
            }

            if (method_exists($oController, $sAction)) {
                $oController->$sAction();
            } else {
                $this->Error('not_found', $command[0]);
            }

        } else {
            $this->Error('not_found', $command[0]);
        }
    }

    private function defaultRoutes()
    {
        $aRoutes = array(
            'Admin' => '/Dashboard/View',
            'Install' => '/Setup/View',
            'Firewall' => '/Core/Filter',
        );

        if (array_key_exists($this->sModule, $aRoutes)) {
            header('location: ' . dirname($_SERVER['PHP_SELF']) . '/' .  $this->sModule . $aRoutes[$this->sModule]);
            return true;
        }
        return false;
    }

    private function Error($sError, $sModule)
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