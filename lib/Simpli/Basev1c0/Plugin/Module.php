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
class Simpli_Basev1c0_Plugin_Module implements Simpli_Basev1c0_Plugin_Module_Interface {

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
    protected $_addon = null;

    /**
     * Get Addon
     *
     * Returns the addon of the module if set, otherwise it will return the addon
     * with the name that is passed as an argument
     *
     * @param none
     * @return void
     */
    public function getAddon($addon_name = null) {


        if (is_null($addon_name)) {
            return $this->_addon;
        }

        /* else, if not null, pass it onto the getPlugin->getAddon() method */
        return $this->getPlugin()->getAddon($addon_name);
    }

    /**
     * Set Addon Reference
     *
     * Set the Addon that the module is dependent on
     *
     * @param string $addon_name
     * @return object Addon
     */
    public function setAddon($addon_object) {

        $this->_addon = $addon_object;
    }

   protected $_debug = null;

    /**
     * Debug
     *
     * Returns a debug object
     *
     * @param none
     * @return void
     */
    public function debug() {

        if (is_null($this->_debug)) {
  $this->_debug = $this->getPlugin()->getModule('Debug');
//            if ($this->getPlugin()->isModuleLoaded('Debug')) {
//                $this->_debug = $this->getPlugin()->getModule('Debug');
//            } else {
//                $this->_debug = null;
//            }
        }
        return $this->_debug;
    }

    /*
     * Get Slug ( Read Only )
     *
     * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
     * @param none
     * @return string
     */

    public function getSlug() {

        if (!isset($this->_slug)) {
            /*
             * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
             * for for MyModule.php , moduleName=MyModule , moduleSlug='my_module'
             * http://stackoverflow.com/q/8611617
             */
            $regex = '/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/';

//            if (!isset($this->_name)) {
//
//                $this->setName();
//            }

            $slug = strtolower(preg_replace($regex, '_$1', $this->getName()));

            $this->_slug = $slug;
        }
        return $this->_slug;
    }

    /*
     * Get Name ( Read Only )
     *
     *  Returns the word after the last underscore in class name as the Module's name.
     * @param none
     * @return string
     */

    public function getName() {

        if (!isset($this->_name)) {
            $class = get_class($this);
            $array_class = explode('_', $class);


            $module_name = end($array_class);

            $this->_name = $module_name;
        }

        return $this->_name;
    }



    /**
     * Add Hooks
     *
     * Initializes Module
     *
     * @param none
     * @return void
     */
    public function addHooks() {

    }

    /**
     * Init
     *
     * Initializes the Module
     *
     * @param none
     * @return void
     */
    public function init() {

        $this->addHooks();
        $this->config();
    }

    /**
     *
     * Initializes the module when initialized in an Admin environment
     * @param none
     * @return void
     */
    public function initModuleAdmin() {
        throw new Exception('No initModuleAdmin method in ' . get_class($this));
    }

    /**
     *
     * nitializes the module when initialized in a non-admin environment
     * @param none
     * @return void
     */
    public function initModule() {
        throw new Exception('No initModule method in ' . get_class($this));
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