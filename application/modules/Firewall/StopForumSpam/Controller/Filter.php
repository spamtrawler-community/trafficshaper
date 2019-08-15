<?php

class Firewall_StopForumSpam_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $sDbTableName = 'stopforumspam_urls';
    protected $sDbFieldName = 'url';
    protected $aParams = NULL;
    protected $sCheckValueIP = NULL;
    protected $sCheckValueEmail = NULL;

    public function __construct($sParams, $aFirewallSettings){
        $this->sFilterClass = __CLASS__;
        $this->sCheckValueIP = SpamTrawler_VisitorDetails::getIP();
        $this->sCheckValueEmail = SpamTrawler_VisitorDetails::getEmail();
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter()
    {
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {

            if ($this->checkUrlList() === true) {
                $oStopForumSpam = new Firewall_StopForumSpam_Helper_API();
                $oStopForumSpam->AllowTor(false);

                if ($oStopForumSpam->CheckIP($this->sCheckValueIP)) {
                    $this->block();
                }

                if (filter_var($this->sCheckValueEmail, FILTER_VALIDATE_EMAIL)) {
                    if ($oStopForumSpam->CheckEmail($this->sCheckValueEmail)) {
                        $this->block();
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
