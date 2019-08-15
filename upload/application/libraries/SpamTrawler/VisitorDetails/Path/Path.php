<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 09:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_VisitorDetails_Path_Path
{
    public static function get()
    {
        if (!isset(SpamTrawler::$Registry['visitordetails']['path'])) {
            if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['path'])){
                $sVisitorPath = $_POST[SpamTrawler::$Config->firewall->apiparameter]['path'];
            } else {
                $sVisitorPath = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
            }
            SpamTrawler::$Registry['visitordetails']['path'] = $sVisitorPath;
        }

        return SpamTrawler::$Registry['visitordetails']['path'];
    }
}
