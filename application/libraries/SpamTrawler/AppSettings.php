<?php

class SpamTrawler_AppSettings
{
     const ErrorReporting = 0;  //Options: 0, 1, NA (NA disables this option which may be necessary on certain systems)
     const DisplayErrors = 'Off'; //Options: On, Off, NA (NA disables this option which may be necessary on certain systems where ini_set is not allowed)

     const TimeZone = 'UTC';

     const ADMIN_PANEL_TEMPLATE = 'Default';

     const FILE_DIRECTORY_PATH = NULL;
     const SETTINGS_INI_FILE_NAME = NULL;

     const CONFIG_STAGE = 'staging';

    /*
     * IP Detection
     */
    const IP_PROXY_HEADER = NULL;

    /*
     * Use CloudFlare GeoIP Header
     */
    const USE_CLOUDFLARE_GEOIP_HEADER = NULL;

    /*
     * Friendly URLs
     * mod_rewrite
     */
    const FRIENDLY_URLS = 1;
}
