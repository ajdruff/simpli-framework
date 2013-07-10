<?php

/**
 * Simpli Framework Class
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
// Check that the class exists before trying to use it
if (class_exists('Simpli_Framework')) {
    return;
}

class Simpli_Framework {

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

    public static function load($plugin_slug, $plugin_file_path, $base_class_version) {



        self::setPluginSlug($plugin_slug);
        self::setPluginFilePath($plugin_file_path);
        self::setBaseClassVersion($base_class_version);





        spl_autoload_register('self::autoloader');

        /*
         * derive namespace from the plugin slug and use it to instantiate the plugin
         */
        $plugin_class = self::getClassNamespace() . '_Plugin';
        $plugin = new $plugin_class();

        $plugin->setSlug(self::getPluginSlug());
        $plugin->setFrameworkVersion($base_class_version);
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
    private static function getBaseClassVersion() {
        return self::$_base_class_version;
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


    public static function autoloader($class) { //e.g. class= 'Simpli_Hello_Plugin'
        $namespaces = array(
            'Simpli_Base' . self::getBaseClassVersion()
            , self::getClassNamespace()
        );

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