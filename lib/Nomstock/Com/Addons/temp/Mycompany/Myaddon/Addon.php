<?php

/**
 * Starter Template - Addon
 *
 * Use this as a template to create your own Addon class
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage Addons
 */
class Nomstock_Com_Addons_Mycompany_Myaddon_Addon extends Nomstock_Com_Base_v1c2_Plugin_Addon {

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
    }

}

?>