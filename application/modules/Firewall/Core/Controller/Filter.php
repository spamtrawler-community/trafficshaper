<?php
class Firewall_Core_Controller_Filter
{
    public $aFirewallSettings = NULL;
    public static $bVisitorIsCached = false;
    public static $bLog = true;
    public static $bIsWhitelisted = false;

    public function __construct()
    {
        //Get Firewall Settings
        $this->aFirewallSettings = Firewall_Core_Helper_Settings::getSettings();

        if ($this->aFirewallSettings['conf_params']['mode'] === 'server') {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                exit(json_encode(array('status' => 'rejected', 'statuscode' => 403, 'error' => 'Invalid Request Type!')));
            } elseif (!isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['apikey']) || $_POST[SpamTrawler::$Config->firewall->apiparameter]['apikey'] !== $this->aFirewallSettings['conf_params']['apikey']) {
                exit(json_encode(array('status' => 'rejected', 'statuscode' => 403, 'error' => 'Invalid API Key!')));
            }
        }
    }

    public function index()
    {
        if (is_array($this->aFirewallSettings) && $this->aFirewallSettings['conf_status'] == 1) {
            if ($this->aFirewallSettings['conf_params']['filter_post_only'] == 0 ||
                ($this->aFirewallSettings['conf_params']['filter_post_only'] == 1 && $_SERVER['REQUEST_METHOD'] === 'POST')
            ) {
                //Check if visitor has already been cached
                if ($this->aFirewallSettings['conf_params']['visitorcache_status'] == 1) {
                    $oVisitordetailsCache = SpamTrawler::$Registry['oCache']->load(sha1(SpamTrawler::$Registry['visitordetails']['ip']));
                    if ($oVisitordetailsCache !== false) {
                        self::$bVisitorIsCached = true;
                        SpamTrawler::$Registry['visitordetails'] = $oVisitordetailsCache;
                    }
                }

                //Check if Visitor is logged into SpamTrawler admin panel
                if (SpamTrawler::$Registry['settings_core']['sysmode'] == 1 || (SpamTrawler::$Registry['settings_core']['sysmode'] == 0 && (!isset(SpamTrawler::$Registry['visitordetails']['isAdmin']) || SpamTrawler::$Registry['visitordetails']['isAdmin'] === 'no'))) {
                    //Get Additional Visitor Details
                    $oDetails = new Firewall_Core_Helper_GetVisitorDetails($this->aFirewallSettings);
                    $oDetails->setDetails();

                    //Run Content Blacklists
                    if (self::$bVisitorIsCached === false || (self::$bVisitorIsCached === true && SpamTrawler::$Registry['visitordetails']['whitelisted'] !== 'yes')) {
                        $this->runContentBlacklists();
                    }

                    //Check if visitor has been prermitted before
                    if (self::$bVisitorIsCached === true && SpamTrawler::$Registry['visitordetails']['filterresult'] === 'passed') {
                        $this->responsePermitted();
                    } else {
                        //Run Whitelists
                        $this->runWhitelists();

                        //Run Remote lists if we don't have a filter result
                        if (self::$bIsWhitelisted === false) {
                            if (!isset(SpamTrawler::$Registry['visitordetails']['filterresult']) || is_null(SpamTrawler::$Registry['visitordetails']['filterresult'])) {
                                $this->runBlacklists();
                                $this->runRemoteServices();
                            } else {
                                self::$bVisitorIsCached = true;
                                if (SpamTrawler::$Registry['visitordetails']['filterresult'] === 'blocked') {
                                    $this->repsonseBlocked();
                                } else {
                                    $this->responsePermitted();
                                }
                            }
                        }
                    }
                }
            }
        } elseif($this->aFirewallSettings['conf_status'] == 1 && $this->aFirewallSettings['conf_params']['mode'] === 'server') {
            header("HTTP/1.0 503 Service Unavailable");
            exit();
        }
    }

    public function runContentBlacklists()
    {
        //check if cookie is set
        $mCookie = $this->getCookie();

            if ($mCookie === $this->aFirewallSettings['conf_params']['cookie_blocked_value']) {
                //Settings details for blocked visitor
                SpamTrawler::$Registry['visitordetails']['filterresult'] = 'blocked';
                SpamTrawler::$Registry['visitordetails']['filterclass'] = __CLASS__;
                SpamTrawler::$Registry['visitordetails']['blockreason'] = 'Cookie';
                $this->repsonseBlocked();
            } else {
                $this->filter('modules_firewall_contentblacklists', 'contentblacklist');
            }
    }

    public function runWhitelists()
    {
        //Run Whitelists
        $this->filter('modules_firewall_whitelists', 'whitelist');

        if (self::$bIsWhitelisted === true) {
            //Set filterresult to passed
            SpamTrawler::$Registry['visitordetails']['filterresult'] = 'passed';
            //$this->showOutput();
        }
    }

    public function runBlacklists()
    {
        //check if cookie is set
        $mCookie = $this->getCookie();

        if ($this->aFirewallSettings['conf_params']['mode'] === 'server' || $mCookie === FALSE) {
            //Run Blacklists if we don't have a filter result
            $this->filter('modules_firewall_blacklists', 'blacklist');

            //Set filterresult to passed
            //SpamTrawler::$Registry['visitordetails']['filterresult'] = 'passed';
            //$this->showOutput();
        } else {
            if ($mCookie === $this->aFirewallSettings['conf_params']['cookie_blocked_value']) {
                //Settings details for blocked visitor
                SpamTrawler::$Registry['visitordetails']['filterresult'] = 'blocked';
                SpamTrawler::$Registry['visitordetails']['filterclass'] = __CLASS__;
                SpamTrawler::$Registry['visitordetails']['blockreason'] = 'Cookie';
                $this->repsonseBlocked();
            } else {
                $this->responsePermitted();
            }
        }
    }

    public function runRemoteServices()
    {
        //check if cookie is set
        $mCookie = $this->getCookie();

        if ($this->aFirewallSettings['conf_params']['mode'] === 'server' || $mCookie === FALSE) {
            //Run Remote lists if we don't have a filter result
            $this->filter('modules_firewall_remote', 'remote');

            //Set filterresult to passed
            SpamTrawler::$Registry['visitordetails']['filterresult'] = 'passed';
            $this->showOutput();
        } else {
            if ($mCookie === $this->aFirewallSettings['conf_params']['cookie_blocked_value']) {
                //Settings details for blocked visitor
                SpamTrawler::$Registry['visitordetails']['filterresult'] = 'blocked';
                SpamTrawler::$Registry['visitordetails']['filterclass'] = __CLASS__;
                SpamTrawler::$Registry['visitordetails']['blockreason'] = 'Cookie';
                $this->repsonseBlocked();
            } else {
                $this->responsePermitted();
            }
        }
    }

    private function getModules($sCacheFileName, $sConfCategory)
    {
        if (!$aRows = SpamTrawler::$Registry['oCache']->load($sCacheFileName)) {
            $oSettings = new SpamTrawler_Db_Tables_Settings();

            $sql = $oSettings->select()
                ->where('conf_group = ?', 'modules')
                ->where('conf_module = ?', 'firewall')
                ->where('conf_category = ?', $sConfCategory)
                ->where('conf_status = 1')
                ->order(array('conf_order ASC'));

            $aRows = $oSettings->fetchAll($sql);

            SpamTrawler::$Registry['oCache']->save($aRows, $sCacheFileName);
        }
        return $aRows;
    }

    public function filter($sCacheFileName, $sConfCategory)
    {
        $aModules = $this->getModules($sCacheFileName, $sConfCategory);


        foreach ($aModules as $module) {
            $sClass = $module->conf_class_name;

            //Check if module is active
            if ($module->conf_status == 1) {

                //Debug
                //echo $sClass . '<br />';

                if (class_exists($sClass)) {
                    $oClass = new $sClass($module->conf_params, $this->aFirewallSettings);

                    if (method_exists($oClass, 'filter')) {
                        $oClass->filter();
                    } else {
                        exit('Method: "filter" not found in class: ' . $sClass . ' !');
                    }
                } else {
                    exit('Firewall Class: ' . $sClass . ' does not exist!');
                }
            }

            if (isset(SpamTrawler::$Registry['visitordetails']['filterresult'])) {
                if (SpamTrawler::$Registry['visitordetails']['filterresult'] == 'whitelisted' || SpamTrawler::$Registry['visitordetails']['filterresult'] == 'blocked') {

                    //exit();
                    //Return firewall result
                    $this->showOutput();
                    break;
                }
            }
        }
    }

    public function logVisitor()
    {
        if (self::$bLog === true) {
            if (self::$bVisitorIsCached == false) {
                $oTable = new SpamTrawler_Db_Tables_CacheVisitors();
                $oTable->add();

                //Cache Visitor
                if (SpamTrawler::$Registry['visitordetails']['filterresult'] === 'blocked') {
                    $sStatusTag = 'blocked';
                } else {
                    $sStatusTag = 'unblocked';
                }

                if (self::$bIsWhitelisted === true) {
                    SpamTrawler::$Registry['visitordetails']['whitelisted'] = 'yes';
                } else {
                    SpamTrawler::$Registry['visitordetails']['whitelisted'] = 'no';
                }
                SpamTrawler::$Registry['visitordetails']['isAdmin'] = 'no';
                SpamTrawler::$Registry['oCache']->save(SpamTrawler::$Registry['visitordetails'], sha1(SpamTrawler::$Registry['visitordetails']['ip']), array('ipcache', $sStatusTag));
            }
        }
    }

    public function setCookie()
    {
        if ($this->aFirewallSettings['conf_params']['cookie_status'] == 1) {

            $sName = $this->aFirewallSettings['conf_params']['cookie_name'];
            $iExpiry = time() + $this->aFirewallSettings['conf_params']['cookie_expiry'] * 3600;
            $sPath = $this->aFirewallSettings['conf_params']['cookie_path'];
            $sDomain = preg_replace('/www/', '', $_SERVER['HTTP_HOST']); //$this->aFirewallSettings['conf_params']['cookie_domain'];
            $bSecure = null;
            $bHttponly = true;

            if (SpamTrawler::$Registry['visitordetails']['filterresult'] === 'blocked') {
                $aValue = $this->aFirewallSettings['conf_params']['cookie_blocked_value'];
            } else {
                $aValue = $this->aFirewallSettings['conf_params']['cookie_permitted_value'];
            }

            setcookie($sName, sha1(md5(strrev($aValue))), $iExpiry, $sPath, $sDomain, $bSecure, $bHttponly);
            //setcookie( $sName, $aValue, $iExpiry, $sPath, $sDomain, $bSecure, $bHttponly );
        }
    }

    public function getCookie()
    {
        //var_dump((int) $this->aFirewallSettings['conf_params']['cookie_status']);
        //exit();

        if ((int)$this->aFirewallSettings['conf_params']['cookie_status'] == 0) {
            return false;
        } else {
            $sCookieName = $this->aFirewallSettings['conf_params']['cookie_name'];

            if (isset($_COOKIE[$sCookieName])) {
                switch ($_COOKIE[$sCookieName]) {
                    case $_COOKIE[$sCookieName] === sha1(md5(strrev($this->aFirewallSettings['conf_params']['cookie_permitted_value']))):
                        return $this->aFirewallSettings['conf_params']['cookie_permitted_value'];
                    default:
                        return $this->aFirewallSettings['conf_params']['cookie_blocked_value']; //Cookie has been tampered with
                }
            }
            return false;
        }
    }

    public function showOutput()
    {
        if (SpamTrawler::$Registry['visitordetails']['filterresult'] && SpamTrawler::$Registry['visitordetails']['filterresult'] === 'blocked') {
            $this->repsonseBlocked();
        } else {
            $this->responsePermitted();
        }
    }

    /*
     * Response Methods
     */
    private function repsonseBlocked()
    {
        //inet_pton is not utf8 compatible due to being binary
        //unset(SpamTrawler::$Registry->visitordetails->inet_pton);

        //Log Visitor in database
        $this->logVisitor();

        //Server always returns json response
        if ($this->aFirewallSettings['conf_params']['mode'] === 'server') {
            $this->aFirewallSettings['conf_params']['block_action'] = 'json';
        } else {
            //Servermode does not set cookies
            $this->setCookie();
        }

        switch ($this->aFirewallSettings['conf_params']['block_action']) {
            case 'accessdenied':
                header('HTTP/1.0 403 Forbidden');
                exit('You are not allowed to access this file.');
                break;

            case 'redirect':
                header('Location: ' . $this->aFirewallSettings['conf_params']['redirection_target']);
                exit();
                break;

            case 'returntosender':
                header('Location: http://' . SpamTrawler_VisitorDetails_IP_IP::get());
                exit();
                break;

            case 'exitmessage':
                header('HTTP/1.0 403 Forbidden');

                if (!isset($_POST['g-recaptcha-response'])) {
                    if (strpos($this->aFirewallSettings['conf_params']['exit_message'], '%Captcha%') !== FALSE) {
                        if (SpamTrawler::$Registry['visitordetails']['allowcaptcha'] === TRUE) {
                            $oCaptcha = new Firewall_ReCaptcha_Controller_View();
                            $sCaptcha = $oCaptcha->index('fetch');


                            $this->aFirewallSettings['conf_params']['exit_message'] = str_replace('%Captcha%', $sCaptcha, $this->aFirewallSettings['conf_params']['exit_message']);
                        } else {
                            $this->aFirewallSettings['conf_params']['exit_message'] = str_replace('%Captcha%', '', $this->aFirewallSettings['conf_params']['exit_message']);
                        }
                    }
                } else {
                    $oCaptcha = new Firewall_ReCaptcha_Controller_View();
                    $oCaptcha->validate();
                }

                //Show IP Placeholder
                if (strpos($this->aFirewallSettings['conf_params']['exit_message'], '%VisitorIP%') !== FALSE) {
                    $this->aFirewallSettings['conf_params']['exit_message'] = str_replace('%VisitorIP%', SpamTrawler::$Registry['visitordetails']['ip'], $this->aFirewallSettings['conf_params']['exit_message']);
                }

                //Show Block Reason Placeholder
                if (strpos($this->aFirewallSettings['conf_params']['exit_message'], '%BlockReason%') !== FALSE) {
                    $this->aFirewallSettings['conf_params']['exit_message'] = str_replace('%BlockReason%', SpamTrawler::$Registry['visitordetails']['blockreason'], $this->aFirewallSettings['conf_params']['exit_message']);
                }

                //Show Block Time Placeholder
                if (strpos($this->aFirewallSettings['conf_params']['exit_message'], '%BlockedTime%') !== FALSE) {
                    $this->aFirewallSettings['conf_params']['exit_message'] = str_replace('%BlockedTime%', date('m/d/Y h:i:s a', time()), $this->aFirewallSettings['conf_params']['exit_message']);
                }

                //exit($this->aFirewallSettings['conf_params']['exit_message']);

                //Get template engine
                $sTemplatePath = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Firewall' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template;
                $oSmarty = SpamTrawler::getSmarty();
                $oSmarty->setTemplateDir($sTemplatePath);
                $oSmarty->assign('VisitorDetails', (array)SpamTrawler::$Registry['visitordetails']);
                $oSmarty->assign('ExitMessage', $this->aFirewallSettings['conf_params']['exit_message']);
                $oSmarty->display('ExitMessage.tpl');

                exit();
                break;

            case 'json':
                $this->returnJson();
                break;

            default:
                $this->returnJson();
                break;
        }
    }

    private function responsePermitted()
    {
        //Log Visitor in database
        $this->logVisitor();

        //Server always returns json response
        if ($this->aFirewallSettings['conf_params']['mode'] === 'server') {
            $this->aFirewallSettings['conf_params']['block_action'] = 'json';
        } else {
            //Servermode does not set cookies
            $this->setCookie();
        }

        switch ($this->aFirewallSettings['conf_params']['block_action']) {
            case 'accessdenied':
                break;

            case 'redirect':
                break;

            case 'returntosender':
                break;

            case 'exitmessage':
                break;

            case 'json':
                $this->returnJson();
                break;

            default:
                $this->returnJson();
                break;
        }
    }

    private function returnJson()
    {
        unset(SpamTrawler::$Registry['visitordetails']['ip']);
        unset(SpamTrawler::$Registry['visitordetails']['iplong']);
        unset(SpamTrawler::$Registry['visitordetails']['inet_pton']);
        unset(SpamTrawler::$Registry['visitordetails']['isipv6']);
        unset(SpamTrawler::$Registry['visitordetails']['path']);
        unset(SpamTrawler::$Registry['visitordetails']['url']);
        unset(SpamTrawler::$Registry['visitordetails']['filterclass']);
        unset(SpamTrawler::$Registry['visitordetails']['useragent']);
        unset(SpamTrawler::$Registry['visitordetails']['referrer']);
        unset(SpamTrawler::$Registry['visitordetails']['email']);
        unset(SpamTrawler::$Registry['visitordetails']['username']);
        unset(SpamTrawler::$Registry['visitordetails']['isAdmin']);

        $json = SpamTrawler_Json_Encoder::encode((array)SpamTrawler::$Registry['visitordetails']);
        header('Content-Type: application/json');
        exit(utf8_encode($json));
    }
}
