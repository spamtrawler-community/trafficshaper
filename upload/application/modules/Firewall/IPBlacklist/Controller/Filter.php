<?php

class Firewall_IPBlacklist_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'blacklist_ips';
    protected $sDbFieldName = 'ip';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;
    protected $bAllowRegex = FALSE;

    public function __construct($sParams, $aFirewallSettings){
        $this->sFilterClass = __CLASS__;
        $this->sCheckValue = SpamTrawler_VisitorDetails_IP_IP::get();
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter(){
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
            parent::filter();
        }
    }
}
