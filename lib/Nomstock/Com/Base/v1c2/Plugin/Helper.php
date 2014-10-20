<?php

/**
 * Plugin Helper Basev1c2 class
 *
 * Helper classes that are dependent on the plugin to create them.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 */
class Simpli_Frames_Base_v1c2_Plugin_Helper implements Simpli_Frames_Base_v1c2_Plugin_Helper_Interface {

    /**
     * Constructor
     *
     * Creates the object instance and sets dependencies
     *
     * @param none
     * @return void
     */
    protected $_plugin = null;

    function __construct(Simpli_Frames_Base_v1c2_Plugin $plugin) {
//        static $count = 0;
//        $count++;
//        if ($count > 2) {
//
//
//            $backtrace = debug_backtrace();
//            echo '<pre>', print_r($backtrace[0], true), '</pre>';
//            die('exiting ' . __LINE__ . __FILE__);
//        }
        $this->_plugin = $plugin;

        $this->config();
        $this->addHooks();
    }

    /**
     * Config
     *
     * @param none
     * @return void
     */
    public function config() {

    }

    /**
     * Add Hooks
     *
     * Add any WordPress action and filter hooks here
     *
     * @param none
     * @return void
     */
    public function addHooks() {

    }

    /**
     * Get Plugin
     *
     * @param none
     * @return string
     */
    public function plugin() {
        return $this->_plugin;
    }

    /**
     * Debug
     *
     * Returns the debug() method from the calling plugin object
     *
     * @param none
     * @return void
     */
    public function debug() {
        return $this->_plugin->debug();
    }

}

?>