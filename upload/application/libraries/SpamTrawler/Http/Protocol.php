<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 11:05
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Http_Protocol
{
      public static function get(){
          if(!isset(SpamTrawler::$Registry['requestprotocol'])){
          $isSecure = false;
          if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
              $isSecure = true;
          }
          elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
              $isSecure = true;
          }
              $sRequestProtocol = $isSecure ? 'https' : 'http';

              SpamTrawler::$Registry['requestprotocol'] = $sRequestProtocol;
          }
              return SpamTrawler::$Registry['requestprotocol'];
      }
}
