<?php

class Firewall_HostnameWhitelist_Controller_Filter extends Firewall_Abstract_Whitelists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'whitelist_hostnames';
    protected $sDbFieldName = 'hostname';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->sFilterClass = __CLASS__;
        $this->sCheckValue = SpamTrawler_VisitorDetails_Hostname_Hostname::get();

        $sReverseHost = gethostbyname( $this->sCheckValue );

        if($sReverseHost == SpamTrawler_VisitorDetails::getIP()){
            parent::__construct($sParams, $aFirewallSettings);
        }
    }
}
