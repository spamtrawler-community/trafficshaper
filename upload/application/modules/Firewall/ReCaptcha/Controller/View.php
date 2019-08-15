<?php

class Firewall_ReCaptcha_Controller_View extends SpamTrawler_BaseClasses_Modules_Controller {
    private $sTemplatePath;
    private $aFirewallSettings;
    private $bVisitorBlocked = FALSE;
    private $aAvailableLanguages = array(
        'ar', 'bg', 'ca', 'zh-CN', 'zh-TW', 'hr', 'cs', 'da', 'nl', 'en-GB', 'en', 'fil', 'fi', 'fr',
        'fr-CA', 'de', 'de-AT', 'de-CH', 'el', 'iw', 'hi', 'hu', 'id', 'it', 'ja', 'ko', 'lv', 'lt',
        'no', 'fa', 'pl', 'pt', 'pt-BR', 'pt-PT', 'ro', 'ru', 'sr', 'sk', 'sl', 'es', 'es-419', 'sv',
        'th', 'tr', 'uk', 'vi'
    );

    //Exclude Captcha from maintenance
    protected $bExcludeFromMaintenance = TRUE;

    public function __construct($sMode = 'display'){
        $this->sTemplatePath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template);
        $this->aFirewallSettings = Firewall_Core_Helper_Settings::getSettings();
        parent::__construct();

        if($this->aFirewallSettings['conf_params']['visitorcache_status'] == 1){
            $oVisitordetailsCache = SpamTrawler::$Registry['oCache']->load(sha1(SpamTrawler::$Registry['visitordetails']['ip']));
            if($oVisitordetailsCache !== false){
                SpamTrawler::$Registry['visitordetails'] = $oVisitordetailsCache;
            }
        }

        if (isset(SpamTrawler::$Registry['visitordetails']['filterresult']) || !is_null(SpamTrawler::$Registry['visitordetails']['filterresult'])) {
            if(SpamTrawler::$Registry['visitordetails']['filterresult'] == 'blocked') {
                $this->bVisitorBlocked = TRUE;
                if (isset($_POST['g-recaptcha-response'])) {
                    $this->validate($sMode);
                    exit();
                }
            }
        }
    }

    public function index($sMode = 'display'){
        if($this->bVisitorBlocked === TRUE){
            if($this->aFirewallSettings['conf_params']['recaptcha_language'] == 'auto'){
                $aLanguages = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $aLanguage = explode(';', $aLanguages[0]);

                if(in_array($aLanguage[0], $this->aAvailableLanguages)){
                    $sLanguage = $aLanguage[0];
                } else {
                    $sLanguage = 'en';
                }
            } else {
                $sLanguage = $this->aFirewallSettings['conf_params']['recaptcha_language'];
            }

            $this->oSmarty->assign('url', SpamTrawler_VisitorDetails::getUrl());
            $this->oSmarty->assign('sitekey', $this->aFirewallSettings['conf_params']['recaptcha_sitekey']);
            $this->oSmarty->assign('captchalang', $sLanguage);

            if($sMode == 'display'){
                $this->oSmarty->display($this->sTemplatePath . DIRECTORY_SEPARATOR . 'Captcha.tpl');
            } else {
                return $this->oSmarty->fetch($this->sTemplatePath . DIRECTORY_SEPARATOR . 'Captcha.tpl');
            }
        } else {
            exit('Direct Access Denied!');
        }
    }

    public function validate(){
        $recaptcha = new \ReCaptcha\ReCaptcha($this->aFirewallSettings['conf_params']['recaptcha_secret']);

        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], SpamTrawler_VisitorDetails::getIP());

        $aErrors = $resp->getErrorCodes();

        if(!empty($aErrors)){
        	foreach($aErrors as $key => $value){
        		//logging error
        		$writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error.log');
        		$logger = new SpamTrawler_Log($writer);

        		$logger->warn('ReCaptcha Error: ' . $value);

        		/*
        		 * Fix Me
        		 * Add Admin notification
        		 */
        		exit('An Error Occurred with the Captcha Challenge!<br /><br />The administrator has been notified!');
        	}
        } else {
        	if ($resp->isSuccess()){
        		SpamTrawler::$Registry['visitordetails']['filterresult'] = 'passed';
        		SpamTrawler::$Registry['visitordetails']['filterclass'] = '';
        		SpamTrawler::$Registry['visitordetails']['captcha_solved'] = 'yes';
        		SpamTrawler::$Registry['visitordetails']['comment'] = SpamTrawler::$Registry['visitordetails']['blockreason'];
        		SpamTrawler::$Registry['visitordetails']['blockreason'] = '';

        		$data = array(
        				'id'                => md5(SpamTrawler_VisitorDetails_IP_IP::get()),
        				'blocked'           => 'no',
        				'block_reason'      => '',
        				'block_code'        => '',
        				'captcha_solved'    => SpamTrawler::$Registry['visitordetails']['captcha_solved'],
        				'comment'           => SpamTrawler::$Registry['visitordetails']['comment'],
        				'updated'           => date('Y-m-d H:i:s')
        		);

        		$oTable = new SpamTrawler_Db_Tables_CacheVisitors();
        		$where = $oTable->getAdapter()->quoteInto('id = ?', $data['id']);
        		$oTable->update($data, $where);

        		SpamTrawler::$Registry['oCache']->save(SpamTrawler::$Registry['visitordetails'], sha1(SpamTrawler::$Registry['visitordetails']['ip']), array('ipcache', 'unblocked'));
                header('Location: ' . SpamTrawler_VisitorDetails::getUrl());
        	}
        }
    }
}
