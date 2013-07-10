<?php

/**
  Plugin Name:   Simpli Hello
  Plugin URI:    http://simpliwp/simpli-framework
  Description:   The Simpli Hello plugin is a demonstration plugin to be used as a template for WordPress plugin developers. The Simpli framework is a WordPress Plugin Framework to help developers build WordPress plugins.
  Author:        Andrew Druffner
  Version:       1.0.1
  Framework Version: Simpli Framework v1c1
  Author URI:    http://simpliwp/about

  Text Domain:   simpli-hello
  Domain Path:   /languages/
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

  You should have received a copy of the GNU General Public License
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

require(dirname(__FILE__) . '/lib/Simpli/Framework/Framework.php');

/*
 * Create the Plugin Object
 *
 * Usage: Simpli_Framework::load($plugin_slug, $plugin_file_path, $base_class_version)
 *
 * must only be called from within this file
 * $plugin_slug must be universally unique, and consist of 2 lowercase words separated by an underscore
 * $plugin_file_path should always be __FILE__
 * $base_class_version must match the name of the 'vXcY' part of the Simpli/BasevXcY directory name and generally should remain unchanged
 */

$simpli_hello = Simpli_Framework::load('simpli_hello', __FILE__,'v1c0');



/*
 * Configure Plugin
 *
 */


$simpli_hello->setVersion('1.0.0'); // Version is the version of your plugin and should match value of 'Version' in the comments at the top of this file.
$simpli_hello->setName('Simpli Hello'); // Name should match the value of 'Plugin Name' in the comments at the top of this file);
$simpli_hello->setTextDomain('simpli-hello'); // TextDomain must *not* include underscores and uniquely identifies



//(optional)
$simpli_hello->getLogger()->setLoggingOn(true); //Set to true to dump debugging logs to javascript console and to the error log.



/*
 * Initialize Plugin
 * (Loads modules and settings)
 */
$simpli_hello->init();



/*
 * Configure Modules - Must Occur After Plugin Initialization
 */

//(optional) $simpli_hello->getModule('Admin')->setMenuPosition ('67.141592653597777777');




//echo '<pre>';
//print_r($simpli_hello);
//echo '</pre>';



/**
 *
 *  Register activation hook. Must be called outside of a class.
 *
 *
 */
register_activation_hook(__FILE__, array($simpli_hello, 'install'));


     function simpli_hello_plugin_action_links($links) {
        echo 'firing action links';
        //$links[] = 'Framework Version:' . $this->getPlugin()->getFrameworkVersion();
        $links['Wow'] = '<a href="#">This is a link</a>';
        return $links;
    }

