<?php

echo '<br> loading theme module';

/**
 * Form Theme Module
 *
 * Provides Form Helper Methods
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Addons_Simpli_Forms_Module_Theme extends Simpli_Basev1c0_Plugin_Module {

    private $_templates = null;
    private $_template = null;
    private $_theme_directory = null;
    private $_theme = null;

    const DEFAULT_THEME = 'default';

    /**
     * Get Templates
     *
     * @param none
     * @return array $this->_templates
     */
    public function getTemplates() {
        if (is_null($this->_templates)) {
            echo '<br> templates are null, resetting to empty array';
            $this->_templates = array();
        }
        return $this->_templates;
    }

    /**
     * Get Template
     *
     * @param none
     * @return string
     */
    public function getTemplate($template_id) {


        if (!is_null($this->_templates[$template_id])) {
            $result = $this->_templates[$template_id];
        } else {
            $result = NULL;
        }

        echo '<br>$templates = <pre>', print_r($this->_templates, true), '</pre>';

        return $result;
    }

    /**
     * Set Template
     *
     * @param string $something
     * @return object $this
     */
    public function setTemplate($template_name, $template) {

        echo '<br> setting template for ' . $template_name;
        $this->_templates[$template_name] = $template;

        return $this;
    }
    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {

        // add_action($this->getAddon()->_slug . '_init',array($this,'loadTheme'));
    }

//    /**
//     * Short Description
//     *
//     * Long Description
//     *
//     * @param none
//     * @return void
//     */
//    public function loadTheme(args) {
//
//
//    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {

        $this->setTheme('Seattle');

       $this->getAddon()->loadModules($this->getThemeDirectory() . '/Module');

        $this->loadTemplates();

        $addon_modules = $this->getAddon()->getModules();
        echo 'Active Addon Modules: <pre>', print_r(array_keys($addon_modules), true), '</pre>';
    }

    /**
     * Load Templates
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function loadTemplates() {

        //todo: replace this with a glob pattern so there are no harcoded supported tags
        $supported_tags = array(
            'text'
            , 'text2'
            , 'select'
            , 'radio'
            , 'textarea'
            , 'checkboxes'
        );

        echo '<br/>(' . __LINE__ . ' ' . __METHOD__ . ')<br><strong style="color:blue;"> Loading Templates</strong>';

        foreach ($supported_tags as $tag) {

            $template_path = $this->getThemeDirectory() . '/templates/' . $tag . '.template.php';

            if (!file_exists($template_path)) {
                   echo '<br> skipping file . ' . $template_path;
                continue; //skip if no template
            }
              echo '<br> loading file . ' . $template_path;
            ob_start();
            /*
             * temporarily define a conveniance function
             * todo: consider adding a special file called 'common.functions.php' to be included with
             * each template that would provide each with common functions
             */
   //         $e = 'simpli_framework_echo';
            include($template_path);
            $template = ob_get_clean();


//
//
            $this->setTemplate($tag, $template);


        }
           echo '<pre>', print_r($this->getTemplates(), true), '</pre>';
    }

    /**
     * Get Theme Directory
     *
     * @param none
     * @return string $_theme_directory
     */
    public function getThemeDirectory() {

        if (is_null($this->_theme_directory)) {
            $dir=$this->getAddon()->getDirectory() . '/' . $this->getAddon()->DIR_NAME_THEMES. '/' . $this->getThemeName();
            $dir=$this->getPlugin()->getTools()->normalizePath($dir);
            $this->_theme_directory=$dir;
             $this->getPlugin()->getLogger()->log('Set Theme Template Directory to : ' . $this->_theme_directory);

        }


          return $this->_theme_directory;
    }

//    /**
//     * Set Template Directory
//     *
//     * @param string $template_directory
//     * @return object $this
//     */
//    public function setTemplateDirectory($template_directory) {
//        $this->_template_directory = $template_directory;
//         $this->getPlugin()->getLogger()->log('Set Simpli_Forms Theme Directory to : ' . $this->_template_directory);
//        return $this;
//    }

    /**
     * Get Theme
     *
     * @param none
     * @return string
     */
    public function getThemeName() {
        if (is_null($this->_theme)) {
            $this->setTheme(self::DEFAULT_THEME);
        }
        return $this->_theme;
    }

    /**
     * Set Theme
     *
     * @param string $theme
     * @return object $this
     */
    public function setTheme($theme) {
        $theme = trim(ucwords($theme));
        $this->_theme = $theme;



        $this->getPlugin()->getLogger()->log('Set Simpli_Forms Theme to : ' . $this->getThemeName());
        return $this;
    }

    /**
     * Get Elements Module
     *
     * Returns the Elements Module of the Current Theme or the module named $this->getAddon->MODULE_NAME_ELEMENTS if it doesnt exist
     * @param none
     * @return object Form Elements Module
     */
    public function getFormElementsModule() {

        $theme = ucwords($this->getThemeName());

        $module_name = $this->getAddon()->MODULE_NAME_ELEMENTS . $theme;

        $theme_elements_module_exists = $this->getAddon()->isModuleLoaded($module_name);

        if ($theme_elements_module_exists) {

            $module_name = $this->getAddon()->MODULE_NAME_ELEMENTS . $theme;
        } else {
            $module_name = $this->getAddon()->MODULE_NAME_ELEMENTS;
        }


        return $this->getAddon()->getModule($module_name);
    }

}

