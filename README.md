simpli-framework
================

## Synopsis
A simple WordPress plugin framework to more easily create WordPress plugins using an object oriented architecture.


## Motivation

I wanted a simple to use framework that would organize my code and handle admin panel settings, metaboxes,  post options, hooks and filters, and other common WordPress tasks.

An early version of this code was based on the work Mike Ems did on the WordPress HTTPs plugin. After downloading [WordPress HTTPS](http://wordpress.org/plugins/wordpress-https/) , I read through his code and thought it well orgamized and that it might make a solid foundation for the plugins I was developing.

Since then, the code has been significantly re-written with very few remnants of the original code.

## Differences from Mike's original code

The current codebase ( as of 1.2.1 ) is a complete re-write of Mike's code. After stripping out the WordPress HTTPS specific code, I rebuilt the  save/reset/setting handling, rebuilt the module loading routines, created the debug module, replaced the logging module with the debug module (providing deep debugging features with robust filtering and tracing capabilities), re-wrote all the major interfaces, re-wrote all javascript, created an 'Addon' capability that allows the creation of sub-plugins that allow you to easily copy functionality from one plugin to another (  the Simpli_Forms Addon is one example, providing a complete form handling and templating system ), added 'helper' classes such as the metabox class, and many other changes and additions. The significant contribution from the original code remains the basic concept of loading modules within a larger plugin architecture.

A useful and necessary addition was the creation of the bump and make shell scripts, which manage the creation of new plugins , using the Simpli Frames plugin as a template. This allows the ability of anyone to download the Simpli Frames plugin, run the scripts, and be able to immediately activate the newly created plugin to run along side the original Simpli Frames plugin from which it derived.

Some ideas being kicked around are the creation of development menus that will provide an easier user interface for the most common housekeeping activities while developing the plugin, including make, bump, and debug functionality.


## Installation

The framework installs as the 'Simpli Frames' WordPress Plugin, which is a demonstration WordPress plugin that you
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
