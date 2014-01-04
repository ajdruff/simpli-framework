<?php

/**
 * Form Elements Module
 *
 * Provides Basic Elements
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Frames_Addons_Simpli_Forms_Modules_Elements extends Simpli_Frames_Base_v1c2_Plugin_Module {

    protected $_form_module;

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();







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
     * Hook - Enqueue Scripts
     *
     * Load Scripts and Styles
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts( ) {
            

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
     * Get Form Module
     *
     *  Read Only
     * @param none
     * @return object Form Module
     */
    public function getFormModule() {
        $this->debug()->t();

        return $this->plugin()->getModule( 'Form' );
    }
    /**
     * Password Field
     *
     * Returns Field's HTML
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function password( $atts ) {
        $this->debug()->t();



        $defaults = array(
            'name' => null, //the name of the form field.
             'render' => null, //false turns off rendering the element
            'class' => null, //class of the text element
            'style' => null, //style of the text element
            'device_size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'label_size' => null,          
            
            
             'placeholder' => null,
             'value' => null, //value of the field
             'heading' => null,
             'label' => null,
             'hint' => null,
             'help' => null,
             'template' => __FUNCTION__
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Text Field
     *
     * Returns HTML for a Text Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function text( $atts ) {
        $this->debug()->t();



        $defaults = array(
            'name' => null, //the name of the form field.
             'render' => null, //false turns off rendering the element
            'class' => null, //class of the text element
            'style' => null, //style of the text element
            'device_size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'label_size' => null,          
            
            
             'placeholder' => null,
             'value' => null, //value of the field
             'heading' => null,
             'label' => null,
             'hint' => null,
             'help' => null,
             'template' => __FUNCTION__
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Button 
     *
     * Returns HTML for a Textarea
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function button( $atts ) {
        $this->debug()->t();

$form=$this->addon()->getModule('Form');
/*
 * The value of name will be used as an id, so create a random id for it.
 */
        $defaults = array(
            'name' => 'button_id' . $form->form['form']['form_name']. rand(1,1000), //the name of the form field.  
             'render' => null, //false turns off rendering the element
            'class' => null, //class
            'action' => null, //action fired by the button
            'style' => null, //style
            'value' => null, //value
            'device_size' => null,
            'size' => null,
            'template' => __FUNCTION__
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Textarea 
     *
     * Returns HTML for a Textarea
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function textarea( $atts ) {
        $this->debug()->t();



        $defaults = array(
            'name' => null, //the name of the form field.
                         'render' => null, //false turns off rendering the element
            'rows' => 5, //number of rows
            'cols' => 40, //number of columns
            'class' => null, //class of the text element
            'style' => null, //style of the text element
            'device_size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'label_size' => null,       
             'placeholder' => null,
             'value' => null, //value of the field
             'heading' => null,
            'label' => null,
             'hint' => null,
            'help' => null,

             'template' => __FUNCTION__
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
       }

    /**
     * File Field
     *
     * Returns HTML for a file input field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function file( $atts ) {
        $this->debug()->t();



        $defaults = array(
            'name' => null,  //the name of the form field.
                         'render' => null, //false turns off rendering the element
'class' => null, //class of the text element
            'style' => null, //style of the text element
            'device_size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'label_size' => null,     
            'accept'=>null, //sets or returns a comma-separated list of accepted content types. e.g.: image/png,audio/*,video/*,image/*,MIME_type,etc. http://en.wikipedia.org/wiki/Internet_media_type#Type_audio
             'value' => null, //value of the field
             'heading' => null,
            'label' => null,
            'hint' => null,
             'help' => null,
             'template' => __FUNCTION__
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Select Element
     *
     * Returns HTML for a Text Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function dropdown( $atts ) {
        $this->debug()->t();


                   
        $defaults = array(
            'name' => null, //the name of the form field.
                         'render' => null, //false turns off rendering the element
            'class' => null, //class of the select element
            'style' => null, //style of the select element
            'heading' => null,  //the name of the form field.
             'device_size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'size' => null, //null here to allow filters to make it equal to  the size of the form if not set.
            'label_size' => null,           
            
            
            
             'label' => null,
             'hint' => null,
             'help' => null,
             'options' => null, //array in the form 'value'=>'display_text'
             'selected' => null, //string indiciating the value that should be selected on default
             'template' => __FUNCTION__,
             'template_option' => null
        );




        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Checkbox Element
     *
     * Returns HTML for a Checkbox
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function checkbox( $atts ) {
        $this->debug()->t();



        $defaults = array(
            'name' => null, //the name of the form field.
                         'render' => null, //false turns off rendering the element
            'heading' => null,  //the name of the form field.
           'class' => null, //class of the select element
            'style' => null, //style of the select element
            'heading' => null,  //the name of the form field.
             'device_size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'size' => null, //null here to allow filters to make it equal to  the size of the form if not set.
            'label_size' => null,   
             'label' => null,
             'hint' => null,
             'help' => null,
             'options' => null, //array in the form 'value'=>'display_text'
            'selected' => null, //string indiciating the value that should be selected on default
             'template' => __FUNCTION__,
             'template_option' => null
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Radio Element
     *
     * Returns HTML for a Text Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function radio( $atts ) {
        $this->debug()->t();



        $defaults = array(
            'name' => null, //the name of the form field.
                         'render' => null, //false turns off rendering the element
              'label' => null, 
            'heading' => null, //the name of the form field.
            'class' => null, //class of the text element
            'style' => null, //style of the text element
            'device_size' =>  null,//null here to allow filters to make it equal to  the size of the form if not set.
            'size' => null,//null here to allow filters to make it equal to  the size of the form if not set.
            'label_size' => null,   
             'hint' => null,
             'help' => null,
             'options' => null, //array in the form 'value'=>'display_text'
             'selected' => null, //string indiciating the value that should be selected on default
             'template' => __FUNCTION__,
             'template_option' => null
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Post Editor
     *
     * Adds the Builtin WordPress post editor widget
     * This will create the same html as what is found in wp-admin/edit-form-advanced.php
     *
     * @param none
     * @return void
     */
    public function postEditor( $atts ) {
        $this->debug()->t();
        $defaults = array(
            'name' => 'postdivrich', //required, because its required for everything else, but ignored
                         'render' => null, //false turns off rendering the element
             'id' => 'postdivrich',
             'label' => null,
             'hint' => null,
             'help' => null,
             'content_override' => null, /* allows the filter to override the template */
             'template' => __FUNCTION__,
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }


  /**
     * Form Start
     *
     * Creates the Form , setting up the theme and properties, and renderig the form tag
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function formStart( $atts ) {

        $this->debug()->t();
        $defaults = array(
            'name' => 'simpli_forms',
            
            'target'=>null,
            'style' => null,
            'class' => null,
            'device_size' => 'medium',
            'size' => 'medium',
            'label_size' => 'extra-small',
            'theme' => 'Admin',
            'ajax' => null,
            'enctype' => null,
            'action' => null,
            'method' => 'post',
            'template' => __FUNCTION__,
            'template_type'=>null,
            'filter' => null
        );

        $this->debug()->logVar( '$atts = ', $atts);

        /*
         * Apply Defaults
         * Use the shortcode_atts function which will also remove
         * any attributes not specified in the element defaults
         */
        $atts = shortcode_atts( $defaults, $atts );


$form_module=$this->plugin()->getAddon('Simpli_Forms')->getModule('Form');
    

        /*
         * increase the form counter
         */
        $form_module->form_counter++;


        $form_module->form = array(); //initialize, clearing any previous form on the same page




        $this->debug()->logVar( '$atts = ', $atts);
      



        /*
         * if a theme was provided, set it.
         */
        if ( !is_null( $atts[ 'theme' ] ) ) {
            $form_module->getTheme()->setTheme( $atts[ 'theme' ] );
        }

        /*
         * if a filter was provided, set it.
         */

        if ( !is_null( $atts[ 'filter' ] ) ) {


            $form_module->setFilter( $atts[ 'filter' ] );
        }






        /*
         * reset the elements array for this new form
         */
        $form_module->form[ 'elements' ] = array();


        /*
         * reset the form properties
         */
        $form_module->form[ 'form' ] = $atts;

        $form_module->debug()->log( 'Loading the form handler scripts' );
        /*
         * Load the javascript needed for the forms
         */
        if ($form_module->formHandler()->ON_DEMAND_SCRIPTS===true){
        $form_module->formHandler()->hookEnqueueScripts();
        };

        /*
         * Load the theme specific javascript and css
         */
        $this->hookEnqueueScripts();
        
        



        /*
         * output the html
         * Note that we pass on *all* the properties
         * The el() method will scrub out the ones that dont have a a default
         *
         */
        $form_module->debug()->logVars( get_defined_vars() );
   





        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Form Start
     *
     * Adds the <form> tag
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function formStartOLD( $atts ) {
        $this->debug()->t();
/*
 * must set all defaults in the Form module
 */

        $defaults = array(
            'name' => 'simpli_forms', //the name of the form field.
            'style' => null,
            'class' => null,
            'target' => null,
            'device_size' => 'medium',
            'size' => 'medium',
            'label_size' => 'extra-small',
            'content_override' => null,
            'ajax' => null, //whether to submit form via ajax.
            'action' => null, //the action of the form
            'method' => null, //the method of the form , 'post' or 'get'
            'enctype' => null,
            'template' => __FUNCTION__
        );




        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

    /**
     * Form End
     *
     * Adds the end tag and buttons to a form
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function formEnd( $atts ) {
        $this->debug()->t();


        $defaults = array(
            'name' => 'form_end', //unique id of the form
            'template' => __FUNCTION__
        );


        return($this->addon()->getModule( 'Form' )->renderElement( __FUNCTION__, $atts, $defaults ));
    }

}
