<?php

/**
 * Plugin Module Interface
 *
 * @author Mike Ems
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
interface Simpli_Basev1c0_Plugin_Module_Interface {

    /**
     * Initializes the module
     *
     * @param none
     * @return void
     */
    public function init();

    /**
     * Set Plugin
     *
     * @param Simpli_Basev1c0_Plugin $plugin
     * @return Simpli_Basev1c0_Plugin_Module
     * @uses Simpli_Basev1c0_Plugin
     */
    public function setPlugin(Simpli_Basev1c0_Plugin $plugin);

    /**
     * Get Plugin
     *
     * @param none
     * @return Simpli_Basev1c0_Plugin
     */
    public function getPlugin();
}