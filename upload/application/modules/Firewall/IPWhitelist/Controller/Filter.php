<?php

class Firewall_IPWhitelist_Controller_Filter extends Firewall_Abstract_Whitelists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'whitelist_ips';
    protected $sDbFieldName = 'ip';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $bAllowRegex = FALSE;
    protected $bLog = true;

    public function __construct($sParams, $aFirewallSettings){
        $this->sCheckValue = SpamTrawler_VisitorDetails_IP_IP::get();
        parent::__construct($sParams, $aFirewallSettings);
    }
}
