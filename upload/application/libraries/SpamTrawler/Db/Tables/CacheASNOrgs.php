<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 14/06/14
 * Time: 15:31
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Db_Tables_CacheASNOrgs extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = 'cache_asnorgs';

    public function get()
    {
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('asn_org' , 'used', 'blocked_count', 'passed_count'));

        //Add Order By
        $order = (array('asn_org asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        array_walk_recursive($aRows, function (&$value) {
            $value = utf8_encode($value);
        });

        //exit(json_encode($aRows));
        return $aRows;
    }
}