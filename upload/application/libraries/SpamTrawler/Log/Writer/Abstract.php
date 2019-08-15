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

/** SpamTrawler_Log_Filter_Priority */
require_once 'SpamTrawler/Log/Filter/Priority.php';

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Writer
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
abstract class SpamTrawler_Log_Writer_Abstract implements SpamTrawler_Log_FactoryInterface
{
    /**
     * @var array of SpamTrawler_Log_Filter_Interface
     */
    protected $_filters = array();

    /**
     * Formats the log message before writing.
     *
     * @var SpamTrawler_Log_Formatter_Interface
     */
    protected $_formatter;

    /**
     * Add a filter specific to this writer.
     *
     * @param  SpamTrawler_Log_Filter_Interface|int $filter Filter class or filter
     *                                               priority
     * @return SpamTrawler_Log_Writer_Abstract
     * @throws SpamTrawler_Log_Exception
     */
    public function addFilter($filter)
    {
        if (is_int($filter)) {
            $filter = new SpamTrawler_Log_Filter_Priority($filter);
        }

        if (!$filter instanceof SpamTrawler_Log_Filter_Interface) {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Invalid filter provided');
        }

        $this->_filters[] = $filter;
        return $this;
    }

    /**
     * Log a message to this writer.
     *
     * @param  array $event log data event
     * @return void
     */
    public function write($event)
    {
        /** @var SpamTrawler_Log_Filter_Interface $filter */
        foreach ($this->_filters as $filter) {
            if (!$filter->accept($event)) {
                return;
            }
        }

        // exception occurs on error
        $this->_write($event);
    }

    /**
     * Set a new formatter for this writer
     *
     * @param  SpamTrawler_Log_Formatter_Interface $formatter
     * @return SpamTrawler_Log_Writer_Abstract
     */
    public function setFormatter(SpamTrawler_Log_Formatter_Interface $formatter)
    {
        $this->_formatter = $formatter;
        return $this;
    }

    /**
     * Perform shutdown activites such as closing open resources
     *
     * @return void
     */
    public function shutdown()
    {}

    /**
     * Write a message to the log.
     *
     * @param  array $event log data event
     * @return void
     */
    abstract protected function _write($event);

    /**
     * Validate and optionally convert the config to array
     *
     * @param  array|SpamTrawler_Config $config SpamTrawler_Config or Array
     * @return array
     * @throws SpamTrawler_Log_Exception
     */
    static protected function _parseConfig($config)
    {
        if ($config instanceof SpamTrawler_Config) {
            $config = $config->toArray();
        }

        if (!is_array($config)) {
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception(
                'Configuration must be an array or instance of SpamTrawler_Config'
            );
        }

        return $config;
    }
}
