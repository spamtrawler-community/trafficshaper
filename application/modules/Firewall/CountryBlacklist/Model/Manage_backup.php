<?php

class Firewall_CountryBlacklist_Model_Manage
{
    private $dbTableName = 'blacklist_countries';
    public $oInput = NULL;
    private $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;

    public function __construct($oInput = NULL){
        $this->oInput = $oInput;
    }

    public function get()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');

        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
        $oTable->getJSONP();
    }

    public function create()
    {
        if(!$this->validate()){
            return false;
        }

        $this->aOutput = array(
            'id' => md5($this->oInput[0]->iso),
            'iso' => $this->oInput[0]->iso,
            'comment' => $this->oInput[0]->comment
        );

        try{
            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
            $oTable->insert($this->aOutput);

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

    public function update()
    {
        if(!$this->validate()){
            return false;
        }

        $aData = array(
            'iso' => $this->oInput[0]->iso,
            'comment' => $this->oInput[0]->comment
        );

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


        if(!preg_match('/^[A-Z0-9]{2}$/', $this->oInput[0]->iso)){
            $aErrors = array(
                'Errors' => 'Invalid Country Code!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }


        return true;
    }

    public static function validateSettings($aSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Country Blacklist Status Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['allowcaptcha'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Country Blacklist Allow Captcha Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Country Blacklist Order Invalid!';
        }
        */

        $sBlockReason = str_replace(' ', '', $aSettings['conf_params']['block_reason']);
        if(!ctype_alnum($sBlockReason)){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Country Blacklist Block Reason Invalid!';
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
