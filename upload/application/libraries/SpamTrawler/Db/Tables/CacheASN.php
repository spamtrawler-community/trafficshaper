<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 14/06/14
 * Time: 15:31
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Db_Tables_CacheASN extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = 'cache_asn';

    public function get()
    {
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('asn' , 'used', 'blocked_count', 'passed_count'));

        //Add Order By
        $order = (array('asn asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        //exit(json_encode($aRows));
        return $aRows;
    }
}