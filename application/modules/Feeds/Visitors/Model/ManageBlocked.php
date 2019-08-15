<?php

class Feeds_Visitors_Model_ManageBlocked
{
    private $dbTableName = 'cache_visitors';
    public $oInput = NULL;
    private $aOutput = NULL;
    public $sOutput = NULL;
    public $sErrors = NULL;

    public function __construct($oInput = NULL){
        $this->oInput = $oInput;
    }

    public function get()
    {
        //$oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
        $oTable = new SpamTrawler_Db_Tables_CacheVisitors();

        //Where parameter to only get blocked visitors
        $aWhere = array(
            array('field' => 'blocked', 'value' => 'yes')
        );

        return $oTable->returnJSONP($aWhere);
    }

    public function create()
    {
    }

    public function update()
    {
        if(!$this->validate()){
            return false;
        }

        $aData = array(
            'comment' => $this->oInput[0]->comment
        );

        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

        $where = $oTable->getAdapter()->quoteInto('id = ?', $this->oInput[0]->id);

        $oTable->update($aData, $where);

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
        SpamTrawler::$Registry['oCache']->remove(sha1($this->oInput[0]->ip));
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

        return true;
    }
}
