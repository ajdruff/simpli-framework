<?php

/**
 * Addon Interface
 *

 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 * @property string $DISABLED_MODULES An array of Module Names of the Addon that you don't want to have loaded
 */
Interface Nomstock_Com_Base_v1c2_Plugin_Addon_Interface {

    public function __construct($plugin);

    /**
     * Get Plugin
     *
     * @param none
     * @return Nomstock_Com_Base_v1c2_Plugin
     */
    public function plugin();

    public function setConfigDefaults();

    /**
     * Get Addon File Path
     *
     * Returns the path to the file that contains the addon class.
     * @param none
     * @return string
     */
    public function getFilePath();
}

