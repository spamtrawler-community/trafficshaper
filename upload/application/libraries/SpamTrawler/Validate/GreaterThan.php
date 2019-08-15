<?php
/**
 * Zend Framework
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
 * @category   Zend
 * @package    SpamTrawler_Validate
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see SpamTrawler_Validate_Abstract
 */
require_once 'SpamTrawler/Validate/Abstract.php';

/**
 * @category   Zend
 * @package    SpamTrawler_Validate
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Validate_GreaterThan extends SpamTrawler_Validate_Abstract
{

    const NOT_GREATER = 'notGreaterThan';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_GREATER => "'%value%' is not greater than '%min%'",
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'min' => '_min'
    );

    /**
     * Minimum value
     *
     * @var mixed
     */
    protected $_min;

    /**
     * Sets validator options
     *
     * @param  mixed|SpamTrawler_Config $min
     * @throws SpamTrawler_Validate_Exception
     */
    public function __construct($min)
    {
        if ($min instanceof SpamTrawler_Config) {
            $min = $min->toArray();
        }

        if (is_array($min)) {
            if (array_key_exists('min', $min)) {
                $min = $min['min'];
            } else {
                require_once 'SpamTrawler/Validate/Exception.php';
                throw new SpamTrawler_Validate_Exception("Missing option 'min'");
            }
        }

        $this->setMin($min);
    }

    /**
     * Returns the min option
     *
     * @return mixed
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * Sets the min option
     *
     * @param  mixed $min
     * @return SpamTrawler_Validate_GreaterThan Provides a fluent interface
     */
    public function setMin($min)
    {
        $this->_min = $min;
        return $this;
    }

    /**
     * Defined by SpamTrawler_Validate_Interface
     *
     * Returns true if and only if $value is greater than min option
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        if ($this->_min >= $value) {
            $this->_error(self::NOT_GREATER);
            return false;
        }
        return true;
    }

}
