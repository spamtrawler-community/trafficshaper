<?php

class Firewall_ProjectHoneypot_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $sDbTableName = 'projecthoneypot_urls';
    protected $sDbFieldName = 'url';
    protected $aParams = NULL;
    protected $sCheckValueIP = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->sFilterClass = __CLASS__;
        $this->sCheckValueIP = SpamTrawler_VisitorDetails::getIP();
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter()
    {
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
            if ($this->checkUrlList() === true) {
                $oProjectHoneypot = new Firewall_ProjectHoneypot_Helper_ProjectHoneypot($this->aParams['api_key']);
                $results = $oProjectHoneypot->query($this->sCheckValueIP);

                if (in_array($results['visitortype'], $this->aParams['block_types']) && $results['last_activity'] <= $this->aParams['last_activity'] && $results['threat_score'] >= $this->aParams['threat_score']) {
                    SpamTrawler::$Registry['visitordetails']['comment'] = implode(', ', $results['categories']);
                    $this->block();
                }
            }
        }
    }

    private function checkUrlList()
    {
        $oList = $this->oTable->getCached();

        foreach($oList as $row){
            if($row->filter_mode == 'regex'){
                if(preg_match($row->{$this->sDbFieldName}, SpamTrawler_VisitorDetails::getUrl())){
                    return true;
                    break;
                }
            } elseif($row->filter_mode == 'exact') {
                if($row->{$this->sDbFieldName} == SpamTrawler_VisitorDetails::getUrl()) {
                    return true;
                    break;
                }
            } elseif($row->filter_mode == 'contains'){
                if (false !== stripos(SpamTrawler_VisitorDetails::getUrl(), $row->{$this->sDbFieldName})) {
                    return true;
                    break;
                };
            }
        }

        return false;
    }
}
