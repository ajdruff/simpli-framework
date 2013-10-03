<?php

/**
 * Basev1c2 class for a WordPress plugin.
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 * @property string $ADDON_NAMESPACE The class namespace used for the Addons, e.g.: 'Simpli_Addons'
 * @property string $FILE_NAME_ADDON The file name for the addon , e.g.: 'Addon'
 * @property string $DIR_NAME_MODULES The directory name containing modules , e.g.: 'Module'
 * @property string $DIR_NAME_LIBS The directory name containing libraries, e.g.: 'lib'
 * @property string $FILE_NAME_PLUGIN The file name for plugin , e.g.: 'plugin.php'
 * @property string $DISABLED_MODULES An array of Module Names that you don't want loaded
 * @property string $DISABLED_ADDONS An array of Addon Names that you don't want loaded
 * @property string $ALWAYS_ENABLED_REGEX_PATTERN Modules matching this pattern will always be loaded regardless if the plugin is disabled from the Admin menu.
 * @property boolean $DEBUG Whether to enable debug by loading the debug module. You will still need to ensure that the DebugConfig.php file has $this->turnOn() in the configuration.
 * @property string $QUERY_VAR The name of the plugin's query variable
 * @property string $QV_ACTION_EDIT_POST The query variable's value that indicates the editing of a post by a custom post editor
 * @property string $QV_ACTION_ADD_POST The query variable's value that indicates the adding of a post by a custom post editor
 * @property boolean $COMPRESS Ajax Compression Set to false if server does not support zlib. True is default
 * @property boolean $ALLOW_SHORTCODES Processes includes files ( like templates ) for shortcodes. Default is true.
 * @property string $MENU_POSITION_DEFAULT Provides the default menu position when a menu page is added.
 * @property string $REL_PATH_ADMIN Relative path to the admin directory.
 * @property string $REL_PATH_RESOURCES Relative path to the resources directory that holds images, css,etc.

 *
 *
 */
class Simpli_Frames_Basev1c2_Plugin implements Simpli_Frames_Basev1c2_Plugin_Interface {

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

//    /**
//     * Logger
//     *
//     * @var Simpli_Frames_Basev1c2_Logger_Interface
//     */
//    protected $_logger = null;

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
    protected $_option_defaults = null;

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
    protected $_disabled_modulesOLD = null;

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
     * @var array An array of the disabled addons
     */
    protected $_disabled_addonsOLD = null;

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

//        /*
//         *
//         * Set Logger dependency
//         *
//         */
//
//        $this->setLogger(Simpli_Frames_Basev1c2_Logger::getInstance());
//

        return $this;
    }

    /**
     * Debug Object
     *
     * The debug object
     *
     * @var object
     */
    protected $_debug = null;

    /**
     * Debug
     *
     * Returns a debug object. Required to override base plugin method so the correct config() method is used.
     *
     * @param none
     * @return void
     */
    public function debug() {
        /*
         * If no debug object, attempt to load it
         * If it didnt load, return a phantom object instead, effectively
         * disabling any debug calls but not creating any errors
         * For Debug to work, it must always be located under the /lib/Simpli/Hello folder or its equivilent and must be named Debug.php
         *
         */

        if (!$this->DEBUG) {
            return (new Simpli_Frames_Basev1c2_Phantom()); //return a phantom object which will silently ignore each call to the debug class. To optimize further, you should comment out all calls to the $this->debug() object using a regex search and replace  in your final released code.
        }
        if (is_null($this->_debug)) {
            $class_namespace_parts = $this->getClassNamespaceParts();
            if (!file_exists($this->getDirectory() . '/lib/' . $class_namespace_parts[0] . '/' . $class_namespace_parts[1] . '/DebugConfig.php')) {
                $this->setConfig('DEBUG', false); //switch Debug to off since phantom will not be accurate.
                $this->_debug = new Simpli_Frames_Basev1c2_Phantom(); //create a phantom
            } else {
                try {

                    $debug_class = $this->getClassNamespace() . '_DebugConfig';
                    $this->_debug = new $debug_class($this);
                    //   $this->_debug->config();
                    //   $this->_debug->addHooks();
                } catch (Exception $exc) {
                    echo $exc->getMessage();
                    $this->_debug = new Simpli_Frames_Basev1c2_Phantom(); //create a phantom
                }
            }
        }

        return $this->_debug;
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
            $this->_directory = $this->tools()->normalizePath($directory);
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
            $this->_module_directory = $this->tools()->normalizePath($module_directory);
        }


        return $this->_module_directory;
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
        // $this->tools()->backtrace();
        $this->debug()->t();
        $available_modules = array();
        if (is_null($this->_available_modules)) {



            $tools = $this->tools();

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

                //   echo 'Disabled Modules<pre>', print_r($this->getDisabledModules(), true), '</pre>';


                if ($this->getUserOption('plugin_enabled') == 'disabled') {
                    $haystack = strtolower($module_name);
// if module doesnt match the always enabled regex, add it to 'disabled'
                    if (preg_match($this->ALWAYS_ENABLED_REGEX_PATTERN, $haystack)) {

                        $available_modules['always_enabled'][$module_name] = $module_file_path;
                        $available_modules['enabled'][$module_name] = $module_file_path;
                    } else {

                        $this->debug()->log('Module ' . $module_name . ' not loaded because user has disabled the plugin');
                        $available_modules['disabled'][$module_name] = $module_file_path;
                    }
                } elseif (in_array($module_name, $this->DISABLED_MODULES)) {

                    $this->debug()->log('Module ' . $module_name . ' not loaded because it has been disabled');

                    $available_modules['disabled'][$module_name] = $module_file_path;
                } else {

                    $available_modules['enabled'][$module_name] = $module_file_path;
                }

                $available_modules['all'][$module_name] = $module_file_path;
            }
            $this->_available_modules = $available_modules;
        }
        if (!isset($this->_available_modules[$filter])) {
            $result = array(); //if all modules are disable, make sure you send back an empty array, else you'll get an error here
        } else {
            $result = $this->_available_modules[$filter];
        }
        return $result;
    }

    /**
     * Get Addon
     *
     * @param string $addon_name
     * @return object
     */
    public function getAddon($addon_name) {
        $this->debug()->t();

        if (isset($this->_addons[$addon_name])) {

            return $this->_addons[$addon_name];
        } else {


            $this->debug()->log('getAddon Failed, Addon \'' . $addon_name . '\' was not found');
            $this->debug()->logcError('getAddon Failed, Addon \'' . $addon_name . '\' was not found');
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
//return $this;
//    }

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
     * Get User Option
     *
     * Get a user option for the plugin. These come from the options
     * saved in the admin panel.
     *
     * @param string $user_option
     * @return mixed
     */
    public function getUserOption($user_option) {
        $this->debug()->t();
        if (isset($this->_user_options[$user_option])) {

            return($this->_user_options[$user_option]);
        }
    }

    /**
     * Get User Option Defaults
     *
     * Gets the user option defaults, which are the user options
     * that you set in the config() method and are not saved to
     * the database until the options panel is saved for the first time.
     *
     * @param none
     * @return array
     */
    public function getUserOptionDefaults() {
        return $this->_option_defaults;
    }

    /**
     * Get User Options
     *
     * Gets all the user optionsf or the plugin as configured
     * in the admin panels
     *
     * @param none
     * @return array
     */
    public function getUserOptions() {

//            if (empty($this->settings)) {
//                return($this->loadUserOptions());
//            }

        return $this->_user_options;
    }

    /**
     * Set User Option
     *
     * Sets a user option for the plugin
     *
     * @param string $option
     * @param mixed $value
     * @param int $blog_id
     * @return $this
     */
    public function setUserOption($option_name, $option_value, $blog_id = 0) {

        /*
         * Update settings array with new value but only if the setting
         * key already exists in the array
         * you set the allowed keys in your plugin's $_settings declaration
         */
        if (in_array(trim($option_name), array_keys($this->getUserOptions()))) {


            if (is_string($option_value)) {
                $option_value = trim($option_value);
            }
            $this->_user_options[$option_name] = $option_value;
        }



        return $this;
    }

    /**
     * Save User Options to the WordPress Database
     *
     * Takes the user options array and saves it to the wp_options table
     * @param $options The options array to be saved
     * @param int $blog_id
     * @return $this
     */
    public function saveUserOptions($options = null, $blog_id = 0) {

        $wp_option_name = $this->getSlug() . '_options';
        if (is_null($options)) {
            $options = $this->getUserOptions();
        }



        if ($blog_id > 0) {
            update_blog_option($blog_id, $wp_option_name, $options);
        } else {
            update_option($wp_option_name, $options);
        }



        return $this;
    }

    /**
     * Load User Options
     *
     * Loads the options from the database or if not in the database, from the
     * defaults
     * @param int $blog_id
     * @return $this
     */
    public function loadUserOptions($blog_id = 0) {

        $wp_option_name = $this->getSlug() . '_options';

        $option_defaults = $this->_option_defaults;



        if ($blog_id > 0) {

            $options = get_blog_option($blog_id, $wp_option_name, $option_defaults);
        } else {
            $options = get_option($wp_option_name, $option_defaults);
        }



        $this->_user_options = $options;
//           echo '<br/> options = <pre>' ;
//        echo '</pre>';



        return $this->_user_options;
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
     * Tools
     *
     * Provides access to the library of methods in the Basev1c2 Tools class
     * @param none
     * @return Simpli_Frames_Basev1c2_Plugin_Tools Basev1c2 Tools
     */
    public function tools() {

        if (is_null($this->_tools)) {

            $this->_tools = new Simpli_Frames_Basev1c2_Plugin_Tools($this);
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

        $this->debug()->log('Starting Debug Log for Plugin ' . $this->getName());

        $this->debug()->log('Plugin Version: ' . $this->getVersion() . ' Framework Version: ' . $this->getFrameworkVersion() . 'Basev1c2 Class Version: ' . $this->getBaseClassVersion());



        /*
         * Load the text domain
         * ref: http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
         */
        load_plugin_textdomain($this->getTextDomain(), false, dirname(plugin_basename($this->getFilePath())) . '/languages/');

        $this->addHooks();




        $this->config();

        /*
         * Compress Output
         */
        if ($this->COMPRESS) {
            $this->tools()->startGzipBuffering();
        }

        /**
         * Load Settings
         */
        $this->loadUserOptions();
        $this->debug()->log('Loaded Plugin User Settings ');

        /**
         * Load Modules
         */
        $this->loadModules();

        /**
         * Load Addons
         */
        $this->loadAddons();



        $this->debug()->log('Loaded Basev1c2 Class Library ' . ' from ' . dirname(__FILE__));


//        /*
//         * Initialize Modules
//         */
//        $modules = $this->getModules();
//
//        foreach ($modules as $module) {
//
//            $module->init();
//            $this->debug()->log('Initialized Plugin Module ' . $this->getSlug() . '/' . $module->getName());
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


        $this->debug()->log('Completed Initialization for Plugin ' . $this->getName());
    }

    /**
     * Get Addons Directory
     *
     * Get Addons Directory Path
     *
     * @param none
     * @return string
     */
    public function getAddonsDirectory() {
        $this->debug()->t();
        if (is_null($this->_addons_directory)) {
            //orig:
            $this->_addons_directory = $this->getDirectory() . '/' . $this->DIR_NAME_LIBS . '/' . str_replace('_', '/', $this->ADDON_NAMESPACE);
            //  $this->_addons_directory = $this->getDirectory() . '/' . $this->DIR_NAME_LIBS . '/' . str_replace('_', '/', $this->getClassNamespace()) . '/' . str_replace('_', '/', $this->ADDON_NAMESPACE);
            $this->debug()->logVar('$this->_addons_directory = ', $this->_addons_directory);
            //  $this->_addons_directory = $this->getDirectory() . '/' . $this->DIR_NAME_LIBS . '/' . str_replace('_', '/', $this->getClassNamespace()) . '/' . str_replace('_', '/', $this->ADDON_NAMESPACE);
        }

        $this->debug()->logVar('$this->_addons_directory = ', $this->_addons_directory);
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
        $this->debug()->log('add on file path = ' . $addon_file_path);
        require_once($addon_file_path); # simpli-framework/lib/simpli/hello/Module/Admin.php

        /*
         * Derive the class name
         */
        $class = $this->ADDON_NAMESPACE . '/' . $addon_name . '/' . $this->FILE_NAME_ADDON;
        $class = str_replace('/', '_', $class);

        if (!isset($this->_addons[$class]) || !is_object($this->_addons[$class]) || get_class($this->_addons[$class]) != $class) {
            try {
                $obj_addon = new $class($this); //create the addon, setting $this as the addon's plugin dependency


                $this->_addons[$addon_name] = $obj_addon;

                //$obj_addon=$this->getAddon($addon_name);
                $obj_addon->setName($addon_name);
                ///  $obj_addon->setTTTTTPlugin($this); //set the add on's plugin reference
                $this->debug()->log('Loaded Addon ' . $addon_name);
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
        $this->debug()->t();
        $tools = $this->tools();
        /*
         * get all the add on files in the add on directory
         */
        $addon_files = $tools->getGlobFiles($this->getAddonsDirectory(), 'Addon.php');

        $this->debug()->logVar('$addon_files = ', $addon_files);
        //echo '<br>add on files after return : ';
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

            $addon_file_path = $this->tools()->normalizePath($addon_file_path);

            //echo '<br/>' . __LINE__ . ' ' . __METHOD__ . ' ' . $addon_file_path;
            /*
             * First, remove  AddonsDirectory path , becomes :  Simpli/Forms/Addon.php
             */
            $addon_name = $this->tools()->getRelativePath($this->getAddonsDirectory(), $addon_file_path); //
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



            if (in_array($addon_name, $this->DISABLED_ADDONS)) {
                $this->debug()->log('Addon ' . $addon_name . ' not loaded because it is has been disabled.');
                continue;
            }
            $this->loadAddon($addon_name);
            $this->debug()->log('Addon ' . $addon_name . ' loaded.');
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
     * @param string $module_name The name of the module
     * @param boolean $halt_on_fail True = will die if cant load module. False will return 'false' if module failed to load. This allows you to let the plugin silently ignore modules that are missing, for example, in the case of the debug module, and handle the failure
     * @return mixed False if failed to load module, module object otherwise
     */
    public function loadModule($module_name, $halt_on_fail = true) {

        $this->debug()->t();

        $available_modules = $this->getAvailableModules('enabled');


        /*
         * check to see if the module_name is enabled; if not, return.
         */
        if (!is_array($available_modules) || !isset($available_modules[$module_name])) {
            $this->debug()->log('Will not attempt to load module \'' . $module_name . '\' , since it is not available. Either there was a problem with loading it or it has been manually disabled');
            return false;
        }





        /*
         * Derive the class from the module file path
         */



        $class = $this->getClassNamespace() . '_' . $this->DIR_NAME_MODULES . '_' . $module_name;
        $module_object = new $class($this); //create the module object, setting $this as its plugin dependency
//        $module_file_path = $available_modules[$module_name];
//        require_once($module_file_path); # simpli-framework/lib/simpli/hello/Module/Admin.php
//        echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> $module_file_path = ' . $module_file_path . '</strong>';
//        $relative_path = $this->tools()->getRelativePath($this->getModuleDirectory(), $module_file_path);
//        echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> $relative_path = ' . $relative_path . '</strong>';
//        $relative_path = basename($relative_path, '.php'); //remove the extension
//        echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> $relative_path = ' . $relative_path . '</strong>';
//        $class = str_replace('/', '_', $relative_path); //


        /*
         * Create the module object and attach it to $_modules
         */
        if (!isset($this->_modules[$module_name]) || !is_object($this->_modules[$module_name]) || get_class($this->_modules[$module_name]) != $class) {
            try {
                /*
                 * create the module object
                 */

                $this->setModule($module_name, $module_object);
                /*
                 * set the plugin reference
                 */
                //   $this->getModule($module_name)->setTTTTPlugin($this);
                $this->debug()->log('Loaded Plugin Module ' . $this->getSlug() . '/' . $module_name);
                /*
                 * initialize the module
                 */
                $module_object->init();
                $this->debug()->log('Initialized Plugin Module ' . $this->getSlug() . '/' . $module_object->getName());
            } catch (Exception $e) {
                if ($halt_on_fail) {
                    die('Unable to load Module: \'' . $module_name . '\'. ' . $e->getMessage());
                } else {
                    return false;
                }
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
        $this->debug()->t();
        if (!isset($this->_modules[$module_name])) {
            //attempt to load module
            $loaded_result = $this->loadModule($module_name);
            if ($loaded_result === false) {
                $this->debug()->logError('Could not find Module  \'' . $module_name . '\' in  ' . get_class($this));
            }


            return($loaded_result);
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
        $this->debug()->t();
        $enabled_modules = $this->getAvailableModules('enabled');



        foreach ($enabled_modules as $module_name => $module_path) {

            $this->loadModule($module_name);
        }
        $this->debug()->logVars(get_defined_vars());
    }

    /**
     * Get Activate Actions
     *
     * @param none
     * @return string
     */
    public function getActivateActions() {
        if (is_null($this->_activate_actions)) {
            $this->_activate_actions = array();
        }
        return $this->_activate_actions;
    }

    /**
     * Add Activate Action
     *
     * Because of the way that activation works, its not possible to trigger activation actions normally through the do_action function.
     * In this way , we are able to cycle through the actions
     * Usage:
     * To add an action
     *  $this->addActivateAction(array($this, 'my_method'));
     * see the Plugin::install method for an example of how to cycle through all the activate actions.
     *
     *
     * @param string $something
     * @return object $this
     */
    public function addActivateAction($action) {

        $this->debug()->t();

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
    public function enqueueInlineScript($handle, $path, $inline_deps = array(), $external_deps = array(), $footer = true) {

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
        $queue['footer'][$handle] = $footer;
        $queue['handles'][$handle] = $handle;
        $queue['inline_deps'][$handle] = $inline_deps;

//  $this->_inline_script_queue = array_merge($this->_inline_script_queue, array('handles'=>array($handle))); //needed to assist with sorting dependencies
//  $this->_inline_script_queue = array_merge($this->_inline_script_queue, array('inline_deps'=>array($handle=>$inline_deps)));//needed to assist with sorting dependencies
        $this->_inline_script_queue = $queue;
        return $queue;
    }

    /**
     * Print Inline Header Scripts (Wrapper/Hook Function)
     *
     * Hook Function for admin_print_scripts or wp_print_scripts and is a wrapper around _printInlineScripts so as to provide the correct $footer parameter.
     * @param none
     * @return void
     */
    function hookPrintInlineHeaderScripts() {

        $this->_printInlineScripts($footer = false);
    }

    /**
     * Print Inline Footer Scripts (Wrapper/Hook Function)
     *
     * Hook Function for admin_print_footer_scripts or wp_print_footer_scripts and is a wrapper around _printInlineScripts so as to provide the correct $footer parameter.
     * @param none
     * @return void
     */
    function hookPrintInlineFooterScripts() {

        $this->_printInlineScripts($footer = true);
    }

    /**
     * Print Inline Footer Scripts
     *
     * Echos the queued inline scripts to the WordPress footer
     * @todo Consider adding a 'dependency resolution' option to toggle  dependency sort off for potentially increased performance. If you do this, you'll likely receive more reference errors and need to sequence the loading of script more carefully manually, as well as place code in jquery ready() blocks
     * @param boolean $dep_resolution  Dependency Resolution - Whether load the scripts in order of dependency which helps to prevent conflicts but may take longer
     * @return void
     */
    function _printInlineScripts($footer) {
        $this->debug()->t();

        $dep_resolution = true;
        /*
         * get the script queue
         */
        $script_queue = $this->_inline_script_queue;
//        echo '<br> printing scripts footer=' . $footer;

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
            $handle_list = $this->tools()->sortDependentList($handle_list, $deps);
        }



//        else
//        {
//            $handle_list = $unsorted_handles;
//        }

        /*
         * Now print out the scripts, in order of their dependencies
         */



        foreach ($handle_list as $handle) {

            $script = $script_queue['scripts'][$handle]; /* get the script queue properties from the script_queue */
            $footer_flag = $script_queue['footer'][$handle];
            /*
             * Skip printing the script if the footer parameter doesnt match the location of printing
             */
            if ($footer !== $footer_flag) {
                continue;
            }
            $ext_dependencies_met = true; /* assume that external dependencies are met,, then toggle it false if found to be untrue */
            $has_external_dependency = false;
            foreach ($script['external_deps'] as $ext_handle) {
                $has_external_dependency = true;
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
                    $this->debug()->log('start load inline script: ' . $handle);

                    /*
                     * get the script source
                     */

                    ob_start();
                    include($script['path']);
                    $script_source = ob_get_clean();


                    /*
                     * create and populate template
                     */
                    $template = '<script type="text/javascript">
         {START_WINDOW_LOAD}
         {SCRIPT_SOURCE}
{END_WINDOW_LOAD}
</script>
';
                    $template = $this->tools()->scrubHtmlWhitespace($template);
                    $tags = array(
                        '{START_WINDOW_LOAD}' => ($has_external_dependency && !$footer) ? 'window.onload = function() {' : '', // need to do this since wordpress loads external scripts after inline and youl get jquery errors otherwise
                        '{END_WINDOW_LOAD}' => ($has_external_dependency && !$footer) ? '}' : '', ////window.onload closing bracket
                        '{SCRIPT_SOURCE}' => $script_source,
                    );


                    $script_html = str_ireplace(array_keys($tags), array_values($tags), $template);


                    echo $script_html;


                    $this->debug()->log('end load inline script: ' . $handle);
                } else {
                    $this->debug()->log('couldnt load script: ' . $handle . ' due to missing script file');
                    echo 'jQuery(document).ready(function() { console.error (\' WordPrsss Plugin ' . $this->getSlug() . ' attempted to enqueue ' . ' Missing Script File ' . str_replace('\\', '\\\\', $script['path']) . '\')});';
                }
            } else {
                $this->debug()->log($handle . ' not loaded, missing dependency ' . $ext_handle);
            }
        }
    }

    /**
     * Enqueue Scripts
     *
     * Enqueues frameworks scripts
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function hookEnqueueScripts() {


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
        $external_deps = array();
        $footer = false; //for some reason, this always needs to be true or you will receive errors when using namespaces
        $this->enqueueInlineScript($handle, $path, $inline_deps, $external_deps, $footer);

        $handle = $this->getSlug() . '_test1.js';
        $path = $this->getDirectory() . '/js/test1.js';
        $inline_deps = array($this->getSlug() . '_test2.js', $this->getSlug() . '_simpli-framework-namespace.js');
        $external_deps = array('jquery');
        $this->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);



        /*
         * Use wp_enqueue for longer local scripts
         */

        /*
         *
         */
        $handle = 'simpli-wp-common.js';
        $src = $this->getUrl() . '/js/simpli-wp-common.js';
        $deps = array();
        $ver = null;
        $in_footer = false;
        wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);



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
                , 'debug' => $this->debug()->isOn() //flag telling javascript whether debugging is on
                , 'admin_url' => get_admin_url()
                , 'nonce' => wp_create_nonce($this->getSlug())
            )
        );


        /*
         * Use the framework setLocalVars to create an object within javascript named after the slug for your plugin, with the properties above.
         * for example,to access the plugins url, do this : alert ( simpli_frames.plugin.url)
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
    function hookPrintLocalVars() {

        $vars = json_encode($this->getLocalVars());
        ?>
        <script type='text/javascript'>

            var <?php echo $this->getSlug(); ?> = <?php echo $vars; ?>

        </script>

        <?php
    }

    /**
     * Set User Option Default
     *
     * Sets a default value for settings that have not yet been saved to the database.
     * If you want a setting to have a value before any configuration by the user occurs,
     * you must set it here.
     *
     * @param string $setting_name The name of the setting. Must be unique for the plugin
     * @param mixed $option_value The value of the setting.
     * @return void
     */
    protected function setUserOptionDefault($option_name, $option_value) {

        $this->_option_defaults[$option_name] = $option_value;
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
     * Get Configs
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
     * Get Module Config Default
     *
     * Provides a default config value if it wasnt set by the user
     *
     * @param mixed $property_name
     * @return mixed The default value of the config
     */
    private function _getConfigDefault($property_name) {

        if (is_null($this->_property_defaults)) {
            $this->_setConfigDefaults();
        }
        if (!isset($this->_property_defaults[$property_name])) {


            throw new Exception('No such configuration property for  \'' . $property_name . '\' in  ' . get_class($this));
        }
        return $this->_property_defaults[$property_name];
    }

    /**
     * Set Module Config Default
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
     * Set Module Config Defaults
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
    private function _setConfigDefaults() {


        /* ADDON_NAMESPACE
         *
         *
         */
        $this->setConfigDefault(
                'ADDON_NAMESPACE'
                , $this->getClassNamespace() . '_Addons'
        );

        /*
         * FILE_NAME_ADDON
         *
         * Long Description
         */
        $this->setConfigDefault(
                'FILE_NAME_ADDON'
                , 'Addon'
        );



        /*
         * DIR_NAME_MODULES
         *
         * Name of the Directory containing the modules
         */
        $this->setConfigDefault(
                'DIR_NAME_MODULES'
                , 'Modules'
        );

        /*
         * DIR_NAME_LIBS
         *
         * Name of the library that contains the plugins libraries
         */
        $this->setConfigDefault(
                'DIR_NAME_LIBS'
                , 'lib'
        );

        /*
         * FILE_NAME_PLUGIN
         *
         * The base file name of the plugin file. e.g.:plugin.php
         */
        $this->setConfigDefault(
                'FILE_NAME_PLUGIN'
                , 'plugin.php'
        );

        /* Disabled Modules
         *
         * Modules that should not be loaded
         * Usage Example:
         * $this->setConfig('disabled_modules',
          array('QueryVars','Shortcodes')
          );
         */
        $this->setConfigDefault(
                'DISABLED_MODULES'
                , array()
        );


        /* Always Enabled Regex Pattern
         *
         * Sets the regex pattern that allows matching modules to remain loaded even after the user selects 'disabled' from the plugin options. This allows the user to continue to acccess the admin options to re-enable the plugin.
         */
        $this->setConfigDefault(
                'ALWAYS_ENABLED_REGEX_PATTERN'
                , '/menu|admin/s'
        );


        /*
         * Disabled Addons
         *
         * An array of addon names that you dont want loaded
         */
        $this->setConfigDefault(
                'DISABLED_ADDONS'
                , array(
                )
        );


        /*
         * DEBUG
         *
         * An array of addon names that you dont want loaded
         */
        $this->setConfigDefault(
                'DEBUG'
                , false
        );

        /*
         * Query Variable that is white listed for use by the plugin
         */
        $this->setConfigDefault(
                'QUERY_VAR'
                , $this->getSlug()
        );
        /*
         * Query Variable value indicating the admin screen that provides
         * a custom editor for adding a new post
         */
        $this->setConfigDefault(
                'QV_ADD_POST'
                , 'add_post'
        );

        /*
         * Query Variable value indicating the admin screen that provides
         * a custom editor for editing a new post
         */
        $this->setConfigDefault(
                'QV_EDIT_POST'
                , 'edit_post'
        );

        /*
         * COMPRESS
         *
         * Enable / Disable ZLIB Compression
         * Uses ob_start('ob_gzhandler') if server supports zlib compression
         * Set to false if server does not support zlib
         *
         */
        $this->setConfigDefault(
                'COMPRESS'
                , true
        );

        /*
         * ALLOW_SHORTCODES
         *
         * Processes included files ( like templates ) for shortcodes. Default is true. Disabling improves performance.
         */
        $this->setConfigDefault(
                'ALLOW_SHORTCODES'
                , true
        );


        /*
         * Default Menu Position
         *
         * Provides a unique menu position
         */
        $this->setConfigDefault(
                'MENU_POSITION_DEFAULT'
                , '67.141592653597777777' . $this->getSlug()
        );

        /*
         * Relative Path Resources
         *
         * Path to the
         */
        $this->setConfigDefault(
                'DIR_NAME_RESOURCES'
                , '' //empty string for root directory of the plugin
        );

        /*
         * Relative Path Admin
         *
         * Path to the
         * relative paths never start with a slash, but always end with one
         */
        $this->setConfigDefault(
                'DIR_NAME_ADMIN'
                , 'admin' //root directory of the plugin
        );
    }

    /**
     * Add Persistent Action
     *
     * Behaves similarly to the WordPress add_action method, but persists through subsequent requests.
     * One application is during activation where an action must be taken at a certain point in your plugin's
     * execution (which occurs during a separate http request after activation) but must only be done once , after activation.
     *
     * @param $action_name The name of the action
     * @param mixed $method An array consisting of an object and a method, or a string consisting of just a method.
     * @return void
     */
    public function addPersistentAction($action_name, $method) {
        $persistent_actions = get_transient($this->getSlug() . '_persistent_actions');
        $persistent_actions[$action_name]['method'] = $method;
        $persistent_actions[$action_name]['action_taken'] = false; //tracks whether the action has been taken at least once. if so, it will be unset.
        set_transient($this->getSlug() . '_persistent_actions', $persistent_actions);
    }

    /**
     * Do Persistent Action
     *
     * Behaves similarly to the WordPress do_action method, but has access to actions that may have been added
     * from a previous http request. This is especially important during activation and other actions that occur
     * outside the normal plugin execution order.
     *
     * @param $action_name The name of the action
     * @return void
     */
    public function doPersistentAction($action_name) {
        $action_name = trim($action_name);
        $persistent_actions = get_transient($this->getSlug() . '_persistent_actions');
        $this->debug()->logVar('$persistent_actions = ', $persistent_actions);
        /*
         * Ignore doPersistentAction calls when the action hasnt been added.
         *
         * This is not an error, so dont raise one. Silently ignoring actions
         * is a way to allow adding actions.
         */
        /*
          $this->debug()->logVar('isset($persistent_actions[$action_name]) = ', isset($persistent_actions[$action_name]));
          $this->debug()->logVar('empty($persistent_actions) = ', empty($persistent_actions));
          $this->debug()->logVar('is_array($persistent_actions) = ', empty($persistent_actions));

          if (!is_array($persistent_actions) || (is_array($persistent_actions) && !empty($persistent_actions) && !isset($persistent_actions[$action_name])) || empty($persistent_actions)) {
          $this->debug()->log('Exiting doPersistentActions because couldnt find action');
          return;
          } else {
          $this->debug()->log('Found action...');
          }
         */
        if (!isset($persistent_actions[$action_name])) {
            $this->debug()->log('Exiting doPersistentActions because couldnt find action');
            return;
        } else {
            $this->debug()->log('Found action...');
        }



        $action = $persistent_actions[$action_name];


        if (is_array($action['method'])) {
            $object = $action['method'][0];
            $method = $action['method'][1];
            $this->debug()->log('Doing Persistent Action : ' . get_class($object) . '::' . $method);

            /*
             * call the method
             *
             * You can do this with call_user_function but you dont
             * get a good debug_backtrace when you do it that way
             *
             */
            $object->$method();
        } else {

            $function = $action['method'];
            $this->debug()->log('Doing Persistent Action : ' . $function);
            $function(); //call the function
        }


        /*
         * mark the action as already taken so we can unset it at the end of the request (see __destruct() method)
         */
        $persistent_actions[$action_name]['action_taken'] = true;
        set_transient($this->getSlug() . '_persistent_actions', $persistent_actions);
    }

    /**
     * Save Activation Error
     *
     * Saves buffer contents to a transient ( a WordPress database entry) which can be retrieved later on shutdown.
     * This allows you to see errors that occur during activation. It also allows you to see debug output
     * that occurs during activation
     *
     * @param none
     * @return void
     */
    public function save_activation_error() {
        set_transient($this->getSlug() . '_activation_error', ob_get_contents(), 5);
    }

    /**
     * Show Activation Extra Characters
     *
     * Shows any output that occurred during activation
     * @param none
     * @return void
     */
    public function show_activation_extra_characters() {


        $activation_error = get_transient($this->getSlug() . '_activation_error');

        if ($activation_error != '') {
            ?>


            <div class="updated">
                <p><strong>Unexpected Output generated during activation of plugin '<?php echo $this->getName(); ?>':</strong></p>
                <?php
                if ($this->DEBUG && $this->debug()->isOn()) {
                    echo '<p style="color:red" ><em>(Debugging is On and may be the reason you are seeing the \'unexpected output\' message.)</em></p>';
                }
                ?>
                <p style="border:gray solid 1px"><?php echo $activation_error; ?></p>
            </div>
            <?php
        }
    }

    /**
     * Get Admin Url
     *
     * Gets the url to the plugin's admin directory
     * Should never end in a slash to be consistent with WordPress
     *
     * @param none
     * @return string
     */
    public function getAdminUrl() {
        $dir_name = trim($this->DIR_NAME_ADMIN);
        if ($dir_name !== '') {
            $dir_name = '/' . $dir_name;
        }
        return ($this->getUrl() . $dir_name );
    }

    /**
     * Get Resource Url
     *
     * Gets the url to the plugin's resource directory
     * Should never end in a slash to be consistent with WordPress
     *
     * @param none
     * @return string
     */
    public function getResourceUrl() {
        $dir_name = trim($this->DIR_NAME_RESOURCES);
        if ($dir_name !== '') {
            $dir_name = '/' . $dir_name;
        }
        return ($this->getUrl() . $dir_name );
    }

    /**
     * Get Admin Directory
     *
     * Gets the absolute directory path to the plugin's admin directory
     * Should never end in a slash to be consistent with WordPress
     *
     * @param none
     * @return string
     */
    public function getAdminDirectory() {
        $dir_name = trim($this->DIR_NAME_ADMIN);
        if ($dir_name !== '') {
            $dir_name = '/' . $dir_name;
        }
        return ($this->getDirectory() . $dir_name );
    }

    /**
     * Get Resource Directory
     *
     * Gets the absolute directory path to the plugin's resource directory
     * Should never end in a slash to be consistent with WordPress
     *
     * @param none
     * @return string
     */
    public function getResourceDirectory() {
        $dir_name = trim($this->DIR_NAME_RESOURCE);
        if ($dir_name !== '') {
            $dir_name = '/' . $dir_name;
        }
        return ($this->getDirectory() . $dir_name );
    }

    protected $_post_helper;

    /**
     * Post Helper
     *
     * Provides access to the library of methods in the Post Helper class
     * @param none
     * @return Simpli_Frames_Basev1c2_Plugin_Module_Post
     */
    public function post() {

        if (is_null($this->_post_helper)) {
            $this->_post_helper = new Simpli_Frames_Basev1c2_Plugin_Post($this);
        }
        return $this->_post_helper;
    }

}

