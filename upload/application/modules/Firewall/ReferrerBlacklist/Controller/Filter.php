<?php

class Firewall_ReferrerBlacklist_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'blacklist_referrer';
    protected $sDbFieldName = 'referrer';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->sFilterClass = __CLASS__;
        $this->sCheckValue = SpamTrawler_VisitorDetails_Referrer_Referrer::get();
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter(){
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
            parent::filter();
        }
    }
}
