<?php

class Firewall_EmailBlacklist_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'blacklist_emails';
    protected $sDbFieldName = 'email';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->aParams = unserialize($sParams);
        $this->sFilterClass = __CLASS__;
        $this->sCheckValue = SpamTrawler::$Registry['visitordetails']['email'];

        if (filter_var($this->sCheckValue, FILTER_VALIDATE_EMAIL)) {
            Firewall_Core_Controller_Filter::$bVisitorIsCached = false;
        }
        parent::__construct($sParams);
    }
}
