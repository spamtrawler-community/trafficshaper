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
 * @package    SpamTrawler_Filter
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see SpamTrawler_Filter_Compress_CompressInterface
 */
require_once 'SpamTrawler/Filter/Compress/CompressInterface.php';

/**
 * Compression adapter for Lzf
 *
 * @category   SpamTrawler
 * @package    SpamTrawler_Filter
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Filter_Compress_Lzf implements SpamTrawler_Filter_Compress_CompressInterface
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        if (!extension_loaded('lzf')) {
            require_once 'SpamTrawler/Filter/Exception.php';
            throw new SpamTrawler_Filter_Exception('This filter needs the lzf extension');
        }
    }

    /**
     * Compresses the given content
     *
     * @param  string $content
     * @return string
     */
    public function compress($content)
    {
        $compressed = lzf_compress($content);
        if (!$compressed) {
            require_once 'SpamTrawler/Filter/Exception.php';
            throw new SpamTrawler_Filter_Exception('Error during compression');
        }

        return $compressed;
    }

    /**
     * Decompresses the given content
     *
     * @param  string $content
     * @return string
     */
    public function decompress($content)
    {
        $compressed = lzf_decompress($content);
        if (!$compressed) {
            require_once 'SpamTrawler/Filter/Exception.php';
            throw new SpamTrawler_Filter_Exception('Error during compression');
        }

        return $compressed;
    }

    /**
     * Returns the adapter name
     *
     * @return string
     */
    public function toString()
    {
        return 'Lzf';
    }
}
