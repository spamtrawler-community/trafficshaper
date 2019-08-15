<?php

class Firewall_Abstract_Whitelists_Filter {
    protected $aFirewallSettings = NULL;
    protected $aParams = NULL;
    protected $sDbTableName = NULL;
    protected $sDbFieldName = NULL;
    protected $oTable = NULL;
    protected $sFilterType = NULL;
    protected $sCheckValue = NULL;
    protected $bAllowRegex = TRUE;
    protected $bLog = false;

    public function __construct($sParams, $aFirewallSettings){
        $this->aFirewallSettings = $aFirewallSettings;
        $this->aParams = unserialize($sParams);
        $this->oTable = new SpamTrawler_Db_Tables_Generic($this->sDbTableName);
    }

    public function filter(){
        //Set filter type
        if($this->sFilterType == 'cache'){
            $this->filterCache();
        } else {
            $this->filterDB();
        }
    }

    public function filterDB(){
        if($this->sCheckValue !== 'false'){
            $sql = $this->oTable->select();
            $aResult = $this->oTable->fetchAll($sql)->toArray();

            foreach($aResult as $key => $value){
                if(isset($value['filter_mode'])){
                    if($this->bAllowRegex === TRUE && $value['filter_mode'] == 'regex'){
                        $validator = new SpamTrawler_Validate_Regex(array('pattern' => $value[$this->sDbFieldName]));
                        if($validator->isValid($this->sCheckValue)){
                            $this->permit();
                        }
                    } elseif($value['filter_mode'] == 'contains'){
                        if (false !== stripos($this->sCheckValue, $value[$this->sDbFieldName])) {
                            $this->permit();
                        };
                    } elseif($value['filter_mode'] == 'exact'){
                        if($value[$this->sDbFieldName] == $this->sCheckValue) {
                            $this->permit();
                        }
                    }
                } else {
                    if($value[$this->sDbFieldName] == $this->sCheckValue) {
                        $this->permit();
                    }
                }
            }
        }
    }

    public function filterCache(){
        $oList = $this->oTable->getCached();

        foreach($oList as $row){
            if(isset($row['filter_mode'])){
                if($this->bAllowRegex === TRUE && $row['filter_mode'] == 'regex'){
                    $sPattern = $row->{$this->sDbFieldName};
                    if(preg_match($sPattern, $this->sCheckValue)){
                        $this->permit();
                    }
                } elseif($row['filter_mode'] == 'contains'){
                    if (false !== stripos($this->sCheckValue, $row->{$this->sDbFieldName})) {
                        $this->permit();
                    };
                } elseif($row['filter_mode'] == 'exact'){
                    if($row->{$this->sDbFieldName} == $this->sCheckValue) {
                        $this->permit();
                    }
                }
            } else {
                if($row->{$this->sDbFieldName} == $this->sCheckValue) {
                    $this->permit();
                }
            }
        }
    }

    public function permit(){
        //Using Registry to store firewall block status and reason
        Firewall_Core_Controller_Filter::$bLog = $this->bLog;
        Firewall_Core_Controller_Filter::$bIsWhitelisted = true;
        SpamTrawler::$Registry['visitordetails']['filterresult'] = 'whitelisted';
    }
}
