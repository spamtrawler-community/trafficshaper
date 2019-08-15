<?php

class SpamTrawler_Password
{
    public static function generateHash($sPassword, $sSalt)
    {
        if(in_array('sha512', hash_algos()))  {
            return hash('sha512', $sPassword . $sSalt);
        } else {
            // SpamTrawler Legacy Code
            return sha1(md5(sha1($sPassword)).sha1(md5($sSalt)));
        }
    }
}
