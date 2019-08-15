<?php

class Install_Setup_Helper_Presets_SpamTrawlerServer {
    public function usernamefields(){
        $aUsernameFields = array(
            SpamTrawler::$Config->firewall->apiparameter . '_username'
        );

        return implode(',' . $aUsernameFields);
    }

    public function emailfields(){
        $aEmailFields = array(
            SpamTrawler::$Config->firewall->apiparameter . '_email'
        );
        return implode(',' . $aEmailFields);

    }

    public function excludedurls(){
        return false;
    }
}
