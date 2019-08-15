<?php

class Firewall_Satellite_Model_Manage
{
    public static function validateSettings($aSettings, $aCoreSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Satellite Client Status Invalid!';
        }

        if($aCoreSettings['conf_params']['mode'] == 'server' && $aSettings['conf_status'] == 1){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Satellite Module can only be used in Standalone or Client mode!';
        }

        if(!empty($aSettings['conf_params']['satellite_url']) && !filter_var($aSettings['conf_params']['satellite_url'], FILTER_VALIDATE_URL)){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Satellite Server URL Invalid!';
        }

        if($aSettings['conf_status'] == 1 && (empty($aCoreSettings['conf_params']['apikey']) || !ctype_alnum($aCoreSettings['conf_params']['apikey']))){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Satellite cannot be empty and has to be alphanumeric!';
        }

        /*
        if(!ctype_digit($aSettings['conf_order'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Satellite Client Order Invalid!';
        }
        */

        if($bErrors === true){
            return false;
        }
        return true;
    }
}
