<?php

/**
 * Utility Module
 *
 * General Utility Functions
 * Add any methods that can be shared across modules here.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Modules_Tools extends Simpli_Hello_Basev1c0_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();
    }

}

