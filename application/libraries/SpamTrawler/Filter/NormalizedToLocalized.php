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
 * @see SpamTrawler_Filter_Interface
 */
require_once 'SpamTrawler/Filter/Interface.php';

/**
 * @see SpamTrawler_Loader
 */
require_once 'SpamTrawler/Locale/Format.php';

/**
 * Localizes given normalized input
 *
 * @category   SpamTrawler
 * @package    SpamTrawler_Filter
 * @copyright  Copyright (c) 2005-2014 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SpamTrawler_Filter_NormalizedToLocalized implements SpamTrawler_Filter_Interface
{
    /**
     * Set options
     */
    protected $_options = array(
        'locale'      => null,
        'date_format' => null,
        'precision'   => null
    );

    /**
     * Class constructor
     *
     * @param string|SpamTrawler_Locale $locale (Optional) Locale to set
     */
    public function __construct($options = null)
    {
        if ($options instanceof SpamTrawler_Config) {
            $options = $options->toArray();
        }

        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    /**
     * Returns the set options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Sets options to use
     *
     * @param  array $options (Optional) Options to use
     * @return SpamTrawler_Filter_LocalizedToNormalized
     */
    public function setOptions(array $options = null)
    {
        $this->_options = $options + $this->_options;
        return $this;
    }

    /**
     * Defined by SpamTrawler_Filter_Interface
     *
     * Normalizes the given input
     *
     * @param  string $value Value to normalized
     * @return string|array The normalized value
     */
    public function filter($value)
    {
        if (is_array($value)) {
            require_once 'SpamTrawler/Date.php';
            $date = new SpamTrawler_Date($value, $this->_options['locale']);
            return $date->toString($this->_options['date_format']);
        } else if ($this->_options['precision'] === 0) {
            return SpamTrawler_Locale_Format::toInteger($value, $this->_options);
        } else if ($this->_options['precision'] === null) {
            return SpamTrawler_Locale_Format::toFloat($value, $this->_options);
        }

        return SpamTrawler_Locale_Format::toNumber($value, $this->_options);
    }
}
