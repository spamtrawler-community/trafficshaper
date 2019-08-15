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
class SpamTrawler_Log_Writer_SpamTrawlerMonitor extends SpamTrawler_Log_Writer_Abstract
{
    /**
     * Is SpamTrawler Monitor enabled?
     *
     * @var boolean
     */
    protected $_isEnabled = true;

    /**
     * Is this for a SpamTrawler Server intance?
     *
     * @var boolean
     */
    protected $_isSpamTrawlerServer = false;

    /**
     * @return void
     */
    public function __construct()
    {
        if (!function_exists('monitor_custom_event')) {
            $this->_isEnabled = false;
        }
        if (function_exists('SpamTrawler_monitor_custom_event')) {
            $this->_isSpamTrawlerServer = true;
        }
    }

    /**
     * Create a new instance of SpamTrawler_Log_Writer_SpamTrawlerMonitor
     *
     * @param  array|SpamTrawler_Config $config
     * @return SpamTrawler_Log_Writer_SpamTrawlerMonitor
     */
    static public function factory($config)
    {
        return new self();
    }

    /**
     * Is logging to this writer enabled?
     *
     * If the SpamTrawler Monitor extension is not enabled, this log writer will
     * fail silently. You can query this method to determine if the log
     * writer is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->_isEnabled;
    }

    /**
     * Log a message to this writer.
     *
     * @param  array $event log data event
     * @return void
     */
    public function write($event)
    {
        if (!$this->isEnabled()) {
            return;
        }

        parent::write($event);
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event log data event
     * @return void
     */
    protected function _write($event)
    {
        $priority = $event['priority'];
        $message  = $event['message'];
        unset($event['priority'], $event['message']);

        if (!empty($event)) {
            if ($this->_isSpamTrawlerServer) {
                // On SpamTrawler Server; third argument should be the event
                SpamTrawler_monitor_custom_event($priority, $message, $event);
            } else {
                // On SpamTrawler Platform; third argument is severity -- either
                // 0 or 1 -- and fourth is optional (event)
                // Severity is either 0 (normal) or 1 (severe); classifying
                // notice, info, and debug as "normal", and all others as
                // "severe"
                monitor_custom_event($priority, $message, ($priority > 4) ? 0 : 1, $event);
            }
        } else {
            monitor_custom_event($priority, $message);
        }
    }
}
