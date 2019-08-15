<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 14/06/14
 * Time: 15:31
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Db_Tables_CacheUseragents extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = 'cache_useragents';

    public function getUseragents()
    {
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('user_agent' , 'used', 'blocked_count', 'passed_count'));
        //$oSelect->where($this->getAdapter()->quoteInto('user_agent != ?', null));
        //$oSelect->group('user_agent');

        //Add Order By
        $order = (array('user_agent asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        //exit(json_encode($aRows));
        return $aRows;
    }
}