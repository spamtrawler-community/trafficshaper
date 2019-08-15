<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 03/07/14
 * Time: 15:01
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler) 
 */

class SpamTrawler_VisitorDetails {
    public static function getIP(){
        return SpamTrawler_VisitorDetails_IP_IP::get();
    }

    public static function getIPLong(){
        return SpamTrawler_VisitorDetails_IP_IP::getLong();
    }

    public static function getIPpton(){
        return SpamTrawler_VisitorDetails_IP_IP::getIppton();
    }

    public static function getCountryCode(){
        return SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryCode();
    }

    public static function getCountryName(){
        return SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryName();
    }

    public static function getCountryLongitude(){
        return SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorLongitude();
    }

    public static function getCountryLatitude(){
        return SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorLatitude();
    }

    public static function getContinentName(){
        return SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorContinentName();
    }

    public static function getContinentCode(){
        return SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorContinentCode();
    }

    public static function getASN(){
        return SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorASN();
    }

    public static function getASNOrg(){
        return SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorASNOrg();
    }

    public static function getHostname(){
        return SpamTrawler_VisitorDetails_Hostname_Hostname::get();
    }

    public static function getUsername(){
        return SpamTrawler_VisitorDetails_Username_Username::get();
    }

    public static function getEmail(){
        return SpamTrawler_VisitorDetails_Email_Email::get();
    }

    public static function getPath(){
        return SpamTrawler_VisitorDetails_Path_Path::get();
    }

    public static function getUrl(){
        return SpamTrawler_VisitorDetails_Url_Url::get();
    }

    public static function getUseragent(){
        return SpamTrawler_VisitorDetails_UserAgent_UserAgent::get();
    }

    public static function getDevice(){
        return SpamTrawler_VisitorDetails_MobileDetect_GetDevice::get();
    }

    public static function getReferrer(){
        return SpamTrawler_VisitorDetails_Referrer_Referrer::get();
    }

    public static function getRequestGetFlattened(){
        if(!isset(SpamTrawler::$Registry['request']['get'])){
            $oGetVisitorDetails = new Firewall_Core_Helper_GetVisitorDetails();
            $oGetVisitorDetails->getFlatGET();
        }
    }

    public static function getRequestPostFlattened(){
        if(!isset(SpamTrawler::$Registry['request']['flattened']['post'])){
            $oGetVisitorDetails = new Firewall_Core_Helper_GetVisitorDetails();
            $oGetVisitorDetails->getFlatPOST();
        }
        return SpamTrawler::$Registry['request']['flattened']['post'];
    }


    public static function set(){
        /*if (!isset(SpamTrawler::$Registry['visitordetails'])) {
            //Add Autoloader Object to registry
            $object = new stdClass();
            SpamTrawler::$Registry['visitordetails'] = $object;
        }*/

        //Network Details
        SpamTrawler_VisitorDetails_IP_IP::get();
        SpamTrawler_VisitorDetails_IP_IP::getLong();
        SpamTrawler_VisitorDetails_IP_IP::getIppton();

        //GeoIP
        //SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryCode();
        //SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryName();
        //SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorLongitude();
        //SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorLatitude();
        //SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorContinentName();
        //SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorContinentCode();
        //SpamTrawler_VisitorDetails_GeoIP_GeoIP::getMaxmindASN();

        //Network Details
        //SpamTrawler_VisitorDetails_Hostname_Hostname::get();

        //System Info
        SpamTrawler_VisitorDetails_Path_Path::get();
        SpamTrawler_VisitorDetails_Url_Url::get();

        //Machine details
        SpamTrawler_VisitorDetails_UserAgent_UserAgent::get();
        //SpamTrawler_VisitorDetails_MobileDetect_GetDevice::get();
        SpamTrawler_VisitorDetails_Referrer_Referrer::get();
    }
} 