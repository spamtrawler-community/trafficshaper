<?php

class Firewall_Abstract_Export_Export {
    protected $dbTableName;
    protected $sCheckValueDbField;
    protected $sCheckValue;
    protected $sFilterMode;
    protected $aErrors = array();
    protected $oTable;
    protected $aGroupAccess = array(1);
    protected $sManagerClass;
    protected $aExportFields;

    public function doExport()
    {
        $this->oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
        $select = $this->oTable->select();
        $select->from($this->oTable, $this->aExportFields);
        $aRows = $this->oTable->fetchAll($select)->toArray();

        return $aRows;
    }
}
