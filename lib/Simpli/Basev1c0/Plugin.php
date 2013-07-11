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
     * Plugin Version
     *
     * @var string
     */
    protected $_version;

    /**
     * Framwork Version
     *
     * @var string
     */
    protected $_framework_version;

    /**
     * Base Class Version
     *
     * @var string
     */
    protected $_base_class_version;

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
    public function getPluginUrl() {
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
     * Set Version
     *
     * @param string $version
     * @return object $this
     */
    public function setVersion($version) {
        $this->_version = $version;
        return $this;
    }

    /**
     * Get Version
     *
     * @param none
     * @return string
     */
    public function getVersion() {
        return $this->_version;
    }

    /**
     * Set Simpli Base Class Version
     *
     * @param string $version
     * @return object $this
     */
    public function setBaseClassVersion($version) {
        $this->_base_class_version = $version;

        return $this;
    }

    /**
     * Get Simpli Base Class Version
     *
     * Set by the Simpli_Framework load method when loading the plugin
     * @param none
     * @return string
     */
    public function getBaseClassVersion($flags = null) {

        $version = $this->_base_class_version;



        if (is_null($flags)) {

            $version = str_replace('v', '', $version);
            $version = str_replace('c', '.', $version);
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

        $version = null;

        /*
         * Since the framework is distributed as a plugin,
         * the framework's version *is* the version of the Simpli Framework plugin.
         * So check if the plugin *is* the framework. if it is, just
         * use the version of the plugin as the framework version
         */
        $plugin_file = plugin_basename($this->getFilePath());
        //   echo '<br> $plugin_file =  ' . $plugin_file;
        //   echo '<br> FilePath = ' .$this->getFilePath();
        if (strpos($plugin_file, 'simpli-framework') !== false) { //if the plugin is teh framework...
            //   echo 'this is the simpli framework plugin';
            $plugin_data = get_plugin_data($this->getFilePath());
            $version = $plugin_data['Version'];
        } else {
            /*
             * If viewing this function's source in a plugin that was created by the
             * Framework's make script, you will see a version number below which was put there by the framework's make script.
             * The make script replaced a placeholder
             * with the version number of the Framework that the script used to build the plugin.
             * If viewing the source from within the Simpli Framework  plugin, you'll see
             * the SIMPLI_FRAMEWORK_VERSION  placeholder instead.
             */
            $version = "__SIMPLI_FRAMEWORK_VERSION__";
        }

        return $version;
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
     * Init
     *
     * Performs basic housekeeping tasks and initializes all modules
     *
     * @param none
     * @return $this
     */
    public function init() {




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

        $this->getLogger()->log($this->getSlug() . ': Plugin URL: ' . $this->getPluginUrl());

        /**
         * Load Settings
         */
        $this->loadSettings();
        $this->getLogger()->log($this->getSlug() . ': Loading Settings ');

        /**
         * Load Modules
         */
        $this->loadModules(array(),'/menu|admin/s');


        /*
         *
         * Tell debugger plugin and class library loaded
         *
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


        $this->getLogger()->log($this->getSlug() . ': Loading Module ' . $module . ' from ' . __FILE__);

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

       public function loadModules($modules = array(),$exclusion_regex='') {



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

}