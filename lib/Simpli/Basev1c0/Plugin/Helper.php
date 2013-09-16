<?php

/**
 * Plugin Helper Base class
 *
 * Helper classes that are dependent on the plugin to create them.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */

class Simpli_Basev1c0_Plugin_Helper implements Simpli_Basev1c0_Plugin_Helper_Interface {


/**
     * Constructor
     *
     * Creates the object instance and sets dependencies
     *
     * @param none
     * @return void
     */

    protected $_plugin = null;

    function __construct(Simpli_Basev1c0_Plugin $plugin) {


            $this->_plugin = $plugin;

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