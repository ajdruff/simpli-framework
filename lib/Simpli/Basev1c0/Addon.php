<?php

/**
 * Addon Base Class
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
class Simpli_Basev1c0_Addon {

    const ADDON_NAMESPACE = 'Simpli_Addons';
    const ADDON_BASE_FILE_NAME = 'Addon';
    const DIR_NAME_MODULES = 'Module';

    /**
     * Base directory
     *
     * @var string
     */
    protected $_directory;

    /**
     * Module directory
     *
     * @var string
     */
    protected $_module_directory;

    /**
     * Loaded Modules
     *
     * @var array
     */
    protected $_modules = array();

    /**
     * Addon URL
     *
     * @var string
     */
    protected $_addon_url;

    /**
     * Addon Name
     *
     * Addon's Friendly Name to appear in displayed text to the user.
     *
     * @var string
     */
    protected $_addon_name;

    /**
     * Addon File Path
     *
     * @var string
     */
    protected $_addon_file_path;

    /**
     * Plugin
     *
     * @var string
     */
    protected $_plugin;

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

            $class = strtolower(get_class());
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
            $path = dirname($this->getPlugin()->getDirectory() . '/' . $path);

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

            $path = $this->getDirectory() . '/' . self::DIR_NAME_MODULES;
            $this->_directory = $this->getPlugin()->getTools()->normalizePath($path);
        }




        return $this->_module_directory;
    }

    /**
     * Get Available Modules
     *
     * @param none
     * @return arrayReadOnly $modules
     */
    public function getAvailableModules() {
        $modules = array();



        $this->getPlugin()->getLogger()->log($this->getSlug() . ': Module directory = ' . $this->getModuleDirectory());

        if (is_dir($this->getModuleDirectory()) && $module_directory = opendir($this->getModuleDirectory())) {
            while (false !== ($entry = readdir($module_directory))) {
                if (strpos($entry, '.') !== 0 && strpos($entry, '.php') !== false) { //if the entry doesnt start with a . and if it ends in php
                    $module = str_replace('.php', '', $entry); //use the name of the file without the extension as the module name
                    if ($module != 'Interface') { // dont use it if its name is Interface
                        $modules[] = $module; // add it to the modules array
                        if (is_dir($this->getModuleDirectory() . $module) && $sub_module_directory = opendir($this->getModuleDirectory() . $module)) {
                            while (false !== ($entry = readdir($sub_module_directory))) {
                                if ($entry != '.' && $entry != '..') {
                                    $sub_module = str_replace('.php', '', $entry);
                                    $modules[] = $module . '\\' . $sub_module;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $modules;
    }

    /**
     * Get Module
     *
     * @param string $module
     * @return object
     */
    public function getModule($module) {

        if (isset($module)) {
            $module = 'Module\\' . $module;
            if (isset($this->_modules[$module])) {
                return $this->_modules[$module];
            }
        } else {
            $this->getPlugin()->getLogger()->logError('getModule() Failed, Module \'' . $module . '\' was not found');
            return (false);
        }
    }

    /**
     * Get Modules
     *
     * Returns an array of all loaded modules
     *
     * @param none
     * @return array $modules
     */
    public function getModules() {
        $modules = array();
        if (isset($this->_modules)) {
            $modules = $this->_modules;
        }
        return $modules;
    }

    /**
     * Set Module
     *
     * @param string $module
     * @param object $object
     * @return $this
     */
    public function setModule($module, $object) {
        $this->_modules[$module] = $object;
        return $this;
    }

    /**
     * Get Url
     *
     * @param none
     * @return stringReadOnly $this->_addon_url
     */
    public function getUrl() {


        if (is_null($this->_addon_ur)) {
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
            $class = get_class();
            $slug = str_replace($this->ADDON_NAMESPACE, '', $class);
            $this->_slug = str_replace($this->ADDON_BASE_FILE_NAME, '', $class);
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


        return $this->ADDON_NAMESPACE;
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
     * Init
     *
     * Performs basic housekeeping tasks and initializes all modules
     *
     * @param none
     * @return $this
     */
    public function init() {

        /*
         * Enqueue the framework's namespace script so we can namespace our javascript
         * Add our local variables
         *
         */

        if (is_admin()) {
            add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        } else {
            add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        }


        $this->getPlugin()->getLogger()->log($this->getSlug() . ': Addon Directory: ' . $this->getDirectory());
        $this->getPlugin()->getLogger()->log($this->getSlug() . ': Addon Module Directory: ' . $this->getModuleDirectory());


        $this->getPlugin()->getLogger()->log($this->getSlug() . ': Addon URL: ' . $this->getUrl());


        /**
         * Load Modules
         */
        $this->loadModules(array(), '/menu|admin/s');


        /*
         *
         * Tell debugger that the addon and class library have been loaded
         */
        $this->getPlugin()->getLogger()->log($this->getSlug() . ': Initializing Addon ');
        $this->getPlugin()->getLogger()->log($this->getSlug() . ': Loaded Base Class Library ' . ' from ' . dirname(__FILE__));

        $modules = $this->getModules();

        foreach ($modules as $module) {

            $module->init();
        }
        if (isset($this->_slug)) {
            do_action($this->_slug . '_init');
        }




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
     * @author Andrew Druffner <andrew@nomstock.com>
     * @param string $module
     * @return $this
     */
    public function loadModule($module) {


        $this->getPlugin()->getLogger()->log($this->getSlug() . ': Loading Module ' . $module);

        $module_full = 'Module\\' . $module;  # Admin
        $filename = str_replace('\\', '/', $module);
        $filename = $filename . '.php'; # Admin.php


        /*
         *
         * Derive the namespace ('Simpli_Hello') from the class name
         *
         */
        $array_class_name = explode('_', get_class($this));  # Changes class Simpli_Hello_Addon to [0]='Simpli' [1]='Hello'  [2]='Addon'
        array_pop($array_class_name);  # $array_class_name =  [0]='Simpli' [1]='Hello'
        $namespace = implode('_', $array_class_name);  # $namespace =  Simpli_Hello



        require_once($this->getModuleDirectory() . $filename); # simpli-framework/lib/simpli/hello/Module/Admin.php


        $class = $namespace . '_Module_' . $module;
        if (!isset($this->_modules[$class]) || !is_object($this->_modules[$class]) || get_class($this->_modules[$class]) != $class) {
            try {
                $object = new $class;
                $this->setModule($module_full, $object);
                $this->getModule($module)->setAddon($this);
            } catch (Exception $e) {
                die('Unable to load module: \'' . $module . '\'. ' . $e->getMessage());
            }
        }

        return $this;
    }

    /**
     * Load Modules
     *
     * Load specified modules. If no modules are specified, all modules are loaded.
     * @author Andrew Druffner
     * @param array $modules
     * @param string $exclusion_regex Regex pattern in the form '/menu|admin/s' to exclude modules from loading
     * @return $this
     */
    public function loadModules($modules = array(), $exclusion_regex = '') {



        if (sizeof($modules) == 0) {
            $modules = $this->getAvailableModules();
        }

        foreach ($modules as $module) {

            //if addon was disabled in settings, load only those modules
            //with menu or admin in their name. that way we can still
            // configure the addon but the rest of the addons functionality
            // is disabled.
            if ($this->getSetting('addon_enabled') == 'disabled') {
                $haystack = strtolower($module);

                if (preg_match_all($exclusion_regex, $haystack, $matches) < 1) {

                    // Skip Module
                    continue;
                }
            }

            $this->loadModule($module);
        }

//        echo 'addon_enabled setting = ' ;
//print_r($this->getSettings());
        return $this;
    }

}

