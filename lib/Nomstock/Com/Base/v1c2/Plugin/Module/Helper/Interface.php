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
interface Simpli_Frames_Base_v1c2_Plugin_Module_Helper_Interface {

public function __construct(Simpli_Frames_Base_v1c2_Plugin_Module $module, Simpli_Frames_Base_v1c2_Plugin_Addon $addon = null);

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
     * @return Simpli_Frames_Base_v1c2_Plugin
     */
    public function plugin() ;




}