<?php

class Firewall_ParameterBlacklist_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'blacklist_parameter';
    protected $sDbFieldName = 'parameter';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->sFilterClass = __CLASS__;
        $this->sCheckValue = SpamTrawler_VisitorDetails_Url_Url::get();
        parent::__construct($sParams, $aFirewallSettings);
    }
}
