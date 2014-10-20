<?php

/**
 * Shortcodes Module
 *
 * Adds Shortcodes
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * 
 *
 */
class Nomstock_Com_Modules_Shortcodes extends Nomstock_Com_Base_v1c2_Plugin_Module {

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();

        add_filter('widget_text', 'do_shortcode');
        add_filter('the_content', 'do_shortcode', 11); // From shortcodes.php


        add_shortcode($this->plugin()->getSlug(), array($this, 'sayHello'), 10);
          add_shortcode($this->plugin()->getSlug() . '_example_forms', array($this, 'exampleForms'), 10);

        /**
         *
         *
         *  add scripts
         * example: add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
         *
         */
        /**
         *
         * Add custom ajax handlers
         *  Map Ajax Handlers to Ajax Actions passed to php by the ajax request
         * example: add_action('wp_ajax_' . $this->plugin()->getSlug() . '_my_action', array($this, 'my_function'));
         * see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         */
    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();
    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function enqueue_scripts() {
        $this->debug()->t();

//       wp_enqueue_style($this->plugin()->getSlug() . '-admin-page', $this->plugin()->getAdminUrl() . '/css/settings.css', array(), $this->plugin()->getVersion());
//        wp_enqueue_script('jquery');
//        wp_enqueue_script('jquery-form');
//        wp_enqueue_script('post');
//
//        if (function_exists('add_thickbox')) {
//            add_thickbox();
//        }
    }

    /**
     *  Say Hello
     *
     *
     *  */
    public function sayHello() {
        $this->debug()->t();




        $result = '<div>Hello World! , says the ' . $this->plugin()->getName() . ' plugin</div>';

        return $result;
    }
    /**
     *  Say Hello
     *
     *
     *  */
    public function exampleForms( $atts ) {
        $this->debug()->t();
        //$this->debug()->setMethodFilter( __FUNCTION__, false );

        $defaults = array(
            'theme' => null, //set equal to a them and it will load all the layout examples
            'layouts'=>null
        );

$atts=shortcode_atts($defaults,$atts);

$layouts=explode(',',$atts['layouts']);
$this->debug()->logVar( '$layouts = ', $layouts );
$content=null;
    $template_directory=$this->plugin()->getAddon('Simpli_Forms')->getDirectory() . '/Themes/' . $atts['theme'];
$this->debug()->logVar( '$template_directory = ', $template_directory );


foreach ( $layouts as $layout ) {
    ob_start();
    $test_form_file_path=$template_directory . '/templates/' . $layout . '/_example.php';
    $this->debug()->logVar( '$test_form_file_path = ', $test_form_file_path );
    if ( file_exists($test_form_file_path)) {
    
        include($test_form_file_path);
}
    $output.=ob_get_clean();
    
}

        return($output);
    }
}


