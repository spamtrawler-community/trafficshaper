<?php

class Filesystem_Signatures_Model_Compare
{
    private $oTableNotifications = null;

    public function __construct(){
        $this->oTableNotifications = new SpamTrawler_Db_Tables_Generic('notifications');
    }

    public function DeepCompare()
    {
        $now = date("d_m_Y__H_i_s");
        $aGridDatasource = array();
        $sResultFiles = '';

        //Current File System
        $aCurrFileList = array();
        $sCheckExtensions = array('php', 'html', 'htm' , 'phtml' ,'htaccess', 'js', 'css' , 'pl', 'cgi');

        $it = new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT']);
        foreach (new RecursiveIteratorIterator(new SpamTrawler_SPL_ReadableFilter($it)) as $file) {
            $exclude = pathinfo($file);

            //Get current file extension
            (isset($exclude['extension'])) ? $excludeExtension = $exclude['extension'] : $excludeExtension = '';

            if (in_array($excludeExtension, $sCheckExtensions) && is_readable($file)) {
                    $sFileSignature = md5_file($file);
                    $sFileNameBase64 = base64_encode($file);
                    $aCurrFileList[$sFileNameBase64] = $sFileSignature;
            }
        }
        //End Current File System

        $sSigfilePath = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'signatures' . DIRECTORY_SEPARATOR . 'checksums.txt';
        $sSignatures = file_get_contents($sSigfilePath);
        $aSignatures = explode("\n", $sSignatures);
        $aSignatures = array_filter($aSignatures);

        // Transform Signatures array in key value pair with key being base64 encoded filename
        foreach($aSignatures as $key => $value){
            $aSignature = explode('||',$value);
            $aSignatures[$aSignature['0']] = $aSignature['1'];
            //Get rid of numeric key
            unset($aSignatures[$key], $aSignature);
        }

        //Compare current file system with signatures array to find new files
        $aNewFiles = array();
        $aModifiedFiles = array();
        foreach($aCurrFileList as $key => $value){
            if(array_key_exists($key, $aSignatures)){
                if($aSignatures[$key] != $value){
                    $aModifiedFiles[$key] = $value;
                }
            } else {
                $aNewFiles[$key] = $value;
            }
        }

        //housekeeping
        unset($sSignatures, $aCurrFileList);

        //Get Details for new files
        $sResultFiles .= "\r\nNew Files: \r\n";
        $i = 0;
        foreach ($aNewFiles as $sFileNameBase64 => $sSignature) {
            $sFileNameDecoded = base64_decode($sFileNameBase64);
            if(file_exists($sFileNameDecoded)) {
                $lastModified = date ("M-j-y H:i:s", filemtime($sFileNameDecoded));
                $minutes = floor((time() - filemtime($sFileNameDecoded)) / 60);
                $d = floor ($minutes / 1440);
                $h = floor (($minutes - $d * 1440) / 60);
                $m = $minutes - ($d * 1440) - ($h * 60);
                $ago = $d.'d '.$h.'h '.$m.'m ';
                $permissions = substr(sprintf('%o', fileperms($sFileNameDecoded)), -4);
                $details = pathinfo($sFileNameDecoded);
                $aFile = array('path'=>dirname($sFileNameDecoded),'file'=> basename($sFileNameDecoded),'filetype'=>$details['extension'],'lastmodified'=>$lastModified,'ago'=>trim($ago),'permissions'=>$permissions,'status' => 'New');


                $sResultFiles .= implode('||', $aFile) . "\r\n";
                $i++;
            }
        }
        $iNumNewFiles = $i;
        ($iNumNewFiles === 0) ? $sResultFiles .= "None \r\n" : '';

        //Get Details for modified files
        $sResultFiles .= "\r\nModified Files: \r\n";
        $i = 0;
        foreach ($aModifiedFiles as $sFileNameBase64 => $sSignature) {
            $sFileNameDecoded = base64_decode($sFileNameBase64);

            if(file_exists($sFileNameDecoded)) {
                $lastModified = date ("M-j-y H:i:s", filemtime($sFileNameDecoded));
                $minutes = floor((time() - filemtime($sFileNameDecoded)) / 60);
                $d = floor ($minutes / 1440);
                $h = floor (($minutes - $d * 1440) / 60);
                $m = $minutes - ($d * 1440) - ($h * 60);
                $ago = $d.'d '.$h.'h '.$m.'m ';
                $permissions = substr(sprintf('%o', fileperms($sFileNameDecoded)), -4);
                $details = pathinfo($sFileNameDecoded);
                $aFile = array('path'=>dirname($sFileNameDecoded),'file'=> basename($sFileNameDecoded),'filetype'=>$details['extension'],'lastmodified'=>$lastModified,'ago'=>trim($ago),'permissions'=>$permissions,'status' => 'Mod');
                $sResultFiles .= implode('||', $aFile) . "\r\n";
            }
            $i++;
        }
        $iNumModifiedFiles = $i;
        ($iNumModifiedFiles == 0) ? $sResultFiles .= "None \r\n" : '';

        //Get Deleted Files
        $i = 0;
        $sResultFiles .= "\r\nDeleted Files: \r\n";
        foreach($aSignatures as $sFileNameBase64 => $sSignature) {
            $sFileNameDecoded = base64_decode($sFileNameBase64);
            $details = pathinfo($sFileNameDecoded);

            if(!file_exists(base64_decode($sFileNameBase64))){
                $aFile = array('path'=>dirname($sFileNameDecoded),'file'=> basename($sFileNameDecoded),'filetype'=>$details['extension'],'lastmodified'=>'-','ago'=>'-','permissions'=>'-','status' => 'Del');
                $sResultFiles .= implode('||', $aFile) . "\r\n";
                $i++;
            }
        }
        $iNumDeletedFiles = $i;
        ($iNumDeletedFiles == 0) ? $sResultFiles .= "None" : '';

        //Create Results File
        //$sResultFileName = 'FileIntegrityDeepCheck_'. $now .'.txt';
        //file_put_contents(SpamTrawler_Config_GetConfig::$aConfigPaths['checksums'] . $sResultFileName, $sResultFiles);

        if($iNumNewFiles == 0 && $iNumModifiedFiles == 0 && $iNumDeletedFiles == 0){
            $sNotificationSubject = 'Signature Comparison: No Changes Detected!';
            $sNotificationContent = 'No changes have been detected in this filesystem signature comparison.';
        } else {
            $sNotificationSubject = 'Signature Comparison: Changes Detected!';
            $sNotificationContent = "The following changes have been detected in this filesystem scan:\r\n" . $sResultFiles;
        }

        $oNotifications = new Admin_Notifications_Model_Manage();
        $oNotifications->add($sNotificationSubject, str_replace('<br />', "\r\n", $sNotificationContent));

/*
        //Send Mail
        if($iNumNewFiles != 0 || $iNumModifiedFiles != 0 || $iNumDeletedFiles != 0 || $this->sAlwaysSendMail === true){
            $oMail = new SpamTrawler_Utilities_Mail_Mail();
            $oMail->sFromName = 'SpamTrawler @ ' . $_SERVER['SERVER_NAME'];
            //$oMail->sImportance = 'high';
            $iNumChanges = $iNumNewFiles + $iNumModifiedFiles + $iNumDeletedFiles;
            $oMail->sSubject =  'File Integrity Check (Deep) ' . $now . ' Changes: ' . $iNumChanges;

            if($iNumNewFiles == 0 && $iNumModifiedFiles == 0 && $iNumDeletedFiles == 0){
                $oMail->sImportance = 'low';
                $oMail->sMessage = 'No Changes Detected!';
            } else {
                $oMail->sImportance = 'high';
                $oMail->sMessage = "Detected Changes: \r\n" . $sResultFiles;
            }

            $oMail->sRecipient = SpamTrawler_Registry_Settings::$settings['admin_email'];
            $oMail->attachmentPath = SpamTrawler_Config_GetConfig::$aConfigPaths['checksums'] . $sResultFileName;
            $oMail->attachmentName = $sResultFileName;
            $oMail->attachmentMime = 'text/plain';
            $oMail->Send();
        }
*/

        //Delete temporary file
        //unlink(SpamTrawler_Config_GetConfig::$aConfigPaths['checksums'] . $sResultFileName);

        return true;
        //var_dump($aGridDatasource);
        //echo LANG_COMPARE_SIGNATURES_RESULT_HEADER_1.': '.$i.'&nbsp;'.LANG_COMPARE_SIGNATURES_RESULT_HEADER_2.'<br><br>';
    }

    public function validate(){}
}
