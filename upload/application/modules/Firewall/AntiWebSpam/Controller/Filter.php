<?php
class Firewall_AntiWebSpam_Controller_Filter extends Firewall_Abstract_Blacklists_Filter
{
    protected $sDbTableName = 'antiwebspam_urls';
    protected $sDbFieldName = 'url';
    protected $aParams = NULL;
    protected $sCheckValueIP = NULL;
    protected $sCheckValueEmail = NULL;
    protected $sCheckValueHostname = NULL;
    protected $sDnsdblUrl = NULL;

    public function __construct($sParams, $aFirewallSettings)
    {
        $this->sFilterClass = __CLASS__;
        $this->sCheckValueIP = SpamTrawler_VisitorDetails::getIP();
        $this->sCheckValueEmail = SpamTrawler::$Registry['visitordetails']['email'];
        $this->sCheckValueHostname = SpamTrawler_VisitorDetails::getHostname();
        $this->sDnsdblUrl = 'dnsbl.antiwebspam.com';
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter()
    {
        if ($this->checkUrlList() === true) {
            if (count($_POST)) {
                if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
                    //Hostname Filter against dnsbl.antiwebspam.com
                    if (in_array('hostnames', $this->aParams['filter_types']) && $this->sCheckValueHostname != $this->sCheckValueIP && preg_match('/[.]/', $this->sCheckValueHostname)) {
                        $result = dns_get_record($this->sCheckValueHostname . '.' . $this->sDnsdblUrl, DNS_A);

                        if ($result && is_array($result) && isset($result[0]['ip']) && preg_match('/^127./', $result[0]['ip'])) {
                            SpamTrawler::$Registry['visitordetails']['comment'] = 'Hostname Blacklisted';
                            $this->block();
                        }
                    }

                    //IP Filter against dnsbl.antiwebspam.com
                    if (in_array('ips', $this->aParams['filter_types'])) { // Fill with logic from settings
                        $sReverseIP = implode('.', array_reverse(explode('.', $this->sCheckValueIP)));
                        $result = dns_get_record($sReverseIP . '.' . $this->sDnsdblUrl, DNS_A);

                        if ($result && is_array($result) && isset($result[0]['ip']) && preg_match('/^127./', $result[0]['ip'])) {
                            SpamTrawler::$Registry['visitordetails']['comment'] = 'IP Address Blacklisted';
                            $this->block();
                        }
                    }
                }

                //EMail Filter against dnsbl.antiwebspam.com
                if (in_array('emails', $this->aParams['filter_types']) && filter_var($this->sCheckValueEmail, FILTER_VALIDATE_EMAIL)) {
                    //Remove dots for gmail email addresses as gmail handles m.e@gmail.com the same as me@gmail.com
                    $aEmail = explode('@', $this->sCheckValueEmail);
                    if ($aEmail[1] == 'gmail.com') {
                        $aEmail[0] = str_replace('.', '', $aEmail[0]);
                        $this->sCheckValueEmail = implode('@', $aEmail);
                    }

                    $sFormattedEmail = str_replace('@', '-2at2-', $this->sCheckValueEmail);
                    $result = dns_get_record($sFormattedEmail . '.' . $this->sDnsdblUrl, DNS_A);

                    if ($result && is_array($result) && isset($result[0]['ip']) && preg_match('/^127./', $result[0]['ip'])) {
                        SpamTrawler::$Registry['visitordetails']['comment'] = 'Email Address Blacklisted';
                        $this->block();
                    }
                }
            }
        }
    }

    private function checkUrlList()
    {
        $oList = $this->oTable->getCached();

        foreach ($oList as $row) {
            if ($row->filter_mode == 'regex') {
                if (preg_match($row->{$this->sDbFieldName}, SpamTrawler_VisitorDetails::getUrl())) {
                    return true;
                    break;
                }
            } elseif ($row->filter_mode == 'exact') {
                if ($row->{$this->sDbFieldName} == SpamTrawler_VisitorDetails::getUrl()) {
                    return true;
                    break;
                }
            } elseif ($row->filter_mode == 'contains') {
                if (false !== stripos(SpamTrawler_VisitorDetails::getUrl(), $row->{$this->sDbFieldName})) {
                    return true;
                    break;
                };
            }
        }

        return false;
    }
}
