<?php

class Firewall_CountryBlacklist_Model_Manage extends Firewall_Abstract_Model_Manage
{
    protected $dbTableName = 'blacklist_countries';
    protected $dbTableNameValueCache = 'cache_countryiso';
    protected $dbFieldNameValueVisitor = 'country_codeID';
    protected $dbFieldNameValueCache = 'country_code';
    public $oInput = NULL;
    protected $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;
    protected $sDbFilterField = 'iso';
    protected $aUpdateData = array();
    protected $preValidated = false;

    public function create()
    {
        $this->oInput[0]->iso = strtoupper($this->oInput[0]->iso);

        $this->aOutput = array(
            'id' => md5($this->oInput[0]->iso),
            'name' => SpamTrawler_VisitorDetails_GeoIP_GeoIP::$aCountryInfo[$this->oInput[0]->iso]['CountryName'],
            'continent' => SpamTrawler_VisitorDetails_GeoIP_GeoIP::$aCountryInfo[$this->oInput[0]->iso]['ContinentName'],
            'iso' => $this->oInput[0]->iso,
            'comment' => $this->oInput[0]->comment
        );

        return parent::create();
    }

    public function update()
    {
        $this->oInput[0]->iso = strtoupper($this->oInput[0]->iso);

        $this->aUpdateData = array(
            'iso' => $this->oInput[0]->iso,
            'comment' => $this->oInput[0]->comment
        );

        return parent::update();
    }

    public function validate($bPreValidated = false)
    {
        if($this->preValidated === true){
            return true;
        }

        if (is_null($this->oInput)) {
            $aErrors = array(
                'Errors' => 'Input Data Missing!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        if (isset($this->oInput[0]->id) && !empty($this->oInput[0]->id)) {
            if (!ctype_alnum($this->oInput[0]->id)) {
                $aErrors = array(
                    'Errors' => 'Invalid ID!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        }


        if (!preg_match('/^[A-Z0-9]{2}$/', $this->oInput[0]->iso)) {
            $aErrors = array(
                'Errors' => 'Invalid Country Code!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }


        return true;
    }

    public static function validateSettings($aSettings)
    {
        $bErrors = false;
        if (!ctype_digit($aSettings['conf_status'])) {
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Country Blacklist Status Invalid!';
        }

        if (!ctype_digit($aSettings['conf_params']['allowcaptcha'])) {
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Country Blacklist Allow Captcha Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Country Blacklist Order Invalid!';
        }
        */

        $sBlockReason = str_replace(' ', '', $aSettings['conf_params']['block_reason']);
        if (!ctype_alnum($sBlockReason)) {
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Country Blacklist Block Reason Invalid!';
        }

        if ($bErrors === true) {
            return false;
        }
        return true;
    }

    public function addContinent($continentISO){
        $this->preValidated = true;

        foreach(SpamTrawler_VisitorDetails_GeoIP_GeoIP::$aCountryInfo as $key => $value){
            if($value['ContinentIso'] == $continentISO){
                $countryISO = $key;

                $this->aOutput = array(
                    'id' => md5($countryISO),
                    'name' => SpamTrawler_VisitorDetails_GeoIP_GeoIP::$aCountryInfo[$countryISO]['CountryName'],
                    'continent' => SpamTrawler_VisitorDetails_GeoIP_GeoIP::$aCountryInfo[$countryISO]['ContinentName'],
                    'iso' => $countryISO,
                    'comment' => ''
                );

                parent::create();
            }
        }
        return false;
    }
}
