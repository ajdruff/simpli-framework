<?php

/**
 * Plugin Module
 *
 * Each Module in the project will extend this base Module class.
 * Modules can be treated as independent plugins. Think of them as sub-plugins.
 *
 * @author Mike Ems
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
abstract class Simpli_Basev1c0_Plugin_Module implements Simpli_Basev1c0_Plugin_Module_Interface {

    /**
     * Plugin object that this module extends
     *
     * @var Simpli_Basev1c0_Plugin
     */
    protected $_plugin;

    /**
     * Module Slug
     *
     * @var Module Slug
     */
    protected $_slug;

    /**
     * Module Name
     *
     * @var Module Name
     */
    protected $_name;

    /**
     * Set Slug
     *
     * @param string $slug
     * @return object $this
     */
    public function setSlug() {
        /*
         * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
         * for for MyModule.php , moduleName=MyModule , moduleSlug='my_module'
         * http://stackoverflow.com/q/8611617
         */
        $regex = '/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/';

        if (!isset($this->_name)) {

            $this->setName();
        }

        $slug = strtolower(preg_replace($regex, '_$1', $this->getName()));


        $this->_slug = $slug;
    }

    /*
     * Get Slug
     *
     * @param none
     * @return string
     */

    public function getSlug() {

        if (!isset($this->_slug)) {
            throw new Exception('Must first setSlug() in Module\'s init method before calling ' . __METHOD__);
        }
        return $this->_slug;
    }

    /**
     * Set Name
     *
     * @param none
     * @return object $this
     */
    public function setName() {

        /*
         * Get the last part of a string separated by underscores
         * Note that this prevents a module from having an underscore
         * in its name
         * It must be Mycompany_Myplugin_Module_MyModuleName
         * Not:
         * Mycompany_Myplugin_Module_My_Module_Name
         */




        $class = get_class($this);
        $array_class = explode('_', $class);


        $module_name = end($array_class);

        $this->_name = $module_name;
        return $this;
    }

    /*
     * Get Name
     *
     * @param none
     * @return string
     */

    public function getName() {

        if (!isset($this->_name)) {
            throw new Exception('Must first setName() in Module\'s init method before calling ' . __METHOD__);
        }
        return $this->_name;
    }

    /**
     *
     * Initializes the module
     * @param none
     * @return void
     */
    public function init() {
        throw new Exception('No init method in ' . get_class($this));
    }

    /**
     * Set Plugin
     *
     * @param Simpli_Basev1c0_Plugin $plugin
     * @return object $this
     * @uses Simpli_Basev1c0_Plugin
     */
    public function setPlugin(Simpli_Basev1c0_Plugin $plugin) {
        $this->_plugin = $plugin;
        return $this;
    }

    /**
     * Get Plugin
     *
     * @param none
     * @return Simpli_Basev1c0_Plugin
     */
    public function getPlugin() {
        if (!isset($this->_plugin)) {
            die('Module ' . __CLASS__ . ' missing Plugin dependency.');
        }

        return $this->_plugin;
    }

}