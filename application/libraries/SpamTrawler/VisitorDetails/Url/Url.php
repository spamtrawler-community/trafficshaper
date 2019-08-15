<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 09:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_VisitorDetails_Url_Url
{
    public static function get()
    {
        if (!isset(SpamTrawler::$Registry['visitordetails']['url'])) {
            if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['url'])){
                $sVisitorUrl = $_POST[SpamTrawler::$Config->firewall->apiparameter]['url'];
            } else {
                $sVisitorUrl = SpamTrawler_Http_Protocol::get() . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            }
            SpamTrawler::$Registry['visitordetails']['url'] = $sVisitorUrl;
        }
        return SpamTrawler::$Registry['visitordetails']['url'];
    }
}