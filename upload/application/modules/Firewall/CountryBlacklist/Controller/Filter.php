<?php
class Firewall_CountryBlacklist_Controller_Filter
{

    private $aParams = NULL;

    public function __construct($sParams, $aFirewallSettings)
    {
        $this->aParams = unserialize($sParams);
    }

    public function filter()
    {
        if (Firewall_Core_Controller_Filter::$bVisitorIsCached === false) {
            $sCountry = SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryCode();

            if ($sCountry !== 'false') {
                $oTable = new SpamTrawler_Db_Tables_Generic('blacklist_countries');

                $sql = $oTable->select()
                    ->where('iso = ?', $sCountry)
                    ->limit(1);

                //print_r($oTable->fetchAll($sql)->toArray());

                if (count($oTable->fetchAll($sql)->toArray()) > 0) {
                    SpamTrawler::$Registry['visitordetails']['filterresult'] = 'blocked';
                    SpamTrawler::$Registry['visitordetails']['filterclass'] = __CLASS__;
                    SpamTrawler::$Registry['visitordetails']['blockreason'] = $this->aParams['block_reason'];
                }
            }
        }
    }
}
