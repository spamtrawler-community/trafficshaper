<?php

class Firewall_IPRangesWhitelist_Controller_Import extends SpamTrawler_BaseClasses_Modules_Controller {

    private $dbTableName = 'whitelist_ipranges';
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
                //trim all values
                //array_walk($aData, create_function('&$val', '$val = trim($val);'));

                foreach($aData as $value){
                    $sRangeStart = $value->{'range_start'};
                    $sRangeEnd = $value->{'range_end'};

                    $bISv6 = false;
                    $bImport = false;
                    if(filter_var($sRangeStart, FILTER_VALIDATE_IP) && filter_var($sRangeEnd, FILTER_VALIDATE_IP)){
                        if(filter_var($sRangeStart, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && filter_var($sRangeEnd, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
                                $bISv6 = true;
                                if (inet_pton($sRangeStart) < inet_pton($sRangeEnd)) {
                                    $bImport = true;
                                }
                        } elseif(filter_var($sRangeStart, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($sRangeEnd, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                            if(ip2long($sRangeStart) < ip2long($sRangeEnd)){
                                $bImport = true;
                            }
                        }
                    }

                    if($bImport === true){
                        $aData = array(
                            'id' => md5($sRangeStart . $sRangeEnd),
                            'range_start' => $sRangeStart,
                            'range_end' => $sRangeEnd,
                            'comment' => ''
                        );

                        if($bISv6 == false){
                            $aData['range_start_long'] = ip2long($sRangeStart);
                            $aData['range_end_long'] = ip2long($sRangeEnd);
                        }

                            try{
                                $oTable->insert($aData);
                            } catch (SpamTrawler_Db_Exception $e)
                            {
                                //Do nothing to ignore duplicates
                            }
                    }
                }
                $oManage = new Firewall_IPRangesWhitelist_Model_Manage(NULL);
                $oManage->deleteCache();

                exit('ok');
            } catch(Exception $e){
                exit('An Error Occured while importing the IP Ranges Whitelist:<br />' . $e);
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
