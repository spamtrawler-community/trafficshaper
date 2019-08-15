<?php
class Firewall_CleanTalk_Controller_Filter extends Firewall_Abstract_Blacklists_Filter
{
    protected $sDbTableName = 'cleantalk_urls';
    protected $sDbFieldName = 'url';
    protected $aParams = NULL;
    protected $sCheckValueIP = NULL;
    protected $sCheckValueEmail = NULL;
    protected $sCheckValueUsername = NULL;

    public function __construct($sParams, $aFirewallSettings)
    {
        $this->sFilterClass = __CLASS__;
        $this->sCheckValueIP = SpamTrawler_VisitorDetails::getIP();
        $this->sCheckValueEmail = SpamTrawler::$Registry['visitordetails']['email'];
        $this->sCheckValueUsername = SpamTrawler::$Registry['visitordetails']['username'];
        parent::__construct($sParams, $aFirewallSettings);
    }

    public function filter()
    {
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
            if ($this->checkUrlList() === true) {
                // Take params from config
                $config_url = 'http://moderate.cleantalk.org/api2.0/';
                //$config_url = 'localhost';
                $auth_key = $this->aParams['api_key']; // Set Cleantalk auth key

                if (count($_POST)) {
                    $sender_nickname = null;
                    if ($this->sCheckValueUsername != '--') {
                        $sender_nickname = $this->sCheckValueUsername;
                    }

                    $sender_email = null;
                    if (filter_var($this->sCheckValueEmail, FILTER_VALIDATE_EMAIL)) {
                        $sender_email = $this->sCheckValueEmail;
                    }

                    $js_on = 1;
                    /*
                    if (isset($_POST['js_on']) && $_POST['js_on'] == date("Y")){
                        $js_on = 1;
                    }
                    */

                    $message = null;
                    if (isset($_POST['message']) && $_POST['message'] != '')
                        $message = $_POST['message'];

                    // The facility in which to store the query parameters
                    $ct_request = new CleantalkRequest();

                    $ct_request->auth_key = $auth_key;
                    $ct_request->agent = 'php-api';
                    $ct_request->sender_email = $sender_email;
                    $ct_request->sender_ip = $this->sCheckValueIP;
                    $ct_request->sender_nickname = $sender_nickname;
                    $ct_request->js_on = $js_on;
                    $ct_request->message = $message;

                    $ct = new Cleantalk();
                    $ct->server_url = $config_url;

                    // Check
                    $ct_result = $ct->isAllowMessage($ct_request);

                    if ($ct_result->allow != 1) {
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
