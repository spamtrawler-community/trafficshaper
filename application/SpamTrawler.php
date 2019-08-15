<?php

class SpamTrawler
{
    public static $Config;
    public static $Registry;
    public static $iAllowedFiltersFree = 1;

    public function __construct()
    {
        //Set Error Reporting
        //try {
            //error_reporting(TRAWLER_ERROR_REPORTING);
            //ini_set('display_errors', TRAWLER_DISPLAY_ERRORS);
            //ini_set('log_errors', TRAWLER_LOG_ERRORS);
        //} catch (Exception $e) {
            //Do nothing
        //}

        //Set Exception Handler
        set_exception_handler(array("SpamTrawler", "handleException"));

        //Set IncludePath
        set_include_path(TRAWLER_PATH_LIBRARIES . PATH_SEPARATOR . TRAWLER_PATH_APPLICATION . PATH_SEPARATOR . TRAWLER_PATH_MODULES . PATH_SEPARATOR . get_include_path());

        require(TRAWLER_PATH_LIBRARIES . DIRECTORY_SEPARATOR . 'SpamTrawlerX' . DIRECTORY_SEPARATOR . 'Loader/StandardAutoloader.php');
        //$oAutoloader = SpamTrawler_Loader_Autoloader::getInstance();
        //Get Composer Autoloader
        require(TRAWLER_PATH_LIBRARIES . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

        /*
        * Set Global Settings
        */
        try {
            //Add Autoloader Object to registry
            $aConfigAutoloader = array();
            require(TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config_autoloader.php');
            try{
                self::$Registry['oAutoloader'] = new SpamTrawlerX_Loader_StandardAutoloader($aConfigAutoloader);
                self::$Registry['oAutoloader']->register();
            } catch (Exception $e){
                exit('Invalid Autoloader Configuration File (config_autoloader.php) detected!');
            }

            try{
                $config = new SpamTrawler_Config(require TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config.php', true);
                self::$Config = $config;
            } catch (Exception $e){
                exit('Invalid Configuration File (config.php) detected!');
            }

            //Setup Cache
            $this->initCache();

            if (self::$Config->database->adapter) {
                //Setup Database in case needed
                $this->_initDatabase();

                //Set Core Settings
                $this->getCoreSettings();

                //Set visitor details
                SpamTrawler_VisitorDetails::set();
            }
        } catch (Exception $e) {
            exit($e);
        }
    }

    public static function getSmarty()
    {
        if (!isset(self::$Registry['oSmarty'])) {
            define('SMARTY_SPL_AUTOLOAD', 1);
            //include(TRAWLER_PATH_SMARTY . DIRECTORY_SEPARATOR . 'Smarty.class.php');
            $oSmarty = new Smarty;
            $oSmarty->setCompileDir(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'templates_c');

            //Assign Version number for footer globally
            $oSmarty->assign('config', self::$Config->core);
            $oSmarty->assign('path_modules', TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR);
            $oSmarty->assign('poweredby', SpamTrawler_Version::getPoweredBy());
            $oSmarty->assign('copyright', SpamTrawler_Version::getCopyright());
            $oSmarty->assign('visitorip', SpamTrawler_VisitorDetails_IP_IP::get());
            $oSmarty->assign('requestprotocol', SpamTrawler_Http_Protocol::get());

            //Register Smarty Object in Registry
            self::$Registry['oSmarty'] = $oSmarty;
            spl_autoload_register('smartyAutoload');
        }
        return self::$Registry['oSmarty'];
    }

    public static function getHTMLPurifier()
    {
        if (!self::$Registry['oHtmlpurifier']) {
            //require TRAWLER_PATH_LIBRARIES . '/htmlpurifier/HTMLPurifier.auto.php';
            //require TRAWLER_PATH_LIBRARIES . '/htmlpurifier/HTMLPurifier.includes.php';
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Cache', 'SerializerPath', TRAWLER_PATH_FILES . '/cache');
            $config->set('Core', 'EscapeInvalidTags', true);
            self::$Registry['oHtmlpurifier'] = new HTMLPurifier($config);
        }

        return self::$Registry['oHtmlpurifier'];
    }

    protected function initCache()
    {
        try {
            // getting a SpamTrawler_Cache_Core object
            $oCache = SpamTrawler_Cache::factory('Core',
                self::$Config->cache->backend,
                self::$Config->cache->frontend_options->toArray(),
                self::$Config->cache->backend_options->toArray()
            );
        } catch (Exception $e) {
            $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log/error.log');
            $logger = new SpamTrawler_Log($writer);

            $logger->warn($e);

            /*
            if(!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache')){
                die('Cache directory not writeable!');
            }*/

            $oCache = SpamTrawler_Cache::factory('Core', 'BlackHole', array(), array());
            //exit('Unable to connect to cache server!');
        }
        //Add Core settings to SpamTrawler Registry
        self::$Registry['oCache'] = $oCache;
    }

    protected function _initDatabase()
    {
        $oDb = SpamTrawler_Db::factory(self::$Config->database->adapter, self::$Config->database->params);

        //set default adapter
        SpamTrawler_Db_Table::setDefaultAdapter($oDb);
        $this->_setMetaDataCache();

        //save Db in registry for later use
        self::$Registry['oDb'] = $oDb;
    }

    public static function setPath($sIndex, $mValue)
    {
        self::$Registry['$sIndex'] = $mValue;
    }

    protected function _setMetaDataCache()
    {
        $backendOptions = self::$Config->cache->backend_options->toArray();

        if (isset($backendOptions['cache_dir'])) {
            $backendOptions['cache_dir'] = $backendOptions['cache_dir'] . '/tablemeta';
        }

        try {
            $cache = SpamTrawler_Cache::factory('Core',
                self::$Config->cache->backend,
                self::$Config->cache->frontend_options->toArray(),
                $backendOptions
            );
        } catch (Exception $e) {
            $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log/error.log');
            $logger = new SpamTrawler_Log($writer);

            $logger->warn($e);

            $cache = SpamTrawler_Cache::factory('Core', 'BlackHole', array(), array());
            //exit('Unable to connect to cache server!');
        }
        // Next, set the cache to be used with all table objects
        SpamTrawler_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }

    private function getCoreSettings($bRefresh = false)
    {
        if (!isset(self::$Registry['settings_core']) || $bRefresh === true) {

            $aSettingsCore = self::$Registry['oCache']->load('settings_core');

            if (!$aSettingsCore) {
                $oSettings = new SpamTrawler_Db_Tables_Settings();

                $sql = $oSettings->select()
                    ->where('conf_group = ?', 'core');

                $aRows = $oSettings->fetchAll($sql)->toArray();

                //Normalize to Array to access settings by name
                $aRows['0']['conf_params'] = unserialize($aRows['0']['conf_params']);
                self::$Registry['oCache']->save($aRows['0']['conf_params'], 'settings_core');
                $aSettingsCore = $aRows['0']['conf_params'];
            }

            //Add Core settings to SpamTrawler Registry
            self::$Registry['settings_core'] = json_decode(json_encode($aSettingsCore), TRUE);

            //set timezone
            date_default_timezone_set(self::$Registry['settings_core']['timezone']);
        }
    }

    public static function handleException(Exception $e)
    {
        $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log/error.log');
        $logger = new SpamTrawler_Log($writer);

        $logger->warn($e);

        exit('An unknown error occurred!' . $e);
    }
}
