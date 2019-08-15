<?php

class Firewall_ASNBlacklist_Model_Manage extends Firewall_Abstract_Model_Manage
{
    protected $dbTableName = 'blacklist_asn';
    protected $dbTableNameValueCache = 'cache_asn';
    protected $dbFieldNameValueVisitor = 'asnID';
    protected $dbFieldNameValueCache = 'asn';
    public $oInput = NULL;
    protected $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;
    protected $sDbFilterField = 'asn';
    protected $aUpdateData = array();

    public function __construct($oInput = NULL){
        //filter_mode
        if(!is_null($oInput)) {
            if (!$oInput[0]->filter_mode) {
                $oInput[0]->filter_mode = 'exact';
            }
        }
        parent::__construct($oInput);
    }

    public function create()
    {
        $this->aOutput = array(
            'id' => md5($this->oInput[0]->asn),
            'asn' => $this->oInput[0]->asn,
            'filter_mode' => $this->oInput[0]->filter_mode,
            'comment' => $this->oInput[0]->comment
        );

        return parent::create();
    }

    protected function afterCreate()
    {
        //Remove from IP Blacklist
        $oManage = new Firewall_ASNWhitelist_Model_Manage();
        $oManage->destroy($this->oInput[0]->asn);
    }

    public function update()
    {
        $this->aUpdateData = array(
            'asn' => $this->oInput[0]->asn,
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
            try{
                //Instantiate SpamTrawler Validator Regex
                new SpamTrawler_Validate_Regex(array('pattern' => $this->oInput[0]->asn));
            } catch(Exception $e) {
                $aErrors = array(
                    'Errors' => 'Invalid Regular Expression!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        } elseif($this->oInput[0]->filter_mode == 'exact') {
            if(!preg_match('/^AS+[0-9]+$/', $this->oInput[0]->asn)){
                $aErrors = array(
                    'Errors' => 'Invalid ASN!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        } elseif($this->oInput[0]->filter_mode == 'contains') {
            if (!preg_match('/^AS+[0-9]+$/', $this->oInput[0]->asn)) {
                $aErrors = array(
                    'Errors' => 'Invalid ASN!'
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
            Firewall_Core_Controller_Manage::$aErrors[] = 'ASN Blacklist Status Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['allowcaptcha'])) {
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'ASN Blacklist Allow Captcha Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'ASN Blacklist Order Invalid!';
        }
        */

        $sBlockReason = str_replace(' ', '', $aSettings['conf_params']['block_reason']);
        if(!ctype_alnum($sBlockReason)){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'ASN Blacklist Block Reason Invalid!';
        }

        if($bErrors === true){
           return false;
        }
        return true;
    }
}
