<?php

/**
 * Addon Interface
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
  * @property string $DISABLED_MODULES An array of Module Names of the Addon that you don't want to have loaded
 */
Interface Simpli_Basev1c0_Plugin_Addon_Interface {

   public  function __construct($plugin) ;




    /**
     * Get Plugin
     *
     * @param none
     * @return Simpli_Basev1c0_Plugin
     */
    public function plugin() ;


public function setConfigDefaults() ;


}

