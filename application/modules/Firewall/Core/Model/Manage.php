<?php

class Firewall_Core_Model_Manage
{
    public static function validateSettings($aSettings){
        $bErrors = false;
        if(!ctype_digit($aSettings['conf_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Status Invalid!';
        }

        $aAllowedModes = array('server', 'standalone', 'integrated');
        if(!in_array($aSettings['conf_params']['mode'], $aAllowedModes)){
                $bErrors = true;
                Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Mode Invalid!';
        }

        if($aSettings['conf_params']['mode'] === 'server' && (empty($aSettings['conf_params']['apikey']) || !ctype_alnum($aSettings['conf_params']['apikey']))){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'API Key Invalid or empty!';
        }

        if(!ctype_digit($aSettings['conf_params']['filter_post_only'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Filter Mode Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['cookie_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Cookie Status Invalid!';
        }

        if(!preg_match('/^[a-z0-9]+$/i', $aSettings['conf_params']['cookie_name'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Cookie Name Invalid!';
        }

        if(!preg_match('/^[a-z0-9]+$/i', $aSettings['conf_params']['cookie_blocked_value'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Cookie Blocked Value Invalid!';
        }

        if(!preg_match('/^[a-z0-9]+$/i', $aSettings['conf_params']['cookie_permitted_value'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Cookie Permitted Value Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['cookie_expiry'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Cookie Expiry Value Invalid!';
        }

        if(!ctype_alnum(str_replace('.', '' ,$aSettings['conf_params']['cookie_domain']))){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Cookie Domain Value Invalid!';
        }

        if(!preg_match('/^[^*?"<>|:]*$/',$aSettings['conf_params']['cookie_path'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Cookie Path Value Invalid!';
        }

        if(!ctype_digit($aSettings['conf_params']['visitorcache_status'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Visitor Cache Status Invalid!';
        }

        if(!ctype_alnum($aSettings['conf_params']['block_action'])){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Block Action Invalid!';
        }

        if($aSettings['conf_params']['block_action'] == 'redirect' && strlen(trim($aSettings['conf_params']['redirection_target'])) == 0){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Please set Firewall Redirection Target!';
        }

        if(strlen(trim($aSettings['conf_params']['redirection_target'])) > 0 && !filter_var($aSettings['conf_params']['redirection_target'], FILTER_VALIDATE_URL)){
            $bErrors = true;
            Firewall_Core_Controller_Manage::$aErrors[] = 'Firewall Redirection Target Invalid!';
        }

        if(!empty($aSettings['conf_params']['emailfields'])){
            $aReplace = array(".", "_", ',');
            $aSettings['conf_params']['emailfields'] = str_replace($aReplace, "", $aSettings['conf_params']['emailfields']);
            $aSettings['conf_params']['emailfields'] = preg_replace('/\s+/', '', $aSettings['conf_params']['emailfields']);
            if(!ctype_alnum($aSettings['conf_params']['emailfields'])){
                $bErrors = true;
                Firewall_Core_Controller_Manage::$aErrors[] = 'Email Fields Invalid!';
            }
        }

        if(!empty($aSettings['conf_params']['usernamefields'])){
            $aReplace = array(".", "_", ',');
            $aSettings['conf_params']['usernamefields'] = str_replace($aReplace, "", $aSettings['conf_params']['usernamefields']);
            $aSettings['conf_params']['usernamefields'] = preg_replace('/\s+/', '', $aSettings['conf_params']['usernamefields']);
            if(!ctype_alnum($aSettings['conf_params']['usernamefields'])){
                $bErrors = true;
                Firewall_Core_Controller_Manage::$aErrors[] = 'Username Fields Invalid!';
            }
        }

        if($bErrors === true){
            return false;
        }
        return true;
    }
}
