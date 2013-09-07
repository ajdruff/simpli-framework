<?php

/**
 * Plugin Module
 *
 * Each Module in the project will extend this base Module class.
 * Modules can be treated as independent plugins. Think of them as sub-plugins.
 *
 * @author Mike Ems
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
class Simpli_Basev1c0_Plugin_Module implements Simpli_Basev1c0_Plugin_Module_Interface {

    /**
     * Plugin object that this module extends
     *
     * @var Simpli_Basev1c0_Plugin
     */
    protected $_plugin;

    /**
     * Module Slug
     *
     * @var Module Slug
     */
    protected $_slug;

    /**
     * Module Name
     *
     * @var Module Name
     */
    protected $_name;
    protected $_addon = null;

    /**
     * Get Addon
     *
     * Returns the addon of the module if set, otherwise it will return the addon
     * with the name that is passed as an argument
     *
     * @param none
     * @return void
     */
    public function getAddon($addon_name = null) {


        if (is_null($addon_name)) {
            return $this->_addon;
        }

        /* else, if not null, pass it onto the getPlugin->getAddon() method */
        return $this->getPlugin()->getAddon($addon_name);
    }

    /**
     * Set Addon Reference
     *
     * Set the Addon that the module is dependent on
     *
     * @param string $addon_name
     * @return object Addon
     */
    public function setAddon($addon_object) {

        $this->_addon = $addon_object;
    }

    /**
     * Debug
     *
     * Returns the Plugin's debug object
     *
     * @param none
     * @return void
     */
    public function debug() {

        return $this->getPlugin()->debug();

    }

    /*
     * Get Slug ( Read Only )
     *
     * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
     * @param none
     * @return string
     */

    public function getSlug() {

        if (!isset($this->_slug)) {
            /*
             * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
             * for for MyModule.php , moduleName=MyModule , moduleSlug='my_module'
             * http://stackoverflow.com/q/8611617
             */
            $regex = '/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/';

//            if (!isset($this->_name)) {
//
//                $this->setName();
//            }

            $slug = strtolower(preg_replace($regex, '_$1', $this->getName()));

            $this->_slug = $slug;
        }
        return $this->_slug;
    }

    /*
     * Get Name ( Read Only )
     *
     *  Returns the word after the last underscore in class name as the Module's name.
     * @param none
     * @return string
     */

    public function getName() {

        if (!isset($this->_name)) {
            $class = get_class($this);
            $array_class = explode('_', $class);


            $module_name = end($array_class);

            $this->_name = $module_name;
        }

        return $this->_name;
    }

    /**
     * Add Hooks
     *
     * Initializes Module
     *
     * @param none
     * @return void
     */
    public function addHooks() {

    }

    /**
     * Init
     *
     * Initializes the Module
     *
     * @param none
     * @return void
     */
    public function init() {
   $this->config();
        $this->addHooks();

    }

    /**
     *
     * Initializes the module when initialized in an Admin environment
     * @param none
     * @return void
     */
    public function initModuleAdmin() {
        throw new Exception('No initModuleAdmin method in ' . get_class($this));
    }

    /**
     *
     * nitializes the module when initialized in a non-admin environment
     * @param none
     * @return void
     */
    public function initModule() {
        throw new Exception('No initModule method in ' . get_class($this));
    }

    /**
     * Set Plugin
     *
     * @param Simpli_Basev1c0_Plugin $plugin
     * @return object $this
     * @uses Simpli_Basev1c0_Plugin
     */
    public function setPlugin(Simpli_Basev1c0_Plugin $plugin) {
        $this->_plugin = $plugin;
        return $this;
    }

    /**
     * Get Plugin
     *
     * @param none
     * @return Simpli_Basev1c0_Plugin
     */
    public function getPlugin() {
        if (!isset($this->_plugin)) {
            die('Module ' . __CLASS__ . ' missing Plugin dependency.');
        }

        return $this->_plugin;
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
    protected function setConfig($property_name, $config_value) {

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

        if (is_null($this->_property_defaults)) {
            $this->setConfigDefaults();
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

    /**
     * Set Config Defaults
     *
     * Sets all the default configs.
     * All configurations must have defaults, or errors will result since the
     * code will look for an option's value, and if not set, will throw an
     * undefined error.
     * Defaults should be set in the base class. There should be no need
     * to set defaults in children, since you can use the protected method setConfig()
     *
     * @param none
     * @return void
     */
    protected function setConfigDefaults() {



    }
}