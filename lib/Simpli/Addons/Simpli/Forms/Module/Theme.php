<?php

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
    private $_theme_name = null;

    const DEFAULT_THEME = 'default';

    /**
     * Get Templates
     *
     * @param none
     * @return array $this->_templates
     */
    public function getTemplatesOLD() {
        $this->debug()->t();
        if (is_null($this->_templates)) {



            $this->debug()->log('$this->_templates is null, resetting to empty array');
            $this->_templates = array();
        }
        return $this->_templates;
    }

    /**
     * Get Template
     *
     * Returns the contents of the theme's template file. Wrapper around _getCachedTemplate()
     * @param string $template_id The id of the template tag
     * @return string The contents of the template file that matches the theme
     */
    public function getTemplate($template_id) {
        $this->debug()->t();

        $result = $this->_getCachedTemplate($template_id);



        $this->debug()->logVars(get_defined_vars());
        return $result;
    }

    /**
     * Set Template
     *
     * @param string $something
     * @return object $this
     */
    public function setTemplateOLD($template_name, $template) {
        $this->debug()->t();


        $this->debug()->logVar('setting template for ' . $template_name, $template_name);
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
        $this->debug()->t();


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
        $this->debug()->t();

/*
 * set default template
 */
        $this->setTheme('Saratoga');


        //  $addon_modules = $this->getAddon()->getModules();
    }

    private $_cached_templates;

    /**
     * Get Cached Template
     *
     * Returns a template from the cache. If not set in cache, it loads it from disk using _setCachedTemplate()
     *
     * @param none
     * @return void
     */
    private function _getCachedTemplate($template_id) {
        $this->debug()->t();
        if (!isset($this->_cached_templates[$template_id])) {
            $this->_setCachedTemplate($template_id);
        }
        $result = $this->_cached_templates[$template_id];
        $this->debug()->logVars(get_defined_vars());
        return $result;
    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    private function _setCachedTemplate($template_id) {




        $template_path = $this->_getThemeDirectory() . '/templates/' . $template_id . '.template.php';

        if (!file_exists($template_path)) {

            $this->debug()->log('No such template for ' . $template_id . ' , setting cache to null  ');
            $this->_cached_templates[$template_id] = null;
        } else {



            ob_start();
            /*
             * @todo: consider adding a special file called 'common.functions.php' to be included with
             * each template that would provide each with common functions
             */

            include($template_path);
            $template = ob_get_clean();
            $this->_cached_templates[$template_id] = $template;
        }



        $result = $this->_cached_templates[$template_id];
        $this->debug()->logVars(get_defined_vars());
        return $result;
    }

    /**
     * Load Templates
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function loadTemplatesOLD() {
        $this->debug()->t();


        //todo: replace this with a glob pattern so there are no harcoded supported tags
        $supported_tags = array(
            'text'
            , 'text2'
            , 'select'
            , 'radio'
            , 'textarea'
            , 'checkboxes'
        );

        $this->debug()->log('Loading Templates...');

        foreach ($supported_tags as $tag) {

            $template_path = $this->getThemeDirectory() . '/templates/' . $tag . '.template.php';

            if (!file_exists($template_path)) {

                $this->debug()->logVar('Skipping file $template_path = ', $template_path);
                continue; //skip if no template
            }

            $this->debug()->logVar('Loading template ...', $template_path);
            ob_start();
            /*
             * temporarily define a conveniance function
             * todo: consider adding a special file called 'common.functions.php' to be included with
             * each template that would provide each with common functions
             */

            include($template_path);
            $template = ob_get_clean();


//
//
            $this->setTemplate($tag, $template);
        }

        $this->debug()->logVar('Templates that were loaded: ', $this->getTemplates());
    }

    /**
     * Set Theme Directory
     *
     * Sets the theme directory after normalizing the provided path
     *
     * @param string $theme_directory_path The full path to the theme directory
     * @return void
     */
    private function _setThemeDirectory($theme_directory_path) {


        $theme_directory_path_normalized = $this->getPlugin()->getTools()->normalizePath($theme_directory_path);
        $this->_theme_directory = $theme_directory_path_normalized;
        $this->debug()->log('Set Theme Template Directory to : ' . $this->_theme_directory);
    }

    /**
     * Get Theme Directory
     *
     * @param none
     * @return string $_theme_directory
     */
    private function _getThemeDirectory() {
        $this->debug()->t();
        //if theme directory wasnt set yet, set it now with whatever theme name exists
        if (is_null($this->_theme_directory)) {
            $this->_theme_directory=$this->getAddon()->getDirectory() . '/' . $this->getAddon()->DIR_NAME_THEMES . '/' . $this->getThemeName();

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
//         $this->debug()->log('Set Simpli_Forms Theme Directory to : ' . $this->_template_directory);
//        return $this;
//    }

    /**
     * Set the theme
     *
     * Initializes the theme, settings its name, directory, clearing the template cache,etc.
     *
     * @param string $theme_name The name of the theme to set
     * @return void
     */
    public function setTheme($theme_name) {
        $this->debug()->t();
        /*
         * dont set theme if its null
         */
        if (is_null($theme_name)) {
            return;
        }
 $this->debug()->log('Setting theme ...');
/*
 * set theme name
 */
        $this->_setThemeName($theme_name);
        $this->debug()->log('Set theme name to ' . $theme_name);
/*
 * set theme directory
 */
        $this->_setThemeDirectory($this->getAddon()->getDirectory() . '/' . $this->getAddon()->DIR_NAME_THEMES . '/' . $this->getThemeName());
$this->debug()->log('Set theme directory to ' . $this->_getThemeDirectory());

        /*
         * load theme modules
         */

        $new_enabled_modules=$this->getAddon()->loadModules($this->_getThemeDirectory() . '/Module');

//        foreach ($new_enabled_modules as $module_name => $module_path) {
//
//            $this->loadModule($module_name);
//        }
//        return $new_enabled_modules;
//
//
//       $modules = $this->getAddOn()->getModules();

        if (is_array($new_enabled_modules)) {


            foreach ($new_enabled_modules as $module_name=>$module) {


                $this->getAddon()->getModule($module_name)->init();
                $this->debug()->log('Initialized Addon Module ' . $this->getSlug() . '/' .$this->getAddon()->getModule($module_name)->getName());
            }
        }



        /*
         * clear template cache so each template will be forced to reload.
         */

        $this->_cached_templates = array();
    }

    /**
     * Set Theme Name
     *
     * Sets the Theme's name
     *
     * @param string $theme_name The name of the theme
     * @return void
     */
    private function _setThemeName($theme_name) {


        $this->_theme_name = trim(ucwords($theme_name));
    }

    /**
     * Get Theme Name
     *
     * @param none
     * @return string The current theme's name. If no theme has been set, will return the default theme.
     */
    public function getThemeName() {
        $this->debug()->t();
        if (is_null($this->_theme_name)) {
            $this->debug()->log('_theme_name is null, setting it to default');

            $this->_setThemeName(self::DEFAULT_THEME);
        }
        $result = $this->_theme_name;
        $this->debug()->logVars(get_defined_vars());
        return $result;
    }

    /**
     * Get Elements Module
     *
     * Returns the Elements Module of the Current Theme or the module named $this->getAddon->MODULE_NAME_ELEMENTS if it doesnt exist
     * @param none
     * @return object Form Elements Module
     */
    public function getFormElementsModule() {
        $this->debug()->t();


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

