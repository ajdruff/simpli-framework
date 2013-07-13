<?php

/**
 * Simpli Framework Loader Class
 *
 * Loads the base classes
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
// Check that the class exists before trying to use it
if (class_exists('Simpli_Basev1c0_Loader')) {
    return;
}

class Simpli_Basev1c0_Loader {

    /**
     * Plugin Name
     *
     * Plugin's Friendly Name to appear in displayed text to the user.
     *
     * @var string
     */
    private static $_base_class_version;

    /**
     * Plugin Name
     *
     * Plugin's Friendly Name to appear in displayed text to the user.
     *
     * @var string
     */
    private static $_plugin_slug;

    /**
     * Plugin File Path
     *
     * Absolute file path to the plugin.php that resides in the plugin's directory
     *
     * @var string
     */
    private static $_plugin_file_path;

    public static function load($plugin_slug, $plugin_file_path,$version) {



        self::setBaseClassVersion( $version);



        /*
         * Set the plugin slug and file path properties
         */

        self::setPluginSlug($plugin_slug);
        self::setPluginFilePath($plugin_file_path);


        /*
         * Register the class autoloader to point to this class's autoloader method
         */

        spl_autoload_register(array(__CLASS__, 'autoloader')); /* cant use self here since it will fail for some reason if you have different versions later */


        /*
         * Create the Plugin object
         * getClassNamespace() simply derives the namespace from the plugin slug that was passed to this method
         */
        $plugin_class = self::getClassNamespace() . '_Plugin';
        $plugin = new $plugin_class();


        /*
         * Set the properties of the new plugin object
         */

        $plugin->setSlug(self::getPluginSlug());
        $plugin->setBaseClassVersion(self::getBaseClassVersion());
        $plugin->setFilePath($plugin_file_path);





        return ($plugin);
    }

    /**
     * Set Base Class Version
     *
     * @param string $base_class_version
     * @return void;
     */
    private static function setBaseClassVersion($base_class_version) {
        self::$_base_class_version = $base_class_version;
    }

    /**
     * Get Base Class Version
     *
     * @param none
     * @return string
     */
    private static function getBaseClassVersion($template=null) {
        //return self::$_base_class_version;



                $version = self::$_base_class_version;



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
    private static function setPluginSlug($plugin_slug) {

        self::$_plugin_slug = strtolower($plugin_slug);
    }

    /**
     * Get Plugin Slug
     *
     * @param none
     * @return string
     */
    private static function getPluginSlug() {
        return self::$_plugin_slug;
    }

    /**
     * Get Plugin File Path
     *
     * @param none
     * @return string
     */
    public function getPluginFilePath() {
        return self::$_plugin_file_path;
    }

    /**
     * Set Plugin File Path
     *
     * @param string $plugin_file_path
     * @return void
     */
    public function setPluginFilePath($plugin_file_path) {
        self::$_plugin_file_path = $plugin_file_path;
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
         * e.g.: simpli_hello
         *
         */

        $namespace = self::getPluginSlug();

        /*
         * Convert to Title Case 'Simpli_Hello'
         */
        $array_namespace = explode('_', $namespace);
        $namespace = ucwords($array_namespace[0]) . '_' . ucwords($array_namespace[1]);

        return ($namespace);
    }


    /**
     * Class Autoloader
     *
     * @param string $class
     * @return void;
     */
    public static function autoloader($class) { //e.g. class= 'Simpli_Hello_Plugin'


        $namespaces = array(
            'Simpli_Base' . self::getBaseClassVersion('v{major}c{minor}')
            , self::getClassNamespace()
        );

//    echo '<pre>';
//print_r($namespaces);
//echo '</pre>';

        $matches = explode('_', $class); // alternative :  $pattern='/[A-Za-z0-9]+/';preg_match_all($pattern, $class, $matches);
//echo '<pre>';
//print_r($matches);
//echo '</pre>';

        if (in_array($matches[0] . '_' . $matches[1], $namespaces)) {  // match[0]='Simpli' match[1]='Hello' match[2]='Plugin'
            $filename = array_pop($matches) . '.php'; // get the last part of $class and use it as the name of the file
            $subdirectory_path = implode(DIRECTORY_SEPARATOR, $matches); // each part of the remaining string is the name of a subdirectory
// do not use the slower 'require_once' since autoload tracks loading. no use of DIRECTORY_SEPARATOR since require will translate
            require dirname(self::getPluginFilePath()) . '/lib/' . $subdirectory_path . '/' . $filename;
        }
    }

}