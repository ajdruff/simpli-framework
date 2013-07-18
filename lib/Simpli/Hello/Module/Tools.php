<?php

/**
 * Utility Module
 *
 * General Utility Functions
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Tools extends Simpli_Basev1c0_Plugin_Module {




    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {
        /*
         * Module base class requires
         * setting Name first, then slug
         */
        $this->setName();
        $this->setSlug();


                  $this->getPlugin()->getLogger()->log($this->getPlugin()->getSlug() . ': initialized  module ' . $this->getName());
    }





}