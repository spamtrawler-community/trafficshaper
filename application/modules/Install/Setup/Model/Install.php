<?php

class Install_Setup_Model_Install {
    public function checkLicense(){
        //stub
        exit('ok');
    }

    public function createDatabase(){
        try{
        $db = SpamTrawlerX_Db_Table_Abstract::getDefaultAdapter();
        $sql = file_get_contents(TRAWLER_PATH_MODULES . '/Intall/Setup/Data/import.sql');
        $db->query($sql);
            exit('ok');
        } catch (Exception $e){
            exit($e);
        }
    }

    public function addAdminUser(){

    }

    public function importStandardSettings(){

    }

}
