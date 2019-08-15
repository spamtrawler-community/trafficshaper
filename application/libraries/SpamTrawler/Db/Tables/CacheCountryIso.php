<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 14/06/14
 * Time: 15:31
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Db_Tables_CacheCountryIso extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = 'cache_countryiso';

    public function getStatsbyCountry()
    {
        //Get Total
        //$oSelect = $this->select();
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('country_code' , 'used' , 'blocked_count' , 'passed_count'));
        $oSelect->group('country_code');
        $order = (array('country_code asc'));
        $oSelect->order($order);

        $aRowsTotal = $this->fetchAll($oSelect)->toArray();

        //exit(json_encode($aRowsTotal));
        $aRowsTotal = array_values($aRowsTotal);
        return $aRowsTotal;
    }
}