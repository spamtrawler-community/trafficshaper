<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 14/06/14
 * Time: 15:31
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawler_Db_Tables_Generic extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = NULL;

    public function __construct($sTableName)
    {
        $tableNameAlnum = str_replace('_', '', $sTableName);
        if(ctype_alnum($tableNameAlnum)){
            $this->_name = $sTableName;
        } else {
            exit('Table name: ' . $sTableName . ' not valid!');
        }
        parent::__construct();
    }
}
