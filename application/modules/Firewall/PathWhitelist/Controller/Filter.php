<?php

class Firewall_PathWhitelist_Controller_Filter extends Firewall_Abstract_Whitelists_Filter{
    protected $aParams = NULL;
    protected $sDbTableName = 'whitelist_paths';
    protected $sDbFieldName = 'path';
    protected $oTable = NULL;
    protected $sFilterType = 'cache';
    protected $sCheckValue = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->sCheckValue = SpamTrawler_VisitorDetails_Path_Path::get();
        parent::__construct($sParams, $aFirewallSettings);
    }
}
