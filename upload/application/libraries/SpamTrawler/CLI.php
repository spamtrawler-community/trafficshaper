<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 16/04/15
 * Time: 18:42
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler) 
 */

class SpamTrawler_CLI {
    public function is_cli() {
        return (!isset($_SERVER['SERVER_SOFTWARE']) && (php_sapi_name() === 'cli' || (is_numeric($_SERVER['argc']) && $_SERVER['argc'] > 0)));
    }
}