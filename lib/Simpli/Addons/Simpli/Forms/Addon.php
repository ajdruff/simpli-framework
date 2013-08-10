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
        $this->setDisabledModule('FilterExample');
        $this->setDisabledModule('FilterSettings');
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
     * Load Modules (Override)
     *
     * Load all modules
     * @author Andrew Druffner
     * @param array $modules
     * @param string $exclusion_regex Regex pattern in the form '/menu|admin/s' to exclude modules from loading
     * @return $this
     */
    public function loadModules($module_directory = null) {
        $this->debug()->t();


        if (is_null($module_directory)) {
            parent::loadModules();
            return;
        }
        $enabled_modules = $this->getAvailableModules('enabled', $module_directory);

        if (!is_array($enabled_modules)) {
            return array();
        }



        foreach ($enabled_modules as $module_name => $module_path) {

            $this->loadModule($module_name);
        }
    }

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
    private $_sub_modules = null;

    public function getAvailableModules($filter = 'enabled', $module_directory = null) {
        $this->debug()->t();


        if (is_null($module_directory)) {
            parent::getAvailableModules($filter);
            $addon_main_modules = $this->_available_modules;
            if (is_null($this->_sub_modules)) {
//                $this->_sub_modules = array();
//                $this->_sub_modules['enabled'] = array();
//                $this->_sub_modules['disabled'] = array();
//                $this->_sub_modules['all'] = array();
                return($addon_main_modules[$filter]);
            }



            // return;
        } else {
            $available_modules = array();

            $tools = $this->getPlugin()->getTools();

            /*
             * Find all the Module files in the module directory
             */

            $module_files = $tools->getGlobFiles($module_directory, '*.php', false);


            if (!is_array($module_files)) {
                return;
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

                    $available_modules['disabled'][$module_name] = $module_file_path;
                } else {

                    $available_modules['enabled'][$module_name] = $module_file_path;
                }

                $available_modules['all'][$module_name] = $module_file_path;
            }
            $this->_sub_modules = $available_modules;

        }

        $addon_main_modules = $this->_available_modules;

        $all_available_modules = array();

        $all_available_modules['enabled'] = array_merge($addon_main_modules['enabled'], $this->_sub_modules['enabled']);
        $all_available_modules['disabled'] = array_merge($addon_main_modules['disabled'], $this->_sub_modules['disabled']);
        $all_available_modules['all'] = array_merge($addon_main_modules['all'], $this->_sub_modules['all']);





        return $all_available_modules[$filter];
    }

}

?>
