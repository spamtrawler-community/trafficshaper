<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 01/07/14
 * Time: 17:30
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler) 
 */

class SpamTrawler_VisitorDetails_MobileDetect_GetDevice {
    public static function get()
    {
        if (!isset(SpamTrawler::$Registry['visitordetails']['device'])) {
            if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['device'])){
                $sDeviceType = $_POST[SpamTrawler::$Config->firewall->apiparameter]['device'];
            } else {
                //Detect Mobile Device
                $oMobileDetect = new Mobile_Detect();
                $oMobileDetect->setUserAgent(SpamTrawler_VisitorDetails_UserAgent_UserAgent::get());
                $sDeviceType = ($oMobileDetect->isMobile() ? ($oMobileDetect->isTablet() ? 'Tablet' : 'Phone') : 'Computer');
            }
            SpamTrawler::$Registry['visitordetails']['device'] = $sDeviceType;
        }
        return SpamTrawler::$Registry['visitordetails']['device'];
    }
} 