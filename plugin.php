<?php

/**
Plugin Name:   Simpli Frames 
 Plugin URI:    http://example.com 
 Description:   The Simpli Frames plugin does some amazing stuff and was built upon the Simpli framework, a WordPress Plugin development framework that makes building WordPress plugins just a bit easier. 
 Author:        Author 
 Version:       1.0.0 
 Author URI:    http://example.com 
 Text Domain:   simpli_frames 
 Domain Path:   /languages/ 

  Simpli Framework Version:     1.3.1
  Simpli Base Class Version: 1.2
 *
 */
/*
  Simpli Framework Copyright 2013  Andrew Druffner  (email :andrew@nomstock.com)
 * The Simpli framework was originally based on the WordPress plugin wordpress-https developed by Mike Ems.
 * Since 1.2.1, the code has been significantly re-written so as to make it virtually unrecognizable except for the basic module architecture.

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

$simpli_frames = Simpli_Framework::load('simpli_frames', __FILE__);


/*
 * Configure Plugin
 *
 */


$simpli_frames->setName('Simpli Frames'); // Name should match the value of 'Plugin Name' in the comments at the top of this file);
$simpli_frames->setTextDomain('simpli-frames'); // TextDomain must *not* include underscores and uniquely identifies the language domain for your plugin


/*
 * Debugging
 *
 * Debugging is handled by the Debug class.
 * To turn on debugging, go to: lib/Simpli
 * $this->turnOn();
 * to the config() method.
 * To turn it off, you can use turnOff() or simply rename Debug.php to _Debug.php . do not touch the base class which resides in the Simpli/Basev1c2XcY directory.
 */



/*
 * Initialize Plugin
 * (Loads modules and settings)
 */
$simpli_frames->init();
