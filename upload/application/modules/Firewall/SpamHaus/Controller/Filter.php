<?php

class Firewall_SpamHaus_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $sDbTableName = 'spamhaus_urls';
    protected $sDbFieldName = 'url';
    protected $aParams = NULL;
    protected $sCheckValueIP = NULL;
    protected $sCheckValueHostname = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->sFilterClass = __CLASS__;
        $this->sCheckValueIP = SpamTrawler_VisitorDetails::getIP();
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter()
    {
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
            if ($this->checkUrlList() === true) {
                if (count($_POST)) {
                    if ($this->sCheckValueHostname != $this->sCheckValueIP && preg_match('/[.]/', $this->sCheckValueHostname)) {
                        $result = dns_get_record($this->sCheckValueHostname . 'dbl.spamhaus.org', DNS_A);

                        if ($result && is_array($result) && isset($result[0]['ip']) && preg_match('/^127./', $result[0]['ip'])) {
                            $this->block();
                        }
                    }
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
