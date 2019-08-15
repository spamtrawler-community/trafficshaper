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
 * SpamTrawler_Db_Adapter_Exception
 */
require_once 'SpamTrawler/Db/Adapter/Exception.php';

/**
 * SpamTrawler_Db_Adapter_Db2_Exception
 *
 * @package    SpamTrawler_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Db_Adapter_Db2_Exception extends SpamTrawler_Db_Adapter_Exception
{
   protected $code = '00000';
   protected $message = 'unknown exception';

   function __construct($message = 'unknown exception', $code = '00000', Exception $e = null)
   {
       parent::__construct($message, $code, $e);
   }
}
