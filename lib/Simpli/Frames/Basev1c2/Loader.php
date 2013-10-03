<?php

/**
 * Simpli Framework Loader Class
 *
 * Loads the base classes
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 */
// Check that the class exists before attempting to declare it again. its possible another plugin loaded it.
if (class_exists('Simpli_Frames_Basev1c2_Loader')) {
    return;
}

class Simpli_Frames_Basev1c2_Loader {

    /**
     * Plugin Name
     *
     * Plugin's Friendly Name to appear in displayed text to the user.
     *
     * @var string
     */
    private $_base_class_version;

    /**
     * Plugin Slug
     *
     * A short unque identifier for the plugin.
     *
     * @var string
     */
    private $_plugin_slug;

    /**
     * Plugin File Path
     *
     * Absolute file path to the plugin.php that resides in the plugin's directory
     *
     * @var string
     */
    private $_plugin_file_path;

    public function load($plugin_slug, $plugin_file_path, $base_class_version) {





        $this->setBasev1c2ClassVersion($base_class_version);



        /*
         * Register the class autoloader to point to this class's autoloader method
         */

        spl_autoload_register(array($this, 'autoloader'));


        /*
         * Set the plugin slug and file path properties
         */

        $this->setPluginSlug($plugin_slug);
        $this->setPluginFilePath($plugin_file_path);











        /*
         * Create the Plugin object
         * getClassNamespace() simply derives the namespace from the plugin slug that was passed to this method
         */
        $plugin_class = $this->getClassNamespace() . '_Plugin';
        $plugin = new $plugin_class();


        /*
         * Set the properties of the new plugin object
         */

        $plugin->setSlug($this->getPluginSlug());

        /**
         *
         *  Register activation hook.
         *
         *
         */
        register_shutdown_function(array($plugin, 'shutdown'));

        register_activation_hook($plugin_file_path, array($plugin, 'activatePlugin'));

        register_deactivation_hook($plugin_file_path, array($plugin, 'deactivatePlugin'));



        $plugin->setFilePath($plugin_file_path);








        return ($plugin);
    }

    /**
     * Set Basev1c2 Class Version
     *
     * @param string $base_class_version
     * @return void;
     */
    private function setBasev1c2ClassVersion($base_class_version) {
        $this->_base_class_version = $base_class_version;
    }

    /**
     * Get Basev1c2 Class Version
     *
     * @param none
     * @return string
     */
    private function getBaseClassVersion($template = null) {
//return $this->_base_class_version;



        $version = $this->_base_class_version;



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
     * Set Plugin Slug
     *
     * @param string $plugin_slug
     * @return void;
     */
    private function setPluginSlug($plugin_slug) {

        $this->_plugin_slug = strtolower($plugin_slug);
    }

    /**
     * Get Plugin Slug
     *
     * @param none
     * @return string
     */
    private function getPluginSlug() {
        return $this->_plugin_slug;
    }

    /**
     * Get Plugin File Path
     *
     * @param none
     * @return string
     */
    public function getPluginFilePath() {
        return $this->_plugin_file_path;
    }

    /**
     * Set Plugin File Path
     *
     * @param string $plugin_file_path
     * @return void
     */
    public function setPluginFilePath($plugin_file_path) {
        $this->_plugin_file_path = $plugin_file_path;
    }

    /**
     * Get Class Namespace - Read Only
     *
     * @param none
     * @return string
     */
    private function getClassNamespace() {

        /*
         * Get the lowercase class plugin slug
         * e.g.: simpli_frames
         *
         */

        $namespace = $this->getPluginSlug();

        /*
         * Convert slug to Title Case 'Mycompany_Myplugin'
         */
        $array_namespace = explode('_', $namespace);
        $namespace = ucwords($array_namespace[0]) . '_' . ucwords($array_namespace[1]);

        return ($namespace);
    }

    /**
     * Class Autoloader
     *
     * Loads the classes dynamically as each object is instanced. Reference the autoloader documentation at php.net for more information.
     *  Example: When an object of class 'Simpli_Frames_Plugin' is asked to be created by your code, the autoloader calls this function
     * the function explodes the class name into an array using the underscore as a delimiter. Each element of the array is a word
     * it then looks to see if the first two words of the class name match any element in namespaces.
     * The namespaces are made up of a ucword version of the slug + the version number (for the base classes)
     *
     * @param string $class
     * @return void;
     */
    public function autoloader($class) { //e.g. class= 'Simpli_Frames_Plugin'
        $base_class_version = $this->getBaseClassVersion('v{major}c{minor}');
        //  echo('<br>$base_class_version = ' . $base_class_version );




        $namespaces = array(
            $this->getClassNamespace() . '_' . $base_class_version //base class namespace
            , $this->getClassNamespace() //core class namespace
        );



        $matches = array();
        $matches = explode('_', $class); // alternative :  $pattern='/[A-Za-z0-9]+/';preg_match_all($pattern, $class, $matches);


        try {
            if (!isset($matches[1]) || (empty($matches))) {
                echo ' offset 1  not found for $class = ' . $class;
                return;
            }

            $class_namespace = $matches[0] . '_' . $matches[1];
        } catch (Exception $exc) {
            die('exiting, class = ' . $class_namespace);
            echo $exc->getTraceAsString();
        }


        if (in_array($matches[0] . '_' . $matches[1], $namespaces)) {  // match[0]='Simpli' match[1]='Hello' match[2]='Plugin'
            $filename = array_pop($matches) . '.php'; // get the last part of $class and use it as the name of the file



            $subdirectory_path = implode('/', $matches); // each part of the remaining string is the name of a subdirectory
// do not use the slower 'require_once' since autoload tracks loading.
            $file = dirname($this->getPluginFilePath()) . '/lib/' . $subdirectory_path . '/' . $filename;
            $file = $path = str_replace('\\', '/', $file); //quick and dirty normalize path
            //  echo '<br> $file path = ' . $file;
            // die('<br>exiting ' . __LINE__ . __FILE__);
            $require_result = require $file; // do not use file_exists for performance reasons

            if (!class_exists($class) && (stripos($class, 'Interface') === false)) {


                echo '<div ><em style="color:red;">Class File and Class Name Mismatch. </em> Check to make sure that the file name for ' . basename($file) . ' is included in its class\'s declared name .</div>';
                ;
            }
        } else {
            //  echo '<br>$class ' . $class . ' was not a match';
            return;
        }
    }

}

