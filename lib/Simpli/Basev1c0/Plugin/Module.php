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

    /* Initalizes Module by branching to child initModule and initModuleAdmin
     *
     *
     * Wrapper around initModuleAdmin and initModule. By using a wrapper we provide backward compatibility and an easier interface  child classes since they dont
     * need to remember to add the extra 'if is_admin()' logic and to help them more clearly differentiate the initialization,
     * as well providing a cleaner interface to the Plugin class which can use just init()
     *
     * @param none
     * @return string
     */

    public function init() {

        if (is_admin()) {

            $this->initModuleAdmin();
        } else {

            $this->initModule();
        }

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