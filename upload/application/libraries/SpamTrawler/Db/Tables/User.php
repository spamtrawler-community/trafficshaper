<?php

class SpamTrawler_Db_Tables_User extends SpamTrawler_Db_Table_Abstract
{
    protected $_name = 'user';

    public function getCount()
    {
        $select = $this->select();
        $select->from($this, array('count(*) as amount'));
        $rows = $this->fetchAll($select);

        return($rows[0]->amount);
    }
}
