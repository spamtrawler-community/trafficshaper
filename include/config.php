<?php
return array(
    'webhost'  => '',
    'database' => array(
        'adapter' => '',
        'params'  => array(
            'host'     => '',
            'username' => '',
            'password' => '',
            'dbname'   => '',
            'port'     => ''
        ),
        'table' => array(
            'prefix' => ''
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
            'file_name_prefix' => 'SpamTrawler'
            //'hashed_directory_level' => 2
        ),
    ),
    'core' => array(
        'template' => 'Default',
        'language' => 'en_EN',
        'gridsize' => 50,
        'timezone' => 'UTC',
        'friendlyurl' => 1,
    ),
    'firewall' => array(
        //'mode' => 'Regular', //Possible values: Server, Regular
        'apiparameter' => 'ST_Firewall_Params'
    )
);