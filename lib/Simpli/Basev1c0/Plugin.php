<?php

/**
 * Base class for a WordPress plugin.
 *

 * @author Andrew Druffner
 * @author Mike Ems
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
class Simpli_Basev1c0_Plugin {

    private $_ro_properties = null;

    /**
     * Get Read Only Properties
     *
     * Define any configuration data here that needs to be accessible to all modules and derived classes and objects.
     * usage from Plugin module $this->NAME_OF_PROPERTY without quotes.
     * Returns read-only properties using a magic method __get
     * ref: http://stackoverflow.com/questions/2343790/how-to-implement-a-read-only-member-variable-in-php
     * @param none
     * @return void
     */
    public function __get($name) {


        if (is_null($this->_ro_properties)) {
            $this->_ro_properties = array
                (
                'ADDON_NAMESPACE' => 'Simpli_Addons'
                , 'FILE_NAME_ADDON' => 'Addon'
                , 'DIR_NAME_MODULES' => 'Module'
                , 'DIR_NAME_LIBS' => 'lib'
                , 'FILE_NAME_PLUGIN' => 'plugin.php'
            );
        }

        if (isset($this->_ro_properties[$name])) {
            return $this->_ro_properties[$name];
        } else {
            return null;
        }
    }

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
     * Logger
     *
     * @var Simpli_Basev1c0_Logger_Interface
     */
    protected $_logger = null;

    /**
     * Plugin URL
     *
     * @var string
     */
    protected $_plugin_url = null;

    /**
     * Plugin Setting Defaults
     *
     * @var array
     */
    protected $_setting_defaults = null;

    /**
     * Plugin Settings
     *
     * @var array
     */
    protected $_settings = null;

    /**
     * Plugin Slug
     *
     * Used as a unique identifier for the plugin.
     *
     * @var string
     */
    protected $_slug = null;

    /**
     * Plugin Name
     *
     * Plugin's Friendly Name to appear in displayed text to the user.
     *
     * @var string
     */
    protected $_plugin_name = null;

    /**
     * Text Domain
     *
     * @var string
     */
    protected $_text_domain = null;

    /**
     * Plugin File Path
     *
     * @var string
     */
    protected $_plugin_file_path = null;

    /**
     * Activate Actions
     *
     * @var string
     */
    protected $_activate_actions = null;

    /**
     * Debug Options
     *
     * @var string
     */
    protected $_debug = null;

    /**
     * Utility Object
     *
     * @var object
     */
    protected $_tools = null;

    /**
     * Inline Script Queue
     *
     * Holds the scripts to be added inline
     * @var array
     */
    protected $_inline_script_queue = null;

    /**
     * Local Variables
     *
     * holds variables to be added to javascript in format required by wp_localize
     *
     * @var array
     */
    protected $_local_vars = null;

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

    /**
     * Addons
     *
     * Contains an array of the loaded Addon objects
     *
     * @var array
     */
    protected $_addons = null;

    /**
     * Disabled Addons
     *
     * Contains an array of the disabled addons
     *
     * @var array
     */
    protected $_disabled_addons = null;

    /**
     * Version
     *
     * Contains the Version of the Plugin
     *
     * @var string
     */
    protected $_version = null;

    /**
     * Framework Version
     *
     * The Framework Version
     *
     * @var string
     */
    protected $_framework_version = null;

    /**
     * Slug Parts
     *
     * The individual words of the slug which are separated by an underscore
     *
     * @var array
     */
    protected $_slug_parts = null;

    /**
     * Class Namespace
     *
     * The first 2 words of the class that serves as the plugin namespace
     *
     * @var string
     */
    protected $_class_namespace = null;

    /**
     * Addons Directory
     *
     * The directory where the addons are located
     *
     * @var string
     */
    protected $_addons_directory = null;

    /**
     * Constructor
     *
     * @param void
     * @return object $this
     */
    public function __construct() {

        /*
         *
         * Set Logger dependency
         *
         */

        $this->setLogger(Simpli_Basev1c0_Logger::getInstance());


        return $this;
    }

    /**
     * Get Directory - Read Only
     *
     * @param none
     * @return stringReadOnly
     */
    public function getDirectory() {

        if (is_null($this->_directory)) {
            $directory = dirname($this->getFilePath());
            $this->_directory = $this->getTools()->normalizePath($directory);
        }

        return $this->_directory;
    }

    /**
     * Get Module Directory - Read Only
     *
     * @param none
     * @return stringReadOnly
     */
    public function getModuleDirectory() {

        if (is_null($this->_module_directory)) {

            /* Since the Module directory will always be 'Module' under the plugin's lib directory,
             * then we can use the namespace of the plugin class to derive the subdirectory
             * A class name of 'Simpli_Hello' will result in a concatenation of:
             * $this->getDirectory() ,i.e, the plugin dir, e.g.: /home/username/public_html/wp-content/plugins/simpli-framework
             * the lib subdirectory, always /lib
             * the conversion of the class namespace : e.g.: /simpli/hello
             * a directory named 'Module'
             *
             * e.g: /home/username/public_html/wp-content/plugins/simpli-framework/lib/simpli/hello/Module
             */
            $class_namespace_parts = $this->getClassNamespaceParts();

            $module_directory = $this->getDirectory() . '/' . $this->DIR_NAME_LIBS . '/' . $class_namespace_parts[0] . '/' . $class_namespace_parts[1] . '/' . $this->DIR_NAME_MODULES;
            $this->_module_directory = $this->getTools()->normalizePath($module_directory);
        }


        return $this->_module_directory;
    }

    /**
     * Get Always Enabled Regex
     *
     * Returns the regex pattern that was set by setAlwaysEnabledRegex
     *
     * @param none
     * @return void
     */
    public function getModuleAlwaysEnabledRegex() {

        return $this->_module_always_enabled_regex;
    }

    /**
     * Set Always Enabled Regex
     *
     * Sets the regex that determines which modules remain enabled even after the user has diabled the plugin
     *
     * @param string $regex Regex Pattern of modules that should remain enabled
     * @return void
     */
    public function setAlwaysEnabledRegex($regex) {

        $this->_module_always_enabled_regex = $regex;
    }

    /**
     * Get Disabled Addons
     *
     * Returns and array of addon names that were manually disabled with the setDisabledAddon method
     *
     * @param none
     * @return void
     */
    public function getDisabledAddons() {
        if (is_null($this->_disabled_addons)) {
            $this->_disabled_addons = array();
        }
        return $this->_disabled_addons;
    }

    /**
     * Set Disabled Addon
     *
     * Adds the name of an Adon to the $_disabled_addons array
     *
     * @param none
     * @return void
     */
    public function setDisabledAddon($addon_name) {

        $this->_disabled_addons[] = $addon_name;
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
     * @param string $filter 'enabled','all','disabled','always_enabled' .
     * enabled are those that are permitted to be loaded
     * disabled are those that appear within the '_disabled_modules' array
     * 'always_active' are those modules not in the 'disabled' array that will load despite the plugins disabled setting
     *
     * @return arrayReadOnly
     */
    public function getAvailableModules($filter = 'enabled') {
        $available_modules = array();
        if (is_null($this->_available_modules)) {



            $tools = $this->getTools();

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
                if ($this->getSetting('plugin_enabled') == 'disabled') {
                    $haystack = strtolower($module_name);
// if module doesnt match the always enabled regex, add it to 'disabled'
                    if (preg_match($this->getModuleAlwaysEnabledRegex(), $haystack)) {

                        $available_modules['always_enabled'][$module_name] = $module_file_path;
                        $available_modules['enabled'][$module_name] = $module_file_path;
                    } else {

                        $this->getLogger()->log('Module ' . $module_name . ' not loaded because user has disabled the plugin');
                        $available_modules['disabled'][$module_name] = $module_file_path;
                    }
                } elseif (in_array($module_name, $this->getDisabledModules())) {

                    $this->getLogger()->log('Module ' . $module_name . ' not loaded because it has been disabled');

                    $available_modules['disabled'][$module_name] = $module_file_path;
                } else {

                    $available_modules['enabled'][$module_name] = $module_file_path;
                }

                $available_modules['all'][$module_name] = $module_file_path;
            }
            $this->_available_modules = $available_modules;
        }
        //      echo '$this->_available_modules = <pre>', print_r($this->_available_modules, true), '</pre>';
        return $this->_available_modules[$filter];
    }

    /**
     * Get Addon
     *
     * @param string $addon_name
     * @return object
     */
    public function getAddon($addon_name) {


        if (isset($this->_addons[$addon_name])) {

            return $this->_addons[$addon_name];
        } else {

            $this->getLogger()->logError('getAddon Failed, Addon \'' . $addon_name . '\' was not found');
            return (false);
        }
    }

    /**
     * Get Addons
     *
     * Returns an array of all loaded addons
     *
     * @param none
     * @return array $addons
     */
    public function getAddons() {

        if (is_null($this->_addons)) {
            $this->_addons = array();
        }
        return $this->_addons;
    }

//
//    /**
//     * Set Addon
//     *
//     * @param string $module
//     * @param object $object
//     * @return $this
//     */
//    public function setAddon($addon_name, $object) {
//        $this->_addons[$addon_name] = $object;
//     //   echo '<pre>', print_r($this->_addons, true), '</pre>';
//return $this;
//    }

    /**
     * Set Logger
     *
     * @param object $logger
     * @return object $this
     */
    public function setLogger(Simpli_Basev1c0_Logger_Interface $logger) {
        $this->_logger = $logger;
        $this->_logger->setPlugin($this); //pass a reference of the plugin to the logger

        return $this;
    }

    /**
     * Get Logger
     *
     * @param none
     * @return object
     */
    public function getLogger() {

        if (!isset($this->_logger)) {
            die(__CLASS__ . ' missing Logger dependency.');
        }

        return $this->_logger->getInstance();
    }

    /**
     * Get Plugin Url - Read Only
     *
     * @param none
     * @return stringReadOnly
     */
    public function getUrl() {

        if (is_null($this->_plugin_url)) {

            $this->_plugin_url = plugins_url('', $this->getDirectory() . '/' . $this->FILE_NAME_PLUGIN);
        }

        return $this->_plugin_url;
    }

    /**
     * Get Plugin Setting
     *
     * @param string $setting
     * @return mixed
     */
    public function getSetting($setting) {

        if (isset($this->_settings[$setting])) {

            return($this->_settings[$setting]);
        }
    }

    /**
     * Get Plugin Setting Defaults
     *
     * @param none
     * @return array
     */
    public function getSettingDefaults() {
        return $this->_setting_defaults;
    }

    /**
     * Get Plugin Settings
     *
     * @param none
     * @return array
     */
    public function getSettings() {

//            if (empty($this->settings)) {
//                return($this->loadSettings());
//            }

        return $this->_settings;
    }

    /**
     * Set Plugin Setting
     *
     * @param string $setting
     * @param mixed $value
     * @param int $blog_id
     * @return $this
     */
    public function setSetting($setting, $value, $blog_id = 0) {

        /*
         * Update settings array with new value but only if the setting
         * key already exists in the array
         * you set the allowed keys in your plugin's $_settings declaration
         */
        if (in_array(trim($setting), array_keys($this->getSettings()))) {


            if (is_string($value)) {
                $value = trim($value);
            }
            $this->_settings[$setting] = $value;
        }



        return $this;
    }

    /**
     * Save Plugin Settings to WordPress Database
     * Takes settings array and saves it to wp_options table
     * @param int $blog_id
     * @return $this
     */
    public function saveSettings($blog_id = 0) {

        $wp_option_name = $this->getSlug() . '_options';
        $options = $this->getSettings();


        if ($blog_id > 0) {
            update_blog_option($blog_id, $wp_option_name, $options);
        } else {
            update_option($wp_option_name, $options);
        }



        return $this;
    }

    /**
     * Load Plugin Settings from database
     * Load settings as a single array
     * @param int $blog_id
     * @return $this
     */
    public function loadSettings($blog_id = 0) {

        $wp_option_name = $this->getSlug() . '_options';

        $option_defaults = $this->_setting_defaults;



        if ($blog_id > 0) {

            $options = get_blog_option($blog_id, $wp_option_name, $option_defaults);
        } else {
            $options = get_option($wp_option_name, $option_defaults);
        }



        $this->_settings = $options;
//           echo '<br/> options = <pre>' ;
//        print_r($this->_settings) ;
//        echo '</pre>';



        return $this->_settings;
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return object $this
     */
    public function setName($name) {
        $this->_plugin_name = $name;
        return $this;
    }

    /**
     * Get Name
     *
     * @param none
     * @return string
     */
    public function getName() {
        return $this->_plugin_name;
    }

    /**
     * Set Slug
     *
     * @param string $slug
     * @return object $this
     */
    public function setSlug($slug) {
        $this->_slug = $slug;
        return $this;
    }

    /**
     * Get Slug
     *
     * @param none
     * @return string
     */
    public function getSlug() {
        return $this->_slug;
    }

    /**
     * Get Version Read Only ( Change by editing plugin.php )
     *
     *
     * @param none
     * @return stringReadOnly
     */
    public function getVersion() {


        if (is_null($this->_version)) {
            $headers = array('Version' => 'Version');

            $plugin_file_data = get_file_data($this->getFilePath(), $headers, 'plugin');
            $this->_version = $plugin_file_data['Version'];
        }


        return $this->_version;
    }

    /**
     * Get Simpli Base Class Version
     *
     * Set by the Simpli_Framework load method when loading the plugin
     * @format string $template e.g.: 'v{major}.c{minor}' a string template where {major} and {minor} tags will be replaced with the major and minor version numbers.
     * @return string
     */
    public function getBaseClassVersion($template = null) {

        $headers = array('SimpliBaseClassVersion' => 'Simpli Base Class Version');

        $plugin_file_data = get_file_data($this->getFilePath(), $headers, 'simpli');



        $version = $plugin_file_data['Simpli Base Class Version'];

//  $version = $this->_base_class_version;



        if (!is_null($template)) {
            $parts = explode('.', $version);
            $major = $parts[0];
            $minor = $parts[1];

            $template = str_replace('{major}', $major, $template);
            $version = str_replace('{minor}', $minor, $template);
        }

        return $version;
    }

    /**
     * Get Framework Version - Read Only ( set by editing the plugin.php file)
     *
     * @param none
     * @return stringReadOnly
     */
    public function getFrameworkVersion() {

        if (is_null($this->_framework_version)) {
            $simpli_data = get_file_data($this->getFilePath(), array(), 'simpli');

            $this->_framework_version = $simpli_data['Simpli Framework Version'];
        }



        return $this->_framework_version;
    }

    /**
     * Get Slug Parts - Read Only
     *
     * @param none
     * @return arrayReadOnly
     */
    public function getSlugParts() {

        /*
         * derive namespace from slug
         * return as array
         */
        if (is_null($this->_slug_parts)) {
            $parts = explode('_', $this->getSlug());

            $parts['prefix'] = $parts[0];
            $parts['suffix'] = $parts[1];
            $this->_slug_parts = $parts;
        }


        return $this->_slug_parts;
    }

    /**
     * Get Class Namespace - Read Only
     *
     * @param none
     * @return stringReadOnly
     */
    public function getClassNamespace() {

        /*
         * derive namespace from slug
         * just Title Case each word
         */
        if (is_null($this->_class_namespace)) {
            $array_class = explode('_', $this->getSlug());
            $namespace = ucwords($array_class[0]) . '_' . ucwords($array_class[1]);
            $this->_class_namespace = $namespace;
        }

        return $this->_class_namespace;
    }

    /**
     * Get Class Namespace Parts (Read Only)
     *
     * Returns and array of the class namespace parts
     *
     * @param none
     * @return string
     */
    public function getClassNamespaceParts() {


        return explode('_', $this->getClassNamespace());
    }

    /**
     * Get Text Domain
     *
     * @param none
     * @return string
     */
    public function getTextDomain() {
        return $this->_text_domain;
    }

    /**
     * Set Text Domain
     *
     * @param string $text_domain
     * @return object $this
     */
    public function setTextDomain($text_domain) {
        $this->_text_domain = $text_domain;
        return $this;
    }

    /**
     * Get Plugin File Path
     *
     * @param none
     * @return string
     */
    public function getFilePath() {
        return $this->_plugin_file_path;
    }

    /**
     * Set Plugin File Path
     *
     * @param string $something
     * @return object $this
     */
    public function setFilePath($plugin_file_path) {
        $this->_plugin_file_path = $plugin_file_path;
        return $this;
    }

    /**
     * Get Variables to be wp_localized
     *
     * @param none
     * @return string
     */
    public function getLocalVars() {
        return $this->_local_vars;
    }

    /**
     * Set Localize Variables for wp_localize
     *
     * Adds variables to the local_vars array so we
     * can later add them to javascript using wp_localize
     * @param string $something
     * @return object $this
     */
    public function setLocalVars($local_vars) {

        ;
        /*
         * need to merge the individual elements since the standard merge isnt recursive,
         * and the array_merge_recursive chokes on the first merge, as well as not overwriting
         * some of the deeper elements.
         */

        foreach ($local_vars as $key => $value) {
            if (isset($this->_local_vars[$key]) && is_array($this->_local_vars[$key])) {

                $this->_local_vars[$key] = array_merge($this->_local_vars[$key], $value);
            } else {
                $this->_local_vars[$key] = $value;
            }
        }




//        if (is_array($local_vars)) {
//            $this->_local_vars = array_merge_recursive_distinct($this->_local_vars, $local_vars);
//        }
//wp_localize_script('simpli-framework-namespace.js', $this->getSlug(), $this->_local_vars);
//wp_localize_script('save-metabox-state.js', $this->getSlug(), $this->_local_vars);
//wp_localize_script($this->getSlug() . '_' . 'localVars', $this->getSlug(), $this->_local_vars );

        return $this;
    }

    /**
     * Get Debug
     *
     * Returns the debug property array or an element of the debug property array
     * @param $key (optional) . If provided, returns the element identified by the key instead of the entire array
     * @return string
     */
    public function getDebug($key = null) {

#if debug has not  yet been set, call the set method to set defaults
        if (is_null($this->_debug)) {
            $this->setDebug(array());
        }



        if (!is_null($key)) { //if key provided, return only a single element
            $result = $this->_debug[$key];
        } else {
            $result = $this->_debug;
        }

        return $result;
    }

    /**
     * Set Debug
     *
     * @param array $debug
     * @return object $this
     */
    public function setDebug($debug) {


        if (is_null($this->_debug)) {
            $debug_defaults = array(
                'js' => false
                , 'consolelog' => false
                , 'src' => false
                , 'filelog' => false
            );

            $this->_debug = $debug_defaults;
        }

        /*
         * return if no $debug arguments have been passed
         * this will happen when the defaults are set by calling
         * get before debug is set, or by a call to get without arguments
         */
        if (!is_array($debug) || is_null($debug) || empty($debug)) {
            return;
        }

        $valid_options = array('js', 'consolelog', 'filelog', 'src');

        /* Check for Validity
         * Use the validateArrayKeys utility to check that all the keys
         * passed to setDebug are allowed, as defined by $valid_options.
         */
        $validity_check = $this->getTools()->validateArrayKeys($debug, $valid_options);


        if ($validity_check !== true) {

            echo ('(<strong>Error/simpli-framework: Bad Debug Options</strong>, only the following options are accepted and they must be set to true or false: \'' . implode('\',\'', $valid_options) . '\')' );
            return;
        }

        /*
         * Merge what was provided with existing, so existing can provide defaults or previously set options
         */
        $debug = array_merge($this->_debug, $debug);

        if (($debug['consolelog']) || $debug['filelog']) {

            $this->getLogger()->setLoggingOn(true);
        }


//        if (isset($debug['filelog'])) {
//
//            $this->getLogger()->setLoggingOn($debug['filelog']);
//        }



        if (isset($debug['src'])) {

            if (!defined('SCRIPT_DEBUG')) {
                define('SCRIPT_DEBUG', $debug['src']);
            }
        }

        $this->_debug = $debug;
        return $this;
    }

    /**
     * Tools
     *
     * Provides access to the library of methods in the Base Tools class
     * @param none
     * @return object Base Tools
     */
    public function getTools() {

        if (is_null($this->_tools)) {

            $this->_tools = new Simpli_Basev1c0_Btools();
        }


        return $this->_tools;
    }

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks. Function is called during plugin initialization
     * @param none
     * @return void
     */
    public function addHooks() {

    }

    /**
     * Config
     *
     * Configure Plugin
     * @param none
     * @return void
     */
    public function config() {

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
         * make sure wordpress is installed properly
         */
        if (!defined('ABSPATH'))
            die('Cannot Load Plugin - WordPress installation not found');

        /*
         * Add initial log messages
         *
         */

        $this->getLogger()->log('Starting Debug Log for Plugin ' . $this->getName());

        $this->getLogger()->log('Plugin Version: ' . $this->getVersion() . ' Framework Version: ' . $this->getFrameworkVersion() . 'Base Class Version: ' . $this->getBaseClassVersion());



        /*
         * Load the text domain
         * ref: http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
         */
        load_plugin_textdomain($this->getTextDomain(), false, dirname(plugin_basename($this->getFilePath())) . '/languages/');

        $this->addHooks();



        $this->config();

        /**
         * Load Settings
         */
        $this->loadSettings();
        $this->getLogger()->log('Loaded Plugin User Settings ');

        /**
         * Load Modules
         */
        $this->loadModules();

        /**
         * Load Addons
         */
        $this->loadAddons();



        $this->getLogger()->log('Loaded Base Class Library ' . ' from ' . dirname(__FILE__));


//        /*
//         * Initialize Modules
//         */
//        $modules = $this->getModules();
//
//        foreach ($modules as $module) {
//
//            $module->init();
//            $this->getLogger()->log('Initialized Plugin Module ' . $this->getSlug() . '/' . $module->getName());
//        }

        /*
         * Initialize Addons
         */
        $addons = $this->getAddons();

        if (is_array($addons)) {
            foreach ($addons as $addon) {

                $addon->init();
            }
        }





        if (isset($this->_slug)) {
            do_action($this->_slug . '_init');
        }

        $this->getLogger()->log('Completed Initialization for Plugin ' . $this->getName());
    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function getAddonsDirectory() {

        if (is_null($this->_addons_directory)) {
            $this->_addons_directory = $this->getDirectory() . '/' . $this->DIR_NAME_LIBS . '/' . str_replace('_', '/', $this->ADDON_NAMESPACE);
        }
        return $this->_addons_directory;
    }

    /**
     * Load Addon
     *
     * Takes the addon name  and loads the associated file.
     * e.g.: 'Admin' loads from '/simpli/hello/Module/Admin.php'
     *
     * @author Andrew Druffner
     * @param string $module
     * @return $this
     */
    public function loadAddon($addon_name) {






//        $module_full = 'Module\\' . $module;  # Admin
//        $filename = str_replace('\\', '/', $module);
//        $filename = $filename . '.php'; # Admin.php


        /*
         * Derive the file path from the addon name
         */
        $addon_namespace = $this->ADDON_NAMESPACE;
        $addon_file_path = $this->getAddonsDirectory() . '/' . str_replace('_', '/', $addon_name) . '/' . $this->FILE_NAME_ADDON . '.php';
        $this->getLogger()->log('add on file path = ' . $addon_file_path);
        require_once($addon_file_path); # simpli-framework/lib/simpli/hello/Module/Admin.php

        /*
         * Derive the class name
         */
        $class = $this->ADDON_NAMESPACE . '/' . $addon_name . '/' . $this->FILE_NAME_ADDON;
        $class = str_replace('/', '_', $class);

        if (!isset($this->_addons[$class]) || !is_object($this->_addons[$class]) || get_class($this->_addons[$class]) != $class) {
            try {
                $obj_addon = new $class;


                $this->_addons[$addon_name] = $obj_addon;

                //$obj_addon=$this->getAddon($addon_name);
                $obj_addon->setName($addon_name);
                $obj_addon->setPlugin($this); //set the add on's plugin reference
                $this->getLogger()->log('Loaded Addon ' . $addon_name);
            } catch (Exception $e) {

                die('Unable to load Addon: \'' . $addon_name . '\'. ' . $e->getMessage());
            }
        }

        return $this;
    }

    /**
     * Load Addons
     *
     * Iterates through Addons directories looking for Addon.php and then hands off file to loadAddon
     *
     * @param none
     * @return void
     */
    public function loadAddons() {

        $tools = $this->getTools();
        $addon_files = $tools->getGlobFiles($this->getAddonsDirectory(), 'Addon.php');
        //echo '<br>add on files after return : ';
        //echo '<pre>', print_r($addon_files, true), '</pre>';
//    const ADDON_BASE_FILE_NAME = 'Addon';
//do a glob search to get add_on_files
        if (!is_array($addon_files)) {
            return;
        }
        foreach ($addon_files as $key => $addon_file_path) {
            /* Determine Add On Name  ( e.g.: SIMPLI_FORMS
             * from file path e.g.: /lib/Simpli/Addons/Simpli/Forms/Addon.php
             * by removing the known addons directory and base file name
             */

            $addon_file_path = $this->getTools()->normalizePath($addon_file_path);

            //echo '<br/>' . __LINE__ . ' ' . __METHOD__ . ' ' . $addon_file_path;
            /*
             * First, remove  AddonsDirectory path , becomes :  Simpli/Forms/Addon.php
             */
            $addon_name = $this->getTools()->makePathRelative($this->getAddonsDirectory(), $addon_file_path); //
            //echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> $addon_name = ' . $addon_name . '</strong>';
            /*
             * Next, remove the file base name and extension , becomes : Simpli/Forms
             */

            $addon_name = str_replace("/" . $this->FILE_NAME_ADDON . '.php', '', $addon_name); //now remove the file base name and extensioin
            //echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> $addon_name = ' . $addon_name . '</strong>';
            /*
             * Next, replace DIRECTORY SEPARATOR with Underscores , becomes : Simpli_Forms
             */
            $addon_name = str_replace("/", '_', $addon_name);
            //echo '<br/>  addon name = ' . $addon_name;

            /*
             * skip loading the addon if it was manually disabled.
             */
            if (in_array($addon_name, $this->getDisabledAddons())) {
                $this->getLogger()->log('Addon ' . $addon_name . ' not loaded because it is has been disabled.');
                continue;
            }
            $this->loadAddon($addon_name);
        }
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
     * @return mixed False if failed to load module, module object otherwise
     */
    public function loadModule($module_name) {



        $available_modules = $this->getAvailableModules('enabled');

        /*
         * check to see if the module_name is enabled; if not, return.
         */
        if (!is_array($available_modules) || !isset($available_modules[$module_name])) {
            $this->getLogger()->log('unable to load Module ' . $module_name . ' , since it is an inactive module');
            return null;
        }





        /*
         * Derive the class from the module file path
         */



        $class = $this->getClassNamespace() . '_' . $this->DIR_NAME_MODULES . '_' . $module_name;
//        $module_file_path = $available_modules[$module_name];
//        require_once($module_file_path); # simpli-framework/lib/simpli/hello/Module/Admin.php
//        echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> $module_file_path = ' . $module_file_path . '</strong>';
//        $relative_path = $this->getTools()->makePathRelative($this->getModuleDirectory(), $module_file_path);
//        echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> $relative_path = ' . $relative_path . '</strong>';
//        $relative_path = basename($relative_path, '.php'); //remove the extension
//        echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> $relative_path = ' . $relative_path . '</strong>';
//        $class = str_replace('/', '_', $relative_path); //
        //     die('<br>' . __LINE__ . 'exiting to check class, $class = ' . $class);

        /*
         * Create the module object and attach it to $_modules
         */
        if (!isset($this->_modules[$class]) || !is_object($this->_modules[$class]) || get_class($this->_modules[$class]) != $class) {
            try {
                /*
                 * create the module object
                 */
                $module_object = new $class;
                $this->setModule($module_name, $module_object);
                /*
                 * set the plugin reference
                 */
                $this->getModule($module_name)->setPlugin($this);
                $this->getLogger()->log('Loaded Plugin Module ' . $this->getSlug() . '/' . $module_name);
                /*
                 * initialize the module
                 */
                $module_object->init();
                $this->getLogger()->log('Initialized Plugin Module ' . $this->getSlug() . '/' . $module_object->getName());
            } catch (Exception $e) {
                die('Unable to load Module: \'' . $module_name . '\'. ' . $e->getMessage());
            }
        }





        return $module_object;
    }

    /**
     * Get Modules
     *
     * Returns and array of loaded modules
     * @param none
     * @return array
     */
    public function getModules() {

        if (is_null($this->_modules)) {
            $this->_modules = array();
        }

        return $this->_modules;
    }

    /**
     * Get Module
     *
     * Get the loaded module object, given its name
     * @param string $module_name
     * @return object $module
     */
    public function getModule($module_name) {
        if (!isset($this->_modules[$module_name])) {
            //attempt to load module
            return($this->loadModule($module_name));
        }
        return $this->_modules[$module_name];
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

//        echo '<br> modules = ' . '<pre>', print_r($enabled_modules, true), '</pre>';

        foreach ($enabled_modules as $module_name => $module_path) {

            $this->loadModule($module_name);
        }
    }

    /**
     * Get Activate Actions
     *
     * @param none
     * @return string
     */
    public function getActivateActions() {
        return $this->_activate_actions;
    }

    /**
     * Add Activate Action
     *
     * Because of the way that activation works, its not possible to trigger activation actions normally through the do_action function.
     * In this way , we are able to cycle through the actions
     * Usage:
     * To add an action
     *  $this->getPlugin()->addActivateAction(array(&$this, 'flush_rewrite_rules'));
     * see the Plugin::install method for an example of how to cycle through all the activate actions.
     *
     *
     * @param string $something
     * @return object $this
     */
    public function addActivateAction($action) {
        #initialize
        if (is_null($this->_activate_actions)) {
            $this->_activate_actions = array();
        }

        array_push($this->_activate_actions, $action);
        return $this;
    }

    /**
     * Enqueue Inline Script
     *
     * Adds an inline script to a queue array that is later printed
     * @param string $handle - The handle of the script
     * @param string $src - The absolute path to the script
     * @para array $deps - An array of handles that the script is dependent on
     * @return array $this->_inline_script_queue The current array of queued scripts
     */
    public function enqueueInlineScript($handle, $path, $inline_deps = array(), $external_deps = array()) {

        /*
         * Its necessary to set defaults for arrays here since doing so in the declaration
         * for some reason doesnt work and carries a null value through instead, which
         * will choke foreach statements later.
         *
         */
        if (is_null($inline_deps)) {
            $inline_deps = array();
        }
        if (is_null($external_deps)) {
            $external_deps = array();
        }
        /*
         * if queue hasnt been created yet, create it
         */
        if (is_null($this->_inline_script_queue)) {
            $this->_inline_script_queue = array('scripts' => array(), 'handles' => array(), 'inline_deps' => array());
        }


//        $handle = 'myscript';
//        $path = $this->getDirectory() . '/js/myscript.js';
//        $deps = array('myscript1', 'myscript2');


        $inline_script = array(
            'path' => $path
            , 'inline_deps' => $inline_deps
            , 'external_deps' => $external_deps
        );



        $queue = $this->_inline_script_queue;
        $queue['scripts'][$handle] = $inline_script;
        $queue['handles'][] = $handle;
        $queue['inline_deps'][$handle] = $inline_deps;

//  $this->_inline_script_queue = array_merge($this->_inline_script_queue, array('handles'=>array($handle))); //needed to assist with sorting dependencies
//  $this->_inline_script_queue = array_merge($this->_inline_script_queue, array('inline_deps'=>array($handle=>$inline_deps)));//needed to assist with sorting dependencies
        $this->_inline_script_queue = $queue;
        return $queue;
    }

    /*     * ge
     * Print Inline Footer Scripts
     *
     * Echos the queued inline scripts to the WordPress footer
     * @todo Consider adding a 'dependency resolution' option to toggle  dependency sort off for potentially increased performance. If you do this, you'll likely receive more reference errors and need to sequence the loading of script more carefully manually, as well as place code in jquery ready() blocks
     * @param boolean $dep_resolution  Dependency Resolution - Whether load the scripts in order of dependency which helps to prevent conflicts but may take longer
     * @return void
     */

    function printInlineFooterScripts() {

        $dep_resolution = true;
        /*
         * get the script queue
         */
        $script_queue = $this->_inline_script_queue;

        /*
         * dont go any further if there are no scripts to process
         */
        if (empty($script_queue)) {

            return;
        }
        /*
         * Now get a sorted list of handles, in order of dependency.
         */
        $handle_list = $script_queue['handles'];
        $deps = $script_queue['inline_deps'];

        if ($dep_resolution) {
            $handle_list = $this->getTools()->sortDependentList($handle_list, $deps);
        }
//        else
//        {
//            $handle_list = $unsorted_handles;
//        }

        /*
         * Now print out the scripts, in order of their dependencies
         */


        echo '<script  type="text/javascript">';

        foreach ($handle_list as $handle) {
            $script = $script_queue['scripts'][$handle]; /* get the script queue properties from the script_queue */
            $ext_dependencies_met = true; /* assume that external dependencies are met,, then toggle it false if found to be untrue */
            foreach ($script['external_deps'] as $ext_handle) {
                if (!wp_script_is($ext_handle)) {
                    $ext_dependencies_met = false;
                }
            }

            /*
             * include the script if external dependencies met
             * if inline script dependencies are not met, then they wouldn't appear here at all anyway because the sortDependentList removes them
             */
            if ($ext_dependencies_met) {

                if (file_exists($script['path'])) { //include the path to the script. if not found, output an error to the javascript console.
                    include($script['path']);
                    $this->getLogger()->log('loaded inline script: ' . $handle);
                } else {
                    $this->getLogger()->log('couldnt load script: ' . $handle . ' due to missing script file');
                    echo 'jQuery(document).ready(function() { console.error (\' WordPrsss Plugin ' . $this->getSlug() . ' attempted to enqueue ' . ' Missing Script File ' . str_replace('\\', '\\\\', $script['path']) . '\')});';
                }
            } else {
                $this->getLogger()->log($handle . ' not loaded, missing dependency ' . $ext_handle);
            }
        }

        echo '</script>';
    }

    /**
     * Enqueue Scripts
     *
     * Enqueues frameworks scripts
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function enqueue_scripts() {


        /*
         * Load 3rd Party Libraries using wp_enqueue
         */

        wp_enqueue_script('jquery');

        /*
         * Load our own 'inline' scripts
         * We use the framework's enqueueInlineScript method to speed loading and manage dependencies
         * ( faster loading since there is no roundtrip request.)
         */



        $handle = $this->getSlug() . '_simpli-framework-namespace.js';
        $path = $this->getDirectory() . '/js/simpli-framework-namespace.js';
        $inline_deps = array();
        $external_deps = array('jquery');
        $this->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);

        $handle = $this->getSlug() . '_test1.js';
        $path = $this->getDirectory() . '/js/test1.js';
        $inline_deps = array($this->getSlug() . '_test2.js', $this->getSlug() . '_simpli-framework-namespace.js');
        $external_deps = array('jquery');
        $this->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);



        /*
         * Use wp_enqueue for longer local scripts
         */



        /*
         * Pass to javascript some basic information about our plugin
         */
        $slug_parts = $this->getSlugParts();

        $vars = array(
            'plugin' => array(
                'slugparts' => array(
                    'prefix' => $slug_parts['prefix']
                    , 'suffix' => $slug_parts['suffix']
                )
                , 'slug' => $this->getSlug()
                , 'name' => $this->getName()
                , 'url' => $this->getUrl()
                , 'version' => $this->getVersion()
                , 'directory' => $this->getDirectory()
                , 'debug' => $this->getDebug('js')
                , 'admin_url' => get_admin_url()
                , 'nonce' => wp_create_nonce($this->getSlug())
            )
        );


        /*
         * Use the framework setLocalVars to create an object within javascript named after the slug for your plugin, with the properties above.
         * for example,to access the plugins url, do this : alert ( simpli_hello.plugin.url)
         * Avoid use of wp_localize as it prevents us from adding variables after the enqueue events.
         */

        $this->setLocalVars($vars);
    }

    /**
     * Prints Local Vars to Footer of Page
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function printLocalVars() {

        $vars = json_encode($this->getLocalVars());
        ?>
        <script type='text/javascript'>

            var <?php echo $this->getSlug(); ?> = <?php echo $vars; ?>

        </script>

        <?php
    }

}

