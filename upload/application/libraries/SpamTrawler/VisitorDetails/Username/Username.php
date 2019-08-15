<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 09:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_VisitorDetails_Username_Username
{
    public static function get()
    {
        if (!isset(SpamTrawler::$Registry['visitordetails']['username'])) {
            $sVisitorUsername = '--';
            if(SpamTrawler::$Config->firewall->mode == 'Server'){
                if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['username'])){
                    $sVisitorUsername = $_POST[SpamTrawler::$Config->firewall->apiparameter]['username'];
                }
            }
            SpamTrawler::$Registry['visitordetails']['username'] = $sVisitorUsername;
        }
        return SpamTrawler::$Registry['visitordetails']['username'];
    }
}
