<?php

/**
 * Simpli Forms Addon
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddons
 */
class Simpli_Addons_Simpli_Forms_Addon extends Simpli_Basev1c0_Addon {

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks. Function is called during addon initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();
    }

    /**
     * Configure Addon
     *
     * Add any Addon configuration code here
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();





        // $this->setDisabledModule('FilterOptions');

        $this->setDisabledModule('Tags');
        $this->setDisabledModule('ElementsOld');
        $this->setDisabledModule('FormOld');
//        $this->setDisabledModule('Filter');
     //   $this->setDisabledModule('FilterExample');
    //    $this->setDisabledModule('FilterSettings');
        //      $this->setDisabledModule('FilterOptions');
        /*
         * Theme Directory name
         */
        $this->setProperty('DIR_NAME_THEMES', 'Themes');
        /*
         * Root Name of the Module that holds the Form Element Definitions
         */
        $this->setProperty('MODULE_NAME_ELEMENTS', 'Elements');

        /*
         * Name of the Module that holds the Filter Definitions
         */
        $this->setProperty('MODULE_NAME_FILTERS', 'Filter');


    }



    /**
     * Load Modules
     *
     * Load all modules
     * @author Andrew Druffner
     * @param array $module_directory The directory path that holds the modules that you want to load
     * @return $this
     */
    public function loadModulesOld_EnabledModules($module_directory) {
        $this->debug()->t();


        $enabled_modules = $this->getEnabledModules($module_directory);


        if (!is_array($enabled_modules)) {
            return array();
        }



        foreach ($enabled_modules as $module_name => $module_path) {

            $this->loadModule($module_name);
        }
    }

        /**
     * Load Modules
     *
     * Load all modules
     * @author Andrew Druffner
     * @param array $modules
     * @param string $exclusion_regex Regex pattern in the form '/menu|admin/s' to exclude modules from loading
     * @return $this
     */
    public function loadModules($module_directory) {
        $this->debug()->t();

$old_enabled_modules=$this->getAvailableModules();
        $enabled_modules = $this->getAvailableModules($module_directory,'enabled');


        if (!is_array($enabled_modules)) {
            return array();
        }


        if (is_array($old_enabled_modules)) {
           $new_enabled_modules=array_diff($enabled_modules,$old_enabled_modules);
        }else {

            $new_enabled_modules=$enabled_modules;
        }





        foreach ($new_enabled_modules as $module_name => $module_path) {

            $this->loadModule($module_name);
        }

        return $new_enabled_modules;
    }


    private $_enabled_modules;


    /**
     * Get Available Modules ( Read Only )
     *
     * Returns a 2 dimensional array with the module file paths of the files that reside in the Modules Directory
     * The dimensions of the array include 'type' and the module's name.
     * Type can be 'enabled' 'disabled' 'always_enabled'
     *
     * @param string $filter 'enabled', 'all', 'disabled', 'always_enabled' .
     * enabled are those that are permitted to be loaded
     * disabled are those that appear within the '_disabled_modules' array
     * 'always_active' are those modules not in the 'disabled' array that will load despite the plugins disabled setting
     *
     * @return arrayReadOnly
     */
    public function getAvailableModules($module_directory=null,$filter = 'enabled') {
        $this->debug()->t();
        /*
         * if no directory is provided, its assumed that all you want is the
         * current list of modules, and you dont need to add any new modules
         * from a directory (such as occurs when loading modules), so just return the current list
         * this allows you to check the enabled modules list without iterating through a directory, which
         * makes this check faster.
         */


        $this->debug()->logVar('$module_directory = ', $module_directory);
        if (is_null($module_directory)) {
            $this->debug()->log('$module_directory is null, returning');
            return $this->_available_modules[$filter];
        }

  /*
 * if enabled modules is null, make it into an array
 */
        if (is_null($this->_available_modules)) {
            $this->_available_modules=array();
        }



            $tools = $this->getPlugin()->getTools();

            /*
             * Find all the Module files in the module directory
             */

            $module_files = $tools->getGlobFiles($module_directory, '*.php', false);

            /*
             * if no files to load, return the existing available modules
             */

            if (!is_array($module_files)) {
                $this->debug()->log('$module_files is not array, returning current module filters array');
                return $this->_available_modules[$filter];
            }

            /*
             * Iterate through each of the files checking to see which filter they belong to
             */
            foreach ($module_files as $module_file_path) {
                $module_name = basename($module_file_path, '.php');

                /*
                 * If the plugin settings have a 'disabled' setting,
                 * check if the module should still be enabled per the regex
                 * this is intended to allow admin menus to persist so
                 * settings can be accessed even though the rest of the plugin
                 * is disabled
                 */
                If (in_array($module_name, $this->getDisabledModules())) {

                    $this->_available_modules['disabled'][$module_name] = $module_file_path;
                } else {

                   $this->_available_modules['enabled'][$module_name] = $module_file_path;
                }

                $this->_available_modules['all'][$module_name] = $module_file_path;
            }

            $this->debug()->logVar('$this->_available_modules = ', $this->_available_modules);
            $this->debug()->logVars(get_defined_vars());


        return $this->_available_modules[$filter];
    }

     /**
     * Get Enabled Modules ( Read Only )
     *
     * Returns an associative array containing the file paths of enabled modules.
      * The index of the array is the module name, and the value is the module's
      * file path. If a $module_directory argument is provided, then
      * it will search the directory for files ending in .php and add
      * those paths to the array, deriving the module name from the file's name.
      * Note that if this function is called repeatedly with different file
      * paths for the module directory, it can overwrite elements with the same
      * module name with the new path. This may or may not be desired behavior.
      * In the case of themes, this can be very useful since you can extend
      * loaded modules easily by simply dropping a module with the same name
      * within a theme directory and then loading that directory's modules.
     *
     * @param string $module_directory - file path of the directory where the modules are located
     *
     * @return arrayReadOnly The array of modules
     */
    protected function getEnabledModulesOld($module_directory=null) {

        $this->debug()->t();

        /*
         * if no directory is provided, its assumed that all you want is the
         * current list of modules, and you dont need to add any new modules
         * from a directory (such as occurs when loading modules), so just return the current list
         * this allows you to check the enabled modules list without iterating through a directory, which
         * makes this check faster.
         */
        if (is_null($module_directory)) {
            return $this->_enabled_modules;
        }


/*
 * if enabled modules is null, make it into an array
 */
        if (is_null($this->_enabled_modules)) {
            $this->_enabled_modules=array();
        }


            $tools = $this->getPlugin()->getTools();

            /*
             * Find all the Module files in the module directory
             */

            $module_files = $tools->getGlobFiles($module_directory, '*.php', false);

/*
 * return exiting $this->_modules if no modules found in directory
 */
            if (!is_array($module_files)) {

                return $this->_enabled_modules;
            }

            /*
             * Iterate through each of the files and adding it to enabled modules unless its been disabled
             */
            foreach ($module_files as $module_file_path) {
                $module_name = basename($module_file_path, '.php');

                /*
                 * If the module has been disabled by the plugin's settings,
                 * dont add it to the result
                 */
                If (!in_array($module_name, $this->getDisabledModules())) {

                   $this->_enabled_modules[$module_name] = $module_file_path;
                } else {

                    $this->debug()->log('Module '.$module_name.' has been disabled, so it has not been added to the getEnabledModules result');

                }

            }


            $this->debug()->logVar('$this->_enabled_modules = ', $this->_enabled_modules);


        return $this->_enabled_modules;
    }
 /**
     * Load Module
     *
     * Takes the module name  and loads the associated file.
     * e.g.: 'Admin' loads from '/simpli/hello/Module/Admin.php'
     *
     * @author Andrew Druffner
     * @param string $module_name
     * @return $this
     */
    public function loadModuleOldUsingEnabledModule($module_name) {

        $this->debug()->t();


/*
 * get current list of enabled modules so we can validate that the module_name
 * has been enabled
 */
        $enabled_modules = $this->getEnabledModules();







        /*
         * check to see if the module_name is enabled; if not, return.
         */
        if (!is_array($enabled_modules) || !isset($enabled_modules[$module_name])) {
            $this->debug()->log('unable to load Module ' . $module_name . ' , since it is an inactive module');
            return;
        }





        /*
         * Include the class file
         */




        $module_file_path = $enabled_modules[$module_name];

        require_once($module_file_path); # simpli-framework/lib/simpli/hello/Module/Admin.php

        /*
         * Derive the class from the module file path
         */


        $relative_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getAddonsDirectory(), $module_file_path);
        $module_namespace = str_replace('/', '_', dirname($relative_path));


        $class = $this->getClassNamespace() . '_' . $module_namespace . '_' . $module_name; //
//  die('<br>' . __LINE__ . 'exiting to check class, $class = ' . $class);

        /*
         * Create the module object and attach it to $_modules
         */
        if (!isset($this->_modules[$module_name]) || !is_object($this->_modules[$module_name]) || get_class($this->_modules[$module_name]) != $class) {
            try {

                $object = new $class;
                $this->_modules[$module_name]= $object;
                $this->getModule($module_name)->setPlugin($this->getPlugin()); //set the plugin dependency
                $this->getModule($module_name)->setAddon($this); //set the addon dependency

                $this->debug()->log('Loaded Addon Module ' . $this->getSlug() . '/' . $module_name);
                $this->debug()->logVars(get_defined_vars());
            } catch (Exception $e) {
                die('Unable to load Module: \'' . $module_name . '\'. ' . $e->getMessage());
                $this->debug()->logVars(get_defined_vars());
            }
        }else
        {

           $this->debug()->log('Addon Module  ' . $this->getSlug() . '/' . $module_name . ' already loaded');
           $this->debug()->logVars(get_defined_vars());
        }

        return $this;
    }


   /**
     * Load Module
     *
     * Takes the module name  and loads the associated file.
     * e.g.: 'Admin' loads from '/simpli/hello/Module/Admin.php'
     *
     * @author Andrew Druffner
     * @param string $module_name
     * @return $this
     */
    public function loadModule($module_name) {

        $this->debug()->t();



        $available_modules = $this->getAvailableModules();







        /*
         * check to see if the module_name is enabled; if not, return.
         */
        if (!is_array($available_modules) || !isset($available_modules[$module_name])) {
            $this->debug()->log('unable to load Module ' . $module_name . ' , since it is an inactive module');
            $this->debug()->logVars(get_defined_vars());
            return;
        }





        /*
         * Include the class file
         */




        $module_file_path = $available_modules[$module_name];

        require_once($module_file_path); # simpli-framework/lib/simpli/hello/Module/Admin.php

        /*
         * Derive the class from the module file path
         */


        $relative_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getAddonsDirectory(), $module_file_path);
        $module_namespace = str_replace('/', '_', dirname($relative_path));


        $class = $this->getClassNamespace() . '_' . $module_namespace . '_' . $module_name; //
//  die('<br>' . __LINE__ . 'exiting to check class, $class = ' . $class);

        /*
         * Create the module object and attach it to $_modules
         */
 //       if (!isset($this->_modules[$module_name]) || !is_object($this->_modules[$module_name]) || get_class($this->_modules[$module_name]) != $class) {
            try {

                $object = new $class;

                $this->_modules[$module_name] = $object;
                $this->getModule($module_name)->setPlugin($this->getPlugin()); //set the plugin dependency
                $this->getModule($module_name)->setAddon($this); //set the addon dependency
//$this->getModule($module_name)->init();
                $this->debug()->log('Loaded Addon Module ' . $this->getSlug() . '/' . $module_name);
                $this->debug()->logVars(get_defined_vars());
            } catch (Exception $e) {
                die('Unable to load Module: \'' . $module_name . '\'. ' . $e->getMessage());
                $this->debug()->logVars(get_defined_vars());
            }
  //      }else
  //      {

   //        $this->debug()->log('Addon Module  ' . $this->getSlug() . '/' . $module_name . ' already loaded');
   //        $this->debug()->logVars(get_defined_vars());
   //     }

        return $this;
    }

}
?>
