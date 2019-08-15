<?php
class API_Firewall_Controller_Firewall {
    public static $bWhitelisted = FALSE;
    public static $bBlocked = FALSE;
    public static $sBlockReason = NULL;

    public function view()
    {
        $this->run();
    }

    public function run(){
        if(SpamTrawler::$aSettingsCore['firewall_status']['conf_status'] == 1) {
        //Run Whitelists
        $this->filter('modules_firewall_whitelists', 'whitelist');

        //Run Blacklists
        $this->filter('modules_firewall_blacklists', 'blacklist');

        //Return firewall result
        $this->showOutput();
        } else {
            header("HTTP/1.0 503 Service Unavailable");
            exit();
        }
    }

    public function filter($sCacheFileName, $sConfCategory)
    {
        if(!$aRows = SpamTrawler::$oCache->load($sCacheFileName)) {
            $oSettings = new SpamTrawler_Db_Tables_Settings();

            $sql = $oSettings->select()
                ->where('conf_group = ?', 'modules')
                ->where('conf_module = ?', 'firewall')
                ->where('conf_category = ?', $sConfCategory)
                ->where('conf_status = 1')
            ;

            $aRows = $oSettings->fetchAll($sql);

            SpamTrawler::$oCache->save($aRows, $sCacheFileName);
        }

        foreach ($aRows as $row) {
            $sClass = $row->conf_class_name;

            //Check if module is active
            if($row->conf_status == 1){
                if(class_exists($sClass)){
                    $oClass = new $sClass($row->conf_params);

                    if(method_exists($oClass, 'filter')){
                        $oClass->filter();
                    } else {
                        exit( 'Method: "filter" not found in class: ' . $sClass . ' !' );
                    }
                } else {
                    exit('Firewall Class: ' . $sClass . ' does not exist!');
                }
            }

            //Check if block has taken place
            if(self::$bBlocked === TRUE){
                SpamTrawler::$aVisitorDetails['block_code'] = $sClass;
            }

            if(self::$bBlocked === TRUE || self::$bWhitelisted === TRUE){
                //Return firewall result
                $this->showOutput();
                break;
            }
        }
    }

    public function logVisitor()
    {
        $oTable = new SpamTrawler_Db_Tables_CacheVisitors();
        $oTable->add();
    }

    private function showOutput()
    {
        if(self::$bBlocked) {
            SpamTrawler::$aVisitorDetails['blocked'] = 'yes';
            SpamTrawler::$aVisitorDetails['block_reason'] = self::$sBlockReason;
        } else {
            SpamTrawler::$aVisitorDetails['blocked'] = 'no';
        }

        $this->logVisitor();
        exit(json_encode(SpamTrawler::$aVisitorDetails));
    }
}
