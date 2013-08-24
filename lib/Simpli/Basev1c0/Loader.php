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
// Check that the class exists before attempting to declare it again. its possible another plugin loaded it.
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



        $this->setBaseClassVersion($base_class_version);


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
         * Get the plugin data from the WordPress information header
         */

//   $headers=array('Version'=>'Version');
//   $plugin_file_data=get_file_data( $plugin_file_path,$headers,'plugin' );


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



//  $plugin->setVersion($plugin_file_data['Version']);  // Version is the version of your plugin and should match value of 'Version' in WordPress Information header
//    $plugin->setBaseClassVersion($this->getBaseClassVersion());
        $plugin->setFilePath($plugin_file_path);








        return ($plugin);
    }

    /**
     * Set Base Class Version
     *
     * @param string $base_class_version
     * @return void;
     */
    private function setBaseClassVersion($base_class_version) {
        $this->_base_class_version = $base_class_version;
    }

    /**
     * Get Base Class Version
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
         * e.g.: simpli_hello
         *
         */

        $namespace = $this->getPluginSlug();

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
    public function autoloader($class) { //e.g. class= 'Simpli_Hello_Plugin'
        $namespaces = array(
            'Simpli_Base' . $this->getBaseClassVersion('v{major}c{minor}')
            , $this->getClassNamespace()
        );


        /*
         * Example: class 'Simpli_Hello_Plugin' is referenced, the autoloader calls this function
         * the function explodes the class name by underscore into an array. Each element of the array is a word
         * it then looks to see if the first two words of the class name match any element in namespaces.
         * the two namespaces are 'Simpli_Base' and the Plugin's slug captialized.
         */
//echo '<br> class:' . $class;

        $matches = array();
        $matches = explode('_', $class); // alternative :  $pattern='/[A-Za-z0-9]+/';preg_match_all($pattern, $class, $matches);
//echo '<pre>';

//echo '</pre>';
//

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

//     $plugin_class_match = strpos($class, 'Simpli_Base') !== false;
//echo '<br/>' . __LINE__ . ' ' . __METHOD__ . ' $plugin_class_match =  ' . $plugin_class_match;
//   $base_class_match = strpos($class, $this->getClassNamespace()) !== false;
//echo '<pre>';

//echo '</pre>';
//echo '<br/>' . __LINE__ . ' ' . __METHOD__ . ' $base_class_match =  ' . $base_class_match;
        if (in_array($matches[0] . '_' . $matches[1], $namespaces)) {  // match[0]='Simpli' match[1]='Hello' match[2]='Plugin'
//   if ($plugin_class_match || $base_class_match) {
            $filename = array_pop($matches) . '.php'; // get the last part of $class and use it as the name of the file


            $subdirectory_path = implode('/', $matches); // each part of the remaining string is the name of a subdirectory
// do not use the slower 'require_once' since autoload tracks loading.
            $file = dirname($this->getPluginFilePath()) . '/lib/' . $subdirectory_path . '/' . $filename;
            $file = $path = str_replace('\\', '/', $file); //quick and dirty normalize path
            require $file; // do not use file_exists for performance reasons
//            if (file_exists($file)) {
//                echo '<br> Including class ' . $class . ' file  = ' . $file;
//                require $file;
//            }
        } else {
            //  echo '<br>$class ' . $class . ' was not a match';
            return;
        }
//  }
    }

}

/**
 * Class Autoloader
 *
 * @param string $class
 * @return void;
 */
// public function autoloader_old($class) { //e.g. class= 'Simpli_Hello_Plugin'
//
//
        //  echo '<br>' . debug_print_backtrace();
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Hello/Plugin.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Plugin.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Logger.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Logger/Interface.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Btools.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin/Module.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin\Module/Interface.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin/Menu.php');
//return;
//        include(dirname(__FILE__) . '/lib/Simpli\Hello/Plugin.php');
//        include(dirname(__FILE__) . '/lib/Simpli\Basev1c0/Plugin.php');
//        include(dirname(__FILE__) . '/lib/Simpli\Basev1c0/Logger.php');
//        include(dirname(__FILE__) . '/lib/Simpli\Basev1c0\Logger/Interface.php');
//        include(dirname(__FILE__) . '/lib/Simpli\Basev1c0/Btools.php');
//        include(dirname(__FILE__) . '/lib/Simpli\Basev1c0\Plugin/Module.php');
//        include(dirname(__FILE__) . '/lib/Simpli\Basev1c0\Plugin\Module/Interface.php');
//        include(dirname(__FILE__) . '/lib/Simpli\Basev1c0\Plugin/Menu.php');
//        $namespaces = array(
//            'Simpli_Base' . $this->getBaseClassVersion('v{major}c{minor}')
//            , $this->getClassNamespace()
//        );


/*
 * Example: class 'Simpli_Hello_Plugin' is referenced, the autoloader calls this function
 * the function explodes the class name by underscore into an array. Each element of the array is a word
 * it then looks to see if the first two words of the class name match any element in namespaces.
 * the two namespaces are 'Simpli_Base' and the Plugin's slug captialized.
 */
// echo '<br> class:' . $class;
//    $matches = explode('_', $class); // alternative :  $pattern='/[A-Za-z0-9]+/';preg_match_all($pattern, $class, $matches);
//echo '<pre>';

//echo '</pre>';
//
//if (isset($matches[0]) && ($matches[0]!='Simpli')) {echo '<br>$class foujnd to be not simpli';return;}
//try {
//    if (!isset($matches[1])||(empty($matches))){echo ' offset 1  not found for $class = ' . $class;return;}
//
//    $class_namespace=$matches[0] . '_' . $matches[1];
//echo '<br> class namespace = ' .$class_namespace;
//} catch (Exception $exc) {
//    die('exiting, class = ' . $class_namespace);
//    echo $exc->getTraceAsString();
//}
//     $plugin_class_match = strpos($class, 'Simpli_Base') !== false;
//echo '<br/>' . __LINE__ . ' ' . __METHOD__ . ' $plugin_class_match =  ' . $plugin_class_match;
//   $base_class_match = strpos($class, $this->getClassNamespace()) !== false;
//echo '<pre>';

//echo '</pre>';
//echo '<br/>' . __LINE__ . ' ' . __METHOD__ . ' $base_class_match =  ' . $base_class_match;
// if (in_array($matches[0] . '_' . $matches[1], $namespaces)) {  // match[0]='Simpli' match[1]='Hello' match[2]='Plugin'
//   if ($plugin_class_match || $base_class_match) {

//switch ($class) {
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Hello/Plugin.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Plugin.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Logger.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Logger/Interface.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Btools.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin/Module.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin\Module/Interface.php');
//        require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin/Menu.php');
//                case 'Simpli_Hello_Plugin':
//                   // $matches = array('Simpli', 'Hello', 'Plugin');
//                     require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Hello/Plugin.php');
//                    break;
//
//                case 'Simpli_Basev1c0_Plugin':
//                    //$matches = array('Simpli', 'Basev1c0', 'Plugin');
//                    require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Plugin.php');
//                    break;
//
//                case 'Simpli_Basev1c0_Logger':
//                    //$matches = array('Simpli', 'Basev1c0', 'Logger');
//                    require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Logger.php');
//                    break;
//
//                case 'Simpli_Basev1c0_Logger_Interface':
//                    require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Logger/Interface.php');
//                  //  $matches = array('Simpli', 'Basev1c0', 'Logger', 'Interface');
//                    break;
//
//
//                case 'Simpli_Basev1c0_Btools':
//                    require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0/Btools.php');
//                  //  $matches = array('Simpli', 'Basev1c0', 'Btools');
//                    break;
//
//                case 'Simpli_Basev1c0_Plugin_Module_Interface':
//
//                 //   $matches = array('Simpli', 'Basev1c0', 'Plugin', 'Module', 'Interface');
//                    require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin\Module/Interface.php');
//                    break;
//                case 'Simpli_Basev1c0_Plugin_Module':
//                    require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin/Module.php');
//                  //  $matches = array('Simpli', 'Basev1c0', 'Plugin', 'Module');
//                    break;
//
//                case 'Simpli_Basev1c0_Plugin_Menu':
//                    require('C:\wamp\www\wpdev.com\public_html\wp-content\plugins\simpli-framework/lib/Simpli\Basev1c0\Plugin/Menu.php');
//                 //   $matches = array('Simpli', 'Basev1c0', 'Plugin', 'Menu');
//                    break;
//
//                case 'WP_User_Search':
//                   // $matches = array('WP', 'User', 'Search');
//
//                    break;
//            }
//$matches=array();
//$matches = explode('_', $class);
//   $filename = array_pop($matches) . '.php'; // get the last part of $class and use it as the name of the file
//   $subdirectory_path = implode('/', $matches); // each part of the remaining string is the name of a subdirectory
// do not use the slower 'require_once' since autoload tracks loading.
//            $file = dirname($this->getPluginFilePath()) . '/lib/' . $subdirectory_path . '/' . $filename;
//            if (file_exists($file)) {
//                echo '<br> Including class ' . $class . ' file  = ' . $file;
//                require $file;
//            }
//        } else {
//            echo '<br>$class ' . $class . ' was not a match';
//            return;
//        }
//  }
//}
//
//}
//}