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
class Simpli_Hello_Basev1c2_Plugin_Module_Helper implements Simpli_Hello_Basev1c2_Plugin_Module_Helper_Interface {

    /**
     * Constructor
     *
     * Creates the object instance and sets dependencies
     *
     * @param none
     * @return void
     */
    protected $_module = null;
    protected $_addon = null;
    protected $_plugin = null;

    function __construct(Simpli_Hello_Basev1c2_Plugin_Module $module, Simpli_Hello_Basev1c2_Plugin_Addon $addon = null) {

        if (is_null($addon)) {
            $this->_module = $module;
            $this->_plugin = $module->plugin();
        } else {
            $this->_module = $module;
            $this->_plugin = $module->plugin();
            $this->_addon = $addon;
        }
    }

    /**
     * Get Module
     *
     * @param none
     * @return string
     */
    public function module() {
        return $this->_module;
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
     * Get Addon
     *
     * Returns the addon that created the helper object if set.
     *
     * @param none
     * @return string
     */
    public function addon() {
        return $this->_addon;
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