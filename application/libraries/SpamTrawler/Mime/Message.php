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
 * @package    SpamTrawler_Mime
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * SpamTrawler_Mime
 */
require_once 'SpamTrawler/Mime.php';

/**
 * SpamTrawler_Mime_Part
 */
require_once 'SpamTrawler/Mime/Part.php';


/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Mime
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Mime_Message
{

    protected $_parts = array();
    protected $_mime = null;

    /**
     * Returns the list of all SpamTrawler_Mime_Parts in the message
     *
     * @return array of SpamTrawler_Mime_Part
     */
    public function getParts()
    {
        return $this->_parts;
    }

    /**
     * Sets the given array of SpamTrawler_Mime_Parts as the array for the message
     *
     * @param array $parts
     */
    public function setParts($parts)
    {
        $this->_parts = $parts;
    }

    /**
     * Append a new SpamTrawler_Mime_Part to the current message
     *
     * @param SpamTrawler_Mime_Part $part
     */
    public function addPart(SpamTrawler_Mime_Part $part)
    {
        /**
         * @todo check for duplicate object handle
         */
        $this->_parts[] = $part;
    }

    /**
     * Check if message needs to be sent as multipart
     * MIME message or if it has only one part.
     *
     * @return boolean
     */
    public function isMultiPart()
    {
        return (count($this->_parts) > 1);
    }

    /**
     * Set SpamTrawler_Mime object for the message
     *
     * This can be used to set the boundary specifically or to use a subclass of
     * SpamTrawler_Mime for generating the boundary.
     *
     * @param SpamTrawler_Mime $mime
     */
    public function setMime(SpamTrawler_Mime $mime)
    {
        $this->_mime = $mime;
    }

    /**
     * Returns the SpamTrawler_Mime object in use by the message
     *
     * If the object was not present, it is created and returned. Can be used to
     * determine the boundary used in this message.
     *
     * @return SpamTrawler_Mime
     */
    public function getMime()
    {
        if ($this->_mime === null) {
            $this->_mime = new SpamTrawler_Mime();
        }

        return $this->_mime;
    }

    /**
     * Generate MIME-compliant message from the current configuration
     *
     * This can be a multipart message if more than one MIME part was added. If
     * only one part is present, the content of this part is returned. If no
     * part had been added, an empty string is returned.
     *
     * Parts are seperated by the mime boundary as defined in SpamTrawler_Mime. If
     * {@link setMime()} has been called before this method, the SpamTrawler_Mime
     * object set by this call will be used. Otherwise, a new SpamTrawler_Mime object
     * is generated and used.
     *
     * @param string $EOL EOL string; defaults to {@link SpamTrawler_Mime::LINEEND}
     * @return string
     */
    public function generateMessage($EOL = SpamTrawler_Mime::LINEEND)
    {
        if (! $this->isMultiPart()) {
            $body = array_shift($this->_parts);
            $body = $body->getContent($EOL);
        } else {
            $mime = $this->getMime();

            $boundaryLine = $mime->boundaryLine($EOL);
            $body = 'This is a message in Mime Format.  If you see this, '
                  . "your mail reader does not support this format." . $EOL;

            foreach (array_keys($this->_parts) as $p) {
                $body .= $boundaryLine
                       . $this->getPartHeaders($p, $EOL)
                       . $EOL
                       . $this->getPartContent($p, $EOL);
            }

            $body .= $mime->mimeEnd($EOL);
        }

        return trim($body);
    }

    /**
     * Get the headers of a given part as an array
     *
     * @param int $partnum
     * @return array
     */
    public function getPartHeadersArray($partnum)
    {
        return $this->_parts[$partnum]->getHeadersArray();
    }

    /**
     * Get the headers of a given part as a string
     *
     * @param int $partnum
     * @return string
     */
    public function getPartHeaders($partnum, $EOL = SpamTrawler_Mime::LINEEND)
    {
        return $this->_parts[$partnum]->getHeaders($EOL);
    }

    /**
     * Get the (encoded) content of a given part as a string
     *
     * @param int $partnum
     * @return string
     */
    public function getPartContent($partnum, $EOL = SpamTrawler_Mime::LINEEND)
    {
        return $this->_parts[$partnum]->getContent($EOL);
    }

    /**
     * Explode MIME multipart string into seperate parts
     *
     * Parts consist of the header and the body of each MIME part.
     *
     * @param string $body
     * @param string $boundary
     * @return array
     */
    protected static function _disassembleMime($body, $boundary)
    {
        $start = 0;
        $res = array();
        // find every mime part limiter and cut out the
        // string before it.
        // the part before the first boundary string is discarded:
        $p = strpos($body, '--'.$boundary."\n", $start);
        if ($p === false) {
            // no parts found!
            return array();
        }

        // position after first boundary line
        $start = $p + 3 + strlen($boundary);

        while (($p = strpos($body, '--' . $boundary . "\n", $start)) !== false) {
            $res[] = substr($body, $start, $p-$start);
            $start = $p + 3 + strlen($boundary);
        }

        // no more parts, find end boundary
        $p = strpos($body, '--' . $boundary . '--', $start);
        if ($p===false) {
            throw new SpamTrawler_Exception('Not a valid Mime Message: End Missing');
        }

        // the remaining part also needs to be parsed:
        $res[] = substr($body, $start, $p-$start);
        return $res;
    }

    /**
     * Decodes a MIME encoded string and returns a SpamTrawler_Mime_Message object with
     * all the MIME parts set according to the given string
     *
     * @param string $message
     * @param string $boundary
     * @param string $EOL EOL string; defaults to {@link SpamTrawler_Mime::LINEEND}
     * @return SpamTrawler_Mime_Message
     */
    public static function createFromMessage($message, $boundary, $EOL = SpamTrawler_Mime::LINEEND)
    {
        require_once 'SpamTrawler/Mime/Decode.php';
        $parts = SpamTrawler_Mime_Decode::splitMessageStruct($message, $boundary, $EOL);

        $res = new self();
        foreach ($parts as $part) {
            // now we build a new MimePart for the current Message Part:
            $newPart = new SpamTrawler_Mime_Part($part['body']);
            foreach ($part['header'] as $key => $value) {
                /**
                 * @todo check for characterset and filename
                 */
                switch(strtolower($key)) {
                    case 'content-type':
                        $newPart->type = $value;
                        break;
                    case 'content-transfer-encoding':
                        $newPart->encoding = $value;
                        break;
                    case 'content-id':
                        $newPart->id = trim($value,'<>');
                        break;
                    case 'content-disposition':
                        $newPart->disposition = $value;
                        break;
                    case 'content-description':
                        $newPart->description = $value;
                        break;
                    case 'content-location':
                        $newPart->location = $value;
                        break;
                    case 'content-language':
                        $newPart->language = $value;
                        break;
                    default:
                        throw new SpamTrawler_Exception('Unknown header ignored for MimePart:' . $key);
                }
            }
            $res->addPart($newPart);
        }
        return $res;
    }
}
