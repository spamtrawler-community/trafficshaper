<?php
/**
 * SpamTrawler Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.SpamTrawler.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@SpamTrawler.com so we can send you a copy immediately.
 *
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Writer
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** SpamTrawler_Log_Writer_Abstract */
require_once 'SpamTrawler/Log/Writer/Abstract.php';

/** SpamTrawler_Log_Exception */
require_once 'SpamTrawler/Log/Exception.php';

/** SpamTrawler_Log_Formatter_Simple*/
require_once 'SpamTrawler/Log/Formatter/Simple.php';

/**
 * Class used for writing log messages to email via SpamTrawler_Mail.
 *
 * Allows for emailing log messages at and above a certain level via a
 * SpamTrawler_Mail object.  Note that this class only sends the email upon
 * completion, so any log entries accumulated are sent in a single email.
 *
 * @category   SpamTrawler
 * @package    SpamTrawler_Log
 * @subpackage Writer
 * @copyright  Copyright (c) 2005-2015 SpamTrawler Technologies USA Inc. (http://www.SpamTrawler.com)
 * @license    http://framework.SpamTrawler.com/license/new-bsd     New BSD License
 * @version    $Id$
 */
class SpamTrawler_Log_Writer_Mail extends SpamTrawler_Log_Writer_Abstract
{
    /**
     * Array of formatted events to include in message body.
     *
     * @var array
     */
    protected $_eventsToMail = array();

    /**
     * Array of formatted lines for use in an HTML email body; these events
     * are formatted with an optional formatter if the caller is using
     * SpamTrawler_Layout.
     *
     * @var array
     */
    protected $_layoutEventsToMail = array();

    /**
     * SpamTrawler_Mail instance to use
     *
     * @var SpamTrawler_Mail
     */
    protected $_mail;

    /**
     * SpamTrawler_Layout instance to use; optional.
     *
     * @var SpamTrawler_Layout
     */
    protected $_layout;

    /**
     * Optional formatter for use when rendering with SpamTrawler_Layout.
     *
     * @var SpamTrawler_Log_Formatter_Interface
     */
    protected $_layoutFormatter;

    /**
     * Array keeping track of the number of entries per priority level.
     *
     * @var array
     */
    protected $_numEntriesPerPriority = array();

    /**
     * Subject prepend text.
     *
     * Can only be used of the SpamTrawler_Mail object has not already had its
     * subject line set.  Using this will cause the subject to have the entry
     * counts per-priority level appended to it.
     *
     * @var string|null
     */
    protected $_subjectPrependText;

    /**
     * MethodMap for SpamTrawler_Mail's headers
     *
     * @var array
     */
    protected static $_methodMapHeaders = array(
        'from' => 'setFrom',
        'to' => 'addTo',
        'cc' => 'addCc',
        'bcc' => 'addBcc',
    );

    /**
     * Class constructor.
     *
     * Constructs the mail writer; requires a SpamTrawler_Mail instance, and takes an
     * optional SpamTrawler_Layout instance.  If SpamTrawler_Layout is being used,
     * $this->_layout->events will be set for use in the layout template.
     *
     * @param  SpamTrawler_Mail $mail Mail instance
     * @param  SpamTrawler_Layout $layout Layout instance; optional
     * @return void
     */
    public function __construct(SpamTrawler_Mail $mail, SpamTrawler_Layout $layout = null)
    {
        $this->_mail = $mail;
        if (null !== $layout) {
            $this->setLayout($layout);
        }
        $this->_formatter = new SpamTrawler_Log_Formatter_Simple();
    }

    /**
     * Create a new instance of SpamTrawler_Log_Writer_Mail
     *
     * @param  array|SpamTrawler_Config $config
     * @return SpamTrawler_Log_Writer_Mail
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $mail = self::_constructMailFromConfig($config);
        $writer = new self($mail);

        if (isset($config['layout']) || isset($config['layoutOptions'])) {
            $writer->setLayout($config);
        }
        if (isset($config['layoutFormatter'])) {
            $layoutFormatter = new $config['layoutFormatter'];
            $writer->setLayoutFormatter($layoutFormatter);
        }
        if (isset($config['subjectPrependText'])) {
            $writer->setSubjectPrependText($config['subjectPrependText']);
        }

        return $writer;
    }

    /**
     * Set the layout
     *
     * @param SpamTrawler_Layout|array $layout
     * @return SpamTrawler_Log_Writer_Mail
     * @throws SpamTrawler_Log_Exception
     */
    public function setLayout($layout)
    {
        if (is_array($layout)) {
            $layout = $this->_constructLayoutFromConfig($layout);
        }

        if (!$layout instanceof SpamTrawler_Layout) {
            require_once 'SpamTrawler/Log/Exception.php';
            throw new SpamTrawler_Log_Exception('Mail must be an instance of SpamTrawler_Layout or an array');
        }
        $this->_layout = $layout;

        return $this;
    }

    /**
     * Construct a SpamTrawler_Mail instance based on a configuration array
     *
     * @param array $config
     * @return SpamTrawler_Mail
     * @throws SpamTrawler_Log_Exception
     */
    protected static function _constructMailFromConfig(array $config)
    {
        $mailClass = 'SpamTrawler_Mail';
        if (isset($config['mail'])) {
            $mailClass = $config['mail'];
        }

        if (!array_key_exists('charset', $config)) {
            $config['charset'] = null;
        }
        $mail = new $mailClass($config['charset']);
        if (!$mail instanceof SpamTrawler_Mail) {
            throw new SpamTrawler_Log_Exception($mail . 'must extend SpamTrawler_Mail');
        }

        if (isset($config['subject'])) {
            $mail->setSubject($config['subject']);
        }

        $headerAddresses = array_intersect_key($config, self::$_methodMapHeaders);
        if (count($headerAddresses)) {
            foreach ($headerAddresses as $header => $address) {
                $method = self::$_methodMapHeaders[$header];
                if (is_array($address) && isset($address['name'])
                    && !is_numeric($address['name'])
                ) {
                    $params = array(
                        $address['email'],
                        $address['name']
                    );
                } else if (is_array($address) && isset($address['email'])) {
                    $params = array($address['email']);
                } else {
                    $params = array($address);
                }
                call_user_func_array(array($mail, $method), $params);
            }
        }

        return $mail;
    }

    /**
     * Construct a SpamTrawler_Layout instance based on a configuration array
     *
     * @param array $config
     * @return SpamTrawler_Layout
     * @throws SpamTrawler_Log_Exception
     */
    protected function _constructLayoutFromConfig(array $config)
    {
        $config = array_merge(array(
            'layout' => 'SpamTrawler_Layout',
            'layoutOptions' => null
        ), $config);

        $layoutClass = $config['layout'];
        $layout = new $layoutClass($config['layoutOptions']);
        if (!$layout instanceof SpamTrawler_Layout) {
            throw new SpamTrawler_Log_Exception($layout . 'must extend SpamTrawler_Layout');
        }

        return $layout;
    }

    /**
     * Places event line into array of lines to be used as message body.
     *
     * Handles the formatting of both plaintext entries, as well as those
     * rendered with SpamTrawler_Layout.
     *
     * @param  array $event Event data
     * @return void
     */
    protected function _write($event)
    {
        // Track the number of entries per priority level.
        if (!isset($this->_numEntriesPerPriority[$event['priorityName']])) {
            $this->_numEntriesPerPriority[$event['priorityName']] = 1;
        } else {
            $this->_numEntriesPerPriority[$event['priorityName']]++;
        }

        $formattedEvent = $this->_formatter->format($event);

        // All plaintext events are to use the standard formatter.
        $this->_eventsToMail[] = $formattedEvent;

        // If we have a SpamTrawler_Layout instance, use a specific formatter for the
        // layout if one exists.  Otherwise, just use the event with its
        // default format.
        if ($this->_layout) {
            if ($this->_layoutFormatter) {
                $this->_layoutEventsToMail[] =
                    $this->_layoutFormatter->format($event);
            } else {
                $this->_layoutEventsToMail[] = $formattedEvent;
            }
        }
    }

    /**
     * Gets instance of SpamTrawler_Log_Formatter_Instance used for formatting a
     * message using SpamTrawler_Layout, if applicable.
     *
     * @return SpamTrawler_Log_Formatter_Interface|null The formatter, or null.
     */
    public function getLayoutFormatter()
    {
        return $this->_layoutFormatter;
    }

    /**
     * Sets a specific formatter for use with SpamTrawler_Layout events.
     *
     * Allows use of a second formatter on lines that will be rendered with
     * SpamTrawler_Layout.  In the event that SpamTrawler_Layout is not being used, this
     * formatter cannot be set, so an exception will be thrown.
     *
     * @param  SpamTrawler_Log_Formatter_Interface $formatter
     * @return SpamTrawler_Log_Writer_Mail
     * @throws SpamTrawler_Log_Exception
     */
    public function setLayoutFormatter(SpamTrawler_Log_Formatter_Interface $formatter)
    {
        if (!$this->_layout) {
            throw new SpamTrawler_Log_Exception(
                'cannot set formatter for layout; ' .
                    'a SpamTrawler_Layout instance is not in use');
        }

        $this->_layoutFormatter = $formatter;
        return $this;
    }

    /**
     * Allows caller to have the mail subject dynamically set to contain the
     * entry counts per-priority level.
     *
     * Sets the text for use in the subject, with entry counts per-priority
     * level appended to the end.  Since a SpamTrawler_Mail subject can only be set
     * once, this method cannot be used if the SpamTrawler_Mail object already has a
     * subject set.
     *
     * @param  string $subject Subject prepend text.
     * @return SpamTrawler_Log_Writer_Mail
     * @throws SpamTrawler_Log_Exception
     */
    public function setSubjectPrependText($subject)
    {
        if ($this->_mail->getSubject()) {
            throw new SpamTrawler_Log_Exception(
                'subject already set on mail; ' .
                    'cannot set subject prepend text');
        }

        $this->_subjectPrependText = (string) $subject;
        return $this;
    }

    /**
     * Sends mail to recipient(s) if log entries are present.  Note that both
     * plaintext and HTML portions of email are handled here.
     *
     * @return void
     */
    public function shutdown()
    {
        // If there are events to mail, use them as message body.  Otherwise,
        // there is no mail to be sent.
        if (empty($this->_eventsToMail)) {
            return;
        }

        if ($this->_subjectPrependText !== null) {
            // Tack on the summary of entries per-priority to the subject
            // line and set it on the SpamTrawler_Mail object.
            $numEntries = $this->_getFormattedNumEntriesPerPriority();
            $this->_mail->setSubject(
                "{$this->_subjectPrependText} ({$numEntries})");
        }


        // Always provide events to mail as plaintext.
        $this->_mail->setBodyText(implode('', $this->_eventsToMail));

        // If a SpamTrawler_Layout instance is being used, set its "events"
        // value to the lines formatted for use with the layout.
        if ($this->_layout) {
            // Set the required "messages" value for the layout.  Here we
            // are assuming that the layout is for use with HTML.
            $this->_layout->events =
                implode('', $this->_layoutEventsToMail);

            // If an exception occurs during rendering, convert it to a notice
            // so we can avoid an exception thrown without a stack frame.
            try {
                $this->_mail->setBodyHtml($this->_layout->render());
            } catch (Exception $e) {
                trigger_error(
                    "exception occurred when rendering layout; " .
                        "unable to set html body for message; " .
                        "message = {$e->getMessage()}; " .
                        "code = {$e->getCode()}; " .
                        "exception class = " . get_class($e),
                    E_USER_NOTICE);
            }
        }

        // Finally, send the mail.  If an exception occurs, convert it into a
        // warning-level message so we can avoid an exception thrown without a
        // stack frame.
        try {
            $this->_mail->send();
        } catch (Exception $e) {
            trigger_error(
                "unable to send log entries via email; " .
                    "message = {$e->getMessage()}; " .
                    "code = {$e->getCode()}; " .
                        "exception class = " . get_class($e),
                E_USER_WARNING);
        }
    }

    /**
     * Gets a string of number of entries per-priority level that occurred, or
     * an emptry string if none occurred.
     *
     * @return string
     */
    protected function _getFormattedNumEntriesPerPriority()
    {
        $strings = array();

        foreach ($this->_numEntriesPerPriority as $priority => $numEntries) {
            $strings[] = "{$priority}={$numEntries}";
        }

        return implode(', ', $strings);
    }
}
