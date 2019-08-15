<?php

class Firewall_Core_Helper_GetVisitorDetails {
    public $aSettings = array();

    public function __construct($aSettings = array()){
        $this->aSettings = $aSettings;
        $this->setDetails();
    }

    public function setDetails(){
        $getFlat = $this->getFlatGET();
        if($getFlat){
            SpamTrawler::$Registry['request']['get'] = $getFlat;
        }

        $postFlat = $this->getFlatPOST();
        if($postFlat){
            SpamTrawler::$Registry['request']['post'] = $postFlat;
        }

        //$visitorEmail = $this->VisitorEmail();
        //if($visitorEmail) {
            SpamTrawler::$Registry['visitordetails']['email'] = $this->VisitorEmail();
        //}

        //$visitorUsername = $this->VisitorUsername();
        //if($visitorUsername) {
            SpamTrawler::$Registry['visitordetails']['username'] = $this->VisitorUsername();
        //}
    }

    private function VisitorEmail(){
        $sEmail = '--';
        if($this->aSettings['conf_params']['mode'] == 'server'
            && isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['email'])
            && filter_var($_POST[SpamTrawler::$Config->firewall->apiparameter]['email'], FILTER_VALIDATE_EMAIL)){
             $sEmail = $_POST[SpamTrawler::$Config->firewall->apiparameter]['email'];
        } elseif ($this->aSettings['conf_params']['mode'] !== 'server' && !empty($this->aSettings['conf_params']['emailfields'])){
            $sEmailFields = preg_replace('/\s+/', '', $this->aSettings['conf_params']['emailfields']);
            $aEmailFields = explode(',', $sEmailFields);

            $aPostFlattened = $this->getFlatPOST();

            //loop through email fields array
            foreach($aEmailFields as $key){
                if(array_key_exists($key, $aPostFlattened) && filter_var($aPostFlattened[$key], FILTER_VALIDATE_EMAIL)) {
                    $sEmail = $aPostFlattened[$key];
                    break;
                }
            }
        }

        return $sEmail;
    }

    private function VisitorUsername(){
        $sUsername = '--';
        if($this->aSettings['conf_params']['mode'] == 'server'
            && isset($_POST[SpamTrawler::$Config->firewall->apiparameter]['username'])){
            $sUsername = $_POST[SpamTrawler::$Config->firewall->apiparameter]['username'];
        } elseif ($this->aSettings['conf_params']['mode'] !== 'server' && !empty($this->aSettings['conf_params']['usernamefields'])){
            $sUsernameFields = preg_replace('/\s+/', '', $this->aSettings['conf_params']['usernamefields']);
            $aUsernameFields = explode(',', $sUsernameFields);

            $aPostFlattened = $this->getFlatPOST();

            //loop through username fields array
            foreach($aUsernameFields as $key){
                if(array_key_exists($key, $aPostFlattened)) {
                    $sUsername = $aPostFlattened[$key];
                    break;
                }
            }
        }

        return $sUsername;
    }

    public function getFlatGET()
    {
        /*
        if (!isset(SpamTrawler::$Registry['request'])) {
            //Add Autoloader Object to registry
            $object = new stdClass();
            SpamTrawler::$Registry['request'] = $object;
        }*/

        $aGetFlattened = array();
        if($_GET){
            if(!isset(SpamTrawler::$Registry['request]']['get'])){
                $aGetFlattened = SpamTrawler_Array::flatten($_GET,'','','.');
            } else {
                $aGetFlattened = SpamTrawler::$Registry['request']['get'];
            }
        }
        return $aGetFlattened;
    }

    public function getFlatPOST()
    {
        /*if (!isset(SpamTrawler::$Registry['request'])) {
            //Add Autoloader Object to registry
            $object = new stdClass();
            SpamTrawler::$Registry['request'] = $object;
        }*/

        $aGetFlattened = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST){
            if(!isset(SpamTrawler::$Registry['request']['flattened->post'])){
                $aGetFlattened = SpamTrawler_Array::flatten($_POST,'','','.');
            } else {
                $aGetFlattened = SpamTrawler::$Registry['request']['flattened']['post'];
            }
        }
        return $aGetFlattened;
    }
}
