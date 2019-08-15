<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 09:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_VisitorDetails_Email_Email
{
    public static function get()
    {
        if (!isset(SpamTrawler::$Registry['visitordetails']['email'])) {
            $sVisitorEmail = '--';
            if(SpamTrawler::$Config->firewall->mode == 'Server'){
                if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['email'])){
                    $sVisitorEmail = $_POST[SpamTrawler::$Config->firewall->apiparameter]['email'];
                }
            }
            SpamTrawler::$Registry['visitordetails']['email'] = $sVisitorEmail;
        }
        return SpamTrawler::$Registry['visitordetails']['email'];
    }
}
