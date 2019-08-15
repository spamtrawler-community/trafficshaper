<?php

class Firewall_IPWhitelist_Model_Manage extends Firewall_Abstract_Model_Manage
{
    protected $dbTableName = 'whitelist_ips';
    public $oInput = NULL;
    protected $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;
    protected $sDbFilterField = 'ip';
    protected $aUpdateData = array();

    public function create()
    {
        $this->aOutput = array(
            'id' => md5($this->oInput[0]->ip),
            'ip' => $this->oInput[0]->ip,
            'comment' => $this->oInput[0]->comment
        );

        return parent::create();
    }

    protected function afterCreate()
    {
        //Remove from IP Blacklist
        $oManage = new Firewall_IPBlacklist_Model_Manage();
        $oManage->destroy($this->oInput[0]->ip);

        $this->cacheMaintenance();
    }

    //Maintain Visitor Cache entries
    public function cacheMaintenance(){
        $table = new SpamTrawler_Db_Tables_CacheVisitors();
        $where = $table->getAdapter()->quoteInto('ip = ?', $this->oInput[0]->ip);
        $table->delete($where);

        SpamTrawler::$Registry['oCache']->remove(sha1($this->oInput[0]->ip));
    }

    public function update()
    {
        $this->aUpdateData = array(
            'ip' => $this->oInput[0]->ip,
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

        if(isset($this->oInput[0]->ip)){
            if (!filter_var($this->oInput[0]->ip, FILTER_VALIDATE_IP)) {
                $aErrors = array(
                    'Errors' => 'Invalid IP Address!'
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
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Whitelist Status Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Whitelist Order Invalid!';
        }
        */

        if($bErrors === true){
            return false;
        }
        return true;
    }
}
