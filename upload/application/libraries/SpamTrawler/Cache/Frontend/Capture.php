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
 * @package    SpamTrawler_Cache
 * @subpackage SpamTrawler_Cache_Frontend
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * @see SpamTrawler_Cache_Core
 */
require_once 'SpamTrawler/Cache/Core.php';


/**
 * @package    SpamTrawler_Cache
 * @subpackage SpamTrawler_Cache_Frontend
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Cache_Frontend_Capture extends SpamTrawler_Cache_Core
{
    /**
     * Page identifiers
     * @var array
     */
    protected $_idStack = array();

    /**
     * Tags
     * @var array
     */
    protected $_tags = array();

    protected $_extension = null;

    /**
     * Start the cache
     *
     * @param  string  $id Cache id
     * @return mixed True if the cache is hit (false else) with $echoData=true (default) ; string else (datas)
     */
    public function start($id, array $tags, $extension = null)
    {
        $this->_tags = $tags;
        $this->_extension = $extension;
        ob_start(array($this, '_flush'));
        ob_implicit_flush(false);
        $this->_idStack[] = $id;
        return false;
    }

    /**
     * callback for output buffering
     * (shouldn't really be called manually)
     *
     * @param  string $data Buffered output
     * @return string Data to send to browser
     */
    public function _flush($data)
    {
        $id = array_pop($this->_idStack);
        if ($id === null) {
            SpamTrawler_Cache::throwException('use of _flush() without a start()');
        }
        if ($this->_extension) {
            $this->save(serialize(array($data, $this->_extension)), $id, $this->_tags);
        } else {
            $this->save($data, $id, $this->_tags);
        }
        return $data;
    }
}
