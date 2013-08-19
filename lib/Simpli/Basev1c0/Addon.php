<?php

/**
 * Addon Base Class
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
class Simpli_Basev1c0_Addon {

    /**
     * Plugin directory path
     *
     * @var string
     */
    protected $_directory = null;

    /**
     * Module directory path
     *
     * @var string
     */
    protected $_module_directory = null;

    /**
     * Loaded Modules
     *
     * @var array
     */
    protected $_modules = null;

    /**
     * Addon URL
     *
     * @var string
     */
    protected $_addon_url = null;

    /**
     * Addon Slug
     *
     * @var string
     */
    protected $_slug = null;

    /**
     * Addon Name
     *
     * Addon's Friendly Name to appear in displayed text to the user.
     *
     * @var string
     */
    protected $_addon_name = null;

    /**
     * Addon File Path
     *
     * @var string
     */
    protected $_addon_file_path = null;

    /**
     * Plugin
     *
     * @var object
     */
    protected $_plugin = null;

    /**
     * Always Enabled Regex
     *
     * Contains the regex expression that identifies modules which remain
     * enabled after the user has disabled the plugin using the plugin settings
     *
     * @var string
     */
    protected $_module_always_enabled_regex = null;

    /**
     * Disabled Modules
     *
     * Contains module names that have been manually added that identify
     * modules that should not be loaded
     *
     * @var array
     */
    protected $_disabled_modules = null;

    /**
     * Available Modules
     *
     * Contains an array of module names that are in the Modules directory
     * and organizes them as to type under an appropriate associative index
     *
     * @var array
     */
    protected $_available_modules = null;
    private $_ro_properties = null;

    /**
     * Get Read Only Properties
     *
     * Use setProperty to define an array that can be used to retrieve read only properties
     * Useful hardcoding strings without needing to search and replace when they change
     * Usage: echo $this->propertyName
     * To Set a property : $this->setProperty('PROPERTY_NAME',property_value);
     * Returns read-only properties using a magic method __get
     * ref: http://stackoverflow.com/questions/2343790/how-to-implement-a-read-only-member-variable-in-php
     * @param none
     * @return void
     */
    public function __get($name) {




        if (is_null($this->_ro_properties)) {

            return null;
        }

        if (isset($this->_ro_properties[$name])) {
            return $this->_ro_properties[$name];
        } else {
            return null;
        }
    }

    /**
     * Set Read Only Property
     *
     * Use to configure readonly property in child class
     *
     * @param none
     * @return void
     */
    protected function setProperty($name, $value) {



        $this->_ro_properties[$name] = $value;
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

    /**
     * Debug Object
     *
     * The debug object
     *
     * @var object
     */

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

    /**
     * Get Directory - Read Only
     *
     * @param none
     * @return stringReadOnly
     */
    public function getDirectory() {



        /*
         * Use the class namespace to derive a directory path
         */
        if (is_null($this->_directory)) {

            $class = get_class($this);

            /*
             * replace underscores of the class with slashes
             *
             */
            $path = str_replace('_', '/', $class);

            /*
             * Use dirname to find the parent directory since the last
             * part of the class name represents the class file name
             * and prefix it with the plugin's directory to get the absolute path
             */
            $path = dirname($this->getPlugin()->getDirectory() . '/' . $this->getPlugin()->DIR_NAME_LIBS . '/' . $path);

            /*
             * Now normalize slashes
             */
            $this->_directory = $this->getPlugin()->getTools()->normalizePath($path);
        }

        return ($this->_directory);
    }

    /**
     * Get Module Directory - Read Only
     *
     * @param none
     * @return string
     */
    public function getModuleDirectory() {





        if (is_null($this->_module_directory)) {
            /*
             * The module directory is always a subdirectory of the directory that the
             * Addon class file is in.
             */

            $path = $this->getDirectory() . '/' . $this->getPlugin()->DIR_NAME_MODULES;


            $this->_module_directory = $this->getPlugin()->getTools()->normalizePath($path);
        }




        return $this->_module_directory;
    }

    /**
     * Get Disabled Modules
     *
     * Returns and array of module names that were manually disabledwith the setDisabledModule method
     *
     * @param none
     * @return void
     */
    public function getDisabledModules() {



        if (is_null($this->_disabled_modules)) {
            $this->_disabled_modules = array();
        }

        return $this->_disabled_modules;
    }

    /**
     * Set Disabled Module
     *
     * Adds the name of a module to the $_disabled_modules array
     *
     * @param none
     * @return void
     */
    public function setDisabledModule($module_name) {



        $this->_disabled_modules[] = $module_name;
    }

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
    public function getAvailableModules($filter = 'enabled') {



        if (is_null($this->_available_modules)) {

            $available_modules = array();

            $tools = $this->getPlugin()->getTools();

            /*
             * Find all the Module files in the module directory
             */

            $module_files = $tools->getGlobFiles($this->getModuleDirectory(), '*.php', false);


            if (!is_array($module_files)) {
                return;
            }

            /*
             * Iterate through each of the files checking to see which filter they belong to
             */
            foreach ($module_files as $module_file_path) {
                $module_name = basename($module_file_path, '.php');

                /*
                 * If the plugin settings have a 'disabled' setting,
                 * check if the module should still be enabled per the regex
                 * this is intended to allow admin menus to persist so
                 * settings can be accessed even though the rest of the plugin
                 * is disabled
                 */
                If (in_array($module_name, $this->getDisabledModules())) {

                    $available_modules['disabled'][$module_name] = $module_file_path;
                } else {

                    $available_modules['enabled'][$module_name] = $module_file_path;
                }

                $available_modules['all'][$module_name] = $module_file_path;
            }
            $this->_available_modules = $available_modules;
        }

        return $this->_available_modules[$filter];
    }

    /**
     * Get Module
     *
     * Get the loaded module object, given its name
     * @param string $module_name
     * @return object $module
     */
    public function getModule($module_name) {

        $this->debug()->t();
        $this->debug()->logVar('Modules Loaded: ', $this->_modules);

        if (!isset($this->_modules[$module_name])) {
            $this->debug()->log('Module not found,returning null');
            return null;
        }
        $this->debug()->log('Module found, returning module ' . $module_name);
        return $this->_modules[$module_name];
    }

    /**
     * Get Modules
     *
     * Returns and array of loaded modules
     * @param none
     * @return array
     */
    public function getModules() {


        return $this->_modules;
    }

    /**
     * Set Loaded Module
     *
     * @param object $module
     * @return object $this
     */
    public function setModule($module_name, $module) {


        $this->_modules[$module_name] = $module;
        return $this;
    }

    /**
     * Get Url
     *
     * @param none
     * @return stringReadOnly $this->_addon_url
     */
    public function getUrl() {





        if (is_null($this->_addon_url)) {
            $this->_addon_url = plugins_url('', $this->getDirectory());
        }


        return $this->_addon_url;
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return object $this
     */
    public function setName($name) {


        $this->_addon_name = $name;
        return $this;
    }

    /**
     * Get Name
     *
     * @param none
     * @return string
     */
    public function getName() {


        return $this->_addon_name;
    }

    /**
     * Get Slug - Read Only
     *
     * @param none
     * @return stringReadOnly
     */
    public function getSlug() {



        if (is_null($this->_slug)) {
            /*
             * The slug is just the name of the addon class with the word 'addon' removed.
             */
            $class = get_class($this); //e.g. Simpli_Addons_Simpli_Forms_Addon

            $slug = substr_replace($class, '', -1 * strlen('_Addon'), strlen('_Addon')); //Simpli_Addons_Simpli_Forms_Addon
            $slug = strtolower($slug); //simpli_addons_simpli_forms
            $this->_slug = $slug;
        }



        return $this->_slug;
    }

    /**
     * Get Version
     *
     *
     * @param none
     * @return string
     */
    public function getVersion() {




        $headers = array('Version' => 'Version');

        $addon_file_data = get_file_data($this->getFilePath(), $headers, 'addon');



        return $addon_file_data['Version'];
    }

    /** Get Slug Parts - Read Only
     *
     * Returns the slug as an array with each element a word in the slug
     * @param none
     * @return arrayReadOnly
     */
    public function getSlugParts() {





        $slug_parts = explode('_', $this->getSlug());


        return $slug_parts;
    }

    /**
     * Get Class Namespace - Read Only (Constant)
     *
     * Used in autoloader
     * @param none
     * @return stringReadOnly
     */
    public function getClassNamespace() {




        return $this->getPlugin()->ADDON_NAMESPACE;
    }

    /**
     * Get Class Namespace Parts (Read Only)
     *
     * Returns and array of the class namespace parts
     *
     * @param none
     * @return stringReadOnly
     */
    public function getClassNamespaceParts() {




        return explode('_', $this->getClassNamespace());
    }

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks. Function is called during addon initialization
     * @param none
     * @return void
     */
    public function addHooks() {

    }

    /**
     * Init
     *
     * Performs basic housekeeping tasks and initializes all modules
     *
     * @param none
     * @return $this
     */
    public function init() {






        $this->debug()->log('Addon Directory: ' . $this->getDirectory());
        $this->debug()->log('Addon Module Directory: ' . $this->getModuleDirectory());


        $this->debug()->log('Addon URL: ' . $this->getUrl());


        $this->addHooks();

        $this->config();

        /**
         * Load Modules
         */
        $this->loadModules();


        /*
         *
         * Initialize Addon modules
         */



        $modules = $this->getModules();

        if (is_array($modules)) {


            foreach ($modules as $module) {

                $module->init();
                $this->debug()->log('Initialized Addon Module ' . $this->getSlug() . '/' . $module->getName());
            }
        }
        if (isset($this->_slug)) {
            do_action($this->_slug . '_init');
        }

        $this->debug()->log('Completed Initialization for Addon ' . $this->getName());


        return $this;
    }

    /**
     * Is Module Loaded?
     *
     * @param string $module
     * @return boolean
     */
    public function isModuleLoaded($module) {




        if (is_object($this->getModule($module))) {
            return true;
        } else {
            return false;
        }
    }

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
    public function loadModule($module_name) {





        $available_modules = $this->getAvailableModules('enabled');







        /*
         * check to see if the module_name is enabled; if not, return.
         */
        if (!is_array($available_modules) || !isset($available_modules[$module_name])) {
            $this->debug()->log('unable to load Module ' . $module_name . ' , since it is an inactive module');
            return;
        }





        /*
         * Include the class file
         */




        $module_file_path = $available_modules[$module_name];

        require_once($module_file_path); # simpli-framework/lib/simpli/hello/Module/Admin.php

        /*
         * Derive the class from the module file path
         */


        $relative_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getAddonsDirectory(), $module_file_path);
        $module_namespace = str_replace('/', '_', dirname($relative_path));


        $class = $this->getClassNamespace() . '_' . $module_namespace . '_' . $module_name; //
//  die('<br>' . __LINE__ . 'exiting to check class, $class = ' . $class);

        /*
         * Create the module object and attach it to $_modules
         */
        if (!isset($this->_modules[$class]) || !is_object($this->_modules[$class]) || get_class($this->_modules[$class]) != $class) {
            try {

                $object = new $class;
                $this->setModule($module_name, $object);
                $this->getModule($module_name)->setPlugin($this->getPlugin()); //set the plugin dependency
                $this->getModule($module_name)->setAddon($this); //set the addon dependency
                $this->debug()->log('Loaded Addon Module ' . $this->getSlug() . '/' . $module_name);
            } catch (Exception $e) {
                die('Unable to load Module: \'' . $module_name . '\'. ' . $e->getMessage());
            }
        }

        return $this;
    }

    /**
     * Load Modules
     *
     * Load all modules
     * @author Andrew Druffner
     * @param array $modules
     * @param string $exclusion_regex Regex pattern in the form '/menu|admin/s' to exclude modules from loading
     * @return $this
     */
    public function loadModules() {



        $enabled_modules = $this->getAvailableModules('enabled');


        if (!is_array($enabled_modules)) {
            return array();
        }



        foreach ($enabled_modules as $module_name => $module_path) {

            $this->loadModule($module_name);
        }
    }

}

