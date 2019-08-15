<?php

class Firewall_IPRangesBlacklist_Model_Manage
{
    private $dbTableName = 'blacklist_ipranges';
    public $oInput = NULL;
    private $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;
    private $bISv6 = false;

    public function __construct($oInput = NULL){
        $this->oInput = $oInput;
    }

    public function create()
    {
        if(!$this->validate()){
            return false;
        }

        $aInput = array(
            'id' => md5($this->oInput[0]->range_start . $this->oInput[0]->range_end),
            'range_start' => $this->oInput[0]->range_start,
            'range_end' => $this->oInput[0]->range_end,
            'comment' => $this->oInput[0]->comment
        );

        $this->aOutput = $aInput;

        if($this->bISv6 === false){
            $aInput['range_start_long'] = ip2long($this->oInput[0]->range_start);
            $aInput['range_end_long'] = ip2long($this->oInput[0]->range_end);
        }
        /*else {
            $aInput['range_start_long'] = bin2hex(inet_pton($this->oInput[0]->range_start));
            $aInput['range_end_long'] = bin2hex(inet_pton($this->oInput[0]->range_end));
        }
        */

        try{
            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
            $oTable->insert($aInput);

            //Clear Cache
            $this->deleteCache();

            $this->sOutput = json_encode($this->aOutput);
            return true;
        } catch(SpamTrawler_Db_Exception $e){
            if($e->getCode() == 1062) {
                $aErrors = array(
                    'Errors' => 'Duplicate Entry!'
                );
            } else {
                $aErrors = array(
                    'Errors' => 'An error occured while inserting to the database!'
                );

                //logging error
                $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error.log');
                $logger = new SpamTrawler_Log($writer);

                $logger->warn('Database Error: ' . $e->getMessage() . ' (Code: ' . $e->getCode . ')');
            }
            $this->sErrors = json_encode($aErrors);
            return false;
        }
    }

    public function get()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');

        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
        $oTable->getJSONP();
    }

    public function update()
    {
        if(!$this->validate()){
            return false;
        }

        $aData = array(
            'range_start' => $this->oInput[0]->range_start,
            'range_end' => $this->oInput[0]->range_end,
            'comment' => $this->oInput[0]->comment
        );

        if($this->bISv6 === false){
            $aInput['range_start_long'] = ip2long($this->oInput[0]->range_start);
            $aInput['range_end_long'] = ip2long($this->oInput[0]->range_end);
        }

        /*/else {
            $aData['range_start_long'] = bin2hex(inet_pton($this->oInput[0]->range_start));
            $aData['range_end_long'] = bin2hex(inet_pton($this->oInput[0]->range_end));
        }
        */

        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

        $where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

        $oTable->update($aData, $where);

        //Clear Cache
        $this->deleteCache();

        $this->sOutput = json_encode($this->oInput);
    }

    public function destroy()
    {
        if(!$this->validate()){
            return false;
        }

        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

        $where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

        $oTable->delete($where);

        //Clear Cache
        $this->deleteCache();

        $this->sOutput = json_encode($this->oInput);
    }

    public function deleteCache()
    {
        $sCacheFileName = SpamTrawler::$Config->database->table->prefix . '_' . $this->dbTableName;
        SpamTrawler::$Registry['oCache']->remove($sCacheFileName);
    }

    public function validate()
    {
        if(is_null($this->oInput)){
                $aErrors = array(
                    'Errors' => 'Input Data Missing!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
        }

        if(isset($this->oInput[0]->id) && !empty($this->oInput[0]->id)){
            if (!ctype_alnum($this->oInput[0]->id)) {
                $aErrors = array(
                    'Errors' => 'Invalid ID!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        }

        if(isset($this->oInput[0]->range_start) && isset($this->oInput[0]->range_end)){
            //Check if both IPs are valid
            if (!filter_var($this->oInput[0]->range_start, FILTER_VALIDATE_IP) || !filter_var($this->oInput[0]->range_end, FILTER_VALIDATE_IP)) {
                $aErrors = array(
                    'Errors' => 'Invalid IP Address in Range!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }

            if(filter_var($this->oInput[0]->range_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($this->oInput[0]->range_end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                //Check if Start IP is lower than End IP
                if (ip2long($this->oInput[0]->range_start) > ip2long($this->oInput[0]->range_end)) {
                    $aErrors = array(
                        'Errors' => 'Start IP has to be lower than End IP!'
                    );

                    $this->sErrors = json_encode($aErrors);
                    return false;
                }
            } elseif(!filter_var($this->oInput[0]->range_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false && !filter_var($this->oInput[0]->range_end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false){
                $this->bISv6 = true;
                if (inet_pton($this->oInput[0]->range_start) > inet_pton($this->oInput[0]->range_end)) {
                    $aErrors = array(
                        'Errors' => 'Start IP has to be lower than End IP!'
                    );

                    $this->sErrors = json_encode($aErrors);
                    return false;
                }
            } elseif(filter_var($this->oInput[0]->range_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && !filter_var($this->oInput[0]->range_end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $aErrors = array(
                    'Errors' => 'Both IPs have to be of the same protocol!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            } elseif(!filter_var($this->oInput[0]->range_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($this->oInput[0]->range_end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $aErrors = array(
                    'Errors' => 'Both IPs have to be of the same protocol!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            } elseif(filter_var($this->oInput[0]->range_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && !filter_var($this->oInput[0]->range_end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $aErrors = array(
                    'Errors' => 'Both IPs have to be of the same protocol!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            } elseif(!filter_var($this->oInput[0]->range_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && filter_var($this->oInput[0]->range_end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $aErrors = array(
                    'Errors' => 'Both IPs have to be of the same protocol!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }

        }

        return true;
    }

    public static function validateSettings($aSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Ranges Blacklist Status Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['allowcaptcha'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Ranges Blacklist Allow Captcha Invalid!';
        }

        $sBlockReason = str_replace(' ', '', $aSettings['conf_params']['block_reason']);
        if(!ctype_alnum($sBlockReason)){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Ip Ranges Blacklist Block Reason Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Ranges Blacklist Order Invalid!';
        }
        */

        if($bErrors === true){
            return false;
        }
        return true;
    }

    public function reinitialize()
    {
        try{
            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

            $oTable->truncate();

            //Clear Cache
            $this->deleteCache();
            return true;
        } catch (Exception $e){
            return false;
        }
    }
}
