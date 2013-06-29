<?php

/**
  Plugin Name:   Simpli Hello
  Plugin URI:    http://simpliwp/simpli-framework
  Description:   The Simpli Hello plugin is a demonstration plugin to be used as a template for WordPress plugin developers. The Simpli framework is a WordPress Plugin Framework to help developers build WordPress plugins.
  Author:        Andrew Druffner
  Version:       1.0.0
  Author URI:    http://simpliwp/about

  Text Domain:   simpli-hello
  Domain Path:   /languages/
 */
/*
  Copyright 2013  Andrew Druffner  (email :andrew@nomstock.com)
 * The Simpli framwork was substantially based on the WordPress plugin wordpress-https developed by Mike Ems Copyright 2012  Mike Ems  (email : mike@mvied.com)

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * dev_note: Please see framework-getting-started.html for information on how to Get Started
 *
 */

/*
 *
 * Configure
 *
 */


define ('SIMPLI_HELLO_NAME','Simpli Hello'); //dev_note: Value should match value of 'Plugin Name' in the comments at the top of this file.
define('SIMPLI_HELLO_VERSION', '1.0.0'); //dev_note: Value should match value of 'Version' in the comments at the top of this file.
define ('SIMPLI_HELLO_SHORTNAME','Hello'); //dev_note: Value must match the name of file lib/Hello/Hello.php
define ('SIMPLI_HELLO_TEXTDOMAIN','simpli-hello'); //dev_note: Value must include no underscores
define ('SIMPLI_HELLO_SLUG','simpli_hello'); //dev_note: Replace the value with a short underscore delimited name of your plugin
define('SIMPLI_HELLO_DEBUG', false); //dev_note:true or false to turn on logging to error log and browser javascript console.
define('SIMPLI_HELLO_MENU_POSITION', 89.8); //dev_note: Menu Position . See as reference. Must be universally unique or menu wont load if conflicts with another plugin's position.  Ref: http://codex.wordpress.org/Function_Reference/add_menu_page#Parameters




//exit if wordpress isn't properly installed
if (!defined('ABSPATH'))
    exit;




// load required wordpress classes that might not be loaded by the default installation
if (!class_exists('WP_Http'))
    include_once( ABSPATH . WPINC . '/class-http.php' );


//add our text domain
load_plugin_textdomain(SIMPLI_HELLO_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');

//add the names of your classes to the namespaces array so the class file is included
//todo: can we add this elsewhere so we dont contaminate the global namespace?
function simpli_hello_autoloader($class) {
    $namespaces = array(
        SIMPLI_HELLO_SHORTNAME
    );
    if (preg_match('/([A-Za-z]+)_?/', $class, $match) && in_array($match[1], $namespaces)) {
        $filename = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . $filename;
    }
}

spl_autoload_register(SIMPLI_HELLO_SLUG . '_autoloader');




/*
 * todo: What is this ? get rid of or find alternate.
 * 
 */
if (defined('WP_UNINSTALL_PLUGIN')) {
    return;
}

$classname=SIMPLI_HELLO_SHORTNAME; //needed so we can instantiate the object using a variable classname. using a constant as classname wont work.
$simpli_hello = new $classname();
$simpli_hello->setSlug(SIMPLI_HELLO_SLUG);
$simpli_hello->setVersion(SIMPLI_HELLO_VERSION);
$simpli_hello->setName(SIMPLI_HELLO_NAME);
$simpli_hello->setLogger(Hello_Logger::getInstance());
$simpli_hello->getLogger()->log(' Starting ' . $simpli_hello->getName() . ' Debug Log');

$simpli_hello->setDirectory(dirname(__FILE__));
$simpli_hello->setModuleDirectory(dirname(__FILE__) . '/lib/'.SIMPLI_HELLO_SHORTNAME.'/Module/');


$simpli_hello->getLogger()->log('Version: ' . $simpli_hello->getVersion());


/**
 * Load Settings
 */
$simpli_hello->loadSettings();

/**
 * Load Modules
 */
$simpli_hello->loadModules();

// Initialize Plugin
$simpli_hello->init();
$simpli_hello->setPluginUrl(plugins_url('', __FILE__));


/**
 *
 * Show debug info
 *
 */
if (SIMPLI_HELLO_DEBUG) {


    add_action('shutdown', 'simpli_hello_print_log');
}

/**
 *
 * Dumps all Logger entries to the browser's javascript console and to a log file
 *
 */
//todo: push this elsewhere outside the global namespace?
function simpli_hello_print_log() {
    global $simpli_hello;
    //echo $simpli_hello->getLogger()->consoleLog();
  //  $simpli_hello->getLogger()->fileLog($simpli_hello->getDirectory() . '/error.log.txt');
}

/**
 *
 *  Register activation hook. Must be called outside of a class.
 *
 *
 */
register_activation_hook(__FILE__, array($simpli_hello, 'install'));