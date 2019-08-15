<?php

class Firewall_IPBlacklist_Controller_Import extends SpamTrawler_BaseClasses_Modules_Controller {

    private $dbTableName = 'blacklist_ips';

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
        switch($_POST['source']){
            case 'upload':
                $this->fileUpload();
                break;
            case 'antiwebspam':
                break;
            case 'projecthoneypot':
                $this->ProjectHoneyPot();
                break;
            case 'stopforumspam':
                break;
        }
    }

    private function fileUpload()
    {
        if(is_uploaded_file($_FILES['importfile']['tmp_name'])) {
            $sData = file_get_contents($_FILES["importfile"]["tmp_name"]);
            $aData = json_decode($sData);

            //Delete temp file
            unlink($_FILES["importfile"]["tmp_name"]);

            $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);

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
                $oManage = new Firewall_IPBlacklist_Model_Manage(NULL);
                $oManage->deleteCache();

                exit('ok');
            } catch(Exception $e){
                exit('An Error Occured while importing the IP Blacklist:<br />' . $e);
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

    private function ProjectHoneyPot(){
        $oTable = new SpamTrawler_Db_Tables_Generic($this->dbTableName);
        $aFeedUrls = array(
            'harvesters' => 'http://www.projecthoneypot.org/list_of_ips.php?t=h&rss=1',
            'spamservers' => 'http://www.projecthoneypot.org/list_of_ips.php?t=s&rss=1',
            'dictionaryattackers' => 'http://www.projecthoneypot.org/list_of_ips.php?t=d&rss=1',
            'commentspammer' => 'http://www.projecthoneypot.org/list_of_ips.php?t=p&rss=1'
        );

        try{
            foreach($aFeedUrls as $url){
                $aFeed = $this->ParseProjectHoneyPotRSS($url);

                if($aFeed !== false){
                    foreach($aFeed as $ip){
                        $ip = trim($ip);
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
                }
            }
            //Clear Cache File
            $oManage = new Firewall_IPBlacklist_Model_Manage(NULL);
            $oManage->deleteCache();

            exit('ok');
        }
        catch(Exception $e){
            exit('An Error Occured while importing the IP Blacklist:<br />' . $e);
        }
    }

    private static function ParseProjectHoneyPotRSS($url)
    {
        $feedReturn = array();
        $doc = new DOMDocument();

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $output=curl_exec($ch);
        //Get the resulting HTTP status code from the cURL handle.
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($http_status_code === 200){
            $doc->loadXML($output);

            foreach ($doc->getElementsByTagName('item') as $node) {
                $ipDetails = explode('|', $node->getElementsByTagName('title')->item(0)->nodeValue);
                $ip = trim($ipDetails['0']);

                array_push($feedReturn, $ip);
            }
            return $feedReturn;
        } else {
            return false;
        }
    }
}
