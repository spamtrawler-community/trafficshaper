<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 20/06/14
 * Time: 09:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_VisitorDetails_BlockInfo_BlockInfo
{
      public static function getBlockStatus()
      {
          if(!array_key_exists('blocked', SpamTrawler::$aVisitorDetails )){
              SpamTrawler::$aVisitorDetails['blocked'] = 'no';
          }
          return SpamTrawler::$aVisitorDetails['blocked'];
      }

    public static function getBlockCode()
    {
        if(!array_key_exists('block_code', SpamTrawler::$aVisitorDetails )){
            SpamTrawler::$aVisitorDetails['block_code'] = NULL;
        }
        return SpamTrawler::$aVisitorDetails['block_code'];
    }

    public static function getBlockReason()
    {
        if(!array_key_exists('block_reason', SpamTrawler::$aVisitorDetails )){
            SpamTrawler::$aVisitorDetails['block_reason'] = NULL;
        }
        return SpamTrawler::$aVisitorDetails['block_reason'];
    }
}
