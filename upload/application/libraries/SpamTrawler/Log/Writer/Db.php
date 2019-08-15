<?php
/**
 * SpamTrawler Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.SpamTrawler.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@SpamTrawler.com so we can send you a copy immediately.
 *
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Writer
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** SpamTrawler_Log_Writer_Abstract */
require_once 'SpamTrawler/Log/Writer/Abstract.php';

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Writer
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
class SpamTrawler_Log_Writer_Db extends SpamTrawler_Log_Writer_Abstract
{
    /**
     * Database adapter instance
     *
     * @var SpamTrawler_Db_Adapter
     */
    protected $_db;

    /**
     * Name of the log table in the database
     *
     * @var string
     */
    protected $_table;

    /**
     * Relates database columns names to log data field keys.
     *
     * @var null|array
     */
    protected $_columnMap;

    /**
     * Class constructor
     *
     * @param SpamTrawler_Db_Adapter $db   Database adapter instance
     * @param string $table         Log table in database
     * @param array $columnMap
     * @return void
     */
    public function __construct($db, $table, $columnMap = null)
    {
        $this->_db    = $db;
        $this->_table = $table;
        $this->_columnMap = $columnMap;
    }

    /**
     * Create a new instance of SpamTrawler_Log_Writer_Db
     *
     * @param  array|SpamTrawler_Config $config
     * @return SpamTrawler_Log_Writer_Db
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(array(
            'db'        => null,
            'table'     => null,
            'columnMap' => null,
        ), $config);

        if (isset($config['columnmap'])) {
            $config['columnMap'] = $config['columnmap'];
        }

        return new self(
            $config['db'],
            $config['table'],
            $config['columnMap']
        );
    }

    /**
     * Formatting is not possible on this writer
     *
     * @return void
     * @throws SpamTrawler_Log_Exception
     */
    public function setFormatter(SpamTrawler_Log_Formatter_Interface $formatter)
    {
        require_once 'SpamTrawler/Log/Exception.php';
        throw new SpamTrawler_Log_Exception(get_class($this) . ' does not support formatting');
    }

    /**
     * Remove reference to database adapter
     *
     * @return void
     */
    public function shutdown()
    {
        $this->_db = null;
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     * @throws SpamTrawler_Log_Exception
     */
    protected function _write($event)
    {
        if ($this->_db === null) {
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Database adapter is null');
        }

        if ($this->_columnMap === null) {
            $dataToInsert = $event;
        } else {
            $dataToInsert = array();
            foreach ($this->_columnMap as $columnName => $fieldKey) {
                if (isset($event[$fieldKey])) {
                    $dataToInsert[$columnName] = $event[$fieldKey];
                }
            }
        }

        $this->_db->insert($this->_table, $dataToInsert);
    }
}
