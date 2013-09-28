<?php

/**
 * Plugin Helper Interface
 *
 * Helper classes that are dependent on the plugin to create them.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
interface Simpli_Basev1c0_Plugin_Helper_Interface {


function __construct(Simpli_Basev1c0_Plugin $plugin);

    /**
     * Debug
     *
     * Returns the Plugin's debug object
     *
     * @param none
     * @return void
     */
    public function debug() ;




    /**
     * Get Plugin
     *
     * @return Simpli_Basev1c0_Plugin
     */
    public function plugin() ;




}