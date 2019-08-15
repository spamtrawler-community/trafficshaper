<?php

class Firewall_CountryBlacklist_Model_Import extends Firewall_Abstract_Import_Import {
    protected $dbTableName = 'blacklist_countries';
    protected $sCheckValueDbField = 'iso';
    protected $sManagerClass;

    public function __construct(){
        $aModelClass = explode('_', __CLASS__);
        $this->sManagerClass = 'Firewall_' . $aModelClass[1] . '_Model_Manage';
    }

    public function doImport()
    {
        if(is_uploaded_file($_FILES['importfile']['tmp_name'])) {
            $this->oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

            $sData = file_get_contents($_FILES["importfile"]["tmp_name"]);

            $aData = json_decode($sData);

            //Delete temp file
            unlink($_FILES["importfile"]["tmp_name"]);

            try{
                $i = 0;
                foreach($aData as $value) {
                    $i++;

                    if(!$this->validate($i)){
                        continue;
                    }

                    $aData = array(
                        'id' => md5($value->{'iso'}),
                        'name' => $value->{'name'},
                        'continent' => $value->{'continent'},
                        'iso' => $value->{'iso'},
                        'comment' => ''
                    );

                    if(!$aData){
                        continue;
                    }

                    try {
                        $this->oTable->insert($aData);
                    } catch (SpamTrawler_Db_Exception $e) {
                        $this->aErrors[] = '[Line: ' . $i . '] Value (' . $value->{'iso'} . ') already exists!';
                        continue;
                    }
                }

                //$sManagerClass = $this->sManagerClass;
                $oManage = new $this->sManagerClass(NULL);
                $oManage->deleteCache();

                if(count($this->aErrors)){
                    try{
                        file_put_contents(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'import.log', implode("\r\n", $this->aErrors));
                    } catch (Exception $e) {
                        exit('Not all data could be imported.<br />Import log not writeable!');
                    }
                    exit('Not all data could be imported.<br />Check import log for more information!');
                }
                exit('ok');
            } catch(Exception $e){
                try{
                    file_put_contents(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'import.log', $e);
                    exit('An Error Occured: Check import log for more information');
                } catch (Exception $e) {
                    exit('An Error Occured: No further details available as import log not writeable!');
                }
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

    protected function validate($iLine){
        return true;
    }
}
