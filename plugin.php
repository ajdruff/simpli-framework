<?php

/**
  Plugin Name:   Simpli Hello
  Plugin URI:    http://simpliwp/simpli-framework
  Description:   The Simpli Hello plugin is a template for WordPress plugin developers to create WordPress plugins using the Simpli Framework. The Simpli framework is a WordPress Plugin Framework that makes building object oriented WordPress plugins just a bit easier.
  Author:        Andrew Druffner
  Version:       1.0.2
  Author URI:    http://simpliwp/about

  Text Domain:   simpli-hello
  Domain Path:   /languages/
  Simpli Framework Version: 1.0.2
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


$simpli_hello->setVersion('1.0.0'); // Version is the version of your plugin and should match value of 'Version' in the comments at the top of this file.
$simpli_hello->setName('Simpli Hello'); // Name should match the value of 'Plugin Name' in the comments at the top of this file);
$simpli_hello->setTextDomain('simpli-hello'); // TextDomain must *not* include underscores and uniquely identifies the language domain for your plugin



//(optional)
$simpli_hello->getLogger()->setLoggingOn(true); //Set to true to dump debugging logs to javascript console and to the error log.default is false



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

//
//add_filter('extra_simpli_headers','simpli_add_extra_headers');
//add_filter('extra_plugin_headers','simpli_add_extra_headers');
//
//
//function simpli_add_extra_headers($extra_headers){
//        	$extra_headers = array(
//            'Simpli Framework Version',
//            'Simpli Base Class Version'
//
//	);
//    return ($extra_headers);
//}
//
//
//if (is_admin()){
//
//
//
//
//    	$default_headers = array(
//		'Name' => 'Plugin Name',
//		'PluginURI' => 'Plugin URI',
//		'Version' => 'Version',
//		'Description' => 'Description',
//		'Author' => 'Author',
//		'AuthorURI' => 'Author URI',
//		'TextDomain' => 'Text Domain',
//		'DomainPath' => 'Domain Path',
//		'Network' => 'Network',
//		// Site Wide Only is deprecated in favor of Network.
//		'_sitewide' => 'Site Wide Only',
//               'SimpliFrameworkVersion'=>'Simpli Framework Version',
//           'SimpliBaseClassVersion'=>'Simpli Base Class Version'
//
//	);
//
////            	$default_headers = array(
////
////
////            'SimpliFrameworkVersion'=>'Simpli Framework Version',
////            'SimpliBaseClassVersion'=>'Simpli Base Class Version'
////
////	);
//
//
//        $plugin_file=__FILE__;
//        //$plugin_file=dirname(__FILE__) . '/lib/Simpli/Framework/plugin-data.php';
// $plugin_data = get_file_data( $plugin_file, $default_headers ,'plugin' );
//
// $simpli_data=get_simpli_data(__FILE__);
//echo '<pre>';
//print_r($plugin_data);
//echo '<pre>';
//echo '<pre>';
//print_r($simpli_data);
//echo '<pre>';

//}

