<?php

class Admin_Settings_Controller_Manage extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $aParams = array();
    private $aErrors = array();

    public function __construct()
    {
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        if (!array_key_exists('sUsername', $_SESSION)) {
            header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Login'));
            exit();
        }

        //Save if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save();
        }

        $this->oSmarty->assign('core', SpamTrawler::$Registry['settings_core']);
        //$this->oSmarty->assign('core', SpamTrawler::$aSettingsCore);
        $this->oSmarty->assign('timezones', $this->getTimezones());
        $this->oSmarty->assign('modules_firewall', $this->getModules('modules', 'firewall'));
        $this->oSmarty->assign('params', $this->aParams);

        //print("<pre>".print_r(SpamTrawler::$aSettingsCore,true)."</pre>");
        //exit();
        //var_dump($this->aParams);

        $this->oSmarty->display('Manage.tpl');
    }

    public function save()
    {
        if ($this->validateSettings() === false) {
            exit(implode('<br />', $this->aErrors));
        }

        $oTable = new SpamTrawler_Db_Tables_Settings();
        foreach ($_POST as $key => $value) {
            $sConfCategory = $key;

            $sWhere = $oTable->getAdapter()->quoteInto('conf_name = ?', $sConfCategory);

            if (array_key_exists('conf_params', $value)) {
                $value['conf_params'] = serialize($value['conf_params']);
            }

            $oTable->update($value, $sWhere);
        }

        //Remove Admin Cache Entries
        if ($_POST['core']['conf_params']['sysmode'] == 1) {
            SpamTrawler::$Registry['oCache']->clean(
                SpamTrawler_Cache::CLEANING_MODE_MATCHING_TAG,
                array('admin')
            );
        } else {
                //Add Logged In IP to IP Cache to prevent firewalling
                SpamTrawler::$Registry['visitordetails']['isAdmin'] = 'yes';
                SpamTrawler::$Registry['visitordetails']['whitelisted'] = 'yes';
                SpamTrawler::$Registry['visitordetails']['filterresult'] = 'passed';
                SpamTrawler::$Registry['oCache']->save(SpamTrawler::$Registry['visitordetails'], sha1(SpamTrawler::$Registry['visitordetails']['ip']), array('ipcache', 'admin'));
        }

        //Delete Core Conf Cache
        SpamTrawler::$Registry['oCache']->remove('settings_core');

        exit('ok');
    }

    private function validateSettings()
    {
        $aTimezones = $this->getTimezones();
        if (!in_array($_POST['core']['conf_params']['timezone'], $aTimezones)) {
            $this->aErrors[] = 'Invalid Time Zone';
        }

        $aAdminAuthMethods = array('DuoSecurity', 'U2F', 'UsernamePassword');
        if (!in_array($_POST['core']['conf_params']['admin_auth_method'], $aAdminAuthMethods)) {
            $this->aErrors[] = 'Invalid Admin Auth Method';
        }

        if (!empty($_POST['core']['conf_params']['duosec_akey']) && !ctype_alnum($_POST['core']['conf_params']['duosec_akey'])) {
            $this->aErrors[] = 'Duo Security API Key Invalid';
        }

        if (!empty($_POST['core']['conf_params']['duosec_ikey']) && !ctype_alnum($_POST['core']['conf_params']['duosec_ikey'])) {
            $this->aErrors[] = 'Duo Security Integration Key Invalid';
        }

        if (!empty($_POST['core']['conf_params']['duosec_skey']) && !ctype_alnum($_POST['core']['conf_params']['duosec_skey'])) {
            $this->aErrors[] = 'Duo Security Secret Key Invalid';
        }

        $aSysModes = array(0, 1);
        if (!in_array($_POST['core']['conf_params']['sysmode'], $aSysModes)) {
            $this->aErrors[] = 'System Mode Invalid';
        }

        $aMaintenanceStatus = array(0, 1);
        if (!in_array($_POST['core']['conf_params']['maintenance_status'], $aMaintenanceStatus)) {
            $this->aErrors[] = 'Maintenance Status Invalid';
        }

        $aMaintenanceActions = array('unavailable', 'redirect', 'exitmessage');
        if (!in_array($_POST['core']['conf_params']['maintenance_action'], $aMaintenanceActions)) {
            $this->aErrors[] = 'Maintenance Action Invalid';
        }

        if ($_POST['core']['conf_params']['maintenance_action'] == 'redirect' && empty($_POST['core']['conf_params']['maintenance_redirection_target'])) {
            $this->aErrors[] = 'Maintenance Redirection Target Required';
        }

        if (!empty($_POST['core']['conf_params']['maintenance_redirection_target']) && !filter_var($_POST['core']['conf_params']['maintenance_redirection_target'], FILTER_VALIDATE_URL)) {
            $this->aErrors[] = 'Maintenance Redirection Target Invalid';
        }

        if (!empty($this->aErrors)) {
            return false;
        }
        return true;
    }

    private function getModules($sConfGroup, $sConfModule)
    {
        //Get Modules
        $oTable = new SpamTrawler_Db_Tables_Settings();

        $sql = $oTable->select()
            ->where('conf_group = ?', $sConfGroup)
            ->where('conf_module = ?', $sConfModule)
            ->order(array('conf_group_order ASC', 'conf_order ASC'));;

        $aRows = $oTable->fetchAll($sql);

        $aModules = array();
        foreach ($aRows as $row) {
            $sTemplatePath = str_replace('_Controller_Filter', '', $row->conf_class_name);
            $sTemplatePath = str_replace('_', DIRECTORY_SEPARATOR, $sTemplatePath);

            //Prepare config parameters for modules
            if (!is_null($row->conf_params)) {
                $this->aParams[$row->conf_name] = unserialize($row->conf_params);
            }
            //Set Status
            $this->aParams[$row->conf_name]['status'] = $row->conf_status;

            //Set Order
            $this->aParams[$row->conf_name]['order'] = $row->conf_order;


            $aModules[$row->conf_class_name] = TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . $sTemplatePath . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . SpamTrawler::$Config->core->template . DIRECTORY_SEPARATOR . 'Settings.tpl';
        }

        return $aModules;
    }

    public function getTimezones()
    {
        $aTimezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return $aTimezones;
    }
}
