<?php

class Firewall_OrganizationWhitelist_Controller_Filter extends Firewall_Abstract_Whitelists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'whitelist_organizations';
    protected $sDbFieldName = 'organization';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;
    protected $bLog = true;

    public function __construct($sParams, $aFirewallSettings){
        $this->sFilterClass = __CLASS__;
        $this->sCheckValue = SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorASNOrg();
        parent::__construct($sParams, $aFirewallSettings);
    }
}
