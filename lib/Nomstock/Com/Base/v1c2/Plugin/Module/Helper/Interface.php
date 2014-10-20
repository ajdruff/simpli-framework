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
interface Nomstock_Com_Base_v1c2_Plugin_Module_Helper_Interface {

public function __construct(Nomstock_Com_Base_v1c2_Plugin_Module $module, Nomstock_Com_Base_v1c2_Plugin_Addon $addon = null);

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
     * @return Nomstock_Com_Base_v1c2_Plugin
     */
    public function plugin() ;




}