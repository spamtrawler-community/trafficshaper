<?php

class Admin_Notifications_Model_Manage {
    private $oTable;
    public $oInput = NULL;
    private $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;

    public function __construct(){
        $this->oTable = new SpamTrawler_Db_Tables_Generic('notifications');
    }

    public function get(){
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=utf-8');

        $this->oTable->getJSONP();
    }

    public function checkNew(){
        $NumNotifications = SpamTrawler::$Registry['oCache']->load('notifications');
        if(!$NumNotifications) {
            $NumNotifications = $this->oTable->getCount();
            SpamTrawler::$Registry['oCache']->save($NumNotifications, 'notifications');
        }
        return $NumNotifications;
    }

    public function add($sSubject, $sText){
        try{
            $aData = array(
                'id' => sha1($sText . microtime()),
                'subject' => $sSubject,
                'text' => $sText
            );

            $this->oTable->insert($aData);
            $this->deleteCache();
        } catch (Exception $e){
            //logging error
            $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error.log');
            $logger = new SpamTrawler_Log($writer);

            $logger->warn('Database Error: ' . $e->getMessage() . ' (Code: ' . $e->getCode . ')');
        }
    }

    public function destroy()
    {
        if(!$this->validate()){
            return false;
        }

        $where = $this->oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

        $this->oTable->delete($where);
        $this->deleteCache();

        $this->sOutput = json_encode($this->oInput);
        return $this->sOutput;
    }

    public function validate(){
        if(null === $this->oInput){
            $aErrors = array(
                'Errors' => 'Input Data Missing!'
            );

            $this->sErrors = json_encode($aErrors);
            return false;
        }

        if(isset($this->oInput[0]->id) && !empty($this->oInput[0]->id) && !ctype_alnum($this->oInput[0]->id)){
                $aErrors = array(
                    'Errors' => 'Invalid ID!'
                );

                $this->sErrors = json_encode($aErrors);
                return false;
        }
        return true;
    }

    public function deleteCache(){
        SpamTrawler::$Registry['oCache']->remove('notifications');
    }
}
