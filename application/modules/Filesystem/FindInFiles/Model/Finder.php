<?php

class Filesystem_FindInFiles_Model_Finder
{
    public function findInFiles($aOptions)
    {
        set_time_limit(3600);
        $sPattern = $aOptions['pattern'];
        $sExtension = $aOptions['extension'];

        $sMode = false;
            try{
                //Instantiate SpamTrawler Validator Regex
                new SpamTrawler_Validate_Regex(array('pattern' => $sPattern));

                $sMode = 'regex';
            } catch(Exception $e) {
                //Do nothing
            }

        $aGridDatasource = array();
        $path = $_SERVER['DOCUMENT_ROOT'];

            $dir_iterator = new RecursiveDirectoryIterator($path);
            $iterator = new RecursiveIteratorIterator(new SpamTrawler_SPL_ReadableFilter($dir_iterator), RecursiveIteratorIterator::SELF_FIRST);
            // could use CHILD_FIRST if you so wish test

            foreach ($iterator as $file) {
                //$fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                $fileExtension = $file->getExtension();

                $bMatch = false;
                if($fileExtension === $sExtension || $sExtension === 'all'){
                    $sContent = file_get_contents($file->getPathname());
                    if($sMode === 'regex' && preg_match($sPattern, $sContent)){
                        $bMatch = true;
                    } elseif(false !== stripos($sContent, $sPattern)){
                        $bMatch = true;
                    } else {
                        //Jump to next iteration
                        continue;
                    }
                    //Free memory used by file content
                    $sContent = NULL;
                }

                if($bMatch === true) {
                    $minutes = floor((time() - $file->getMTime()) / 60);
                    $days = floor ($minutes / 1440);
                    $hours = floor (($minutes - $days * 1440) / 60);
                    $minutes = $minutes - ($days * 1440) - ($hours * 60);

                    $sFullPath = $file->getPathName();
                    $aGridDatasource[] = array(
                        'id' => sha1($sFullPath),
                        'fullPath' => $sFullPath, //str_replace($_SERVER['DOCUMENT_ROOT'], '', $file->getPath()),
                        'filename'=> $file->getFilename(),
                        'extension' =>$fileExtension,
                        'lastModified' => date('M j y H:i:s', $file->getMTime()),
                        'lastChanged' => date('M j y H:i:s', $file->getCTime()),
                        'inode' => $file->getInode(),
                        'size' => $this->human_filesize($file->getSize()),
                        'owner' => $file->getOwner(),
                        'group' => $file->getGroup(),
                        'ago'=>$days.'d '.$hours.'h '.$minutes.'m ',
                        //'lastModifiedHours' => $diff,
                        'permissions' => substr(sprintf('%o', $file->getPerms()), -4));
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
