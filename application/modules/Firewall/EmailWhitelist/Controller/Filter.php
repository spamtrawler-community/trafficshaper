<?php

class Firewall_EmailWhitelist_Controller_Filter extends Firewall_Abstract_Whitelists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'whitelist_emails';
    protected $sDbFieldName = 'email';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;
    protected $bLog = true;

    public function __construct($sParams, $aFirewallSettings){
        $this->sCheckValue = SpamTrawler::$Registry['visitordetails']['email'];
        parent::__construct($sParams, $aFirewallSettings);
    }
}
