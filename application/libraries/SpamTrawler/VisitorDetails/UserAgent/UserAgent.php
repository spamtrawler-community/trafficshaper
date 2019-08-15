<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 09:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_VisitorDetails_UserAgent_UserAgent
{
      public static function get()
      {
          if (!isset(SpamTrawler::$Registry['visitordetails']['useragent'])) {
              if(isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['useragent'])){
                  $sVisitorUserAgent = $_POST[SpamTrawler::$Config->firewall->apiparameter]['useragent'];
              } else {
                  $sVisitorUserAgent = $_SERVER['HTTP_USER_AGENT'];
              }
              SpamTrawler::$Registry['visitordetails']['useragent'] = $sVisitorUserAgent;
          }
          return SpamTrawler::$Registry['visitordetails']['useragent'];
      }
}
