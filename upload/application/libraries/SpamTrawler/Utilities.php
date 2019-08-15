<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 05/11/2015
 * Time: 15:09
 */
class SpamTrawler_Utilities
{
    public static function deleteDir($path)
    {
        return !empty($path) && is_file($path) ?
            @unlink($path) :
            (array_reduce(glob($path.'/*'), function ($r, $i) { return $r && self::deleteDir($i); }, TRUE)) && @rmdir($path);
    }

    public static function downloadFile($sURL, $sPathArchive, $sUserAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.21 (KHTML, like Gecko) Chrome/44.0.1042.0 Safari/535.21'){
        //set_time_limit(0);

        //File to save the contents to
        $fp = fopen ($sPathArchive, 'w+');

        //Here is the file we are downloading, replace spaces with %20
        $ch = curl_init(str_replace(" ","%20",$sURL));

        curl_setopt($ch, CURLOPT_TIMEOUT, 50);

        //give curl the file pointer so that it can write to it
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        //Set Useragent
        curl_setopt($ch, CURLOPT_USERAGENT, $sUserAgent);

        $data = curl_exec($ch);//get curl response

        //done
        curl_close($ch);

        return true;
    }

    public static function extractMoveGZ($srcName, $dstName) {
        $sfp = gzopen($srcName, "rb");
        $fp = fopen($dstName, "w");

        while ($string = gzread($sfp, 4096)) {
            fwrite($fp, $string, strlen($string));
        }
        gzclose($sfp);
        fclose($fp);
    }
}