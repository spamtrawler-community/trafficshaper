<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 23/06/14
 * Time: 17:02
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Session
{
    private $lifetime;

    public function __construct($lifetime = 1800){
        /*
        if (!is_writeable(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'sessions')) {
            exit('Session Path not writeable: ' . TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'sessions');
        }
        */

        $this->lifetime = $lifetime;
        // Set the max lifetime
        ini_set("session.gc_maxlifetime", $this->lifetime);

        // Set the session cookie to timeout
        //ini_set("session.cookie_lifetime", $this->lifetime);
        //Custom Session Save Path
        session_save_path(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'sessions');

        //ini_set("session.gc_probability", 100);
        //ini_set("session.gc_divisor", 100); // Should always be 100
    }

    public function start($sSessionName = 'SpamTrawlerAdmin', $iSecureCookie = 0)
    {
        try{
            //Set session security parameters in ini
            if($iSecureCookie == 1) {
                ini_set('session.cookie_secure',1);
            }

            ini_set('session.cookie_httponly',1);

            //Removed as Obsolete since PHP 5.3.0
            //ini_set('session.use_only_cookies',1);

            session_name($sSessionName);
            session_start();

            // Set last regen session variable first time
            if (!isset($_SESSION['last_regen'])) {
                $_SESSION['last_regen'] = time();
            }

            // Set session regeneration time in seconds
            $session_regen_time = 60*5;

            // Only regenerate session id if last_regen is older than the given regen time.
            if ($_SESSION['last_regen'] + $session_regen_time < time()){
                $_SESSION['last_regen'] = time();
                session_regenerate_id(true);
            }

            $this->setToken();

            //Clearing Old Sessions
            $this->clearOld();

            //only initialize counter if not already initialized
            if(!isset($_SESSION['sessCounter'])) {
                $_SESSION['sessCounter'] = 1;
            } else {
                $_SESSION['sessCounter'] ++;
            }
            return true;
        } catch(Exception $e)
        {
            return false;
        }
    }

    public function destroy()
    {
        $_SESSION = array();
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );

        session_destroy();
        return true;
    }

    public static function setToken()
    {
        $_SESSION['sToken'] = md5($_SERVER['HTTP_USER_AGENT'] . SpamTrawler_VisitorDetails_IP_IP::get());
    }

    public static function checkToken($sToken)
    {
        if(!isset($_SESSION['sToken']) || $_SESSION['sToken'] != $sToken) {
            return false;
        }
        return true;
    }

    public function clearAll()
    {
        try {
            array_map('unlink', (glob(session_save_path() . '*') ? glob(session_save_path() . '*') : array()));
            return true;
        } catch (Exception $e) {
            $this->logError('Session Deletion All', $e);
            return false;
        }
    }

    public function clearOld()
    {
        try {
            $interval = time() - $this->lifetime;//files older than 24hours

            foreach (glob(session_save_path() . DIRECTORY_SEPARATOR . "sess_*") as $file){
                //delete if older
                if (filemtime($file) <= $interval) unlink($file);
            }
            return true;
        } catch (Exception $e){
            $this->logError('Session Deletion Old', $e);
            return false;
        }
    }

    private function logError($subject, $e){
        //logging error
        $writer = new SpamTrawler_Log_Writer_Stream(TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'error.log');
        $logger = new SpamTrawler_Log($writer);
        $logger->warn($subject . ': ' . $e);
    }
}
