<?php

class Firewall_UsernameBlacklist_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'blacklist_usernames';
    protected $sDbFieldName = 'username';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->aParams = unserialize($sParams);
        $this->sFilterClass = __CLASS__;
        $this->sCheckValue = SpamTrawler::$Registry['visitordetails']['username'];

        if (isset($this->sCheckValue)) {
            Firewall_Core_Controller_Filter::$bVisitorIsCached = false;
        }

        parent::__construct($sParams, $aFirewallSettings);
    }
}
