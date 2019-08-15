<?php

class Firewall_IPBlacklist_Model_Manage
{
    private $dbTableName = 'blacklist_ips';
    public $oInput = NULL;
    private $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;

    public function __construct($oInput = NULL){
        $this->oInput = $oInput;
    }

    public function create()
    {
        if(!$this->validate()){
            return false;
        }

        $this->aOutput = array(
            'id' => md5($this->oInput[0]->ip),
            'ip' => $this->oInput[0]->ip,
            'comment' => $this->oInput[0]->comment
        );

        try{
        	//Add to IP Blacklist
            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
            $oTable->insert($this->aOutput);

            //Remove from IP Whitelist
            $oManage = new Firewall_IPWhitelist_Model_Manage();
            $oManage->destroy($this->oInput[0]->ip);

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
            'ip' => $this->oInput[0]->ip,
            'comment' => $this->oInput[0]->comment
        );

        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

        $where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

        $oTable->update($aData, $where);

        //Clear Cache
        $this->deleteCache();

        $this->sOutput = json_encode($this->oInput);
        return true;
    }

    public function destroy($ip = null)
    {
    	if($ip !== null){
    		$oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
    		$where = $oTable->getAdapter()->quoteInto('ip = ?', $ip);
    		$oTable->delete($where);
    	} else {
    		if(!$this->validate()){
    			return false;
    		}

    		$oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

    		$where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

    		$oTable->delete($where);

    		$this->sOutput = json_encode($this->oInput);
    	}
    	//Clear Cache
    	$this->deleteCache();
    	return true;
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

        if(isset($this->oInput[0]->ip)){
            if (!filter_var($this->oInput[0]->ip, FILTER_VALIDATE_IP)) {
                $aErrors = array(
                    'Errors' => 'Invalid IP Address!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        }

        /*
        if(isset($this->oInput[0]->comment)){
            $validator = new SpamTrawler_Validate_Ip();
            if (!$validator->isValid($this->oInput[0]->ip)) {
                $aErrors = array(
                    'Errors' => 'Invalid IP Address!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        }
        */

        return true;
    }

    public static function validateSettings($aSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Blacklist Status Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['allowcaptcha'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Blacklist Allow Captcha Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Blacklist Order Invalid!';
        }
        */

        $sBlockReason = str_replace(' ', '', $aSettings['conf_params']['block_reason']);
        if(!ctype_alnum($sBlockReason)){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'IP Blacklist Block Reason Invalid!';
        }

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
