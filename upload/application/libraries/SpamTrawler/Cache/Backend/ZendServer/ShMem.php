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
 * @subpackage SpamTrawler_Cache_Backend
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/** @see SpamTrawler_Cache_Backend_Interface */
require_once 'SpamTrawler/Cache/Backend/Interface.php';

/** @see SpamTrawler_Cache_Backend_SpamTrawlerServer */
require_once 'SpamTrawler/Cache/Backend/SpamTrawlerServer.php';


/**
 * @package    SpamTrawler_Cache
 * @subpackage SpamTrawler_Cache_Backend
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Cache_Backend_SpamTrawlerServer_ShMem extends SpamTrawler_Cache_Backend_SpamTrawlerServer implements SpamTrawler_Cache_Backend_Interface
{
    /**
     * Constructor
     *
     * @param  array $options associative array of options
     * @throws SpamTrawler_Cache_Exception
     */
    public function __construct(array $options = array())
    {
        if (!function_exists('zend_shm_cache_store')) {
            SpamTrawler_Cache::throwException('SpamTrawler_Cache_SpamTrawlerServer_ShMem backend has to be used within SpamTrawler Server environment.');
        }
        parent::__construct($options);
    }

    /**
     * Store data
     *
     * @param mixed  $data        Object to store
     * @param string $id          Cache id
     * @param int    $timeToLive  Time to live in seconds
     *
     */
    protected function _store($data, $id, $timeToLive)
    {
        if (zend_shm_cache_store($this->_options['namespace'] . '::' . $id,
                                  $data,
                                  $timeToLive) === false) {
            $this->_log('Store operation failed.');
            return false;
        }
        return true;
    }

    /**
     * Fetch data
     *
     * @param string $id          Cache id
     */
    protected function _fetch($id)
    {
        return zend_shm_cache_fetch($this->_options['namespace'] . '::' . $id);
    }

    /**
     * Unset data
     *
     * @param string $id          Cache id
     * @return boolean true if no problem
     */
    protected function _unset($id)
    {
        return zend_shm_cache_delete($this->_options['namespace'] . '::' . $id);
    }

    /**
     * Clear cache
     */
    protected function _clear()
    {
        zend_shm_cache_clear($this->_options['namespace']);
    }
}
