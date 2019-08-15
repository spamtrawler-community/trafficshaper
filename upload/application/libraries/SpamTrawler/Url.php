<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 23/07/14
 * Time: 19:10
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler) 
 */

class SpamTrawler_Url {
    public static function MakeFriendly($sParams)
    {
        if(SpamTrawler::$Config->core->friendlyurl != 1){
            $sParams = 'index.php?do=/' . $sParams;
        }
        $sUrl = SpamTrawler::$Registry['requestprotocol'] . '://' . SpamTrawler::$Registry['sLinkUrl'] . $sParams;

        return $sUrl;
    }
} 