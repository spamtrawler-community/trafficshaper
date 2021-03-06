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

/** @see SpamTrawler_Log_Formatter_Interface */
require_once 'SpamTrawler/Log/Formatter/Interface.php';

/** @see SpamTrawler_Log_FactoryInterface */
require_once 'SpamTrawler/Log/FactoryInterface.php';

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Formatter
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
abstract class SpamTrawler_Log_Formatter_Abstract
    implements SpamTrawler_Log_Formatter_Interface, SpamTrawler_Log_FactoryInterface
{
}
