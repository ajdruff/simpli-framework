<?php

/**
 * Logger Class
 *
 * @author Mike Ems
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 *
 */
class Simpli_Basev1c0_Logger implements Simpli_Basev1c0_Logger_Interface {

    /**
     * Instance
     *
     * @var Instance
     */
    private static $_instance;

    /**
     * Log Entries
     *
     * @var array
     */
    protected $_log = array();

    public function __construct() {

    }

    /**
     * Enabled
     *
     * @var Enabled
     */
    private $_enabled = false;

    /**
     * Turn Logging On or Off
     *
     * @param boolean $enabled
     * @return array
     */
    public function setLoggingOn($enabled = true) {
        if ($enabled === true) {

            add_action('shutdown', array($this, 'print_log'));
        }

        $this->_enabled = ($enabled === true) ? true : false;
        return $this->_enabled;
    }

    /**
     * Check if Debug is Enabled
     *
     * Will return value of $_debug['consolelog'] if loggingEnabled wasnt turned on explicitly
     * @param none
     * @return array
     */
    public function getLoggingState() {

        if (is_null($this->_enabled)) {

            $debug = $this->getPlugin()->getDebug();

            $this->_enabled = ($debug['consolelog'] || $debug['filelog']);
        }

        return $this->_enabled;
    }

    /**
     * Plugin
     *
     * @var Plugin
     */
    private $_plugin;

    /**
     * Get Plugin Reference
     *
     * @param none
     * @return object plugin
     */
    public function getPlugin() {
        return $this->_plugin;
    }

    /**
     * Set Plugin
     *
     * @param none
     * @return object
     */
    public function setPlugin($plugin) {
        $this->_plugin = $plugin;
        return $this->_plugin;
    }

    /**
     * Get singleton instance
     *
     * @param none
     * @return Logger
     */
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * Get Log
     *
     * @param none
     * @return array
     */
    public function getLog() {
        return $this->_log;
    }

    /**
     * Adds an Error to the Log
     *
     * Wrapper to log
     * @param string $string
     * @return $this
     */
    public function logError($string) {
        $this->log($string, $type = 'error');
    }

    /**
     * Adds an Info entry to the log
     *
     * @param string $string
     * @return $this
     */
    public function log($string, $type = 'info') {
//        echo '<br>' . $this->getPlugin()->getSlug() . ':  logging enabled = ';
//        echo ($this->getLoggingState() === true) ? "true" : "false";
        /*
         * if logging isnt turned on, dont log it.
         */
        if (!$this->getLoggingState()) {
            return;
        }

        $time_now = date("Y-m-d H:i:s");
        $prefix = ' ' . $this->getPlugin()->getSlug() . ': ';
        $this->_log[] = array(
            'text' => $time_now . $prefix . $string
            , 'type' => $type);






        return $this;
    }

    /**
     * Console Log
     *
     * Output contents of the log to the browser's console.
     *
     * @param none
     * @return string $code
     */
    public function consoleLog() {

        // echo '<br>adding logs within consolelog';


        $code = "<script  type=\"text/javascript\">\n\tif ( typeof console === 'object' ) {\n";
        $log = $this->getLog();


        foreach ($log as $log_entry) {
            $log_text = $log_entry['text'];
            if (is_array($log_text)) {
                $log_text = json_encode($log_text);
            } else {
                $log_text = "'" . addslashes($log_text) . "'";
            }

            if ($log_entry['type'] === 'info') {

                $code .= "\t\tconsole.log(" . $log_text . ");\n";
            } elseif ($log_entry['type'] === 'error') {

                $code .= "\t\tconsole.error(" . $log_text . ");\n";
            }
        }
        $code .= "\t}\n</script>\n";
        return $code;
    }

    /**
     * File Log
     *
     * Writes the contens of the log to a file
     *
     * @param sring $filename
     * @return int | false
     */
    public function fileLog($filename = '') {

        /*
         * dont log to file if debug options turned it off
         */
        $debug = $this->getPlugin()->getDebug();
        if (!$debug['filelog']) {
            return;
        }

        $log_entries = $this->getLog();
        $contents = '';
        foreach ($log_entries as $entry) {
            $contents[] = $entry['text'];
        }

        //  die('exiting logger');
        /*
         * Do not write to file if ajax request
         * AJAX check
         */
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            /* special ajax here */
            return;
        }
        if ($filename == '') {
            $filename = 'error.log.txt';
        }

        return file_put_contents($filename, implode("\r\n", $contents)); //, FILE_APPEND);
    }

    /**
     * Print Log
     *
     * Dumps all Logger entries to the browser's javascript console and to a log file
     *
     */
    public function print_log() {

        /*
         * if logging isnt turned on, exit function.
         */
        if (!$this->getLoggingState()) {
            return;
        }


        /*
         * Do not write to console if ajax request
         * AJAX check
         */
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            /* special ajax here */
            return;
        }

        /*
         *
         * Echo the output of the consoleLog() script which creates the javascript that
         * includes the console.log calls for each of the log statements
         *
         */


        $debug = $this->getPlugin()->getDebug();

        if ($debug['consolelog']) {
            echo $this->consoleLog();
        }

        if ($debug['filelog']) {
            $this->fileLog($this->getPlugin()->getDirectory() . '/error.log.txt');
        }
    }

}

