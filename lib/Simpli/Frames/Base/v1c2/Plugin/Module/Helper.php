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
class Simpli_Frames_Base_v1c2_Plugin_Module_Helper implements Simpli_Frames_Base_v1c2_Plugin_Module_Helper_Interface {

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

    function __construct(Simpli_Frames_Base_v1c2_Plugin_Module $module, Simpli_Frames_Base_v1c2_Plugin_Addon $addon = null) {

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

    
    protected $_config_properties = null;

    /**
     * Get Configuration (Magic Method)
     *
     * Return 'read only' properties using the $this->Property format.
     * You *can* add or edit these values by using the protected method $this->setConfig().
     * Returns read-only properties using a magic method __get
     * ref: http://stackoverflow.com/questions/2343790/how-to-implement-a-read-only-member-variable-in-php
     * @param none
     * @return void
     */
    public function __get($property_name) {


        $properties = $this->_getProperties();

        if (isset($properties[$property_name])) {
            $config_value = $properties[$property_name];
        } else {

            $config_value = $this->_getConfigDefault($property_name);
        }

        return $config_value;
    }

    /**
     * Set Config
     *
     * @param string $property_name
     * @param string $config_value
     *
     * @return object $this
     */
    public function setConfig($property_name, $config_value) {

        $this->_config_properties[$property_name] = $config_value;

        return $this->_config_properties;
    }

    /**
     * Get Properties
     *
     * Returns the properties array
     * @param none
     * @return array
     */
    private function _getProperties() {

        if (is_null($this->_config_properties)) {
            $this->_config_properties = array();
        }
        return $this->_config_properties;
    }

    /**
     * Get Config Default
     *
     * Provides a default config value if it wasnt set by the user
     *
     * @param mixed $property_name
     * @return mixed The default value of the config
     */
    private function _getConfigDefault($property_name) {
        $this->debug()->t();
        if (is_null($this->_property_defaults)) {
            //$this->setConfigDefaults();
            $this->_property_defaults = array();
        }
        if (!isset($this->_property_defaults[$property_name])) {


            throw new Exception('No such configuration property for  \'' . $property_name . '\' in  ' . get_class($this));
        }
        return $this->_property_defaults[$property_name];
    }

    /**
     * Set Config Default
     *
     * Sets a default config value
     *
     * @param string $property_name The name of the config
     * @param string $config_value The value of the the config
     * @return void
     */
    protected function setConfigDefault($property_name, $config_value) {
        $this->_property_defaults[$property_name] = $config_value;
    }

    protected $_property_defaults = null;

}

?>