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
 * @package    SpamTrawler_Locale
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @version    $Id$
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * SpamTrawler_Exception
 */
require_once 'SpamTrawler/Locale/Exception.php';


/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Locale
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Locale_Math_Exception extends SpamTrawler_Locale_Exception
{
    protected $op1 = null;
    protected $op2 = null;
    protected $result = null;

    public function __construct($message, $op1 = null, $op2 = null, $result = null)
    {
        $this->op1 = $op1;
        $this->op2 = $op2;
        $this->result = $result;
        parent::__construct($message);
    }

    public function getResults()
    {
        return array($this->op1, $this->op2, $this->result);
    }
}
