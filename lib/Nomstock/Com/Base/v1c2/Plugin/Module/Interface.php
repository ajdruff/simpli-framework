<?php

/**
 * Plugin Module Interface
 *
 * Each Module in the project will extend this base Module class.
 * Modules can be treated as independent plugins. Think of them as sub-plugins.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 */
interface Simpli_Frames_Base_v1c2_Plugin_Module_Interface {



    /**
     * Get Addon
     *
     * Returns the addon of the module if set, otherwise it will return the addon
     * with the name that is passed as an argument
     *
     * @param none
     * @return void
     */
    public function addon($addon_name = null);


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
     * Get Plugin
     *
     * @return Simpli_Frames_Base_v1c2_Plugin
     */
    public function plugin() ;



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