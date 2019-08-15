<?php

class Firewall_IPRangesWhitelist_Controller_Export extends SpamTrawler_BaseClasses_Modules_Controller {
    private $aConfig = array(
        'dbTableName' => 'whitelist_ipranges',
        'aExportFields' => array('range_start', 'range_end')
    );

    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index(){
        $this->doExport();
    }

    public function doExport()
    {
        $oImport = new Firewall_IPRangesWhitelist_Model_Export($this->aConfig);
        $data = $oImport->getExport();

        header("Content-Type: text/plain");
        header('Content-Disposition: attachment; filename="'. $this->aConfig['dbTableName'] .'.txt"');
        header("Content-Length: " . strlen($data));
        echo $data;
        exit;
    }
}
