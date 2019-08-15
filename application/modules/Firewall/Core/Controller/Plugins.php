<?php

class Firewall_Core_Controller_Plugins extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $aParams = array();
    public static $aErrors = array();

    public function __construct(){
        $this->aGroupAccess = array(1);
        $this->bExcludeFromMaintenance = TRUE;
        parent::__construct();
    }

    public function index()
    {
      if(!isset($_SESSION['sUsername'])){
          header('Location: ' . SpamTrawler_Url::MakeFriendly('Admin/Auth/Login'));
          exit();
      }
    }

    public function install(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' && file_exists($_FILES['firewall_plugin_file']['tmp_name'])){
            //var_dump($_FILES);
            //exit(file_get_contents($_FILES['firewall_plugin_file']['tmp_name']));
			$error = false;
            $aData = json_decode(file_get_contents($_FILES['firewall_plugin_file']['tmp_name']), true);

         	if(class_exists($aData['conf_class_name'])){
                try{
                    $oTable = new SpamTrawler_Db_Tables_Settings();
                    $aData['conf_params'] = serialize($aData['conf_params']);
                    $oTable->insert($aData);
                } catch(Exception $e){
                    $error = 'Plugin already exists!';
                }
         	} else {
         		$error = 'Firewall Filter Class "' . $aData['conf_class_name'] . '" does not exist!';
         	}
        } else {
        	$error = 'Please choose Plugin File to upload!';
        }

        if($error !== false ){
        	$error = '&error=' . urlencode($error);
        }

        header('Location: ' . SpamTrawler_Url::MakeFriendly('Firewall/Core/Manage?tab=plugins' . $error));
    }

    public function remove(){
        $oTable = new SpamTrawler_Db_Tables_Settings();

        $where = $oTable->getAdapter()->quoteInto('conf_name = ?', $_POST['id']);

        $oTable->delete($where);

        //Delete Active Modules Cache
        SpamTrawler::$Registry['oCache']->remove('modules_firewall_core');
        SpamTrawler::$Registry['oCache']->remove('modules_firewall_blacklists');
        SpamTrawler::$Registry['oCache']->remove('modules_firewall_whitelists');
        SpamTrawler::$Registry['oCache']->remove('modules_firewall_remote');

        exit('Filter Removed!');
    }

    public function export()
    {
        $oSettings = new SpamTrawler_Db_Tables_Settings();

        $sql = $oSettings->select()
            ->where('conf_name = ?', $_GET['plugin']);

        $aRows = $oSettings->fetchAll($sql);

        foreach ($aRows as $module) {
            $aModule = array(
                'conf_group' => $module->conf_group,
                'conf_module' => $module->conf_module,
                'conf_category' => $module->conf_category,
                'conf_name' => $module->conf_name,
                'conf_params' => unserialize($module->conf_params),
                'conf_class_name' => $module->conf_class_name,
                'conf_group_order' => $module->conf_group_order,
                'conf_order' => 32767
            );

            $sPlugin = str_replace('Controller_Filter', '', $module->conf_class_name);
            $sZipFileName = str_replace('Firewall_', '', $sPlugin);
            $sZipFileName = str_replace('_', '', $sZipFileName);
            $sExportDir = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $sZipFileName;

            $sPlugin =  TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Firewall' . DIRECTORY_SEPARATOR . $sZipFileName . DIRECTORY_SEPARATOR;

            $zipFile =  $sExportDir . DIRECTORY_SEPARATOR . $sZipFileName . '.zip';
            $finalZipFile = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache'. DIRECTORY_SEPARATOR . $sZipFileName . '.zip';

            //Create temporary Plugin Export Directory
            mkdir($sExportDir);

            //Zip Plugin
            $this->Zip($sPlugin, $zipFile);

            //Create Installer File
            $sInstallerfile = $sExportDir . DIRECTORY_SEPARATOR . 'install.json';
            $content = json_encode($aModule, JSON_PRETTY_PRINT);
            file_put_contents($sInstallerfile, $content);

            //ReadMe File
            $sReadMeFile = $sExportDir . DIRECTORY_SEPARATOR . 'ReadMe.html';
            $content = $this->generateReadMe($sZipFileName);
            file_put_contents($sReadMeFile, $content);

            //Zip temporary Export Directory
            $this->Zip($sExportDir, $finalZipFile);

            //Delete temporary plugin export directory
            SpamTrawler_Utilities::deleteDir($sExportDir . DIRECTORY_SEPARATOR);

            //Offer file for download
            if (file_exists($finalZipFile)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($finalZipFile).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($finalZipFile));
                readfile($finalZipFile);
                //Delete final zip file
                unlink($finalZipFile);
                exit;
            } else {
                exit('Plugin export file does not exist!');
            }
        }
    }

    private function Zip($source, $destination)
    {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true)
        {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file)
            {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                    continue;

                $file = realpath($file);

                if (is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }
                else if (is_file($file) === true)
                {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }
        else if (is_file($source) === true)
        {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

        return $zip->close();
    }

    private function generateReadMe($sPluginname){
        $this->oSmarty->assign('pluginname', $sPluginname);

        $result = $this->oSmarty->fetch('ReadMe.tpl');

        return $result;
    }
}
