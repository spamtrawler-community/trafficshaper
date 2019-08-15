<?php

class Filesystem_Signatures_Model_Create
{
    private $oTableNotifications = null;

    public function __construct(){
        $this->oTableNotifications = new SpamTrawler_Db_Tables_Generic('notifications');
    }

    public function CreateChecksums($aScanOptions)
    {
        $now = date('d_m_Y__H_i_s');
        $sFileList = '';

        //$exludedFiles = array('png', 'gif', 'jpeg', 'jpg', 'flv', 'sqlite');
        //$exludedDirectories = array();

        $sCheckExtensions = array('php', 'html', 'htm' , 'phtml' ,'htaccess', 'js', 'css' , 'pl', 'cgi', 'txt');
        $exludedDirectories = array();

        $it = new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT']);
        $iterator = new RecursiveIteratorIterator(new SpamTrawler_SPL_ReadableFilter($it));

        foreach ($iterator as $file) {
            $exclude = pathinfo($file);

            //Get current file extension
            if (isset($exclude['extension']) && isset($exclude['dirname'])) {
                $excludeExtension = $exclude['extension'];
                $excludeDir = $exclude['dirname'];
            } else {
                $excludeExtension = '';
                $excludeDir = '';
            }

            if (in_array($excludeExtension, $sCheckExtensions) && !in_array($excludeDir, $exludedDirectories) && is_readable($file) && stripos($file, 'SpamTrawler_cache') === false) {
                    $checksum = md5_file($file);
                    $fileEnc = base64_encode($file);
                    $sFileList .= $fileEnc . '||' . $checksum . "\n";
            }
        }

        $sSigfilePath = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'signatures' . DIRECTORY_SEPARATOR . 'checksums.txt';
        try{
            $sNotificationContent = 'Filesystem Signatures Created<br />Signature file located at: ' . $sSigfilePath;
            file_put_contents($sSigfilePath, $sFileList);
        }
        catch(Exception $e){
            $sNotificationContent = 'Filesyste Signatures Error:<br />' . $e;
        }

        $oNotifications = new Admin_Notifications_Model_Manage();
        $oNotifications->add('Signature Creation', str_replace('<br />', "\r\n", $sNotificationContent));

/*
        $oMail = new SpamTrawler_Utilities_Mail_Mail();
        $oMail->sFromName = 'SpamTrawler @ ' . $_SERVER['SERVER_NAME'];
        $oMail->sImportance = 'high';
        $oMail->sSubject =  'Checksums ' . $_SERVER['SERVER_NAME'] . ' ' . $now;
        $oMail->sMessage = 'SpamTrawler Checksums ' . $now;
        $oMail->sRecipient = SpamTrawler_Registry_Settings::$settings['admin_email'];
        $oMail->attachmentPath = SpamTrawler_Config_GetConfig::$aConfigPaths['checksums'] . 'checksums.txt';
        $oMail->attachmentName = 'checksums.txt';
        $oMail->attachmentMime = 'text/plain';

        $oMail->Send();
        ($bMailStatus === true ? $output = 'Log Files archived and mailed successfully!' : $output = 'Archive created successfully but email could not be sent!');
*/


        return true;
    }

    public function validate(){}
}
