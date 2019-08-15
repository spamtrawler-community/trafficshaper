<?php

class Firewall_OrganizationBlacklist_Model_Export extends Firewall_Abstract_Export_Export {
    protected $dbTableName;
    protected $aExportFields;

    public function __construct(array $aConfig){
        $this->dbTableName = $aConfig['dbTableName'];
        $this->aExportFields = $aConfig['aExportFields'];

        $aModelClass = explode('_', __CLASS__);
        $this->sManagerClass = 'Firewall_' . $aModelClass[1] . '_Model_Manage';
    }

    public function getExport(){
        $aTableContent = $this->doExport();

        return json_encode($aTableContent);
    }
}
