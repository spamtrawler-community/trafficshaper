<?php

abstract class Firewall_Abstract_Model_Manage
{
    protected $dbTableName = NULL;
    protected $dbTableNameValueCache = NULL;
    protected $dbFieldNameValueVisitor = NULL;
    protected $dbFieldNameValueCache = NULL;
    public $oInput = NULL;
    protected $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;
    protected $sDbFilterField = NULL;
    protected $aUpdateData = array();
    protected $preValidated = false;

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

        try{
            //Add to IP Blacklist
            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
            $oTable->insert($this->aOutput);

            //Remove from IP Whitelist
            $this->afterCreate();

            //Clear Cache
            $this->deleteCache();

            //Maintain Visitor Cache entries
            $this->cacheMaintenance();

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

        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

        $where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

        $oTable->update($this->aUpdateData, $where);

        $this->afterUpdate();

        //Clear Cache
        $this->deleteCache();

        $this->sOutput = json_encode($this->oInput);
        return true;
    }

    public function destroy($value = null)
    {
        if($value !== null){
            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
            $where = $oTable->getAdapter()->quoteInto($this->sDbFilterField . ' = ?', $value);
            $oTable->delete($where);

            $this->afterDestroy();
        } else {
            if(!$this->validate()){
                return false;
            }

            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

            $where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

            $oTable->delete($where);

            $this->afterDestroy();

            $this->sOutput = json_encode($this->oInput);
        }
        //Clear Cache
        $this->deleteCache();
        return true;
    }

    public function reinitialize()
    {
        try{
            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

            $oTable->truncate();

            $this->afterReInitialize();

            //Clear Cache
            $this->deleteCache();
            return true;
        } catch (Exception $e){
            return false;
        }
    }

    protected function afterCreate(){
        return true;
    }

    protected function afterUpdate(){
        return true;
    }

    protected function afterDestroy(){
        return true;
    }

    protected function afterReInitialize(){
        return true;
    }

    public function validate()
    {
        return false;
    }

    public function deleteCache()
    {
        $sCacheFileName = SpamTrawler::$Config->database->table->prefix . '_' . $this->dbTableName;
        SpamTrawler::$Registry['oCache']->remove($sCacheFileName);
    }

    protected function cacheMaintenance()
    {
        try{
            $sFilterMode = 'exact';
            if(!is_null($this->oInput[0])){
                $value = $this->oInput[0]->{$this->sDbFilterField};
                if(isset($this->oInput[0]->filter_mode)){
                    $sFilterMode = $this->oInput[0]->filter_mode;
                }
            } else if(!is_null($this->aOutput)) {
                $value = $this->aOutput[$this->sDbFilterField];
            } else {
                exit('No Input Data!');
            }

            //Get id from Cache Table
            if(!is_null($this->dbTableNameValueCache) && !is_null($this->dbFieldNameValueCache) && !is_null($this->dbFieldNameValueVisitor)){
            /*    SELECT ip
            FROM spamtrawler_cache_visitors
            LEFT JOIN spamtrawler_cache_countryiso ON spamtrawler_cache_countryiso.id=spamtrawler_cache_visitors.country_codeID
            WHERE spamtrawler_cache_countryiso.country_code = 'US'
            */
                try{
                    $table = new SpamTrawler_Db_Tables_CacheVisitors();
                    $select = $table->select();
                    $select->setIntegrityCheck(false);
                    $select->from(array('v' => SpamTrawler::$Config->database->table->prefix  . '_cache_visitors'), array('v.id', 'v.ip'));
                    $select->joinLeft(array('c' => SpamTrawler::$Config->database->table->prefix  . '_' . $this->dbTableNameValueCache), 'v.' . $this->dbFieldNameValueVisitor . '= c.id', array());

                    if($sFilterMode == 'exact'){
                        $select->where('c.'. $this->dbFieldNameValueCache .' = ?', $value);
                    } else if($sFilterMode == 'contains'){
                        $select->where('c.'. $this->dbFieldNameValueCache . ' LIKE ? ' , '%' . $value . '%');
                    } else {
                        $sRegex = $value;
                        $sRegexFirstChar = $sRegex[0];
                        $sRegexLastChar = $sRegex[strlen($sRegex) - 1];

                        if($sRegexFirstChar === $sRegexLastChar) {
                            $sRegex = str_replace($sRegexFirstChar, '', $sRegex);
                        }

                        $select->where('c.'. $this->dbFieldNameValueCache . ' REGEXP ? ' , $sRegex);
                    }

                    $aRows = $table->fetchAll($select)->toArray();


                    foreach($aRows as $key => $value){
                        $where = $table->getAdapter()->quoteInto('ip = ?', $value['ip']);
                        $table->delete($where);

                        SpamTrawler::$Registry['oCache']->remove(sha1($value['ip']));
                    }

                    //Debug
                    /*
                    if($value == 'US'){
                        var_dump($aRows);
                        exit('Result');
                    }*/

                } catch(Exception $e){
                    exit($e);
                }
            }

            //Removing Cache
            /*
            $oTableCache = new SpamTrawler_Db_Tables_CacheVisitors();
            switch ($sFilterMode){
            case 'exact':
                $where = $oTableCache->getAdapter()->quoteInto($this->sDbFilterField . ' = ? ' , $value);
                break;
            case 'contains':
                $where = $oTableCache->getAdapter()->quoteInto($this->sDbFilterField . ' LIKE ? ' , '%' . $value . '%');
                break;
            case 'regex':
                $sRegex = $value;
                $sRegexFirstChar = $sRegex[0];
                $sRegexLastChar = $sRegex[strlen($sRegex) - 1];

                if($sRegexFirstChar === $sRegexLastChar) {
                    $sRegex = str_replace($sRegexFirstChar, '', $sRegex);
                }
                $where = $oTableCache->getAdapter()->quoteInto($this->sDbFilterField . ' REGEXP ? ' , $sRegex);
                break;
            default:
                $where = $oTableCache->getAdapter()->quoteInto($this->sDbFilterField . ' = ? ' , $value);
                break;
            }

        $aRows = $oTableCache->fetchAll($where)->toArray();
            */

        } catch(Exception $e){
            //logging error
            $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error.log');
            $logger = new SpamTrawler_Log($writer);

            $logger->warn('Database Error: ' . $e->getMessage() . ' (Code: ' . $e . ')');
        }

        return true;
    }
}
