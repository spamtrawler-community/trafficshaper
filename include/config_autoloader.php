<?php
$aConfigAutoloader = array(
    'prefixes' => array(
        'SpamTrawler' => TRAWLER_PATH_LIBRARIES . DIRECTORY_SEPARATOR . 'SpamTrawler',
        'SpamTrawlerX' => TRAWLER_PATH_LIBRARIES . DIRECTORY_SEPARATOR . 'SpamTrawlerX',
        'Admin' => TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Admin',
        'API' => TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'API',
        'Feeds' => TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Feeds',
        'Filesystem' => TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Filesystem',
        'Firewall' => TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Firewall',
        'Install' => TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'Install',
        'User' => TRAWLER_PATH_MODULES . DIRECTORY_SEPARATOR . 'User'
    ),
    'namespaces' => array(
    ),
    'fallback_autoloader' => false,
);
