<?php

/**
 * Plugin Module Interface
 *
 * Each Module in the project will extend this base Module class.
 * Modules can be treated as independent plugins. Think of them as sub-plugins.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
interface Simpli_Basev1c0_Plugin_Module_Interface {



    /**
     * Get Addon
     *
     * Returns the addon of the module if set, otherwise it will return the addon
     * with the name that is passed as an argument
     *
     * @param none
     * @return void
     */
    public function getAddon($addon_name = null);

    /**
     * Set Addon Reference
     *
     * Set the Addon that the module is dependent on
     *
     * @param string $addon_name
     * @return object Addon
     */
    public function setAddon($addon_object);

    /**
     * Debug
     *
     * Returns the Plugin's debug object
     *
     * @param none
     * @return void
     */
    public function debug() ;

    /*
     * Get Slug ( Read Only )
     *
     * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
     * @param none
     * @return string
     */

    public function getSlug();

    /*
     * Get Name ( Read Only )
     *
     *  Returns the word after the last underscore in class name as the Module's name.
     * @param none
     * @return string
     */

    public function getName() ;

    /**
     * Add Hooks
     *
     * Initializes Module
     *
     * @param none
     * @return void
     */
    public function addHooks() ;

    /**
     * Init
     *
     * Initializes the Module
     *
     * @param none
     * @return void
     */
    public function init() ;

    /**
     *
     * Initializes the module when initialized in an Admin environment
     * @param none
     * @return void
     */
    public function initModuleAdmin() ;

    /**
     *
     * nitializes the module when initialized in a non-admin environment
     * @param none
     * @return void
     */
    public function initModule() ;

    /**
     * Set Plugin
     *
     * @param Simpli_Basev1c0_Plugin $plugin
     * @return object $this
     * @uses Simpli_Basev1c0_Plugin
     */
    public function setPlugin(Simpli_Basev1c0_Plugin $plugin) ;

    /**
     * Get Plugin
     *
     * @param none
     * @return Simpli_Basev1c0_Plugin
     */
    public function getPlugin() ;


    /**
     * Get Configuration (Magic Method)
     *
     * Return 'read only' properties using the $this->Property format.
     * You *can* add or edit these values by using the protected method $this->setConfig().
     * Returns read-only properties using a magic method __get
     * ref: http://stackoverflow.com/questions/2343790/how-to-implement-a-read-only-member-variable-in-php
     * @param none
     * @return void
     */
    public function __get($property_name) ;



}