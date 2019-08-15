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
 * @subpackage Filter
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** SpamTrawler_Log_Filter_Interface */
require_once 'SpamTrawler/Log/Filter/Abstract.php';

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Filter
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
class SpamTrawler_Log_Filter_Suppress extends SpamTrawler_Log_Filter_Abstract
{
    /**
     * @var boolean
     */
    protected $_accept = true;

    /**
     * This is a simple boolean filter.
     *
     * Call suppress(true) to suppress all log events.
     * Call suppress(false) to accept all log events.
     *
     * @param  boolean  $suppress  Should all log events be suppressed?
     * @return  void
     */
    public function suppress($suppress)
    {
        $this->_accept = (! $suppress);
    }

    /**
     * Returns TRUE to accept the message, FALSE to block it.
     *
     * @param  array    $event    event data
     * @return boolean            accepted?
     */
    public function accept($event)
    {
        return $this->_accept;
    }

    /**
     * Create a new instance of SpamTrawler_Log_Filter_Suppress
     *
     * @param  array|SpamTrawler_Config $config
     * @return SpamTrawler_Log_Filter_Suppress
     * @throws SpamTrawler_Log_Exception
     */
    static public function factory($config)
    {
        return new self();
    }
}
