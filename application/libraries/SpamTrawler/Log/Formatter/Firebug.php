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
 * @subpackage Formatter
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** SpamTrawler_Log_Formatter_Abstract */
require_once 'SpamTrawler/Log/Formatter/Abstract.php';

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Formatter
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Log_Formatter_Firebug extends SpamTrawler_Log_Formatter_Abstract
{
    /**
	 * Factory for SpamTrawler_Log_Formatter_Firebug classe
	 *
     * @param array|SpamTrawler_Config $options useless
	 * @return SpamTrawler_Log_Formatter_Firebug
     */
    public static function factory($options)
    {
        return new self;
    }

    /**
     * This method formats the event for the firebug writer.
     *
     * The default is to just send the message parameter, but through
     * extension of this class and calling the
     * {@see SpamTrawler_Log_Writer_Firebug::setFormatter()} method you can
     * pass as much of the event data as you are interested in.
     *
     * @param  array    $event    event data
     * @return mixed              event message
     */
    public function format($event)
    {
        return $event['message'];
    }
}
