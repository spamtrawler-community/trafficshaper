<?php

class Firewall_Abstract_Blacklists_Filter {
    protected $aFirewallSettings = NULL;
    protected $aParams = NULL;
    protected $sDbTableName = NULL;
    protected $sDbFieldName = NULL;
    protected $oTable = NULL;
    protected $sFilterType = NULL;
    protected $sCheckValue = NULL;
    protected $bAllowRegex = TRUE;
    protected $sFilterClass = NULL;

    public function __construct($sParams, $aFirewallSettings = NULL){
        $this->aFirewallSettings = $aFirewallSettings;

        if(is_null($this->aParams)){
            $this->aParams = unserialize($sParams);
        }

        if(!is_null($this->sDbTableName)){
            $this->oTable = new SpamTrawler_Db_Tables_Generic($this->sDbTableName);
        }
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
                            $this->block();
                        }
                    } elseif($value['filter_mode'] == 'contains'){
                        if (false !== stripos($this->sCheckValue, $value[$this->sDbFieldName])) {
                            $this->block();
                        };
                    } elseif($value['filter_mode'] == 'exact'){
                        if($value[$this->sDbFieldName] == $this->sCheckValue) {
                            $this->block();
                        }
                    }
                } else {
                    if($value[$this->sDbFieldName] == $this->sCheckValue) {
                        $this->block();
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
                        $this->block();
                    }
                } elseif($row['filter_mode'] == 'contains'){
                    if (false !== stripos($this->sCheckValue, $row->{$this->sDbFieldName})) {
                        $this->block();
                    };
                } elseif($row['filter_mode'] == 'exact'){
                    if($row->{$this->sDbFieldName} == $this->sCheckValue) {
                        $this->block();
                    }
                }
            } else {
                if($row->{$this->sDbFieldName} == $this->sCheckValue) {
                    $this->block();
                }
            }
        }
    }

    public function block(){
        //Using Registry to store firewall block status and reason
        SpamTrawler::$Registry['visitordetails']['allowcaptcha'] = FALSE;

        if(isset($this->aParams['allowcaptcha']) && $this->aParams['allowcaptcha'] == 1){
            SpamTrawler::$Registry['visitordetails']['allowcaptcha'] = TRUE;
        }

        SpamTrawler::$Registry['visitordetails']['filterresult'] = 'blocked';
        SpamTrawler::$Registry['visitordetails']['filterclass'] = $this->sFilterClass;
        SpamTrawler::$Registry['visitordetails']['blockreason'] = $this->aParams['block_reason'];
    }
}
