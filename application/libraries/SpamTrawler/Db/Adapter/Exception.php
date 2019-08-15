<?php
/**
 * SpamTrawler Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   SpamTrawler
 * @package    SpamTrawler_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * SpamTrawler_Db_Exception
 */
require_once 'SpamTrawler/Db/Exception.php';

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Db_Adapter_Exception extends SpamTrawler_Db_Exception
{
    protected $_chainedException = null;

    public function __construct($message = '', $code = 0, Exception $e = null)
    {
        if ($e && (0 === $code)) {
            $code = $e->getCode();
        }
        parent::__construct($message, $code, $e);
    }

    public function hasChainedException()
    {
        return ($this->getPrevious() !== null);
    }

    public function getChainedException()
    {
        return $this->getPrevious();
    }

}
