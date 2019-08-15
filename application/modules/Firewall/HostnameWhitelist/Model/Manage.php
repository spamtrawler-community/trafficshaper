<?php

class Firewall_HostnameWhitelist_Model_Manage extends Firewall_Abstract_Model_Manage
{
    protected $dbTableName = 'whitelist_hostnames';
    protected $dbTableNameValueCache = 'cache_hostnames';
    protected $dbFieldNameValueVisitor = 'host_nameID';
    protected $dbFieldNameValueCache = 'host_name';
    public $oInput = NULL;
    protected $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;
    protected $sDbFilterField = 'hostname';
    protected $aUpdateData = array();

    public function __construct($oInput = null){
        //filter_mode
        if(!is_null($oInput)){
            if(!($oInput[0]->filter_mode)){
                $oInput[0]->filter_mode = 'exact';
            }
        }
        parent::__construct($oInput);
    }

    public function create()
    {
        $this->aOutput = array(
            'id' => md5($this->oInput[0]->hostname),
            'hostname' => $this->oInput[0]->hostname,
            'filter_mode' => $this->oInput[0]->filter_mode,
            'comment' => $this->oInput[0]->comment
        );

        return parent::create();
    }

    protected function afterCreate()
    {
        //Remove from IP Blacklist
        $oManage = new Firewall_HostnameBlacklist_Model_Manage();
        $oManage->destroy($this->oInput[0]->hostname);
    }

    public function update()
    {
        $this->aUpdateData = array(
            'hostname' => $this->oInput[0]->hostname,
            'filter_mode' => $this->oInput[0]->filter_mode,
            'comment' => $this->oInput[0]->comment
        );

        return parent::update();
    }

    public function validate()
    {
        if(is_null($this->oInput)){
            $aErrors = array(
                'Errors' => 'Input Data Missing!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        if(isset($this->oInput[0]->id) && !empty($this->oInput[0]->id)){
            if (!ctype_alnum($this->oInput[0]->id)) {
                $aErrors = array(
                    'Errors' => 'Invalid ID!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        }

        //filter_mode
        $aAllowedFilterModes = array('exact','contains','regex');
        if(!in_array($this->oInput[0]->filter_mode, $aAllowedFilterModes)){
            $aErrors = array(
                'Errors' => 'Invalid Filter Mode!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        } elseif($this->oInput[0]->filter_mode == 'regex'){
            $this->oInput[0]->isregex = 'true';
            try{
                //Instantiate SpamTrawler Validator Regex
                new SpamTrawler_Validate_Regex(array('pattern' => $this->oInput[0]->hostname));
            } catch(Exception $e) {
                $aErrors = array(
                    'Errors' => 'Invalid Regular Expression!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        } elseif($this->oInput[0]->filter_mode == 'exact') {
            if(!preg_match('/^[a-zA-Z0-9_.-]+$/', $this->oInput[0]->hostname)){
                $aErrors = array(
                    'Errors' => 'Invalid Hostname!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        } elseif($this->oInput[0]->filter_mode == 'contains') {
            if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $this->oInput[0]->hostname)) {
                $aErrors = array(
                    'Errors' => 'Invalid Hostname!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        }

        return true;
    }

    public static function validateSettings($aSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Hostname Whitelist Status Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Hostname Whitelist Order Invalid!';
        }
        */

        if($bErrors === true){
            return false;
        }
        return true;
    }
}