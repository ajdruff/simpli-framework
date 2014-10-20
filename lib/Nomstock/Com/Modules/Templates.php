<?php

/**
 * Core Module
 *
 * Plugin's core functionality
 * See http://simpliwp.com/plugin-builder/ for examples
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 *
 */





class Nomstock_Com_Modules_Templates extends Nomstock_Com_Base_v1c2_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t(); //trace provides a information about the method and arguments, and provides a backtrace in an expandable box. A visual trace is also provided if graphiviz is enabled.


        $this->setConfig('TemplateDirectory', dirname(dirname($this->plugin()->getDirectory())) . '/content/published/_jekyll-output/templates');
        
        
     
    }

    private $_title='';
    
    /**
     * Filter Hook - Add WordPress Page Title
     *
     * Checks title and if not set, sets it
     *
     * @param string $title The title of the page being rendered
     * @return void
     */
    public function addWpPageTitle( $title ) {
       
        if ( is_null($title) or $title==='') {
            $title=$this->_title;
         
}
   return $title;
    }
    
    /**
     * Set Page Title
     *
     * Sets the page title used by the template.
     *
     * @param string $title The title page.
     * @return void
     */
    public function setPageTitle( $title  ) {
            $this->_title=$title;

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

 add_filter('wp_title', array($this,'addWpPageTitle'));
    

        /*
         * add scripts
         *  */

        add_action( 'wp_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );


        /*
         *  add custom ajax handlers
         * this is where you map any form actions with the class method that handles the ajax request
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->plugin()->getSlug() . '_xxxx'
          see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         *
         * Example:
         * add_action('wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array($this, 'save'));
         *
         */
    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - hookEnqueueScripts
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {
        $this->debug()->t();
        /* Example
          wp_enqueue_style($this->plugin()->getSlug() . '-admin-page', $this->plugin()->getAdminUrl() . '/css/settings.css', array(), $this->plugin()->getVersion());
          wp_enqueue_script('jquery');
          wp_enqueue_script('jquery-form');
          wp_enqueue_script('post');
         *
         */

        /* Example
          $handle = $this->plugin()->getSlug() . '_core.js';
          $src = $this->plugin()->getUrl() . '/js/' . $this->plugin()->getSlug() . '_core.js';
          $deps = 'jquery';
          $ver = '1.0';
          $in_footer = false;
          wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
         *
         */
    }

    /**
     * Get Template
     *
     * Returns the template as a string, uses cache if already accessed
     *
     * @param string $template_name The base name of the template file, without the extension
     * @return string
     */
    private $_template_cache = array();

    public function getTemplate($template_name) {

        if (!isset($this->_template_cache[$template_name])) {
            $template_path = $this->TemplateDirectory . '/' . $template_name . '.tpl';
            if (!file_exists($template_path)) {
                $this->debug()->logError('Template file ' . $template_path . '  Doesnt exist! Returning an empty string as the template');
                return null;
            }

            ob_start();
            include($template_path);
            $template = ob_get_clean();
            $this->_template_cache[$template_name] = $template;
        }

        return $this->_template_cache[$template_name];
    }

}

