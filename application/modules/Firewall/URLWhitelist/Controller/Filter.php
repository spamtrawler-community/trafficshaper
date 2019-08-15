<?php

class Firewall_URLWhitelist_Controller_Filter extends Firewall_Abstract_Whitelists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'whitelist_urls';
    protected $sDbFieldName = 'url';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->sCheckValue = SpamTrawler_VisitorDetails_Url_Url::get();
        parent::__construct($sParams, $aFirewallSettings);
    }
}
