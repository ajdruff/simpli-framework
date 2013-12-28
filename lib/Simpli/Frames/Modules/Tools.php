<?php

/**
 * Utility Module
 *
 * General Utility Functions
 * Add any methods that can be shared across modules here.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * 
 *
 */
class Simpli_Frames_Modules_Tools extends Simpli_Frames_Base_v1c2_Plugin_Module {

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

