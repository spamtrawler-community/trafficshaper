<?php

class Filesystem_FindFiles_Model_Finder
{
    public function find_files($aOptions)
    {
        set_time_limit(3600);
        $file = $aOptions['pattern'];
        $extension = $aOptions['extension'];

        $sMode = false;
        if(ctype_alnum(str_replace(array('-', '_', '.'), '', $file))){
            $sMode = 'filename';
        } else {
            try{
                //Instantiate SpamTrawler Validator Regex
                new SpamTrawler_Validate_Regex(array('pattern' => $aOptions['pattern']));

                $sMode = 'regex';
            } catch(Exception $e) {
                //Do nothing
            }
        }

        if($sMode === false) {
            $aErrors = array(
                'Errors' => 'Invalid Filename or Pattern!'
            );

            exit(json_encode($aErrors));
        }

        $aGridDatasource = array();
        $path = $_SERVER['DOCUMENT_ROOT'];

        $dir_iterator = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator(new SpamTrawler_SPL_ReadableFilter($dir_iterator), RecursiveIteratorIterator::SELF_FIRST);
        // could use CHILD_FIRST if you so wish

        foreach ($iterator as $file) {
            $bMatch = false;
            $fileExtension = $file->getExtension();
            if(($fileExtension === $extension || $extension === 'all') && is_file($file)){
                if($sMode === 'regex' && preg_match($aOptions['pattern'], $file->getFilename())){
                    $bMatch = true;
                } elseif(false !== stripos($file->getFilename(), $aOptions['pattern'])){
                    $bMatch = true;
                }
            }

            if(false !== $bMatch)
            {
                //$diff = floor((time() - $file->getMTime()) / (60 * 60));
                $minutes = floor((time() - $file->getMTime()) / 60);
                $days = floor ($minutes / 1440);
                $hours = floor (($minutes - $days * 1440) / 60);
                $minutes = $minutes - ($days * 1440) - ($hours * 60);

                //$fileExtension = pathinfo($file, PATHINFO_EXTENSION);

                /*
                * This method is only available as of PHP 5.3.6. Another way of getting the extension is to use the pathinfo() function.
                */

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

        //exit('['.implode(',', $aGridDatasource).']');

        exit(json_encode(array('data' => $aGridDatasource, 'total' => count($aGridDatasource))));
        //echo(json_encode($aGridDatasource));
        //return $aGridDatasource;
    }

    private function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    public function validate(){}
}
