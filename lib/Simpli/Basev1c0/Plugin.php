<?php

/**
 * Base class for a WordPress plugin.
 *
 * @author Mike Ems
 * @author Andrew Druffner (Significantly re-wrote loadModule following code refactoring,loadSettings and saveSettings)
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
class Simpli_Basev1c0_Plugin {

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
     * Logger
     *
     * @var Simpli_Basev1c0_Logger_Interface
     */
    protected $_logger;

    /**
     * Plugin URL
     *
     * @var string
     */
    protected $_plugin_url;

    /**
     * Plugin Setting Defaults
     *
     * @var array
     */
    protected $_setting_defaults;

    /**
     * Plugin Settings
     *
     * @var array
     */
    protected $_settings = array();

    /**
     * Plugin Slug
     *
     * Used as a unique identifier for the plugin.
     *
     * @var string
     */
    protected $_slug;

    /**
     * Plugin Name
     *
     * Plugin's Friendly Name to appear in displayed text to the user.
     *
     * @var string
     */
    protected $_plugin_name;

    /**
     * Text Domain
     *
     * @var string
     */
    protected $_text_domain;

    /**
     * Plugin File Path
     *
     * @var string
     */
    protected $_plugin_file_path;

    /**
     * Activate Actions
     *
     * @var string
     */
    protected $_activate_actions = array();

    /**
     * Debug Options
     *
     * @var string
     */
    protected $_debug = array();

    /**
     * Utility Object
     *
     * @var string
     */
    protected $_tools;

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
     * Localize Variables
     *
     * holds variables to be added to javascript in format required by wp_localize
     *
     * @var array
     */
    protected $_local_vars = array();

    /**
     * Set Directory
     *
     * @param string $directory
     * @return object $this
     */
    public function setDirectory($directory) {
        $this->_directory = $directory;
        return $this;
    }

    /**
     * Get Directory
     *
     * @param none
     * @return string
     */
    public function getDirectory() {
        return $this->_directory;
    }

    /**
     * Set Module Directory
     *
     * @param string $module_directory
     * @return object $this
     */
    public function setModuleDirectory($module_directory) {
        $this->getLogger()->log($this->getSlug() . ': Setting module directory to ' . $module_directory);
        $this->_module_directory = $module_directory;
        return $this;
    }

    /**
     * Get Module Directory
     *
     * @param none
     * @return string
     */
    public function getModuleDirectory() {
        return $this->_module_directory;
    }

    /**
     * Get Available Modules
     *
     * @param none
     * @return array $modules
     */
    public function getAvailableModules() {
        $modules = array();



        $this->getLogger()->log($this->getSlug() . ': Module directory = ' . $this->getModuleDirectory());

        if (is_dir($this->getModuleDirectory()) && $module_directory = opendir($this->getModuleDirectory())) {
            while (false !== ($entry = readdir($module_directory))) {
                if (strpos($entry, '.') !== 0 && strpos($entry, '.php') !== false) {
                    $module = str_replace('.php', '', $entry);
                    if ($module != 'Interface') {
                        $modules[] = $module;
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
        $module = 'Module\\' . $module;
        if (isset($module)) {
            if (isset($this->_modules[$module])) {
                return $this->_modules[$module];
            }
        }

        die('LINE ' . __LINE__ . ' ' . __METHOD__ . ' Module not found: \'' . $module . '\'.');
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
     * Set Plugin Url
     *
     * @param string $plugin_url
     * @return object $this
     */
    public function setPluginUrl($plugin_url) {
        $this->_plugin_url = $plugin_url;
        return $this;
    }

    /**
     * Get Plugin Url
     *
     * @param none
     * @return string
     */
    public function getUrl() {
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
     * Get Version
     *
     *
     * @param none
     * @return string
     */
    public function getVersion() {


        $headers = array('Version' => 'Version');

        $plugin_file_data = get_file_data($this->getFilePath(), $headers, 'plugin');



        return $plugin_file_data['Version'];
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
     * Get Framework Version
     *
     * @param none
     * @return string
     */
    public function getFrameworkVersion() {



        $simpli_data = get_file_data($this->getFilePath(), array(), 'simpli');

        $framework_version = $simpli_data['Simpli Framework Version'];

        return $framework_version;
    }

    /**
     * Get Slug Parts
     *
     * @param none
     * @return object
     */
    public function getSlugParts() {

        /*
         * derive namespace from slug
         * return as object
         */

        $array_class = explode('_', $this->getSlug());

        $parts = new stdClass;
        $parts->prefix = $array_class[0];
        $parts->suffix = $array_class[1];

        return $parts;
    }

    /**
     * Get Class Namespace
     *
     * @param none
     * @return string
     */
    public function getClassNamespace() {

        /*
         * derive namespace from slug
         * just Title Case each word
         */

        $array_class = explode('_', $this->getSlug());
        $namespace = ucwords($array_class[0]) . '_' . ucwords($array_class[1]);
        return $namespace;
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

        foreach ($local_vars as $key=>$value) {
            if (isset($this->_local_vars[$key]) && is_array($this->_local_vars[$key])) {

                $this->_local_vars[$key]=array_merge($this->_local_vars[$key],$value);
            }
           else
            {
               $this->_local_vars[$key]=$value;
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


        $debug_defaults = array(
            'js' => false
            , 'consolelog' => false
            , 'src' => false
            , 'filelog' => false
        );

        $debug = array_merge($debug_defaults, $this->_debug);

        if (!is_null($key)) { //if key provided, return only a single element
            $result = $debug[$key];
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

        $valid_options = array('js', 'consolelog', 'filelog', 'src');

        /* Check for Validtity
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
        $debug = array_merge($this->getDebug(), $debug);

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

        if (!isset($this->_tools)) {

            $this->_tools = new Simpli_Basev1c0_Btools();
        }


        return $this->_tools;
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
            add_action('admin_print_footer_scripts', array(&$this, 'printLocalVars'));
            add_action('admin_print_footer_scripts', array(&$this, 'printInlineFooterScripts'));
        } else {
            add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
            add_action('wp_print_footer_scripts', array(&$this, 'printLocalVars'));
            add_action('wp_print_footer_scripts', array(&$this, 'printInlineFooterScripts'));
        }








        /*
         * Load the text domain
         * ref: http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
         */
        load_plugin_textdomain($this->getTextDomain(), false, dirname(plugin_basename($this->getFilePath())) . '/languages/');



        /*
         * set the directory of the Plugin          *
         */



        $this->setDirectory(dirname($this->getFilePath())); //e.g.: /home/username/public_html/wp-content/plugins/simpli-framework

        /*
         * set the Module Directory
         *         */

        $class_namespace_parts = $this->getClassNamespaceParts();



        $this->setModuleDirectory($this->getDirectory() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $class_namespace_parts[0] . DIRECTORY_SEPARATOR . $class_namespace_parts[1] . DIRECTORY_SEPARATOR . 'Module' . DIRECTORY_SEPARATOR); //e.g. /home/username/public_html/wp-content/plugins/simpli-framework/lib/simpli/hello/Module/

        $this->getLogger()->log($this->getSlug() . ': Plugin Directory: ' . $this->getDirectory());
        $this->getLogger()->log($this->getSlug() . ': Module Directory: ' . $this->getModuleDirectory());
        /*
         * Set the Plugin Url
         */

        $this->setPluginUrl(plugins_url('', $this->getDirectory() . '/plugin.php'));

        $this->getLogger()->log($this->getSlug() . ': Plugin URL: ' . $this->getUrl());




        /**
         * Load Settings
         */
        $this->loadSettings();
        $this->getLogger()->log($this->getSlug() . ': Loading Settings ');

        /**
         * Load Modules
         */
        $this->loadModules(array(), '/menu|admin/s');


        /*
         *
         * Tell debugger that the plugin and class library have been loaded
         */
        $this->getLogger()->log($this->getSlug() . ': Initializing Plugin ');
        $this->getLogger()->log($this->getSlug() . ': Loaded Base Class Library ' . ' from ' . dirname(__FILE__));

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


        $this->getLogger()->log($this->getSlug() . ': Loading Module ' . $module);

        $module_full = 'Module\\' . $module;  # Admin
        $filename = str_replace('\\', '/', $module);
        $filename = $filename . '.php'; # Admin.php


        /*
         *
         * Derive the namespace ('Simpli_Hello') from the class name
         *
         */
        $array_class_name = explode('_', get_class($this));  # Changes class Simpli_Hello_Plugin to [0]='Simpli' [1]='Hello'  [2]='Plugin'
        array_pop($array_class_name);  # $array_class_name =  [0]='Simpli' [1]='Hello'
        $namespace = implode('_', $array_class_name);  # $namespace =  Simpli_Hello



        require_once($this->getModuleDirectory() . $filename); # simpli-framework/lib/simpli/hello/Module/Admin.php


        $class = $namespace . '_Module_' . $module;
        if (!isset($this->_modules[$class]) || !is_object($this->_modules[$class]) || get_class($this->_modules[$class]) != $class) {
            try {
                $object = new $class;
                $this->setModule($module_full, $object);
                $this->getModule($module)->setPlugin($this);
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

            //if plugin was disabled in settings, load only those modules
            //with menu or admin in their name. that way we can still
            // configure the plugin but the rest of the plugins functionality
            // is disabled.
            if ($this->getSetting('plugin_enabled') == 'disabled') {
                $haystack = strtolower($module);

                if (preg_match_all($exclusion_regex, $haystack, $matches) < 1) {

                    // Skip Module
                    continue;
                }
            }

            $this->loadModule($module);
        }

//        echo 'plugin_enabled setting = ' ;
//print_r($this->getSettings());
        return $this;
    }

    /**
     * Unload Module
     *
     * @param string $module
     * @return $this
     */
    public function unloadModule($module) {
        if (strpos(get_class($this), '_') !== false) {
            $base_class = substr(get_class($this), 0, strpos(get_class($this), '_'));
        } else {
            $base_class = get_class($this);
        }
        $module = 'Module\\' . $module;

        $modules = $this->getModules();

        unset($modules[$module]);

        $this->_modules = $modules;

        return $this;
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
        array_push($this->_activate_actions, $action);
        return $this;
    }

    protected $_inline_script_queue = array();

    /**
     * Enqueue Inline Script
     *
     * Adds an inline script to a queue array that is later printed
     * @param string $handle - The handle of the script
     * @param string $src - The absolute path to the script
     * @para array $deps - An array of handles that the script is dependent on
     * @return array $this->_inline_script_queue The current array of queued scripts
     */
    public function enqueueInlineScript($handle, $path, $inline_deps=array(), $external_deps=array()) {

/*
 * Its necessary to set defaults for arrays here since doing so in the declaration
 * for some reason doesnt work and carries a null value through instead, which
 * will choke foreach statements later.
 *
 */
if (is_null($inline_deps)){$inline_deps=array();}
if (is_null($external_deps)){$external_deps=array();}
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

    /**
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
        $vars = array(
            'plugin' => array(
                'slugparts' => array(
                    'prefix' => $this->getSlugParts()->prefix
                    , 'suffix' => $this->getSlugParts()->suffix
                )
                , 'slug' => $this->getSlug()
                , 'name' => $this->getName()
                , 'url' => $this->getUrl()
                , 'version' => $this->getVersion()
                , 'directory' => $this->getDirectory()
                , 'debug' => $this->getDebug('js')
                , 'admin_url'=> get_admin_url()
                , 'nonce'=> wp_create_nonce($this->getSlug())
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