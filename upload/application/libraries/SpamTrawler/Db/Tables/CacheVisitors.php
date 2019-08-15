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
        //Get Firewall info
        $sBlockStatus = 'no';
        $sBlockCode = NULL;
        $sBlockReason = NULL;

        if (isset(SpamTrawler::$Registry['visitordetails']['filterresult'])) {
            $sBlockStatus = SpamTrawler::$Registry['visitordetails']['filterresult'];

            if($sBlockStatus === 'blocked'){
                $sBlockStatus = 'yes';
                $sBlockCode = SpamTrawler::$Registry['visitordetails']['filterclass'];
                $sBlockReason = SpamTrawler::$Registry['visitordetails']['blockreason'];
            } else {
                $sBlockStatus = 'no';
            }
        }

        //Cache Hostname
        $hostid = $this->cacheUserAgent($sBlockStatus, 'cache_hostnames', 'host_name', SpamTrawler_VisitorDetails_Hostname_Hostname::get());

        //Cache Referrer
        $referrerid = $this->cacheReferrer($sBlockStatus, SpamTrawler_VisitorDetails_Referrer_Referrer::get());

        //Cache Referrer
        $urlid = $this->cacheURL($sBlockStatus, SpamTrawler_VisitorDetails_Url_Url::get());

        //Cache UserAgent
        $uaid = $this->cacheUserAgent($sBlockStatus, 'cache_useragents', 'user_agent', SpamTrawler_VisitorDetails_UserAgent_UserAgent::get());

        //Cache Username
        $usernameid = $this->cacheUserAgent($sBlockStatus, 'cache_usernames', 'username', SpamTrawler_VisitorDetails_Username_Username::get());

        //Cache email
        $emailid = $this->cacheUserAgent($sBlockStatus, 'cache_emails', 'email', SpamTrawler_VisitorDetails_Email_Email::get());

        //Cache Device
        $deviceid = $this->cacheUserAgent($sBlockStatus, 'cache_devices', 'device', SpamTrawler_VisitorDetails_MobileDetect_GetDevice::get());

        //Cache ASNOrgs
        $asnorgid = $this->cacheUserAgent($sBlockStatus, 'cache_asnorgs', 'asn_org', SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorASNOrg());

        //Cache ASN
        $asnid = $this->cacheASN($sBlockStatus, SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorASN(), $asnorgid);

        //Cache Countryname
        $countrynameid = $this->cacheUserAgent($sBlockStatus, 'cache_countrynames', 'country_name', SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryName());

        //Cache CountryCode
        $countryisoid = $this->cacheCountryIso($sBlockStatus, SpamTrawler_VisitorDetails_GeoIP_GeoIP::getVisitorCountryCode(), $countrynameid);


        $data = array(
            'id'                => md5(SpamTrawler_VisitorDetails_IP_IP::get()),
            'ip'                => SpamTrawler_VisitorDetails_IP_IP::get(),
            'host_nameID'       => $hostid,
            'asnID'             => $asnid,
            'asn_orgID'         => $asnorgid,
            'country_codeID'    => $countryisoid,
            'country_nameID'    => $countrynameid,
            'referrerID'        => $referrerid,
            'user_agentID'      => $uaid,
            'device_typeID'     => $deviceid,
            'emailID'           => $emailid,
            'usernameID'        => $usernameid,
            'urlID'             => $urlid,
            'blocked'           => $sBlockStatus,
            'block_reason'      => $sBlockReason,
            'block_code'        => $sBlockCode,
            'captcha_solved'    => 0,
            'comment'           => '',
            'updated'           => date('Y-m-d H:i:s')
        );

        //Enable modules to set comments in order to get a better understanding of why a filter has blocked a visitor
        if(isset(SpamTrawler::$Registry['visitordetails']['comment'])){
            $data['comment'] = SpamTrawler::$Registry['visitordetails']['comment'];
        }

        //var_dump($data);
        //exit();

        try {
            $this->insert($data);
        } catch (Exception $e) {
            if(23000 === $e->getCode() || 1062 === $e->getCode()){
                $where = $this->getAdapter()->quoteInto('id = ?', $data['id']);
                $this->update($data, $where);
            }
        }
    }



    /*
     * Statistical Functions
     * Return JSON data
    */
    public function getMaxHourToday()
    {
        //Get Total
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('hour(updated) as updated' , 'COUNT(updated) as total'));
        $oSelect->group('hour(updated)');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));

        $aRowsTotal = $this->fetchAll($oSelect)->toArray();

        $tmpArray = array();
        foreach($aRowsTotal as $key => $value){
            $tmpArray[$value['updated']] = $value;
        }

        foreach($tmpArray as $key => $value){
            $tmpArray[$key] = $value['total'];
        }

        if(!empty($tmpArray)){
            return max($tmpArray);
        }
         return 0;
    }

    public function getStatsToday()
    {
        //Get Total
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('hour(updated) as updated' , 'COUNT(updated) as total'));
        $oSelect->group('hour(updated)');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));
        $order = (array('updated asc'));
        $oSelect->order($order);

        $aRowsTotal = $this->fetchAll($oSelect)->toArray();

        $tmpArray = array();
        foreach($aRowsTotal as $key => $value){
            $tmpArray[$value['updated']] = $value;
        }

        foreach($aRowsTotal as $key => $value){
            if(!isset($aRowsTotal[$value['updated']]['blocked'])){
                $aRowsTotal[$value['updated']]['blocked'] = 0;
            }

            if(!isset($aRowsTotal[$value['updated']]['unblocked'])){
                $aRowsTotal[$value['updated']]['unblocked'] = 0;
            }
            //$aRowsTotal[$value['updated']]['captcha'] = 0;

            if(!isset($aRowsTotal[$value['updated']]['captcha'])){
                $aRowsTotal[$value['updated']]['captcha'] = 0;
            }

            $aRowsTotal[$value['updated']]['updated'] = date("ga", strtotime($key['updated']));
            $aRowsTotal[$value['updated']] = array_map('utf8_encode', $value);
        }

        $aRowsTotal = $tmpArray;

        //Get Blocked
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('hour(updated) as updated' , 'COUNT(updated) as total'));
        $oSelect->group('hour(updated)');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));
        $oSelect->where('blocked = ?', 'yes');
        $order = (array('updated asc'));
        $oSelect->order($order);

        $aRowsBlocked = $this->fetchAll($oSelect)->toArray();

        foreach($aRowsBlocked as $key => $value){
            $aRowsTotal[$value['updated']]['blocked'] = $value['total'];
        }

        //Get Unblocked
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('hour(updated) as updated' , 'COUNT(updated) as total'));
        $oSelect->group('hour(updated)');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));
        $oSelect->where('blocked = ?', 'no');
        $order = (array('updated asc'));
        $oSelect->order($order);

        $aRowsUnblocked = $this->fetchAll($oSelect)->toArray();

        foreach($aRowsUnblocked as $key => $value){
            $aRowsTotal[$value['updated']]['unblocked'] = $value['total'];
        }

        //Get Captcha Solved
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('hour(updated) as updated' , 'COUNT(updated) as total'));
        $oSelect->group('hour(updated)');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));
        $oSelect->where('blocked = ?', 'no');
        $oSelect->where('captcha_solved = ?', 1);
        $order = (array('updated asc'));
        $oSelect->order($order);

        $aRowsCaptchaSolved = $this->fetchAll($oSelect)->toArray();

        foreach($aRowsCaptchaSolved as $key => $value){
            $aRowsTotal[$value['updated']]['captcha'] = $value['total'];
        }

        foreach($aRowsTotal as $key => $value){
            if(!isset($aRowsTotal[$value['updated']]['blocked'])){
                $aRowsTotal[$value['updated']]['blocked'] = 0;
            }

            if(!isset($aRowsTotal[$value['updated']]['unblocked'])){
                $aRowsTotal[$value['updated']]['unblocked'] = 0;
            }
            //$aRowsTotal[$value['updated']]['captcha'] = 0;

            if(!isset($aRowsTotal[$value['updated']]['captcha'])){
                $aRowsTotal[$value['updated']]['captcha'] = 0;
            }

            $aRowsTotal[$value['updated']]['updated'] = date("ga", strtotime($key['updated']));
            $aRowsTotal[$value['updated']] = array_map('utf8_encode', $value);
        }


        //print("<pre>".print_r($aRowsTotal,true)."</pre>");
        //exit();

        //exit(json_encode($aRowsTotal));
        $aRowsTotal = array_values($aRowsTotal);
        return $aRowsTotal;
        //exit(json_encode($aRows));
    }

    public function getStatsbyCountry()
    {
        //Get Total
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('country_code' , 'COUNT(country_code) as total'));
        $oSelect->group('country_code');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));
        $order = (array('country_code asc'));
        $oSelect->order($order);

        $aRowsTotal = $this->fetchAll($oSelect)->toArray();

        //return $aRowsTotal;

        $tmpArray = array();
        foreach($aRowsTotal as $key => $value){
            $tmpArray[$value['country_code']] = $value;
        }

        //return $tmpArray;

        foreach($aRowsTotal as $key => $value){
            if(!isset($aRowsTotal[$value['country_code']]['blocked'])){
                $aRowsTotal[$value['country_code']]['blocked'] = 0;
            }

            if(!isset($aRowsTotal[$value['country_code']]['unblocked'])){
                $aRowsTotal[$value['country_code']]['unblocked'] = 0;
            }
            //$aRowsTotal[$value['updated']]['captcha'] = 0;

            if(!isset($aRowsTotal[$value['country_code']]['captcha'])){
                $aRowsTotal[$value['country_code']]['captcha'] = 0;
            }

            $aRowsTotal[$value['country_code']] = array_map('utf8_encode', $value);
        }

        $aRowsTotal = $tmpArray;

        //Get Blocked
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('country_code' , 'COUNT(country_code) as total'));
        $oSelect->group('country_code');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));
        $oSelect->where('blocked = ?', 'yes');
        $order = (array('country_code asc'));
        $oSelect->order($order);

        $aRowsBlocked = $this->fetchAll($oSelect)->toArray();

        foreach($aRowsBlocked as $key => $value){
            $aRowsTotal[$value['country_code']]['blocked'] = $value['total'];
        }

        //Get Unblocked
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('country_code' , 'COUNT(country_code) as total'));
        $oSelect->group('country_code');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));
        $oSelect->where('blocked = ?', 'no');
        $order = (array('country_code asc'));
        $oSelect->order($order);

        $aRowsUnblocked = $this->fetchAll($oSelect)->toArray();

        foreach($aRowsUnblocked as $key => $value){
            $aRowsTotal[$value['country_code']]['unblocked'] = $value['total'];
        }

        //Get Captcha Solved
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('country_code' , 'COUNT(country_code) as total'));
        $oSelect->group('country_code');
        $oSelect->where('date(updated) = ?', date('Y-m-d'));
        $oSelect->where('blocked = ?', 'no');
        $oSelect->where('captcha_solved = ?', 1);
        $order = (array('country_code asc'));
        $oSelect->order($order);

        $aRowsCaptchaSolved = $this->fetchAll($oSelect)->toArray();

        foreach($aRowsCaptchaSolved as $key => $value){
            $aRowsTotal[$value['country_code']]['captcha'] = $value['total'];
        }

        foreach($aRowsTotal as $key => $value){
            if(!isset($aRowsTotal[$value['country_code']]['blocked'])){
                $aRowsTotal[$value['country_code']]['blocked'] = 0;
            }

            if(!isset($aRowsTotal[$value['country_code']]['unblocked'])){
                $aRowsTotal[$value['country_code']]['unblocked'] = 0;
            }
            //$aRowsTotal[$value['updated']]['captcha'] = 0;

            if(!isset($aRowsTotal[$value['country_code']]['captcha'])){
                $aRowsTotal[$value['country_code']]['captcha'] = 0;
            }

            $aRowsTotal[$value['country_code']] = array_map('utf8_encode', $value);
        }


        //print("<pre>".print_r($aRowsTotal,true)."</pre>");
        //exit();

        //exit(json_encode($aRowsTotal));
        $aRowsTotal = array_values($aRowsTotal);
        return $aRowsTotal;
        //exit(json_encode($aRows));
    }


    public function getStatsByCountryOld()
    {
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('user_agent' , 'COUNT(country_code) as total'));
        $oSelect->group('user_agent');
        $oSelect->where('user_agent REGEXP ?', 'Java');

        //Add Order By
        $order = (array('user_agent asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();


        return $aRows;
    }

    public function getStatsByFilter()
    {
    	//Instantiate Select
    	$oSelect = $this->select();
    	$oSelect->from($this->_name, array('block_code' , 'COUNT(block_code) as total'));
    	$oSelect->group('block_code');
    
    	//Add Order By
    	$order = (array('block_code asc'));
    	$oSelect->order($order);
    
    	$aRows = $this->fetchAll($oSelect)->toArray();
    
    	//exit(json_encode($aRows));
    	return $aRows;
    }

    public function getUseragents()
    {
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array('user_agent' , 'COUNT(user_agent) as numused'));
        $oSelect->where($this->getAdapter()->quoteInto('user_agent != ?', null));
        $oSelect->group('user_agent');

        //Add Order By
        $order = (array('user_agent asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        //exit(json_encode($aRows));
        return $aRows;
    }

    public function getByField($sField)
    {
        $aAllowedFields = array(
            'host_name',
            'asn',
            'asn_org',
            'country_code',
            'country_name',
            'referrer',
            'user_agent',
            'device_type',
            'email',
            'username',
            'url'
        );

        if(!in_array($sField, $aAllowedFields)){
            exit('Invalid Field!');
        }
        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->from($this->_name, array($sField , 'COUNT(' . $sField . ') as numused'));
        $oSelect->where($this->getAdapter()->quoteInto($sField . ' != ?', null));
        $oSelect->group($sField);

        //Add Order By
        $order = (array( $sField . ' asc'));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        //exit(json_encode($aRows));
        return $aRows;
    }

    public function reInitialize(){
        try{
            $this->truncate();

            try{
                SpamTrawler::$Registry['oCache']->clean(SpamTrawler_Cache::CLEANING_MODE_ALL);
            } catch(Exception $e){
                $error = 'An Error Occurred while clearing the cache entries!';
                return $error;
            }
        } catch(Exception $e){
            $error = 'An Error Occurred while clearing the cache table!';
            return $error;
        }
        return('Cache cleared successfully!');
    }

    public function normalize(){
        try{
            $where = $this->getAdapter()->quoteInto('time(updated) <= ?', time() - SpamTrawler::$Config->cache->frontend_options->lifetime);
            $this->delete($where);

            try{
                SpamTrawler::$Registry['oCache']->clean(SpamTrawler_Cache::CLEANING_MODE_OLD);
            } catch(Exception $e){
                $error = 'An Error Occurred while normalizing the cache entries!';
                return $error;
            }
        } catch(Exception $e){
            $error = 'An Error Occurred while normalizing the cache table! <br />' . $e;
            return $error;
        }
        return('Cache normalized successfully!');
    }

    public function returnJSONP($aWhereParam = array())
    {
        $oFields = NULL;
        if(isset($_POST['fields'])){
            $oFields = json_decode($_POST['fields']);
        }

        //Instantiate Select
        $oSelect = $this->select();
        $oSelect->setIntegrityCheck(false);
        $oSelect->from(array('v' => $this->TableName), array('v.id', 'v.ip', 'v.blocked', 'v.block_code','v.block_reason', 'v.captcha_solved', 'v.updated','v.comment'));
        $oSelect->joinLeft(array('ua' => SpamTrawler::$Config->database->table->prefix  . '_cache_useragents'), 'v.user_agentID = ua.id', array('ua.user_agent as user_agent'));
        $oSelect->joinLeft(array('h' => SpamTrawler::$Config->database->table->prefix  . '_cache_hostnames'), 'v.host_nameID = h.id', array('h.host_name as host_name'));
        $oSelect->joinLeft(array('uname' => SpamTrawler::$Config->database->table->prefix  . '_cache_usernames'), 'v.usernameID = uname.id', array('uname.username as username'));
        $oSelect->joinLeft(array('asn' => SpamTrawler::$Config->database->table->prefix  . '_cache_asn'), 'v.asnID = asn.id', array('asn.asn as asn'));
        $oSelect->joinLeft(array('asnorg' => SpamTrawler::$Config->database->table->prefix  . '_cache_asnorgs'), 'v.asn_orgID = asnorg.id', array('asnorg.asn_org as asn_org'));
        $oSelect->joinLeft(array('countryiso' => SpamTrawler::$Config->database->table->prefix  . '_cache_countryiso'), 'v.country_codeID = countryiso.id', array('countryiso.country_code as country_code'));
        $oSelect->joinLeft(array('countryname' => SpamTrawler::$Config->database->table->prefix  . '_cache_countrynames'), 'v.country_nameID = countryname.id', array('countryname.country_name as country_name'));
        $oSelect->joinLeft(array('email' => SpamTrawler::$Config->database->table->prefix  . '_cache_emails'), 'v.emailID = email.id', array('email.email as email'));
        $oSelect->joinLeft(array('referrer' => SpamTrawler::$Config->database->table->prefix  . '_cache_referrer'), 'v.referrerID = referrer.id', array('referrer.referrer as referrer'));
        $oSelect->joinLeft(array('url' => SpamTrawler::$Config->database->table->prefix  . '_cache_urls'), 'v.urlID = url.id', array('url.url as url'));
        $oSelect->joinLeft(array('device' => SpamTrawler::$Config->database->table->prefix  . '_cache_devices'), 'v.device_typeID = device.id', array('device.device as device_type'));


        //Allow where parameter to be passed when calling function from php directly
        if(is_array($aWhereParam) && !empty($aWhereParam)){
            foreach($aWhereParam as $key => $value){
                if(is_array($aWhereParam[$key]) && isset($aWhereParam[$key]['field']) && isset($aWhereParam[$key]['value'])){
                    $oSelect->where($aWhereParam[$key]['field'] . ' = ?', $aWhereParam[$key]['value']);
                }
            }
        }

        //Filter
        //->where('bug_status = ?', 'NEW')
        if(isset($oFields->filter) && !empty($oFields->filter)){
            $aLogicWhitelist = array('and', 'or');
            $sFilterLogic = $oFields->filter->logic;

            foreach($oFields->filter->filters as $sFiltersKey => $aFiltersValue){
                $sFilterField = $oFields->filter->filters[$sFiltersKey]->field;
                $sFilterOperator = $oFields->filter->filters[$sFiltersKey]->operator;
                $sFilterValue = $oFields->filter->filters[$sFiltersKey]->value;

                //Check if filtering regex field
                if($sFilterField == 'isregex'){
                    if($sFilterValue == '1'){
                        $sFilterValue = 'true';
                    } elseif($sFilterValue == '0'){
                        $sFilterValue = 'false';
                    }
                }

                if($sFiltersKey == 0 || !($sFiltersKey & 1)){
                    switch ($sFilterOperator) {
                        case 'eq':
                            $oSelect->where($sFilterField . ' = ?', $sFilterValue);
                            break;
                        case 'neq':
                            $oSelect->where($sFilterField . ' != ?', $sFilterValue);
                            break;
                        case 'startswith':
                            $oSelect->where($sFilterField . ' LIKE ?', $sFilterValue . '%');
                            break;
                        case 'contains':
                            $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                            break;
                        case 'doesnotcontain':
                            $oSelect->where($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                            break;
                        case 'endswith':
                            $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                            break;
                    }
                } else {
                    if(in_array($sFilterLogic, $aLogicWhitelist)){
                        if($sFilterLogic == 'and'){
                            switch ($sFilterOperator) {
                                case 'eq':
                                    $oSelect->where($sFilterField . ' = ?', $sFilterValue);
                                    break;
                                case 'neq':
                                    $oSelect->where($sFilterField . ' != ?', $sFilterValue);
                                    break;
                                case 'startswith':
                                    $oSelect->where($sFilterField . ' LIKE ?', $sFilterValue . '%');
                                    break;
                                case 'contains':
                                    $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'doesnotcontain':
                                    $oSelect->where($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'endswith':
                                    $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                                    break;
                            }
                        } else {
                            switch ($sFilterOperator) {
                                case 'eq':
                                    $oSelect->orWhere($sFilterField . ' = ?', $sFilterValue);
                                    break;
                                case 'neq':
                                    $oSelect->orWhere($sFilterField . ' != ?', $sFilterValue);
                                    break;
                                case 'startswith':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', $sFilterValue . '%');
                                    break;
                                case 'contains':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'doesnotcontain':
                                    $oSelect->orWhere($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'endswith':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                                    break;
                            }
                        }
                    }
                }
            }
        }

        //Order By
        $aOrderDirectionsWhitelist = array('asc', 'desc');
        if(isset($oFields->sort[0]->field) && isset($oFields->sort[0]->dir) && ctype_alpha($oFields->sort[0]->field) && in_array($oFields->sort[0]->dir, $aOrderDirectionsWhitelist)){
            $sOrderField = $oFields->sort[0]->field;
            $sOrderDirection = $oFields->sort[0]->dir;
        } else {
            $sOrderField = 'updated';
            $sOrderDirection = 'desc';
        }

        //Add Order By
        $order = (array($sOrderField . ' ' . $sOrderDirection));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        // Return only chunk of data
        $count = 1;
        $offset = 0;
        if(isset($oFields->take)){$count  = $oFields->take;}
        if(isset($oFields->skip)){$offset  = $oFields->skip;}
        $aOutput = array_slice($aRows, $offset, $count);

        array_walk_recursive($aOutput, function (&$value) {
            $value = utf8_encode($value);
        });

        return json_encode(array("data" => $aOutput, "total" => count($aRows)));
    }

    private function cacheUserAgent($sBlockStatus, $sTableName, $sDataField, $sValue){
        //Cache UserAgent
        $uaID = 0;
        $oTableUseragent = new SpamTrawler_Db_Tables_Generic($sTableName);
        $aDataUserAgent = array(
            $sDataField => $sValue
        );
        try {
            $aDataUserAgent['used'] = 1;
            if($sBlockStatus == 'yes'){
                $aDataUserAgent['blocked_count'] = 1;
                $aDataUserAgent['passed_count'] = 0;
            } else {
                $aDataUserAgent['blocked_count'] = 0;
                $aDataUserAgent['passed_count'] = 1;
            }
            $oTableUseragent->insert($aDataUserAgent);
            $uaID = $oTableUseragent->getAdapter()->lastInsertId();
        } catch (Exception $e) {
            if(23000 === $e->getCode() || 1062 === $e->getCode()){
                $aDataUserAgent['used'] = new SpamTrawler_Db_Expr('used + 1');
                if($sBlockStatus == 'yes'){
                    $aDataUserAgent['blocked_count'] = new SpamTrawler_Db_Expr('blocked_count + 1');
                    unset($aDataUserAgent['passed_count']);
                } else {
                    unset($aDataUserAgent['blocked_count']);
                    $aDataUserAgent['passed_count'] = new SpamTrawler_Db_Expr('passed_count + 1');
                }
                $where = $oTableUseragent->getAdapter()->quoteInto($sDataField . ' = ?', $aDataUserAgent[$sDataField]);
                $oTableUseragent->update($aDataUserAgent, $where);

                $select = $oTableUseragent->select();
                $select->from($oTableUseragent, array('id'))
                    ->where($where);
                $rowUaID = $oTableUseragent->fetchRow($select);

                $uaID = $rowUaID->id;
            }
        }
        return $uaID;
    }

    private function cacheASN($sBlockStatus, $sASN, $iASNOrg){
        //Cache UserAgent
        $uaID = 0;
        $oTableUseragent = new SpamTrawler_Db_Tables_Generic('cache_asn');
        $aDataUserAgent = array(
            'asn' => $sASN,
            'asn_orgID' => $iASNOrg
        );
        try {
            $aDataUserAgent['used'] = 1;
            if($sBlockStatus == 'yes'){
                $aDataUserAgent['blocked_count'] = 1;
                $aDataUserAgent['passed_count'] = 0;
            } else {
                $aDataUserAgent['blocked_count'] = 0;
                $aDataUserAgent['passed_count'] = 1;
            }
            $oTableUseragent->insert($aDataUserAgent);
            $uaID = $oTableUseragent->getAdapter()->lastInsertId();
        } catch (Exception $e) {
            if(23000 === $e->getCode() || 1062 === $e->getCode()){
                $aDataUserAgent['used'] = new SpamTrawler_Db_Expr('used + 1');
                if($sBlockStatus == 'yes'){
                    $aDataUserAgent['blocked_count'] = new SpamTrawler_Db_Expr('blocked_count + 1');
                    unset($aDataUserAgent['passed_count']);
                } else {
                    unset($aDataUserAgent['blocked_count']);
                    $aDataUserAgent['passed_count'] = new SpamTrawler_Db_Expr('passed_count + 1');
                }
                $where = $oTableUseragent->getAdapter()->quoteInto('asn = ?', $aDataUserAgent['asn']);
                $oTableUseragent->update($aDataUserAgent, $where);

                $select = $oTableUseragent->select();
                $select->from($oTableUseragent, array('id'))
                    ->where($where);
                $rowUaID = $oTableUseragent->fetchRow($select);

                $uaID = $rowUaID->id;
            }
        }
        return $uaID;
    }

    private function cacheCountryIso($sBlockStatus, $sCountryIso, $iCountryNameID){
        //Cache UserAgent
        $uaID = 0;
        $oTableUseragent = new SpamTrawler_Db_Tables_Generic('cache_countryiso');
        $aDataUserAgent = array(
            'country_code' => $sCountryIso,
            'country_nameID' => $iCountryNameID
        );
        try {
            $aDataUserAgent['used'] = 1;
            if($sBlockStatus == 'yes'){
                $aDataUserAgent['blocked_count'] = 1;
                $aDataUserAgent['passed_count'] = 0;
            } else {
                $aDataUserAgent['blocked_count'] = 0;
                $aDataUserAgent['passed_count'] = 1;
            }
            $oTableUseragent->insert($aDataUserAgent);
            $uaID = $oTableUseragent->getAdapter()->lastInsertId();
        } catch (Exception $e) {
            if(23000 === $e->getCode() || 1062 === $e->getCode()){
                $aDataUserAgent['used'] = new SpamTrawler_Db_Expr('used + 1');
                if($sBlockStatus == 'yes'){
                    $aDataUserAgent['blocked_count'] = new SpamTrawler_Db_Expr('blocked_count + 1');
                    unset($aDataUserAgent['passed_count']);
                } else {
                    unset($aDataUserAgent['blocked_count']);
                    $aDataUserAgent['passed_count'] = new SpamTrawler_Db_Expr('passed_count + 1');
                }
                $where = $oTableUseragent->getAdapter()->quoteInto('country_code = ?', $aDataUserAgent['country_code']);
                $oTableUseragent->update($aDataUserAgent, $where);

                $select = $oTableUseragent->select();
                $select->from($oTableUseragent, array('id'))
                    ->where($where);
                $rowUaID = $oTableUseragent->fetchRow($select);

                $uaID = $rowUaID->id;
            }
        }
        return $uaID;
    }

    private function cacheReferrer($sBlockStatus, $sReferrer){
        //Cache UserAgent
        $uaID = 0;
        $oTableUseragent = new SpamTrawler_Db_Tables_Generic('cache_referrer');
        $aDataUserAgent = array(
            'referrer' => $sReferrer,
            'referrer_hash' => sha1($sReferrer)
        );
        try {
            $aDataUserAgent['used'] = 1;
            if($sBlockStatus == 'yes'){
                $aDataUserAgent['blocked_count'] = 1;
                $aDataUserAgent['passed_count'] = 0;
            } else {
                $aDataUserAgent['blocked_count'] = 0;
                $aDataUserAgent['passed_count'] = 1;
            }
            $oTableUseragent->insert($aDataUserAgent);
            $uaID = $oTableUseragent->getAdapter()->lastInsertId();
        } catch (Exception $e) {
            if(23000 === $e->getCode() || 1062 === $e->getCode()){
                $aDataUserAgent['used'] = new SpamTrawler_Db_Expr('used + 1');
                if($sBlockStatus == 'yes'){
                    $aDataUserAgent['blocked_count'] = new SpamTrawler_Db_Expr('blocked_count + 1');
                    unset($aDataUserAgent['passed_count']);
                } else {
                    unset($aDataUserAgent['blocked_count']);
                    $aDataUserAgent['passed_count'] = new SpamTrawler_Db_Expr('passed_count + 1');
                }
                $where = $oTableUseragent->getAdapter()->quoteInto('referrer_hash = ?', $aDataUserAgent['referrer_hash']);
                $oTableUseragent->update($aDataUserAgent, $where);

                $select = $oTableUseragent->select();
                $select->from($oTableUseragent, array('id'))
                    ->where($where);
                $rowUaID = $oTableUseragent->fetchRow($select);

                $uaID = $rowUaID->id;
            }
        }
        return $uaID;
    }

    private function cacheURL($sBlockStatus, $sURL){
        //Cache UserAgent
        $uaID = 0;
        $oTableUseragent = new SpamTrawler_Db_Tables_Generic('cache_urls');
        $aDataUserAgent = array(
            'url' => $sURL,
            'url_hash' => sha1($sURL)
        );
        try {
            $aDataUserAgent['used'] = 1;
            if($sBlockStatus == 'yes'){
                $aDataUserAgent['blocked_count'] = 1;
                $aDataUserAgent['passed_count'] = 0;
            } else {
                $aDataUserAgent['blocked_count'] = 0;
                $aDataUserAgent['passed_count'] = 1;
            }
            $oTableUseragent->insert($aDataUserAgent);
            $uaID = $oTableUseragent->getAdapter()->lastInsertId();
        } catch (Exception $e) {
            if(23000 === $e->getCode() || 1062 === $e->getCode()){
                $aDataUserAgent['used'] = new SpamTrawler_Db_Expr('used + 1');
                if($sBlockStatus == 'yes'){
                    $aDataUserAgent['blocked_count'] = new SpamTrawler_Db_Expr('blocked_count + 1');
                    unset($aDataUserAgent['passed_count']);
                } else {
                    unset($aDataUserAgent['blocked_count']);
                    $aDataUserAgent['passed_count'] = new SpamTrawler_Db_Expr('passed_count + 1');
                }
                $where = $oTableUseragent->getAdapter()->quoteInto('url_hash = ?', $aDataUserAgent['url_hash']);
                $oTableUseragent->update($aDataUserAgent, $where);

                $select = $oTableUseragent->select();
                $select->from($oTableUseragent, array('id'))
                    ->where($where);
                $rowUaID = $oTableUseragent->fetchRow($select);

                $uaID = $rowUaID->id;
            }
        }
        return $uaID;
    }
}