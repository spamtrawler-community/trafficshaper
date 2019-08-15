<?php

class Install_Setup_Controller_Steps extends SpamTrawler_BaseClasses_Modules_Controller
{
    private $_sError = array();

    public function __construct(){
        new SpamTrawler_Session('SpamTrawler Installer');
    }

    public function index()
    {
        exit('Invalid Route!');
    }

    public function checkRequirements(){
        $aError = array();

        if (version_compare(phpversion(), '5.4', '<')) {
            $aError[] = '<li>To use SpamTrawler v6 your server needs to have at least PHP 5.4 installed!</li>';
        }

        /*
        if(!function_exists('ioncube_read_file')){
            $aError[] = '<li>IonCube Extension missing!</li>';
        }
        */

        if(!function_exists('mysqli_connect') && !extension_loaded('pdo_mysql')) {
            $aError[] = '<li>Database Driver missing. Please install pdo_mysql or MySQLi extension!</li>';
        }

        if(!function_exists('curl_version')){
            $aError[] = '<li>Curl required. Please install php curl extension!</li>';
        }

        if(!empty($aError)){
            die('<div class="small-11 center text-left"><h1>Server Requirements not met</h1><ul class="circle">' . implode('<br />', $aError) . '</ul></div>');
        } else {
            die('ok');
        }
    }

    public function checkPermissions(){
        $aError = array();

        $sConfigfile = TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config.php';
        if (!file_exists($sConfigfile) || !is_writable($sConfigfile)) {
            $aError[] = '<li>File: ' . TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config.php' . ' needs to be writable</li>';
        }

        $sPathLogs = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'log';
        if(!is_writable($sPathLogs)){
            $aError[] = '<li>Directory: ' . $sPathLogs . ' needs to be writable</li>';
        }

        $sPathCache = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache';
        if(!is_writable($sPathCache)){
            $aError[] = '<li>Directory: ' . $sPathCache . ' needs to be writable</li>';
        }

        $sPathTablemeta = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'tablemeta';
        if(!is_writable($sPathTablemeta)){
            $aError[] = '<li>Directory: ' . $sPathTablemeta . ' needs to be writable</li>';
        }

        $sPathTemplatesC = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'templates_c';
        if(!is_writable($sPathTemplatesC)){
            $aError[] = '<li>Directory: ' . $sPathTemplatesC . ' needs to be writable</li>';
        }

        $sPathSessions = TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'sessions';
        if(!is_writable($sPathSessions)){
            $aError[] = '<li>Directory: ' . $sPathSessions . ' needs to be writable</li>';
        }

        if(!empty($aError)){
            die('<div class="small-11 center text-left"><h1>Permissions</h1><ul class="circle">' . implode('<br />', $aError) . '</ul></div>');
        } else {
            die('ok');
        }
    }

    public function checkDatabaseCredentials(){
        $aAllowedDbAdapters = array(
            'mysqli',
            'pdo_mysql'
        );

        if(!isset($_POST['dbadapter'], $_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname'], $_POST['dbport'])){
            die('Please provide all database parameters!');
        }

        if(!ctype_digit($_POST['dbport'])){
            die('Port value can be numbers only!');
        }

        if(!in_array($_POST['dbadapter'], $aAllowedDbAdapters)){
            die('Invalid Adapter!');
        }

        try{
            $db = SpamTrawler_Db::factory($_POST['dbadapter'], array(
                'host'     => $_POST['dbhost'],
                'username' => $_POST['dbuser'],
                'password' => $_POST['dbpass'],
                'dbname'   => $_POST['dbname'],
                'dbport'   => $_POST['dbport']
            ));


            //$db->beginTransaction();
            $sql = file_get_contents(TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Install' . DIRECTORY_SEPARATOR . 'Setup' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'spamtrawler.sql');
            $sql = str_replace('%tblprefix%', $_POST['tblprefix'], $sql);
            $aQueries = explode('<--QUERY-->', $sql);

            $db->getConnection();
            //$db->beginTransaction();
            foreach($aQueries as $sql){
                $db->query($sql);
            }
            //$db->commit();

            $this->setupConfig($_POST['dbhost'], $_POST['dbname'], $_POST['dbuser'], $_POST['dbpass'], $_POST['tblprefix'], $_POST['dbadapter'], $_POST['dbport']);

            die('ok');
        } catch(Exception $e){
            die('Cannot access Database!<br />' . $e);
        }
    }

    private function setupConfig($dbhost, $dbname, $dbuser, $dbpass, $tblprefix, $dbadapter, $dbport){

        $config = <<<CONFDATA
<?php
return array(
    'webhost'  => 'dev.spamtrawler.net',
    'database' => array(
        'adapter' => '$dbadapter',
        'params'  => array(
            'host'     => '$dbhost',
            'username' => '$dbuser',
            'password' => '$dbpass',
            'dbname'   => '$dbname',
            'port'     => '$dbport'
        ),
        'table' => array(
            'prefix' => '$tblprefix'
        )
    ),
    'cache' => array(
        'backend' => 'File',
        'frontend_options'  => array(
            'lifetime' => 86400,
            'automatic_serialization' => true
        ),
        //Backend Options Redis Example
        /*'backend_options' => array(
            'servers' => array(
                array(
                'host' => '127.0.0.1',
                'port' => 6379,
                'password' => '')
            )
        ),
        */
        //Backend Options File example

        'backend_options' => array(
            'cache_dir'=> TRAWLER_PATH_FILES . DIRECTORY_SEPARATOR . 'cache',
            'file_name_prefix' => 'SpamTrawler',
            'cache_file_perm' => 0644,
            //'hashed_directory_level' => 2
        ),
    ),
    'core' => array(
        'template' => 'Default',
        'language' => 'en_EN',
        'gridsize' => 10,
        'timezone' => 'UTC',
        'friendlyurl' => 1,
        'sysmode' => 'production'
    ),
    'firewall' => array(
        'apiparameter' => 'ST_Firewall_Params'
    )
);
CONFDATA;

        file_put_contents(TRAWLER_PATH_INCLUDE . DIRECTORY_SEPARATOR . 'config.php', $config);
    }

    public function addAdminUser(){

        if(!$_POST['adminuser'] || !$_POST['adminpass'] || !$_POST['adminemail']){
            die('Please fill in all form fields!');
        }

        $sAdminUser = $_POST['adminuser'];
        $sAdminPass = $_POST['adminpass'];
        $sAdminEmail = $_POST['adminemail'];

        if($this->validate($sAdminUser, $sAdminPass, $sAdminEmail) === false){
            die($this->_sError);
        }

        try{
            $aData = array(
                'group_id' => 1,
                'username' => $sAdminUser,
                'email' => $sAdminEmail
            );

            //Generate Salt and Passwordhash
            $aData['salt'] = SpamTrawler_Random::generate(9, false, 'luds');
            $aData['password'] = SpamTrawler_Password::generateHash($sAdminPass, $aData['salt']);

            $oTable = new SpamTrawler_Db_Tables_Generic('user');
            $oTable->insert($aData);

            die('ok');
        } catch(Exception $e){
            die($e);
        }
    }

    public function generatePassword(){
        $sPassword = SpamTrawler_Random::generate(9, false, 'luds');
        exit($sPassword);
    }

    private function validate($username, $password, $email){
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $username)){
            $this->_sError = '<u><strong>Invalid Username</strong></u><br />
                Length: 6-32 characters<br/>
                Characters: Alphanumeric<br/>
                Must begin with letter';
            return false;
        }

        if(!preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password)){
            $this->_sError = '<u><strong>Invalid Password</strong></u><br />
                Length: min. 8 characters<br/>
                Characters: Alphanumeric, Special Characters<br/><br />
                <u><strong>Complexity required:</strong></u><br />
                At least 1 lowercase and uppercase letter<br />
                At least 1 digit<br />
                At least 1 special character (e.g.: @$"%^&*_)';
            return false;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->_sError = 'Invalid email address!';
            return false;
        }

        return true;
    }
}
