<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 02/07/14
 * Time: 13:01
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler) 
 */

class SpamTrawler_VisitorDetails_Hostname_Hostname {
    public static function get()
    {
        if (!isset(SpamTrawler::$Registry['visitordetails']['hostname'])) {
            if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['hostname'])){
                $sVisitorHost = $_POST[SpamTrawler::$Config->firewall->apiparameter]['hostname'];
            } else {
                $sVisitorHost = gethostbyaddr(SpamTrawler_VisitorDetails_IP_IP::get());
            }
            SpamTrawler::$Registry['visitordetails']['hostname'] = $sVisitorHost;
        }
        return SpamTrawler::$Registry['visitordetails']['hostname'];
    }
} 