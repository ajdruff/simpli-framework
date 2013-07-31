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
/*
 * Theme Directory name
 */
$this->setProperty('DIR_NAME_THEMES','Themes');
/*
 * Root Name of the Module that holds the Form Element Definitions
 */
$this->setProperty('MODULE_NAME_ELEMENTS','Elements');

/*
 * Name of the Module that holds the Filter Definitions
 */
$this->setProperty('MODULE_NAME_FILTERS','Filter');




    }

}

?>
