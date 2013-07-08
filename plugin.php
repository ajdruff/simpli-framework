<?php

/**
  Plugin Name:   Simpli Hello
  Plugin URI:    http://simpliwp/simpli-framework
  Description:   The Simpli Hello plugin is a demonstration plugin to be used as a template for WordPress plugin developers. The Simpli framework is a WordPress Plugin Framework to help developers build WordPress plugins.
  Author:        Andrew Druffner
  Version:       1.0.1
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
 * dev_note: Please see framework-getting-started.html for information on how to Get Started
 *
 */

/*
 *
 * Configure
 *
 */









define('SIMPLI_HELLO_SLUG', 'simpli_hello'); //dev_note: Replace the value with a short underscore delimited name of your plugin


define('SIMPLI_HELLO_SLUG_PREFIX', substr(SIMPLI_HELLO_SLUG,0,stripos ( SIMPLI_HELLO_SLUG , '_' ))); //dev_note: Value must match the name of file lib/Hello/Hello.php
define('SIMPLI_HELLO_SLUG_SUFFIX',substr(SIMPLI_HELLO_SLUG,stripos ( SIMPLI_HELLO_SLUG , '_' )+1,strlen(SIMPLI_HELLO_SLUG)-1) ); //dev_note: Value must match the name of file lib/Hello/Hello.php
define('SIMPLI_HELLO_TEXTDOMAIN', 'simpli-hello'); //dev_note: Value must include no underscores




define('SIMPLI_HELLO_MENU_POSITION', '67.141592653597777777' . SIMPLI_HELLO_SLUG); //dev_note: Menu Position . The default provided here will nearly always work and not conflict with another plugin as long as your slug is unique. You can make it anything you want though and it must be universally unique or menu wont load if conflicts with another plugin's position.  Ref: http://codex.wordpress.org/Function_Reference/add_menu_page#Parameters
//exit if wordpress isn't properly installed
if (!defined('ABSPATH'))
    die('Cannot Load Plugin - WordPress installation not found');



// load required wordpress classes that might not be loaded by the default installation
if (!class_exists('WP_Http'))
    include_once( ABSPATH . WPINC . '/class-http.php' );


//add our text domain
load_plugin_textdomain(SIMPLI_HELLO_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');

//add the names of your classes to the namespaces array so the class file is included
//todo: can we add this elsewhere so we dont contaminate the global namespace?
function simpli_hello_autoloader($class) { //e.g. class= 'Simpli_Hello_Plugin'
    $base_class_version='v1c0'; //dont change name or update this manually - controlled by the bump script. must match the vXcX part of the Simpli/BasevXcX directory
    $namespaces = array(
        'Simpli_Base' . $base_class_version
        , 'Simpli_Hello'
    );





//    $pattern='/[A-Za-z0-9]+/';

//    echo '<br> class = ' .$class;
#preg_match_all($pattern, $class, $match);
$matches=explode('_',$class); // alternative :  $pattern='/[A-Za-z0-9]+/';preg_match_all($pattern, $class, $matches);
//echo '<pre>';
//print_r($matches);
//echo '</pre>';

    if (in_array($matches[0]. '_' .$matches[1], $namespaces)) {  // match[0]='Simpli' match[1]='Hello' match[2]='Plugin'
        $filename=array_pop($matches).'.php'; // get the last part of $class and use it as the name of the file
        $subdirectory_path=implode(DIRECTORY_SEPARATOR,$matches); // each part of the remaining string is the name of a subdirectory
       # $filename =  $match[3] . '.php'; // e.g. : Plugin.php
        #$subdirectory_path= $match[1] . DIRECTORY_SEPARATOR . $match[2] ; // e.g.: /Simpli/Hello
       # $filename = str_replace('_', DIRECTORY_SEPARATOR, $match[0]) . '.php';

        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib'  .DIRECTORY_SEPARATOR .  $subdirectory_path .DIRECTORY_SEPARATOR .  $filename;
    }



}


spl_autoload_register(SIMPLI_HELLO_SLUG . '_autoloader');




/*
 *
 * Create a global Plugin object with a unique name equal
 * to your plugin's slug. This helps to prevent conflict with other plugins.
 *
 */


$simpli_hello = new Simpli_Hello_Plugin();
$simpli_hello->setSlug('simpli_hello');
$simpli_hello->setVersion('1.0.0'); //Value should match value of 'Version' in the comments at the top of this file.
$simpli_hello->setName('Simpli Hello'); // Value should match value of 'Plugin Name' in the comments at the top of this file);

// Initialize Plugin
$simpli_hello->init();







/**
 *
 *  Register activation hook. Must be called outside of a class.
 *
 *
 */
register_activation_hook(__FILE__, array($simpli_hello, 'install'));