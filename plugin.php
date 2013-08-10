<?php

/**
  Plugin Name:   Simpli Hello
  Plugin URI:    http://simpliwp/simpli-framework
  Description:   The Simpli Hello plugin is a template for WordPress plugin developers to create WordPress plugins using the Simpli Framework. The Simpli framework is a WordPress Plugin Framework that makes building object oriented WordPress plugins just a bit easier.
  Author:        Andrew Druffner
  Version:       1.2.1
  Author URI:    http://simpliwp/about

  Text Domain:   simpli-hello
  Domain Path:   /languages/
  Simpli Framework Version: 1.2.0
  Simpli Base Class Version: 1.0
 *
 */
/*
  Simpli Framework Copyright 2013  Andrew Druffner  (email :andrew@nomstock.com)
 * The Simpli framwork was substantially based on the WordPress plugin wordpress-https developed by Mike Ems Copyright 2012  Mike Ems  (email : mike@mvied.com)

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * Get Started with the Plugin Framework
 * Please see framework-getting-started.html for information on how to Get Started
 *
 */




/*
 * Add framework bootstrapping code
 */

require(dirname(__FILE__) . '/lib/Simpli/Framework.php');

/*
 * Create the Plugin Object
 *
 * Usage: Simpli_Framework::load($plugin_slug, $plugin_file_path)
 *
 * must only be called from within this file
 * $plugin_slug must be universally unique, and consist of 2 lowercase words separated by an underscore
 * $plugin_file_path should always be __FILE__
 */

$simpli_hello = Simpli_Framework::load('simpli_hello', __FILE__);


/*
 * Configure Plugin
 *
 */


$simpli_hello->setName('Simpli Hello'); // Name should match the value of 'Plugin Name' in the comments at the top of this file);
$simpli_hello->setTextDomain('simpli-hello'); // TextDomain must *not* include underscores and uniquely identifies the language domain for your plugin
//(optional)
$simpli_hello->setDebug(
        array(
            'consolelog' => true  // true/false Turn on Logging to Javascript console for php logs
            , 'js' => true // true/false Turn on Logging to Javascript console for javascript logs
            , 'src' => false  // true/false Whether to use the full source for javascript or just the minimized versions
            , 'filelog' => false // true/false Turn on Logging to File for php logs
        )
);


/*
 * Initialize Plugin
 * (Loads modules and settings)
 */
$simpli_hello->init();



/*
 * Configure Modules - Must Occur After Plugin Initialization
 */

//e.g.:  $simpli_hello->getModule('Admin')->setMenuPosition ('67.141592653597777777');


/**
 *
 *  Register activation hook. Must be called outside of a class.
 *
 *
 */
register_activation_hook(__FILE__, array($simpli_hello, 'install'));

/**
 * Short Description
 *
 * Long Description
 * @param string $content The shortcode content
 * @return string The parsed output of the form body tag
 */
function testMyFunction($a = 1, $b = 2) {

    global $simpli_hello;
    $simpli_hello->getModule('Debug')->t(true, 99);
    //$simpli_hello->getModule('Debug')->t($always_debug = true, debug_backtrace(), 5);

 //   $simpli_hello->getModule('Debug')->dt(__LINE__, '', __FUNCTION__, __FILE__, true, debug_backtrace(), $levels = 3);
}
