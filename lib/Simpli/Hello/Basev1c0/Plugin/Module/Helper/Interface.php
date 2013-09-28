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
interface Simpli_Hello_Basev1c0_Plugin_Module_Helper_Interface {

public function __construct(Simpli_Hello_Basev1c0_Plugin_Module $module, Simpli_Hello_Basev1c0_Plugin_Addon $addon = null);

    /**
     * Get Addon
     *
     * Returns the addon of the module if set.
     *
     * @param none
     * @return void
     */
    public function addon();


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
     * @return Simpli_Hello_Basev1c0_Plugin
     */
    public function plugin() ;




}