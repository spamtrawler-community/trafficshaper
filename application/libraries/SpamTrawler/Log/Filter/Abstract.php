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

/** @see SpamTrawler_Log_Filter_Interface */
require_once 'SpamTrawler/Log/Filter/Interface.php';

/** @see SpamTrawler_Log_FactoryInterface */
require_once 'SpamTrawler/Log/FactoryInterface.php';

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Filter
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
abstract class SpamTrawler_Log_Filter_Abstract
    implements SpamTrawler_Log_Filter_Interface, SpamTrawler_Log_FactoryInterface
{
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
            throw new SpamTrawler_Log_Exception('Configuration must be an array or instance of SpamTrawler_Config');
        }

        return $config;
    }
}
