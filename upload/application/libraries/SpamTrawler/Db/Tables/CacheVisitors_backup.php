<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 14/06/14
 * Time: 15:31
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Db_Tables_CacheVisitors extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = 'cache_visitors';

    public function add(){

        $data = array(
            'id'                => md5(SpamTrawler_VisitorDetails_IP_IP::get()),
            'ip'                => SpamTrawler_VisitorDetails_IP_IP::get(),
            'host_name'         => SpamTrawler_VisitorDetails_Hostname_Hostname::get(),
            'country_code'      => SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryCode(),
            'country_name'      => SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryName(),
            'referrer'          => SpamTrawler_VisitorDetails_Referrer_Referrer::get(),
            'user_agent'        => SpamTrawler_VisitorDetails_UserAgent_UserAgent::get(),
            'device_type'       => SpamTrawler_VisitorDetails_MobileDetect_GetDevice::get(),
            'email'             => SpamTrawler_VisitorDetails_Email_Email::get(),
            'username'          => SpamTrawler_VisitorDetails_Username_Username::get(),
            'url'               => SpamTrawler_VisitorDetails_Url_Url::get(),
            'blocked'           => SpamTrawler_VisitorDetails_BlockInfo_BlockInfo::getBlockStatus(),
            'block_reason'      => SpamTrawler_VisitorDetails_BlockInfo_BlockInfo::getBlockReason(),
            'block_code'        => SpamTrawler_VisitorDetails_BlockInfo_BlockInfo::getBlockCode(),
            'captcha_solved'    => 0,
            'comment'           => '',
            'updated'           => date('Y-m-d H:i:s')
        );

        try {
            $this->insert($data);
        } catch (Exception $e) {
            if(23000 === $e->getCode()){
                $where = $this->getAdapter()->quoteInto('id = ?', $data['id']);
                $this->update($data, $where);
            }
        }
    }

    /*
     * Statistical Functions
     * Return JSON data
    */
    public function getStatsToday()
    {
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('time(updated) as updated' , 'COUNT(updated) as total'));
        $oSelect->group('time(updated)');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));

        //Add Order By
        $order = (array('updated asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();


        foreach($aRows as $key => $row){
            //Get Counts for blocked and unblocked visitors
            $oSelect = $this->select();
            $oSelect->from($this->_name, array('COUNT(updated) as blocked'));
            $oSelect->where('hour(updated) = ?', date("H", strtotime($row['updated'])));
            $oSelect->where('blocked = ?', 'yes');
            $aRowsBlocked = $this->fetchAll($oSelect)->toArray();

            $row['blocked'] = $aRowsBlocked[0]['blocked'];

            //Get Counts for blocked and unblocked visitors
            $oSelect = $this->select();
            $oSelect->from($this->_name, array('COUNT(updated) as unblocked'));
            $oSelect->where('hour(updated) = ?', date("H", strtotime($row['updated'])));
            $oSelect->where('blocked = ?', 'no');
            $aRowsBlocked = $this->fetchAll($oSelect)->toArray();

            $row['unblocked'] = $aRowsBlocked[0]['unblocked'];

            //Get Counts for blocked and unblocked visitors
            $oSelect = $this->select();
            $oSelect->from($this->_name, array('COUNT(updated) as captcha'));
            $oSelect->where('hour(updated) = ?', date("H", strtotime($row['updated'])));
            $oSelect->where('captcha_solved = ?', 1);
            $aRowsBlocked = $this->fetchAll($oSelect)->toArray();

            $row['captcha'] = $aRowsBlocked[0]['captcha'];

            //echo(date("h", strtotime($row['updated'])) . ' -> ' . $row['updated'] . ': Blocked = ' . $row['blocked'] . ': Unblocked = ' . $row['unblocked'] . '<br />');



            $row['updated'] = date("ga", strtotime($row['updated']));
            $aRows[$key] = array_map('utf8_encode', $row);
            //$row[$key] = utf8_encode($value);
        }

        //exit(json_encode($aRows));
        exit(json_encode($aRows));
    }

    public function getStatsByCountry()
    {
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('country_code as country' , 'COUNT(country_code) as total'));
        $oSelect->group('country_code');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));

        //Add Order By
        $order = (array('country_code asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        foreach($aRows as $key => $row){
            //Get Counts for blocked and unblocked visitors
            $oSelect = $this->select();
            $oSelect->from($this->_name, array('COUNT(updated) as blocked'));
            $oSelect->where('country_code = ?', $row['country']);
            $oSelect->where('blocked = ?', 'yes');
            $aRowsBlocked = $this->fetchAll($oSelect)->toArray();

            $row['blocked'] = $aRowsBlocked[0]['blocked'];

            //Get Counts for blocked and unblocked visitors
            $oSelect = $this->select();
            $oSelect->from($this->_name, array('COUNT(updated) as unblocked'));
            $oSelect->where('country_code = ?', $row['country']);
            $oSelect->where('blocked = ?', 'no');
            $aRowsBlocked = $this->fetchAll($oSelect)->toArray();

            $row['unblocked'] = $aRowsBlocked[0]['unblocked'];

            //Get Counts for blocked and unblocked visitors
            $oSelect = $this->select();
            $oSelect->from($this->_name, array('COUNT(updated) as captcha'));
            $oSelect->where('country_code = ?', $row['country']);
            $oSelect->where('captcha_solved = ?', 1);
            $aRowsBlocked = $this->fetchAll($oSelect)->toArray();

            $row['captcha'] = $aRowsBlocked[0]['captcha'];

            //echo(date("h", strtotime($row['updated'])) . ' -> ' . $row['updated'] . ': Blocked = ' . $row['blocked'] . ': Unblocked = ' . $row['unblocked'] . '<br />');


            $aRows[$key] = array_map('utf8_encode', $row);
            //$row[$key] = utf8_encode($value);
        }

        //exit(json_encode($aRows));
        exit(json_encode($aRows));
    }
}
