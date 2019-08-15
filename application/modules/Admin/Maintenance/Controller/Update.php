<?php

class Admin_Maintenance_Controller_Update extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $DBs = array();

    public function __construct()
    {
        $this->aGroupAccess = array(1,2);
        $this->bExcludeFromMaintenance = TRUE;

        $this->DBs = array(
            array('url' => 'http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz', 'archive' => 'GeoIP.dat.gz', 'db' => 'GeoIP.dat'),
            array('url' => 'http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz', 'archive' => 'GeoIPv6.dat.gz', 'db' => 'GeoIPv6.dat'),
            array('url' => 'http://download.maxmind.com/download/geoip/database/asnum/GeoIPASNum.dat.gz', 'archive' => 'GeoIPASNum.dat.gz', 'db' => 'GeoIPASNum.dat'),
            array('url' => 'http://download.maxmind.com/download/geoip/database/asnum/GeoIPASNumv6.dat.gz', 'archive' => 'GeoIPASNumv6.dat.gz', 'db' => 'GeoIPASNumv6.dat')
        );
        parent::__construct();
    }

    public function index(){}

    public function GeoIP()
    {
        foreach($this->DBs as $key => $db){
            $pathArchive = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $db['archive'];
            $pathDb = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'GeoIP' . DIRECTORY_SEPARATOR . 'Maxmind' . DIRECTORY_SEPARATOR . $db['db'];

            SpamTrawler_Utilities::downloadFile($db['url'], $pathArchive);

            SpamTrawler_Utilities::extractMoveGZ($pathArchive, $pathDb);
            unlink($pathArchive);
        }
        exit('GeoIP Databases Updated!');
    }
}
