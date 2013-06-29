simpli-framework
================

## Synopsis
A simple WordPress plugin framework to quickly and easily create WordPress plugins using an object oriented architecture.


## Motivation

This framework is based on the work of Mike Ems  (email : mike@mvied.com). After downloading his [WordPress HTTPS](http://wordpress.org/plugins/wordpress-https/) plugin,
I read through his code and thought it well orgamized and that it might make a great foundation for the plugins I was developing. 

## Differences from Mike's original code
Besides stripping out the application specific code, I rebuilt the  save/reset/setting handling so all settings are now stored in
one database record in wp_options. I also added a Shortcodes module and added some housekeeping features to the modules.

One major addition is the use of the Simpli Framework Base class library. This library is the formerly named 'Mvied' classes. Because WordPress
does not have a clean way of sharing libraries across pluguins ( excluding namespace management only available in PHP 5.3) , 
I leveraged the WordPress 'must use' diectory to centrally locate the shared library and built a registration mechanism through which
installed plugins can negotiate which version of framework they use.

## Installation

The framework installs as the 'Simpli Hello' WordPress Plugin, which is a demonstration WordPress plugin that you 
can then modify and build off to create your plugin.

Therefore, install it as you would any other WordPress plugin.

Using the built-in plugin installer:

    1. Go to Plugins > Add New.
   1.  Under Search, type in the name of the WordPress Plugin or descriptive keyword, author, or tag in the search form or click a tag link below the search form.
    1. Find the WordPress Plugin you wish to install.
        1.Click Details for more information about the Plugin and instructions you may wish to print or save to help setup the Plugin.
        2. Click Install Now to install the WordPress Plugin. 
    1. A popup window will ask you to confirm your wish to install the Plugin.
    1. If this is the first time you've installed a WordPress Plugin, you may need to enter the FTP login credential information. If you've installed a Plugin before, it will still have the login information. This information is available through your web server host.
    1. Click Proceed to continue with the installation. The resulting installation screen will list the installation as successful or note any problems during the install.
    1. If successful, click Activate Plugin to activate it, or Return to Plugin Installer for further actions. 



## Contributors

Andrew Druffner @simpliwp

## License

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
