<?php

/**
 * Form Theme 'Seattle' Module
 *
 * Provides Form Helper Methods
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_FormTheme extends Simpli_Basev1c0_Plugin_Module {

    private $_templates = array();
    private $_template;
    private $_template_directory;
    private $_theme;

    const DEFAULT_THEME = 'default';

    /**
     * Get Templates
     *
     * @param none
     * @return array $this->_templates
     */
    public function getTemplates() {
        if (!isset($this->_templates)) {
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
    public function getTemplate($template_name) {

        $templates = $this->getTemplates();

        if (isset($this->_templates[$template_name])) {
            $result = $templates[$template_name];
        } else {
            $result = NULL;
        }



        return $result;
    }

    /**
     * Set Template
     *
     * @param string $something
     * @return object $this
     */
    public function setTemplate($template_name, $template) {


        $this->_templates[$template_name] = $template;

        return $this;
    }

    /**
     * Initialize Module when in Admin environment
     *
     * @param none
     * @return object $this
     */
    public function initModuleAdmin() {

    }

    /**
     * Initialize Module
     *
     * @param none
     * @return object $this
     */
    public function initModule() {
        return $this;
    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {

        $this->setTheme('seattle');

        $this->setTemplateDirectory($this->getPlugin()->getDirectory() . '/admin/templates/forms');


        $this->loadTemplates();
    }


    /**
     * Load Templates
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function loadTemplates() {
        // echo '<br/> loading templates';
        //todo: replace this with a glob pattern so there are no harcoded supported tags
        $supported_tags = array(
            'text'
            , 'text2'
            , 'select'
            , 'radio'
            , 'textarea'
            , 'checkboxes'
        );
        //   echo '<pre>', print_r($supported_tags, true), '</pre>';

                    //$e = create_function('$text', 'echo $text;');

  if (!function_exists('simpli_framework_echo')){function simpli_framework_echo($text){echo $text;}}

        foreach ($supported_tags as $tag) {

            $template_path = $this->getTemplateDirectory() . '/' . $this->getTheme() . '/' . $tag . '.template.php';
            //   echo '<br/>' . __LINE__ . ' ' . __METHOD__ . ' $template_path=' . $template_path;

            if (!file_exists($template_path)) {
                //   echo '<br> skipping file . ' . $template_path;
                continue; //skip if no template
            }
            //   echo '<br> loading file . ' . $template_path;
            ob_start();
            /*
             * temporarily define a conveniance function
             * todo: consider adding a special file called 'common.functions.php' to be included with
             * each template that would provide each with common functions
             */
$e='simpli_framework_echo';
            include($template_path);
            $template = ob_get_clean();


//
//
            $this->setTemplate($tag, $template);
        }
        //   echo '<pre>', print_r($this->getTemplates(), true), '</pre>';
    }

    /**
     * Get Template Directory
     *
     * @param none
     * @return string $_template_directory
     */
    public function getTemplateDirectory() {

        if (is_null($this->_template_directory)) {
            $this->setTemplateDirectory($this->getPlugin()->getDirectory() . '/forms/templates');
        }


        return $this->_template_directory;
    }

    /**
     * Set Template Directory
     *
     * @param string $template_directory
     * @return object $this
     */
    public function setTemplateDirectory($template_directory) {
        $this->_template_directory = $template_directory;
        return $this;
    }

    /**
     * Get Theme
     *
     * @param none
     * @return string
     */
    public function getTheme() {
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
        $theme = trim(strtolower($theme));
        $this->_theme = $theme;
        return $this;
    }

}