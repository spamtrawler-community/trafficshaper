<?php

class Firewall_IPWhitelist_Controller_Import extends SpamTrawler_BaseClasses_Modules_Controller {

    private $dbTableName = 'whitelist_ips';
    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
        $this->oSmarty->display('Import.tpl');
    }

    public function import()
    {
        $this->fileUpload();
    }

    private function fileUpload()
    {
        if(is_uploaded_file($_FILES['importfile']['tmp_name'])) {
            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

            $sData = file_get_contents($_FILES["importfile"]["tmp_name"]);
            $aData = json_decode($sData);

            //Delete temp file
            unlink($_FILES["importfile"]["tmp_name"]);

            try{
                foreach($aData as $key => $value){
                    $ip = $value->{'ip'};
                    if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {
                        $aData = array(
                            'id' => md5($ip),
                            'ip' => $ip,
                            'comment' => ''
                        );

                        try{
                            $oTable->insert($aData);
                        } catch (SpamTrawler_Db_Exception $e)
                        {
                            //Do nothing to ignore duplicates
                        }

                    }
                }
                $oManage = new Firewall_IPWhitelist_Model_Manage(NULL);
                $oManage->deleteCache();

                exit('ok');
            } catch(Exception $e){
                exit('An Error Occured while importing the IP Whitelist:<br />' . $e);
            }
        } else {
            switch($_FILES['importfile']['error']){
                case 0: //no error; possible file attack!
                    exit('There was a problem with your upload');
                    break;
                case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
                    exit('The file you are trying to upload is too big');
                    break;
                case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                    exit('The file you are trying to upload is too big');
                    break;
                case 3: //uploaded file was only partially uploaded
                    exit('The file you are trying upload was only partially uploaded');
                    break;
                case 4: //no file was uploaded
                    exit('You must select an import file for upload');
                    break;
                default: //a default error, just in case!  :)
                    exit('There was a problem with your upload');
                    break;
            }
        }
    }
}
