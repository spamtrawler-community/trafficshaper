<?php

class Firewall_CleanTalk_Model_Manage
{
    private $dbTableName = 'cleantalk_urls';
    public $oInput = NULL;
    private $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;

    public function __construct($oInput = NULL){
        if($oInput) {
            $this->oInput = $oInput;

            //filter_mode
            if (!$this->oInput[0]->filter_mode) {
                $this->oInput[0]->filter_mode = 'exact';
            }
        }
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
            'id' => md5($this->oInput[0]->url),
            'url' => $this->oInput[0]->url,
            'filter_mode' => $this->oInput[0]->filter_mode,
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
            'url' => $this->oInput[0]->url,
            'filter_mode' => $this->oInput[0]->filter_mode,
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

        //filter_mode
        $aAllowedFilterModes = array('exact','contains','regex');
        if(!in_array($this->oInput[0]->filter_mode, $aAllowedFilterModes)){
            $aErrors = array(
                'Errors' => 'Invalid Filter Mode!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        } elseif($this->oInput[0]->filter_mode == 'regex'){
            try{
                //Instantiate SpamTrawler Validator Regex
                new SpamTrawler_Validate_Regex(array('pattern' => $this->oInput[0]->url));
            } catch(Exception $e) {
                $aErrors = array(
                    'Errors' => 'Invalid Regular Expression!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
            }
        } elseif($this->oInput[0]->filter_mode == 'exact') {
            if(!filter_var($this->oInput[0]->url, FILTER_VALIDATE_URL)){
                $aErrors = array(
                    'Errors' => 'Invalid URL!'
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
            Firewall_Core_Controller_Manage::$aErrors[] = 'CleanTalk Status Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['allowcaptcha'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'CleanTalk Allow Captcha Invalid!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'CleanTalk Order Invalid!';
        }
        */

        $sBlockReason = str_replace(' ', '', $aSettings['conf_params']['block_reason']);
        if(!ctype_alnum($sBlockReason)){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'CleanTalk Block Reason Invalid!';
        }

        if(!empty($aSettings['conf_params']['api_key']) && !ctype_alnum($aSettings['conf_params']['api_key'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'CleanTalk API Key Invalid!';
        }

        if($bErrors === true){
            return false;
        }
        return true;
    }
}
