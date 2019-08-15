<?php
/**
 * SpamTrawler Community - A Website Traffic Manager
 * @package  SpamTrawler Community
 * @author   Oliver Putzer <emea@spamtrawler.net>
 */

//Instantiate SpamTrawler
define('SpamTrawler', true);

require(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR .'constants.php'));

require(TRAWLER_PATH_APPLICATION . DIRECTORY_SEPARATOR . 'SpamTrawler.php');

new SpamTrawler();

$oFirewall = new Firewall_Core_Controller_Filter();
$oFirewall->index();
restore_include_path();
