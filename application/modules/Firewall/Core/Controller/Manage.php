<?php

class Firewall_Core_Controller_Manage extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $aParams = array();
    public static $aErrors = array();
    private $aModules = array();
    private $aModulesCore = array();
    private $aModulesWhitelist = array();
    private $aModulesContentBlacklist = array();
    private $aModulesBlacklist = array();
    private $aModulesRemote = array();
    private $aStatsBlockCode = array();
    private $aStatsBlockedByFilter = array();

    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();

        //Init Filter Stats
    	$this->getStatsByFilter();
    }

    private function getStatsByFilter(){
    	if(empty($this->aStatsBlockedByFilter)){
    		$oTable = new SpamTrawler_Db_Tables_CacheVisitors();
    		$aFilterStats = $oTable->getStatsByFilter();

    		$iValueAll = 0;
    		foreach($aFilterStats as $key => $value){
    			$this->aStatsBlockedByFilter[$value['block_code']] = $value['total'];
    			$iValueAll = $iValueAll + $value['total'];
    		}
    		$this->aStatsBlockedByFilter['all'] = $iValueAll;
    		unset($aFilterStats);
    	}
    	return $this->aStatsBlockedByFilter;
    }

    public function index()
    {
      if(!isset($_SESSION['sUsername'])){
          header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Login'));
          exit();
      }

        //Save if request is POST
        if($_SERVER['REQUEST_METHOD'] === 'POST' && empty(self::$aErrors)){
            $this->save();
        }

      //$this->oSmarty->assign('core', SpamTrawler::$Registry->settings_core);
      //$this->oSmarty->assign('timezones', $this->getTimezones());
        if(!empty(self::$aErrors)){
            $this->oSmarty->assign('errors', self::$aErrors);
        }

        //Satellite status integration
        $sSatelliteStatus = 'online';
        $iSatelliteLastError = SpamTrawler::$Registry['oCache']->load('SatelliteLock');
        if($iSatelliteLastError !== false) {
            $sSatelliteStatus = 'offline';
        }

        $this->oSmarty->assign('SatelliteStatus', $sSatelliteStatus);
        $this->oSmarty->assign('SatelliteLastError', $iSatelliteLastError);

      //Get Modules
      $this->getModules('modules', 'firewall');

      $this->oSmarty->assign('modules_firewall_core', $this->aModulesCore);
      $this->oSmarty->assign('modules_firewall_whitelists', $this->aModulesWhitelist);
      $this->oSmarty->assign('modules_firewall_contentblacklists', $this->aModulesContentBlacklist);
      $this->oSmarty->assign('modules_firewall_blacklists', $this->aModulesBlacklist);
      $this->oSmarty->assign('modules_firewall_remote', $this->aModulesRemote);

        //$this->oSmarty->assign('modules_firewall', $this->getModules('modules', 'firewall'));
      $this->oSmarty->assign('params', $this->aParams);
      $this->oSmarty->assign('StatsBlockedByFilter', $this->aStatsBlockedByFilter);

      //Set active tab
      if(!isset($_GET['tab'])) {
      	$sTab = 'firewall';
      } else {
      	$sTab = $_GET['tab'];
      }

      $this->oSmarty->assign('tab', $sTab);

      //print "<pre>";
      //print_r($this->aParams);
      //print "</pre>";

        //print_r($this->aParams);
        //exit();

      $this->oSmarty->display('Manage.tpl');
    }

    public function save(){
        //$debug = new SpamTrawler_Debug();
        //$debug->mData = $_POST;
        //$debug->bExit = true;
        //$debug->show();
        //exit(json_encode($_POST));

            $oTable = new SpamTrawler_Db_Tables_Settings();

        //$_POST['firewall_core']['conf_params']['usernamefields'] = $_POST['username_blacklist_filter']['conf_params']['usernamefields'];
        //$_POST['firewall_core']['conf_params']['emailfields'] = $_POST['email_blacklist_filter']['conf_params']['emailfields'];

            $i = 1;
            foreach($_POST as $key => $value){
                $sConfName = $key;

                //echo $key . ': ' . $value['conf_class_name'] . '<br />';
                $sModuleClass = str_replace('Controller_Filter', 'Model_Manage' , $value['conf_class_name']);
                $sModuleValidator = ('validateSettings');

                //Only proceed if module manager class has callable validate method
                if(method_exists($sModuleClass, $sModuleValidator) && is_callable(array($sModuleClass, $sModuleValidator))){
                   $bModuleValidator = call_user_func_array($sModuleClass . '::' . $sModuleValidator, array($value, $_POST['firewall_core']));

                    if($bModuleValidator === false){
                        /*
                         * Do Nothing in order for non erroneous settings to be saved
                         * Errors will be handled after loop
                         */
                    } else {
                        $sWhere = $oTable->getAdapter()->quoteInto('conf_name = ?', $sConfName);

                        if(array_key_exists('conf_params', $value))
                        {
                            $value['conf_params'] = serialize($value['conf_params']);
                        }

                        $value['conf_order'] = $i;

                        $oTable->update($value, $sWhere);
                    }
                }
                $i++;
            }

            //Delete Active Modules Cache
            SpamTrawler::$Registry['oCache']->remove('modules_firewall_core');
            SpamTrawler::$Registry['oCache']->remove('modules_firewall_contentblacklists');
            SpamTrawler::$Registry['oCache']->remove('modules_firewall_blacklists');
            SpamTrawler::$Registry['oCache']->remove('modules_firewall_whitelists');
            SpamTrawler::$Registry['oCache']->remove('modules_firewall_remote');

            if(!empty(self::$aErrors)){
                $sErrors = '';
                foreach(self::$aErrors as $key => $value){
                   $sErrors .= $value . '<br />';
                }
                exit($sErrors);
                //Show index with errors
                //$this->index();
            } else {
                //Back to view after saving
                //header('Location: ' . SpamTrawler_Url::MakeFriendly('Firewall/Core/Manage'));
                exit('ok');
            }
    }

    private function getModules($sConfGroup, $sConfModule)
    {
        //Get Modules
        $oTable = new SpamTrawler_Db_Tables_Settings();

        $sql = $oTable->select()
            ->where('conf_group = ?', $sConfGroup)
            ->where('conf_module = ?', $sConfModule)
            ->order(array('conf_group_order ASC' , 'conf_order ASC'));
        ;

        $aRows = $oTable->fetchAll($sql);

        $aModules = array();
        $aModulesCore = array();
        $aModulesWhitelist = array();
        $aModulesContentBlacklist = array();
        $aModulesBlacklist = array();
        $aModulesRemote = array();
        foreach ($aRows as $row) {
            $sTemplatePath = str_replace('_Controller_Filter', '', $row->conf_class_name);
            $sTemplatePath = str_replace('_', DIRECTORY_SEPARATOR, $sTemplatePath);

            //Prepare config parameters for modules
            if(!is_null($row->conf_params)){
                $this->aParams[$row->conf_name] = unserialize($row->conf_params);
            }
            //Set Status
            $this->aParams[$row->conf_name]['status'] = $row->conf_status;

            //Set Class Name
            $this->aParams[$row->conf_name]['conf_class_name'] = $row->conf_class_name;

            //Set Order
            $this->aParams[$row->conf_name]['order'] = $row->conf_order;

            //Prepare config parameters for modules
            if($row->conf_category === 'whitelist'){
                $aModulesWhitelist[$row->conf_class_name] = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $sTemplatePath . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template . DIRECTORY_SEPARATOR . 'Settings.tpl';
            } elseif ($row->conf_category === 'contentblacklist'){
                $aModulesContentBlacklist[$row->conf_class_name] = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $sTemplatePath . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template . DIRECTORY_SEPARATOR . 'Settings.tpl';
            }elseif ($row->conf_category === 'blacklist'){
                $aModulesBlacklist[$row->conf_class_name] = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $sTemplatePath . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template . DIRECTORY_SEPARATOR . 'Settings.tpl';
            }elseif ($row->conf_category === 'remote'){
                $aModulesRemote[$row->conf_class_name] = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $sTemplatePath . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template . DIRECTORY_SEPARATOR . 'Settings.tpl';
            }elseif ($row->conf_category === 'core'){
                $aModulesCore[$row->conf_class_name] = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $sTemplatePath . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template . DIRECTORY_SEPARATOR . 'Settings.tpl';
            }
            //$aModules[$row->conf_class_name] = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $sTemplatePath . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . TRAWLER_TEMPLATE . DIRECTORY_SEPARATOR . 'Settings.tpl';
        }

        $this->aModules = array_merge($aModulesCore, $aModulesWhitelist, $aModulesBlacklist, $aModulesRemote);
        $this->aModulesCore = $aModulesCore;
        $this->aModulesWhitelist = $aModulesWhitelist;
        $this->aModulesContentBlacklist = $aModulesContentBlacklist;
        $this->aModulesBlacklist = $aModulesBlacklist;
        $this->aModulesRemote = $aModulesRemote;

        //return $aModules;
    }
}
