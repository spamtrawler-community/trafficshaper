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
 * @package    SpamTrawler_Log
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
class SpamTrawler_Log
{
    const EMERG   = 0;  // Emergency: system is unusable
    const ALERT   = 1;  // Alert: action must be taken immediately
    const CRIT    = 2;  // Critical: critical conditions
    const ERR     = 3;  // Error: error conditions
    const WARN    = 4;  // Warning: warning conditions
    const NOTICE  = 5;  // Notice: normal but significant condition
    const INFO    = 6;  // Informational: informational messages
    const DEBUG   = 7;  // Debug: debug messages

    /**
     * @var array of priorities where the keys are the
     * priority numbers and the values are the priority names
     */
    protected $_priorities = array();

    /**
     * @var array of SpamTrawler_Log_Writer_Abstract
     */
    protected $_writers = array();

    /**
     * @var array of SpamTrawler_Log_Filter_Interface
     */
    protected $_filters = array();

    /**
     * @var array of extra log event
     */
    protected $_extras = array();

    /**
     *
     * @var string
     */
    protected $_defaultWriterNamespace = 'SpamTrawler_Log_Writer';

    /**
     *
     * @var string
     */
    protected $_defaultFilterNamespace = 'SpamTrawler_Log_Filter';

    /**
     *
     * @var string
     */
    protected $_defaultFormatterNamespace = 'SpamTrawler_Log_Formatter';

    /**
     *
     * @var callback
     */
    protected $_origErrorHandler       = null;

    /**
     *
     * @var boolean
     */
    protected $_registeredErrorHandler = false;

    /**
     *
     * @var array|boolean
     */
    protected $_errorHandlerMap        = false;

    /**
     *
     * @var string
     */
    protected $_timestampFormat        = 'c';

    /**
     * Class constructor.  Create a new logger
     *
     * @param SpamTrawler_Log_Writer_Abstract|null  $writer  default writer
     * @return void
     */
    public function __construct(SpamTrawler_Log_Writer_Abstract $writer = null)
    {
        $r = new ReflectionClass($this);
        $this->_priorities = array_flip($r->getConstants());

        if ($writer !== null) {
            $this->addWriter($writer);
        }
    }

    /**
     * Factory to construct the logger and one or more writers
     * based on the configuration array
     *
     * @param  array|SpamTrawler_Config Array or instance of SpamTrawler_Config
     * @return SpamTrawler_Log
     * @throws SpamTrawler_Log_Exception
     */
    static public function factory($config = array())
    {
        if ($config instanceof SpamTrawler_Config) {
            $config = $config->toArray();
        }

        if (!is_array($config) || empty($config)) {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Configuration must be an array or instance of SpamTrawler_Config');
        }

        if (array_key_exists('className', $config)) {
            $class = $config['className'];
            unset($config['className']);
        } else {
            $class = __CLASS__;
        }

        $log = new $class;

        if (!$log instanceof SpamTrawler_Log) {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Passed className does not belong to a descendant of SpamTrawler_Log');
        }

        if (array_key_exists('timestampFormat', $config)) {
            if (null != $config['timestampFormat'] && '' != $config['timestampFormat']) {
                $log->setTimestampFormat($config['timestampFormat']);
            }
            unset($config['timestampFormat']);
        }

        if (!is_array(current($config))) {
            $log->addWriter(current($config));
        } else {
            foreach($config as $writer) {
                $log->addWriter($writer);
            }
        }

        return $log;
    }


    /**
     * Construct a writer object based on a configuration array
     *
     * @param  array $spec config array with writer spec
     * @return SpamTrawler_Log_Writer_Abstract
     * @throws SpamTrawler_Log_Exception
     */
    protected function _constructWriterFromConfig($config)
    {
        $writer = $this->_constructFromConfig('writer', $config, $this->_defaultWriterNamespace);

        if (!$writer instanceof SpamTrawler_Log_Writer_Abstract) {
            $writerName = is_object($writer)
                        ? get_class($writer)
                        : 'The specified writer';
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception("{$writerName} does not extend SpamTrawler_Log_Writer_Abstract!");
        }

        if (isset($config['filterName'])) {
            $filter = $this->_constructFilterFromConfig($config);
            $writer->addFilter($filter);
        }

        if (isset($config['formatterName'])) {
            $formatter = $this->_constructFormatterFromConfig($config);
            $writer->setFormatter($formatter);
        }

        return $writer;
    }

    /**
     * Construct filter object from configuration array or SpamTrawler_Config object
     *
     * @param  array|SpamTrawler_Config $config SpamTrawler_Config or Array
     * @return SpamTrawler_Log_Filter_Interface
     * @throws SpamTrawler_Log_Exception
     */
    protected function _constructFilterFromConfig($config)
    {
        $filter = $this->_constructFromConfig('filter', $config, $this->_defaultFilterNamespace);

        if (!$filter instanceof SpamTrawler_Log_Filter_Interface) {
             $filterName = is_object($filter)
                         ? get_class($filter)
                         : 'The specified filter';
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception("{$filterName} does not implement SpamTrawler_Log_Filter_Interface");
        }

        return $filter;
    }

   /**
    * Construct formatter object from configuration array or SpamTrawler_Config object
    *
    * @param  array|SpamTrawler_Config $config SpamTrawler_Config or Array
    * @return SpamTrawler_Log_Formatter_Interface
    * @throws SpamTrawler_Log_Exception
    */
    protected function _constructFormatterFromConfig($config)
    {
        $formatter = $this->_constructFromConfig('formatter', $config, $this->_defaultFormatterNamespace);

        if (!$formatter instanceof SpamTrawler_Log_Formatter_Interface) {
             $formatterName = is_object($formatter)
                         ? get_class($formatter)
                         : 'The specified formatter';
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception($formatterName . ' does not implement SpamTrawler_Log_Formatter_Interface');
        }

        return $formatter;
    }

    /**
     * Construct a filter or writer from config
     *
     * @param string $type 'writer' of 'filter'
     * @param mixed $config SpamTrawler_Config or Array
     * @param string $namespace
     * @return object
     * @throws SpamTrawler_Log_Exception
     */
    protected function _constructFromConfig($type, $config, $namespace)
    {
        if ($config instanceof SpamTrawler_Config) {
            $config = $config->toArray();
        }

        if (!is_array($config) || empty($config)) {
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception(
                'Configuration must be an array or instance of SpamTrawler_Config'
            );
        }

        $params    = isset($config[ $type .'Params' ]) ? $config[ $type .'Params' ] : array();
        $className = $this->getClassName($config, $type, $namespace);
        if (!class_exists($className)) {
            require_once 'SpamTrawler/Loader.php';
            SpamTrawler_Loader::loadClass($className);
        }

        $reflection = new ReflectionClass($className);
        if (!$reflection->implementsInterface('SpamTrawler_Log_FactoryInterface')) {
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception(
                $className . ' does not implement SpamTrawler_Log_FactoryInterface and can not be constructed from config.'
            );
        }

        return call_user_func(array($className, 'factory'), $params);
    }

    /**
     * Get the writer or filter full classname
     *
     * @param array $config
     * @param string $type filter|writer
     * @param string $defaultNamespace
     * @return string full classname
     * @throws SpamTrawler_Log_Exception
     */
    protected function getClassName($config, $type, $defaultNamespace)
    {
        if (!isset($config[$type . 'Name'])) {
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception("Specify {$type}Name in the configuration array");
        }

        $className = $config[$type . 'Name'];
        $namespace = $defaultNamespace;

        if (isset($config[$type . 'Namespace'])) {
            $namespace = $config[$type . 'Namespace'];
        }

        // PHP >= 5.3.0 namespace given?
        if (substr($namespace, -1) == '\\') {
            return $namespace . $className;
        }

        // empty namespace given?
        if (strlen($namespace) === 0) {
            return $className;
        }

        return $namespace . '_' . $className;
    }

    /**
     * Packs message and priority into Event array
     *
     * @param  string   $message   Message to log
     * @param  integer  $priority  Priority of message
     * @return array Event array
     */
    protected function _packEvent($message, $priority)
    {
        return array_merge(array(
            'timestamp'    => date($this->_timestampFormat),
            'message'      => $message,
            'priority'     => $priority,
            'priorityName' => $this->_priorities[$priority]
            ),
            $this->_extras
        );
    }

    /**
     * Class destructor.  Shutdown log writers
     *
     * @return void
     */
    public function __destruct()
    {
        foreach($this->_writers as $writer) {
            $writer->shutdown();
        }
    }

    /**
     * Undefined method handler allows a shortcut:
     *   $log->priorityName('message')
     *     instead of
     *   $log->log('message', SpamTrawler_Log::PRIORITY_NAME)
     *
     * @param  string  $method  priority name
     * @param  string  $params  message to log
     * @return void
     * @throws SpamTrawler_Log_Exception
     */
    public function __call($method, $params)
    {
        $priority = strtoupper($method);
        if (($priority = array_search($priority, $this->_priorities)) !== false) {
            switch (count($params)) {
                case 0:
                    /** @see SpamTrawler_Log_Exception */
                    require_once 'SpamTrawler/Log/Exception.php';
                    throw new SpamTrawler_Log_Exception('Missing log message');
                case 1:
                    $message = array_shift($params);
                    $extras = null;
                    break;
                default:
                    $message = array_shift($params);
                    $extras  = array_shift($params);
                    break;
            }
            $this->log($message, $priority, $extras);
        } else {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Bad log priority');
        }
    }

    /**
     * Log a message at a priority
     *
     * @param  string   $message   Message to log
     * @param  integer  $priority  Priority of message
     * @param  mixed    $extras    Extra information to log in event
     * @return void
     * @throws SpamTrawler_Log_Exception
     */
    public function log($message, $priority, $extras = null)
    {
        // sanity checks
        if (empty($this->_writers)) {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('No writers were added');
        }

        if (! isset($this->_priorities[$priority])) {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Bad log priority');
        }

        // pack into event required by filters and writers
        $event = $this->_packEvent($message, $priority);

        // Check to see if any extra information was passed
        if (!empty($extras)) {
            $info = array();
            if (is_array($extras)) {
                foreach ($extras as $key => $value) {
                    if (is_string($key)) {
                        $event[$key] = $value;
                    } else {
                        $info[] = $value;
                    }
                }
            } else {
                $info = $extras;
            }
            if (!empty($info)) {
                $event['info'] = $info;
            }
        }

        // abort if rejected by the global filters
        foreach ($this->_filters as $filter) {
            if (! $filter->accept($event)) {
                return;
            }
        }

        // send to each writer
        foreach ($this->_writers as $writer) {
            $writer->write($event);
        }
    }

    /**
     * Add a custom priority
     *
     * @param  string   $name      Name of priority
     * @param  integer  $priority  Numeric priority
     * @throws SpamTrawler_Log_Exception
     */
    public function addPriority($name, $priority)
    {
        // Priority names must be uppercase for predictability.
        $name = strtoupper($name);

        if (isset($this->_priorities[$priority])
            || false !== array_search($name, $this->_priorities)) {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Existing priorities cannot be overwritten');
        }

        $this->_priorities[$priority] = $name;
        return $this;
    }

    /**
     * Add a filter that will be applied before all log writers.
     * Before a message will be received by any of the writers, it
     * must be accepted by all filters added with this method.
     *
     * @param  int|SpamTrawler_Config|array|SpamTrawler_Log_Filter_Interface $filter
     * @return SpamTrawler_Log
     * @throws SpamTrawler_Log_Exception
     */
    public function addFilter($filter)
    {
        if (is_int($filter)) {
            /** @see SpamTrawler_Log_Filter_Priority */
            require_once 'SpamTrawler/Log/Filter/Priority.php';
            $filter = new SpamTrawler_Log_Filter_Priority($filter);

        } elseif ($filter instanceof SpamTrawler_Config || is_array($filter)) {
            $filter = $this->_constructFilterFromConfig($filter);

        } elseif(! $filter instanceof SpamTrawler_Log_Filter_Interface) {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Invalid filter provided');
        }

        $this->_filters[] = $filter;
        return $this;
    }

    /**
     * Add a writer.  A writer is responsible for taking a log
     * message and writing it out to storage.
     *
     * @param  mixed $writer SpamTrawler_Log_Writer_Abstract or Config array
     * @return SpamTrawler_Log
     */
    public function addWriter($writer)
    {
        if (is_array($writer) || $writer instanceof  SpamTrawler_Config) {
            $writer = $this->_constructWriterFromConfig($writer);
        }

        if (!$writer instanceof SpamTrawler_Log_Writer_Abstract) {
            /** @see SpamTrawler_Log_Exception */
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception(
                'Writer must be an instance of SpamTrawler_Log_Writer_Abstract'
                . ' or you should pass a configuration array'
            );
        }

        $this->_writers[] = $writer;
        return $this;
    }

    /**
     * Set an extra item to pass to the log writers.
     *
     * @param  string $name    Name of the field
     * @param  string $value   Value of the field
     * @return SpamTrawler_Log
     */
    public function setEventItem($name, $value)
    {
        $this->_extras = array_merge($this->_extras, array($name => $value));
        return $this;
    }

    /**
     * Register Logging system as an error handler to log php errors
     * Note: it still calls the original error handler if set_error_handler is able to return it.
     *
     * Errors will be mapped as:
     *   E_NOTICE, E_USER_NOTICE => NOTICE
     *   E_WARNING, E_CORE_WARNING, E_USER_WARNING => WARN
     *   E_ERROR, E_USER_ERROR, E_CORE_ERROR, E_RECOVERABLE_ERROR => ERR
     *   E_DEPRECATED, E_STRICT, E_USER_DEPRECATED => DEBUG
     *   (unknown/other) => INFO
     *
     * @link http://www.php.net/manual/en/function.set-error-handler.php Custom error handler
     *
     * @return SpamTrawler_Log
     */
    public function registerErrorHandler()
    {
        // Only register once.  Avoids loop issues if it gets registered twice.
        if ($this->_registeredErrorHandler) {
            return $this;
        }

        $this->_origErrorHandler = set_error_handler(array($this, 'errorHandler'));

        // Contruct a default map of phpErrors to SpamTrawler_Log priorities.
        // Some of the errors are uncatchable, but are included for completeness
        $this->_errorHandlerMap = array(
            E_NOTICE            => SpamTrawler_Log::NOTICE,
            E_USER_NOTICE       => SpamTrawler_Log::NOTICE,
            E_WARNING           => SpamTrawler_Log::WARN,
            E_CORE_WARNING      => SpamTrawler_Log::WARN,
            E_USER_WARNING      => SpamTrawler_Log::WARN,
            E_ERROR             => SpamTrawler_Log::ERR,
            E_USER_ERROR        => SpamTrawler_Log::ERR,
            E_CORE_ERROR        => SpamTrawler_Log::ERR,
            E_RECOVERABLE_ERROR => SpamTrawler_Log::ERR,
            E_STRICT            => SpamTrawler_Log::DEBUG,
        );
        // PHP 5.3.0+
        if (defined('E_DEPRECATED')) {
            $this->_errorHandlerMap['E_DEPRECATED'] = SpamTrawler_Log::DEBUG;
        }
        if (defined('E_USER_DEPRECATED')) {
            $this->_errorHandlerMap['E_USER_DEPRECATED'] = SpamTrawler_Log::DEBUG;
        }

        $this->_registeredErrorHandler = true;
        return $this;
    }

    /**
     * Error Handler will convert error into log message, and then call the original error handler
     *
     * @link http://www.php.net/manual/en/function.set-error-handler.php Custom error handler
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param array $errcontext
     * @return boolean
     */
    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $errorLevel = error_reporting();

        if ($errorLevel & $errno) {
            if (isset($this->_errorHandlerMap[$errno])) {
                $priority = $this->_errorHandlerMap[$errno];
            } else {
                $priority = SpamTrawler_Log::INFO;
            }
            $this->log($errstr, $priority, array('errno'=>$errno, 'file'=>$errfile, 'line'=>$errline, 'context'=>$errcontext));
        }

        if ($this->_origErrorHandler !== null) {
            return call_user_func($this->_origErrorHandler, $errno, $errstr, $errfile, $errline, $errcontext);
        }
        return false;
    }

    /**
     * Set timestamp format for log entries.
     *
     * @param string $format
     * @return SpamTrawler_Log
     */
    public function setTimestampFormat($format)
    {
        $this->_timestampFormat = $format;
        return $this;
    }

    /**
     * Get timestamp format used for log entries.
     *
     * @return string
     */
    public function getTimestampFormat()
    {
        return $this->_timestampFormat;
    }
}
