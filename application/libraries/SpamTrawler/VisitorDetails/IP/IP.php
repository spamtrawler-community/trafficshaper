<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 09:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_VisitorDetails_IP_IP
{
    public static function get()
    {
        //var_dump(SpamTrawler::$Registry);
        //exit();
        if (!isset(SpamTrawler::$Registry['visitordetails']['ip'])) {
            if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['ip'])){
                $sVisitorIP = $_POST[SpamTrawler::$Config->firewall->apiparameter]['ip'];
            } elseif ( defined( 'TRAWLER_IP_PROXY_HEADER' ) ) {
                $sVisitorIP = $_SERVER[TRAWLER_IP_PROXY_HEADER];
            } elseif(isset($_SERVER['REMOTE_ADDR'])) {
                $sVisitorIP = $_SERVER['REMOTE_ADDR'];
            } else {
                $sVisitorIP = '127.0.0.1';
            }

            if (filter_var($sVisitorIP, FILTER_VALIDATE_IP)) {
                $sVisitorIP = $sVisitorIP;
            } else {
                exit('Invalid IP Detected!');
            }

            //exit($sVisitorIP);

            //SpamTrawler_Registry::set('visitordetails->ip', $sVisitorIP);
            //SpamTrawler_Registry::get('visitordetails->ip')

            //var_dump(SpamTrawler::$Registry);
            //exit(SpamTrawler_Registry::get('visitordetails->ip'));
            SpamTrawler::$Registry['visitordetails']['ip'] = $sVisitorIP;

            //exit(SpamTrawler::$Registry->visitordetails->ip);
        }
        return SpamTrawler::$Registry['visitordetails']['ip'];
    }

    public static function getLong(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['iplong'])){
            SpamTrawler::$Registry['visitordetails']['iplong'] = ip2long(SpamTrawler::$Registry['visitordetails']['ip']);
        }

        return SpamTrawler::$Registry['visitordetails']['iplong'];
    }

    public static function getIppton(){
        if(!isset(SpamTrawler::$Registry['visitordetails']['inet_pton'])) {
            SpamTrawler::$Registry['visitordetails']['inet_pton'] = inet_pton(SpamTrawler::$Registry['visitordetails']['ip']);
        }
        return SpamTrawler::$Registry['visitordetails']['inet_pton'];
    }

    public static function isV6(){
        //Check if IP is v6
        if(!isset(SpamTrawler::$Registry['visitordetails']['isipv6'])){
            $isv6 = FALSE;
            if(filter_var(SpamTrawler::$Registry['visitordetails']['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
                $isv6 = TRUE;
            }
            SpamTrawler::$Registry['visitordetails']['isipv6'] = $isv6;
        }
        return SpamTrawler::$Registry['visitordetails']['isipv6'];
    }
}
