<?php

class Firewall_Satellite_Controller_Filter extends Firewall_Abstract_Blacklists_Filter{
    protected $aParams = NULL;
    protected $sCheckValueIP = NULL;
    protected $sCheckValueEmail = NULL;
    protected $sCheckValueUsername = NULL;
    protected $sCheckValueUseragent = NULL;
    protected $sCheckValueReferrer = NULL;
    private $sLockFileCacheName = 'SatelliteLock';

    public function __construct($sParams, $aFirewallSettings){
        if(!SpamTrawler::$Registry['oCache']->load($this->sLockFileCacheName)) {
            $this->sFilterClass = __CLASS__;
            $this->sCheckValueIP = SpamTrawler_VisitorDetails::getIP();
            $this->sCheckValueEmail = SpamTrawler_VisitorDetails::getEmail();
            $this->sCheckValueUsername = SpamTrawler_VisitorDetails::getUsername();
            $this->sCheckValueUseragent = SpamTrawler_VisitorDetails::getUseragent();
            $this->sCheckValueReferrer = SpamTrawler_VisitorDetails::getReferrer();
            $this->sCheckValueURL = SpamTrawler_VisitorDetails::getUrl();
            $this->sCheckValuePath = SpamTrawler_VisitorDetails::getPath();
            parent::__construct($sParams, $aFirewallSettings);
        }
    }

    public function filter()
    {
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
            //Filter can only be used by firewall in standalone or client mode
            if ($this->aFirewallSettings['conf_params']['mode'] !== 'server') {
                $ApiParamName = SpamTrawler::$Config->firewall->apiparameter;
                $aFilterData = array(
                    $ApiParamName => array(
                        'ip' => $this->sCheckValueIP,
                        'email' => $this->sCheckValueEmail,
                        'username' => $this->sCheckValueUsername,
                        'useragent' => $this->sCheckValueUseragent,
                        'referrer' => $this->sCheckValueEmail,
                        'url' => $this->sCheckValueURL,
                        'path' => $this->sCheckValuePath,
                        'apikey' => $this->aParams['apikey']
                    )
                );

                $SatelliteResponse = $this->httpPost($this->aParams['satellite_url'], $aFilterData);

                if ($SatelliteResponse !== false) {
                    $SatelliteResponse = json_decode($SatelliteResponse);

                    //Assign visitor details returned by Satellite to SpamTrawler::$Registry->visitordetails to save server resources
                    foreach ($SatelliteResponse as $key => $value) {
                        SpamTrawler::$Registry['visitordetails'][$key] = $value;
                    }

                    if ($SatelliteResponse->filterresult && $SatelliteResponse->filterresult === 'blocked') {
                        if (isset($SatelliteResponse->allowcaptcha) && $SatelliteResponse->allowcaptcha == 'true') {
                            $this->aParams['allowcaptcha'] = 1;
                        }

                        $this->aParams['block_reason'] = $this->aParams['block_reason'] . '(' . $SatelliteResponse->blockreason . ')';
                        $this->block();
                    }

                }
            }
        }
    }

    private function httpPost($url,$params)
    {
        $postData = http_build_query($params);
        //$postData = $params;
        //var_dump($postData);
        //exit('Satellite Error<br />');

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output=curl_exec($ch);
        //Get the resulting HTTP status code from the cURL handle.
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($http_status_code === 200){
            return $output;
        } else {
            SpamTrawler::$Registry['oCache']->save($http_status_code, $this->sLockFileCacheName);

            $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log/error.log');
            $logger = new SpamTrawler_Log($writer);

            $logger->warn('Satellite Error: ' . $http_status_code);
            return false;
        }
    }
}
