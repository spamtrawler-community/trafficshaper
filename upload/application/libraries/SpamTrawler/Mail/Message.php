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
 * @package    SpamTrawler_Mail
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * SpamTrawler_Mail_Part
 */
require_once 'SpamTrawler/Mail/Part.php';

/**
 * SpamTrawler_Mail_Message_Interface
 */
require_once 'SpamTrawler/Mail/Message/Interface.php';

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Mail
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Mail_Message extends SpamTrawler_Mail_Part implements SpamTrawler_Mail_Message_Interface
{
    /**
     * flags for this message
     * @var array
     */
    protected $_flags = array();

    /**
     * Public constructor
     *
     * In addition to the parameters of SpamTrawler_Mail_Part::__construct() this constructor supports:
     * - file  filename or file handle of a file with raw message content
     * - flags array with flags for message, keys are ignored, use constants defined in SpamTrawler_Mail_Storage
     *
     * @param  string $rawMessage  full message with or without headers
     * @throws SpamTrawler_Mail_Exception
     */
    public function __construct(array $params)
    {
        if (isset($params['file'])) {
            if (!is_resource($params['file'])) {
                $params['raw'] = @file_get_contents($params['file']);
                if ($params['raw'] === false) {
                    /**
                     * @see SpamTrawler_Mail_Exception
                     */
                    require_once 'SpamTrawler/Mail/Exception.php';
                    throw new SpamTrawler_Mail_Exception('could not open file');
                }
            } else {
                $params['raw'] = stream_get_contents($params['file']);
            }
        }

        if (!empty($params['flags'])) {
            // set key and value to the same value for easy lookup
            $this->_flags = array_merge($this->_flags, array_combine($params['flags'],$params['flags']));
        }

        parent::__construct($params);
    }

    /**
     * return toplines as found after headers
     *
     * @return string toplines
     */
    public function getTopLines()
    {
        return $this->_topLines;
    }

    /**
     * check if flag is set
     *
     * @param mixed $flag a flag name, use constants defined in SpamTrawler_Mail_Storage
     * @return bool true if set, otherwise false
     */
    public function hasFlag($flag)
    {
        return isset($this->_flags[$flag]);
    }

    /**
     * get all set flags
     *
     * @return array array with flags, key and value are the same for easy lookup
     */
    public function getFlags()
    {
        return $this->_flags;
    }
}
