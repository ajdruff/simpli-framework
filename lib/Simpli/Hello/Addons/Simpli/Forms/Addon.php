<?php

/**
 * Simpli Forms Addon
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddons
 * @property array $DISABLED_MODULES An array of Module Names of the Addon that you don't want to have loaded
 * @property string $DIR_NAME_THEMES Name of the Directory that contains the themes
 * @property string $MODULE_NAME_ELEMENTS  Root Name of the Module that holds the Form Element Definitions
 * @property string $MODULE_NAME_FILTERS   Name of the Module that holds the Filter Definitions
 */
class Simpli_Hello_Addons_Simpli_Forms_Addon extends Simpli_Hello_Basev1c2_Plugin_Addon {

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

        /*
         * DISABLED_MODULES
         *
         * An array of Module Names of the Addon that you don't want to have loaded
         */
        $this->setConfig(
                'DISABLED_MODULES'
                , array(
            'Tags'
            , 'ElementsOld'
            , 'FormOld'
                )
        );

        /*
         * DIR_NAME_THEMES
         *
         * Name of the Directory that contains the themes
         */
        $this->setConfig(
                'DIR_NAME_THEMES'
                , 'Themes'
        );


        /*
         * MODULE_NAME_ELEMENTS
         *
         * Root Name of the Module that holds the Form Element Definitions
         */
        $this->setConfig(
                'MODULE_NAME_ELEMENTS'
                , 'Elements'
        );


        /*
         * MODULE_NAME_FILTERS
         *
         * Name of the Module that holds the Filter Definitions
         */
        $this->setConfig(
                'MODULE_NAME_FILTERS'
                , 'Filter'
        );
    }

    /**
     * Set Config Defaults
     *
     * Default Configurations that will be used if no settings are found in config()
     *
     * @param none
     * @return void
     */
    public function setConfigDefaults() {



        /*
         * DISABLED_MODULES
         *
         * An array of Module Names of the Addon that you don't want to have loaded
         */
        $this->setConfigDefault(
                'DISABLED_MODULES'
                , array(
                )
        );

        /*
         * DIR_NAME_THEMES
         *
         * Name of the Directory that contains the themes
         */
        $this->setConfigDefault(
                'DIR_NAME_THEMES'
                , 'Themes'
        );


        /*
         * MODULE_NAME_ELEMENTS
         *
         * Root Name of the Module that holds the Form Element Definitions
         */
        $this->setConfigDefault(
                'MODULE_NAME_ELEMENTS'
                , 'Elements'
        );


        /*
         * MODULE_NAME_FILTERS
         *
         * Name of the Module that holds teh Filter Definitions
         */
        $this->setConfigDefault(
                'MODULE_NAME_FILTERS'
                , 'Filter'
        );
    }


}

?>
