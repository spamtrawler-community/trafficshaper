<?php

class Filesystem_CheckPermissions_Model_Finder
{
    public $aExcludedNames = array('SpamTrawler---');
    public $aExcludedTypes = array('gif', 'jpg', 'jpeg', 'png', 'fla', 'mp3');

    public function find_files($aOptions)
    {
        $sCheckExtension = $aOptions['extension'];

        //set allowed files
        $aGridDatasource = array();

        $path = $_SERVER['DOCUMENT_ROOT'];
        $dir_iterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator(new SpamTrawler_SPL_ReadableFilter($dir_iterator), RecursiveIteratorIterator::SELF_FIRST);
        // could use CHILD_FIRST if you so wish

        foreach ($iterator as $file) {
            if($file === '.' || $file === '..'){
                continue;
            }

            $diff = floor((time() - $file->getCTime()) / (60 * 60));

            $minutes = floor((time() - $file->getCTime()) / 60);
            $days = floor ($minutes / 1440);
            $hours = floor (($minutes - $days * 1440) / 60);
            $minutes = $minutes - ($days * 1440) - ($hours * 60);

            $sExtension = 'Directory';
            if(is_file($file)){
                $sExtension = $file->getExtension();
            }
            $sFileName = $file->getFilename();

            $bCheck = true;
            if(in_array($sExtension, $this->aExcludedTypes, false)){
                $bCheck = false;
            }

            foreach($this->aExcludedNames as $value) {
                if (false !== stripos($file->getFilename(), $value)) {
                    $bCheck = false;
                    continue;
                }
            }

            if($bCheck === false){
                continue;
            }

            $iFilePermissions = substr(sprintf('%o', $file->getPerms()), -4);

            if(($sCheckExtension === $sExtension || $sCheckExtension === 'all') && (false !== strpos($iFilePermissions, '666') || false !== strpos($iFilePermissions, '777')))
            {
                $aGridDatasource[] = array(
                    'id' => sha1($file->getPath() . $file->getFilename()),
                    'fullPath' => $file->getPath(), //str_replace($_SERVER['DOCUMENT_ROOT'], '', $file->getPath()),
                    'filename'=> $sFileName,
                    'extension' => $sExtension,
                    'lastModified' => date('M j y H:i:s', $file->getMTime()),
                    'lastChanged' => date('M j y H:i:s', $file->getCTime()),
                    'inode' => $file->getInode(),
                    'size' => $this->human_filesize($file->getSize()),
                    'owner' => $file->getOwner(),
                    'group' => $file->getGroup(),
                    'ago'=>$days.'d '.$hours.'h '.$minutes.'m ',
                    //'lastModifiedHours' => $diff,
                    'permissions' => $iFilePermissions
                );
            }
        }
        exit(json_encode(array('data' => $aGridDatasource, 'total' => count($aGridDatasource))));
    }

    private function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    public function validate(){}
}
