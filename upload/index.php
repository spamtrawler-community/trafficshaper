<?php
/**
 * SpamTrawler Community - A Website Traffic Manager
 * @package  SpamTrawler Community
 * @author   Oliver Putzer <emea@spamtrawler.net>
 */

//Instantiate SpamTrawler
define('SpamTrawler', true);

require(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR .'constants.php'));

//Check Permissions for installation
if(strpos($_SERVER['REQUEST_URI'],'/Install') !== false ){
    $aErrors = array();
    if(!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'archive')){
        $aErrors[] = 'Directory permissions:<br />' . TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'archive needs to be writeable recursive (CHMOD 777)';
    }
    if(!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache')){
        $aErrors[] = 'Directory permissions:<br />' . TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache needs to be writeable recursive (CHMOD 777)';
    }
    if(!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'GeoIP')){
        $aErrors[] = 'Directory permissions:<br />' . TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'GeoIP needs to be writeable recursive (CHMOD 777)';
    }
    if(!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log')){
        $aErrors[] = 'Directory permissions:<br />' . TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log needs to be writeable recursive (CHMOD 777)';
    }
    if(!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'scanlogs')){
        $aErrors[] = 'Directory permissions:<br />' . TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'scanlogs needs to be writeable recursive (CHMOD 777)';
    }
    if(!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'sessions')){
        $aErrors[] = 'Directory permissions:<br />' . TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'sessions needs to be writeable (CHMOD 777)';
    }
    if(!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'signatures')){
        $aErrors[] = 'Directory permissions:<br />' . TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'signatures needs to be writeable recursive (CHMOD 777)';
    }
    if(!is_writeable(TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config.php')){
        $aErrors[] = 'File permissions:<br />' . TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config.php needs to be writeable';
    }
    if(!is_writeable(TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config.php')){
        $aErrors[] = 'File permissions:<br />' . TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config.php needs to be writeable';
    }

    if(!empty($aErrors)){
        foreach($aErrors as $key => $value){
            echo $value . '<br />';
        }
        exit('<br />Once adjusted, please refresh your browser!');
    }
}

require(TRAWLER_PATH_APPLICATION . DIRECTORY_SEPARATOR . 'SpamTrawler.php');

new SpamTrawler();

new SpamTrawler_Router();
