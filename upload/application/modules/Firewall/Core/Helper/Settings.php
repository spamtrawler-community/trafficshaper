<?php

class Firewall_Core_Helper_Settings {
    public static $aFirewallSettings = NULL;

    public static function getSettings(){
        if(is_null(self::$aFirewallSettings)){
            $sCacheFileName = 'modules_firewall_core';
            if(!$aRows = SpamTrawler::$Registry['oCache']->load($sCacheFileName)) {
                $oSettings = new SpamTrawler_Db_Tables_Settings();

                $sql = $oSettings->select()
                    ->where('conf_name = ?', 'firewall_core');
                $aRows = $oSettings->fetchAll($sql)->toArray();

                $aRows['0']['conf_params'] = unserialize($aRows['0']['conf_params']);

                SpamTrawler::$Registry['oCache']->save($aRows, $sCacheFileName);
            }

            self::$aFirewallSettings = $aRows['0'];
        }
        return self::$aFirewallSettings;
    }
}
