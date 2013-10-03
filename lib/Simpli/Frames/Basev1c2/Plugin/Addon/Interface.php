<?php

/**
 * Addon Interface
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
  * @property string $DISABLED_MODULES An array of Module Names of the Addon that you don't want to have loaded
 */
Interface Simpli_Frames_Basev1c2_Plugin_Addon_Interface {

   public  function __construct($plugin) ;




    /**
     * Get Plugin
     *
     * @param none
     * @return Simpli_Frames_Basev1c2_Plugin
     */
    public function plugin() ;


public function setConfigDefaults() ;


}

