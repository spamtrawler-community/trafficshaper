<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 09:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_VisitorDetails_Referrer_Referrer
{
    public static function get()
    {
        $sVisitorReferrer = '';
        if (!isset(SpamTrawler::$Registry['visitordetails']['referrer'])) {
            if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['referrer'])){
                $sVisitorReferrer = $_POST[SpamTrawler::$Config->firewall->apiparameter]['referrer'];
            } else {
                if(isset($_SERVER['HTTP_REFERER'])){
                    $sVisitorReferrer = $_SERVER['HTTP_REFERER'];
                }
            }
            SpamTrawler::$Registry['visitordetails']['referrer'] = $sVisitorReferrer;
        }
        return SpamTrawler::$Registry['visitordetails']['referrer'];
    }
}
