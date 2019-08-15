<?php

class Firewall_ASNWhitelist_Controller_Filter extends Firewall_Abstract_Whitelists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'whitelist_asn';
    protected $sDbFieldName = 'asn';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;
    protected $bLog = true;

    public function __construct($sParams, $aFirewallSettings){
        $this->sCheckValue = SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorASN();
        parent::__construct($sParams, $aFirewallSettings);
    }
}
