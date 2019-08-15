<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 14/06/14
 * Time: 15:31
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Db_Tables_CacheHostnames extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = 'cache_hostnames';

    public function getHostnames()
    {
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('host_name' , 'used', 'blocked_count', 'passed_count'));

        //Add Order By
        $order = (array('host_name asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        //exit(json_encode($aRows));
        return $aRows;
    }
}