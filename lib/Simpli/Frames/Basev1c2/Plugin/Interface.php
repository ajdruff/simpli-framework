<?php

/**
 * Plugin Interface
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
  * @property string $DISABLED_MODULES An array of Module Names of the Addon that you don't want to have loaded
 */
Interface Simpli_Frames_Basev1c2_Plugin_Interface {






    /**
     * Debug
     *
     * Returns the Plugin's debug object
     *
     * @param none
     * @return void
     */
    public function debug() ;

    /**
     * Get Directory - Read Only
     *
     * @param none
     * @return stringReadOnly
     */
    public function getDirectory() ;

    /**
     * Get Module Directory - Read Only
     *
     * @param none
     * @return string
     */
    public function getModuleDirectory() ;



    /**
     * Get Module
     *
     * Get the loaded module object, given its name
     * @param string $module_name
     * @return object $module
     */
    public function getModule($module_name) ;

    /**
     * Get Modules
     *
     * Returns and array of loaded modules
     * @param none
     * @return array
     */
    public function getModules() ;



    /**
     * Get Url
     *
     * @param none
     * @return stringReadOnly $this->_addon_url
     */
    public function getUrl() ;

    /**
     * Set Name
     *
     * @param string $name
     * @return object $this
     */
    public function setName($name) ;

    /**
     * Get Name
     *
     * @param none
     * @return string
     */
    public function getName() ;

    /**
     * Get Slug - Read Only
     *
     * @param none
     * @return stringReadOnly
     */
    public function getSlug() ;

    /**
     * Get Version
     *
     *
     * @param none
     * @return string
     */
    public function getVersion() ;

    /** Get Slug Parts - Read Only
     *
     * Returns the slug as an array with each element a word in the slug
     * @param none
     * @return arrayReadOnly
     */
    public function getSlugParts() ;

    /**
     * Get Class Namespace - Read Only (Constant)
     *
     * Used in autoloader
     * @param none
     * @return stringReadOnly
     */
    public function getClassNamespace() ;

    /**
     * Get Class Namespace Parts (Read Only)
     *
     * Returns and array of the class namespace parts
     *
     * @param none
     * @return stringReadOnly
     */
    public function getClassNamespaceParts() ;

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks. Function is called during addon initialization
     * @param none
     * @return void
     */
    public function addHooks() ;

    /**
     * Init
     *
     * Performs basic housekeeping tasks and initializes all modules
     *
     * @param none
     * @return $this
     */
    public function init() ;



    /**
     * Is Module Loaded?
     *
     * @param string $module
     * @return boolean
     */
    public function isModuleLoaded($module) ;






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
    public function __get($property_name) ;


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

  //  @todo: can we make this public for plugin? public function setConfigDefaults() ;

    /**
     * Load Modules
     *
     * Load all modules
     * @author Andrew Druffner
     * @param array $modules
     * @param string $exclusion_regex Regex pattern in the form '/menu|admin/s' to exclude modules from loading
     * @return $this
     */
     //@todo: public function loadModules($module_directory) ;

    /**
     * Get Available Modules ( Read Only )
     *
     * Returns a 2 dimensional array with the module file paths of the files that reside in the Modules Directory
     * The dimensions of the array include 'type' and the module's name.
     * Type can be 'enabled' 'disabled' 'always_enabled'
     *
     * @param string $filter 'enabled', 'all', 'disabled', 'always_enabled' .
     * enabled are those that are permitted to be loaded
     * disabled are those that appear within the '_disabled_modules' array
     * 'always_active' are those modules not in the 'disabled' array that will load despite the plugins disabled setting
     *
     * @return arrayReadOnly
     */
    //@todo: enforce interface public function getAvailableModules($module_directory = null, $filter = 'enabled') ;



    /**
     * Load Module
     *
     * Takes the module name  and loads the associated file.
     * e.g.: 'Admin' loads from '/simpli/hello/Module/Admin.php'
     *
     * @author Andrew Druffner
     * @param string $module_name
     * @return $this
     */
    public function loadModule($module_name) ;


}

