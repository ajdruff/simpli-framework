<?php

/**
 * Plugin Helper Interface
 *
 * Helper classes that are dependent on the plugin to create them.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 */
interface Simpli_Hello_Basev1c2_Plugin_Helper_Interface {

    function __construct(Simpli_Hello_Basev1c2_Plugin $plugin);

    /**
     * Debug
     *
     * Returns the Plugin's debug object
     *
     * @param none
     * @return void
     */
    public function debug();

    /**
     * Get Plugin
     *
     * @return Simpli_Hello_Basev1c2_Plugin
     */
    public function plugin();


    /*
     * Config
     *
     * @param none
     * @return void
     */

    public function config();
    /*
     * Add Hooks
     * 
     * @param none
     * @return void
     */

    public function addHooks();
}

