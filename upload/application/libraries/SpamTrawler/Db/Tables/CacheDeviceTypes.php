<?php

class SpamTrawler_Db_Tables_CacheDeviceTypes extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = 'cache_devices';

    public function getStatsbyDevice()
    {
        //Get Total
        //$oSelect = $this->select();
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('device' , 'used' , 'blocked_count' , 'passed_count'));
        $oSelect->group('device');
        $order = (array('device asc'));
        $oSelect->order($order);

        $aRowsTotal = $this->fetchAll($oSelect)->toArray();

        //exit(json_encode($aRowsTotal));
        $aRowsTotal = array_values($aRowsTotal);
        return $aRowsTotal;
    }
}
