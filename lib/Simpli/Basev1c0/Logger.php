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

            add_action('shutdown', array(&$this, 'print_log'));
        }

        $this->_enabled = ($enabled === true) ? true : false;
        return $this->_enabled;
    }

    /**
     * Check if Debug is Enabled
     *
     * @param none
     * @return array
     */
    public function getLoggingState() {
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
     * Adds a string to an array of log entries
     *
     * @param string $string
     * @return $this
     */
    public function log($string) {
//        echo '<br>' . $this->getPlugin()->getSlug() . ':  logging enabled = ';
//        echo ($this->getLoggingState() === true) ? "true" : "false";
        /*
         * if logging isnt turned on, dont log it.
         */
        if (!$this->getLoggingState()) {
            return;
        }

        $time_now = date("Y-m-d H:i:s");
        $this->_log[] = $time_now . ' ' . $string;





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


        $code = "<script id=\"my_log\" type=\"text/javascript\">\n\tif ( typeof console === 'object' ) {\n";
        $log = $this->getLog();


        foreach ($log as $log_entry) {
            if (is_array($log_entry)) {
                $log_entry = json_encode($log_entry);
            } else {
                $log_entry = "'" . addslashes($log_entry) . "'";
            }
            $code .= "\t\tconsole.log(" . $log_entry . ");\n";
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


        if ($filename == '') {
            $filename = 'error.log.txt';
        }
        return file_put_contents($filename, implode("\r\n", $this->getLog())); //, FILE_APPEND);
    }

    /**
     * Print Log
     *
     * Dumps all Logger entries to the browser's javascript console and to a log file
     *
     */
    public function print_log() {


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
        echo $this->consoleLog();

        $this->fileLog($this->getPlugin()->getDirectory() . '/error.log.txt');
    }

}