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
 * The Simpli framework was originally based on the WordPress plugin wordpress-https developed by Mike Ems Copyright 2012  Mike Ems  (email : mike@mvied.com).
 * Since 1.2.1, the code has been significantly re-written so as to make it virtually unrecognizable except for the basic module architecture, whose
 * implementation was included in the re-write.

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
 * Please see http://simpliwp.com/framework for information on how to get
 * started using the Simpli Framework for creating your next WordPress plugin.
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


/*
 * Debugging
 *
 * Debugging is handled by the Debug class.
 * To configure, go to Debug.php in your plugin directory (NOT the Base directory), and add
 * $this->turnOn();
 * to the config() method.
 * To turn it off, you can use turnOff() or simply rename Debug.php to _Debug.php . do not touch the base class which resides in the Simpli/BasevXcY directory.
 */



/*
 * Initialize Plugin
 * (Loads modules and settings)
 */
$simpli_hello->init();







//register_activation_hook(__FILE__, array('Simpli_Hello_Plugin', 'activatePlugin'));