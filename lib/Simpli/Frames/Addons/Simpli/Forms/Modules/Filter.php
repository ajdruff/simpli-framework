<?php

/**
 * Form Filter Module
 *
 * Modifies Field Inputs from Form Templates
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Frames_Addons_Simpli_Forms_Modules_Filter extends Simpli_Frames_Base_v1c2_Plugin_Module {

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();
    }

//    /**
//     * Get Filter Tag - Read Only
//     *
//     * Provides a Unique Filter Name to be used for hooks
//     *
//     * @param none
//     * @return stringReadOnly
//     */
//    public function getHookName() {
//        $this->debug()->t();
//
//        $hook_name = $this->addon()->getSlug() . '_' . $this->getSlug(); //e.g.: simpli_addons_simpli_forms_filters
//        return $hook_name;
//    }

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
     * Filter Wrapper ( Acts as central proxy for other filters within this module)
     *
     * Acts as a wrapper around the various form filter methods.
     * @param string $tag_id The tag identifier. e.g.: 'text' for the text tag
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    public function filter( $properties ) {
        $this->debug()->t();
        $this->debug()->logVars( get_defined_vars() );
        $this->debug()->log( 'Filtering using filter method in base class' );
        $method = 'filter' . ucwords( $properties[ 'scid' ] );




        /*
         * apply the common filter
         */
        $properties = $this->_commonFilter( $properties );

        /*
         * apply the element filter
         */
        if ( method_exists( $this, $method ) ) {
            $this->debug()->log( 'base class is calling filter for ' . $method );
            $properties = $this->$method( $properties );
} else {
            $this->debug()->log( 'No filter for ' . $method . ' exists' );
}


        return ($properties);
    }

    /**
     * Common Filter
     *
     * All Fields are subject to this filter
     * * @param none
     * @return void
     */
    protected function _commonFilter( $properties ) {
        $this->debug()->t();
        $this->debug()->log( 'applying the common filters of the base class' );

        extract( $properties );
        $this->debug()->logVar( '$properties = ', $properties );

        /*
         * Template
         * First, check to see if a template type is defined, and if so, add it as a suffix
         * then check to see if the template incorporating type exists, and if so, use it
         */

        $theme = $this->addon()->getModule( 'Form' )->getTheme();
        $this->debug()->logVar( '$theme = ', $theme->getThemeName() );
        $this->debug()->logVar( 'Form Tracker = ', $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' )->form );


        /*
         * if scid is 'formStart' then take layout from form, otherwise, check to see if one is defined in the 
         * elements[scid] and if so, use it instead.
         */

        /*
         * if the element has defined a 'layout' attribute, then check to see if it has been
         * assigned a value by the user, and if so, use that instead.
         */
//           if ( isset($atts['layout']) && !is_null( $atts['layout'] ) ) {
//               $template_layout = $atts['layout'];
//               
//           }else{
//               
//                   $template_layout = $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' )->form[ 'form' ][ 'layout' ];
//    
//               
//           }     
        //     $this->debug()->logVar( '$template_layout = ', $template_layout );
//        if ( (!is_null( $template_layout )) && $template_layout !== '' ) {
//            $template_path_modified_with_layout =  $template_layout . '/' . $atts[ 'template' ];
//            /*
//             * if the template is not null, replace the template with the new modified template that
//             * incorporates the type
//             */
//
//            if ( !is_null( $template_path_modified_with_layout ) ) {
//                $atts[ 'template' ] = $template_path_modified_with_layout;
//}
//}

        /*
         * Template Option
         */
        if ( $atts [ 'template_option' ] && is_null( $atts[ 'template_option' ] ) || $atts[ 'template_option' ] === '' ) {

            $atts[ 'template_option' ] = $atts[ 'template' ] . '_option';
}

/*
 * Set Default Template
 */

          if ( array_key_exists( 'template', $atts ) && is_null( $atts[ 'template' ] ) ) {

            $atts[ 'template' ] = $scid;
}


        $this->debug()->logVar( '$atts = ', $atts );
        /*
         * Return error if required arguments are not found
         * Do NOT use isset since its not the same thing.
         */
        if ( array_key_exists( 'name', $atts ) && is_null( $atts[ 'name' ] ) ) {

            $atts [ '_error' ][] = 'Name attribute is required';



} else{
            $tags[ 'id' ] = $atts[ 'name' ] . '_' . $this->addon()->getModule( 'Form' )->form_counter;
}




        /*
         * Add a unique prefix to the name so we dont conflict with other plugins that might be on the same form
         */

        if ( array_key_exists( 'name', $atts ) && is_null( $atts[ 'name' ] ) ) {
            $atts[ 'name' ] = $this->getFieldPrefix() . $atts[ 'name' ];
}



        /*
         * Add a default label if one wasnt provided
         */


        if ( array_key_exists( 'label', $atts ) && is_null( $atts[ 'label' ] ) ) {

            $atts[ 'label' ] = $this->getDefaultFieldLabel( $atts[ 'name' ] );
}

        $this->debug()->logVar( '$atts = ', $atts );

        $tags[ 'form_counter' ] = $this->addon()->getModule( 'Form' )->form_counter;
        if ( isset( $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'name' ] ) ) {
            $tags[ 'form_name' ] = $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'name' ];
}



        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Filter Password
     *
     * Filters the Tag Attributes
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterPassword( $properties ) {
        $this->debug()->t();

        extract( $properties );


        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Filter Button
     *
     * Filters the Button tag attributes
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterButton( $properties ) {
        $this->debug()->t();

        extract( $properties );

        /*
         * Set the default {SPINNER} tag
         */
        if ( array_key_exists( 'spinner', $atts ) && is_null( $atts[ 'spinner' ] ) ) {

            $atts[ 'spinner' ] = $this->plugin()->getUrl() . '/images/wpspin_light.gif';
}

        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Filter Text
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterText( $properties ) {
        $this->debug()->t();

        extract( $properties );


        if ( $atts[ 'disabled' ] === true ){

            $atts[ 'disabled' ] = 'disabled';

} else{
            $atts[ 'disabled' ] = '';
}

        if ( $atts[ 'readonly' ] === true ){

            $atts[ 'readonly' ] = 'readonly';

} else{
            $atts[ 'readonly' ] = '';
}

        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Filter Response
     *
     * Filters the Response tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterResponse( $properties ) {
        $this->debug()->t();

        extract( $properties );
        $tags[ 'response_html' ] = apply_filters( 'simpli_forms_response', '' );

        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Filter File
     *
     * Filters the File Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterFile( $properties ) {
        $this->debug()->t();

        extract( $properties );


        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Filter Dropdown
     *
     * Filters the Dropdown Attributes
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterDropdown( $properties ) {
        $this->debug()->t();

        extract( $properties );

        /*
         * use the shared code for radio,dropdown, and checkbox elements
         */
        return($this->_filterOptions( $properties ));
    }

    protected function filterDropdownOld( $properties ) {
        $this->debug()->t();

        extract( $properties );



        /*
         * Create the options_html
         *
         */
        $options_html = '';
        $tokens = $atts;



        foreach ( $atts[ 'options' ] as $option_value => $option_text ) {


            $tokens[ 'selected_html' ] = (($atts[ 'selected' ] == $option_value) ? ' selected = "selected"' : '');
            $tokens[ 'option_value' ] = $option_value;
            $tokens[ 'option_text' ] = $option_text;
            $option_template = '<option {selected_html} value = "{option_value}">{option_text}</option>';
            $options_html.=$this->plugin()->tools()->crunchTpl( $tokens, $option_template );
}


        $tags[ 'options_html' ] = $options_html;





        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Filter Checkboxes
     *
     * Filters the Checkbox Attributes
     * @param string $properties The properties of the checkbox
     * @return string $atts
     *
     *
     */

    /**
     * Filter Checkbox
     *
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterCheckbox( $properties ) {
        $this->debug()->t();

        extract( $properties );

        /*
         * use the shared code for radio,dropdown, and checkbox elements
         */
        return($this->_filterOptions( $properties ));
    }

    protected function filterCheckboxOld( $properties ) {
        $this->debug()->t();

        extract( $properties );



        /*
         * Create the options_html
         *
         */
        $options_html = '';
        $tokens = $atts;

        $this->debug()->logVar( '$atts = ', $atts );

        foreach ( $atts[ 'options' ] as $option_value => $option_text ) {


            $tokens[ 'checked_html' ] = (($atts[ 'selected' ][ $option_value ] == 'yes') ? ' checked = "checked" selected = "selected" ' : '');
            $tokens[ 'option_value' ] = $option_value;
            $tokens[ 'option_text' ] = $option_text;
            $option_template = ' <p>
<label style = "padding: 18px 2px 5px;" for = "checkbox_settings_{OPTION_VALUE}"><span style = "padding-left:5px" >{OPTION_TEXT}</span>
<input type = "checkbox" name = "checkbox_settings[{OPTION_VALUE}]" id = "checkbox_settings_{OPTION_VALUE}" value = "yes" {CHECKED_HTML} >


</label>
</p>';

            $options_html.=$this->plugin()->tools()->crunchTpl( $tokens, $option_template );
}


        $tags[ 'options_html' ] = $options_html;


        $properties = compact( 'scid', 'atts', 'tags' );
        $this->debug()->logVar( '$properties = ', $properties );

        return ($properties);
    }

    /**
     * Filter Options (Internal, shared code for radio,checkbox,dropdown)
     *
     * Provides shared code for the rendering of an 'options' control , which include radio, checkbox, and dropdown(select), or potentially any other control that requires selection from a group of options. Uses 2 templates, one that provides the surrounding html of the options collection, and one that contains the option html itself, which will be used within a loop to create an 'options_html' tag rendered within the larger template.
     *
     * @param $properties The element's properties (shortcode attributes)
     * @return The html to be rendered
     */
    protected function _filterOptions( $properties ) {

        $this->debug()->t();

        extract( $properties );
//dropdown
//$tokens['selected_html'] = (($atts['selected'] == $option_value) ? ' selected="selected"' : '');
//checkbox
//$tokens['checked_html'] = (($atts['selected'][$option_value] == 'yes') ? ' checked="checked"  selected="selected" ' : '');


        /*
         * template_option
         */

        if ( is_null( $atts[ 'template_option' ] ) ) {
            $atts[ 'template_option' ] = $atts[ 'template' ] . '_' . 'option';
}

        /*
         * Create the options_html
         *
         */
        $options_html = '';
        $tokens = $atts;
        foreach ( $atts[ 'options' ] as $option_value => $option_text ) {
            $tokens = $atts; //need to reset to original atts for each iteration, since we change the value of the tokens during the loop.
//  $tokens['checked'] = (($atts['selected'][$option_value] == 'yes') ? ' checked="checked"' : '');
//  $tokens['selected'] = (($atts['selected'][$option_value] == $option_value) ? ' selected="selected" ' : '');
//radio and checkbox
//        $tokens['checked'] = (($atts['selected'][$option_value] == 'yes') ? ' checked="checked" ' : '');
//$tokens['checked'] = (($atts['selected'][$option_value] == $option_value) ? ' checked="checked" ' : '');
//
//
//radio - works but only if not filtered
//$tokens['checked'] = (($atts['selected'] == $option_value) ? ' checked="checked"' : '');

            /*
             * works for radio and checkbox
             */
            if ( is_array( $atts[ 'selected' ] ) ) {
                if ( isset( $atts[ 'selected' ][ $option_value ] ) ) {


                    $tokens[ 'checked' ] = (($atts[ 'selected' ][ $option_value ] == 'yes') ? ' checked="checked"' : '');
} else {
                    $tokens[ 'checked' ] = '';
}
} else {

                $tokens[ 'checked' ] = (($atts[ 'selected' ] == $option_value) ? ' checked="checked"' : '');
}

            /*
             * dropdown
             */
            if ( is_array( $atts[ 'selected' ] ) ) {
                if ( isset( $atts[ 'selected' ][ $option_value ] ) ) {
                    $tokens[ 'selected' ] = (($atts[ 'selected' ][ $option_value ] == 'yes') ? ' selected="selected" ' : '');
                    $this->debug()->logVar( '$option_value = ', $option_value );
                    $this->debug()->logVar( '$selected[$option_value] = ', $atts[ 'selected' ][ $option_value ] );
                    $this->debug()->logVar( '$tokens[selected] = ', $tokens[ 'selected' ] );
} else {
                    $tokens[ 'selected' ] = '';
}
} else {

                $tokens[ 'selected' ] = (($atts[ 'selected' ] == $option_value) ? ' selected="selected" ' : '');
}

//dropdown - works
//     $tokens['selected'] = (($atts['selected'] == $option_value) ? ' selected="selected" ' : '');

            $tokens[ 'option_value' ] = $option_value;
            $tokens[ 'option_text' ] = $option_text;

            if ( isset( $atts[ 'layout' ] ) && !is_null( $atts[ 'layout' ] ) ) {
                $layout = $atts[ 'layout' ];
} else {

                $layout = $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' )->form[ 'form' ][ 'layout' ];
}


            $option_template = $this->addon()->getModule( 'Form' )->getTheme()->getTemplate( $atts[ 'template_option' ], $layout );

            $options_html.=$this->plugin()->tools()->crunchTpl( $tokens, $option_template );
            $this->debug()->logVar( '$tokens = ', $tokens );
}

        $this->debug()->logVar( '$options_html = ', $options_html );

        $tags[ 'options_html' ] = $options_html;





        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Filter Radio
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterRadio( $properties ) {
        $this->debug()->t();

        extract( $properties );

        /*
         * use the shared code for radio,dropdown, and checkbox elements
         */
        return($this->_filterOptions( $properties ));
    }

    /**
     * Filter Form End
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterFormEnd( $properties ) {
        ////$this->debug()->setMethodFilter( __FUNCTION__, false );
        $this->debug()->t();
        $this->debug()->logVar( '$properties = ', $properties );
        extract( $properties );

        $tags[ 'spam-controls' ] = $this->spamControls();

        $form_props = $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' )->form[ 'form' ];

        $this->debug()->logVar( '$form_props = ', $form_props );
        /*
         * Add a container
         */
        if ( array_key_exists( 'container', $form_props ) && $form_props[ 'container' ] === true ) {

            $tags[ 'container_end' ] = '</div>';

    }else{
        $tags[ 'container_end' ] = '';
    }


        $properties = compact( 'scid', 'atts', 'tags' );

        return ($properties);
    }

    /**
     * Post Editor Filter
     *
     * Provides the php required for the post editor to work. The code is taken directly from wp-admin/edit-form-advanced.php , just search for 'postdivrich' or 'wp_editor'
     *
     * @param none
     * @return void
     */
    public function filterPostEditor( $properties ) {
        $this->debug()->t();

        extract( $properties );
        $post = $this->plugin()->post()->getPost();
        $post_type = $post->post_type;
        $this->debug()->logVar( '$post_type = ', $post_type );
        $post_ID = $post->ID;
        $this->debug()->logVar( '$post_ID = ', $post_ID );

        if ( post_type_supports( $post_type, 'editor' ) ) {
            $this->debug()->log( 'Post type ' . $post_type . ' supports editor, displaying editor' );

            /*
             * capture the output of wp_editor so
             * we can assign it to a tag
             */
            ob_start();
            wp_editor( $post->post_content, 'content', array( 'dfw' => true, 'tabindex' => 1 ) );
            $tags[ 'wp_editor' ] = ob_get_clean();


            $tags[ 'word_count' ] = sprintf( __( 'Word count: %s' ), '<span class="word-count">0</span>' );
            $tags[ 'last_edit' ] = '';
            if ( 'auto-draft' != $post->post_status ) {
                $this->debug()->log( 'not an auto draft' );
                $this->debug()->log( 'adding last edit' );
                $tags[ 'last_edit' ].= '<span id="last-edit">';
                if ( $last_id = get_post_meta( $post_ID, '_edit_last', true ) ) {
                    $last_user = get_userdata( $last_id );
                    $tags[ 'last_edit' ].=sprintf( __( 'Last edited by %1$s on %2$s at %3$s' ), esc_html( $last_user->display_name ), mysql2date( get_option( 'date_format' ), $post->post_modified ), mysql2date( get_option( 'time_format' ), $post->post_modified ) );
} else {
                    $this->debug()->log( 'auto draft' );
                    $this->debug()->log( 'adding last edit' );
                    $tags[ 'last_edit' ].=sprintf( __( 'Last edited on %1$s at %2$s' ), mysql2date( get_option( 'date_format' ), $post->post_modified ), mysql2date( get_option( 'time_format' ), $post->post_modified ) );
}
                $tags[ 'last_edit' ].= '</span>';
}
} else {
            $this->debug()->logError( 'Post type ' . $post_type . ' doesnt support editor, not showing editor' );

            /*
             * If Editor Not supported
             * Output nothing
             */
            $atts[ 'content_override' ] = '';
}
        /*
         * Ensure default editor id.
         * if id is duplicate, the editor wont display
         */
        if ( is_null( $atts[ 'id' ] ) ) {
            $atts[ 'id' ] = 'postdivrich';
}


        $properties = compact( 'scid', 'atts', 'tags' );

        return ($properties);
    }

    /**
     * Form Start
     *
     * Filters the formStart attribute
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterFormStart( $properties ) {
        $this->debug()->t();
        //$this->debug()->setMethodFilter( __FUNCTION__, false );
        extract( $properties );

            $local_vars = $this->plugin()->getLocalVars();
            
        if ( array_key_exists( 'name', $atts ) && is_null( $atts[ 'name' ] ) ) {

            $atts[ 'name' ] = 'simpli_forms';
}


        /*
         * Make Ajax the default form behavior ( if no 'ajax' attribute is provided)
         */

        if (
                !array_key_exists( 'ajax', $atts )  //if ajax is not provided ( default is true)
                || array_key_exists( 'ajax', $atts ) && $atts[ 'ajax' ] !== false  // or if ajax is true
        ) {

            $atts[ 'ajax' ] === true;
}
        /*
         * Localize the Target attribute, which tells the form where to place the response
         */

        if ( array_key_exists( 'target', $atts ) && !is_null( $atts[ 'target' ] ) ) {

            /*
             * Localize Success Message
             */
   
$local_vars['forms']['target']=$atts[ 'target' ];
     
}

        /*
         * Add a container
         * 
         * add container start tag if container is set to true and class is provided. if class is not provided, set to 'container'
         * the filterFormEnd() provides an ending tag for {container_end} if the formStart has contained set to true.
         * 
         * 
         */
        if ( array_key_exists( 'container', $atts ) && $atts[ 'container' ] === true ) {
            if ( array_key_exists( 'container_class', $atts ) && !is_null( $atts[ 'container_class' ] ) ) {
                $temp_class = $atts[ 'container_class' ];

     } else{
                $temp_class = 'container';
     }
            $tags[ 'container_start' ] = '<div class="' . $temp_class . '"><!-- start of container, added by formStart() -->';

 } else{
            $atts[ 'container' ] = false;
            $tags[ 'container_start' ] = '';

 }

        /*
         *
         * For ajax forms, make the form action attribute an empty string
         * This will allow javascript to use an empty action attribute as a flag
         * to tell it to use an ajax script to submit the form
         */
        if ( array_key_exists( 'ajax', $atts ) && $atts[ 'ajax' ] === true ) {

            $atts[ 'action' ] = ''; //form action attribute must be empty string if ajax
} elseif ( array_key_exists( 'ajax', $atts ) && $atts[ 'ajax' ] === false ) {

            /*
             *
             *  if ajax is false, but no action
             *  is given, the action should be set to the
             * $_SERVER['REQUEST_URI']&queryvar_action={action}. this will tell the ajax script to
             * replace the {action} token with the action
             * provided by the button.
             */

            if (
            /*
             * if  a form action attribute is not provided ...
             *
             */

                    array_key_exists( 'action', $atts ) && is_null( $atts[ 'action' ] ) //if no action property provided
                    || trim( $atts[ 'action' ] ) === ''
            ) {
                /*
                 *  ...then set the action attribute
                 * to the query variable action url pattern
                 * which can be later completed by the javascript
                 * when it knows which button is pressed, giving it the action.
                 * the final url will look like
                 * <current_url>?mycompany_myplugin_action=save_settings , which will trigger a post action on submission
                 */


                /*
                 * Use the current URL as the submission url, and add the query action variable onto it.
                 */
                $atts[ 'action' ] = $this->plugin()->tools()->rebuildUrl( array( $this->plugin()->QUERY_VAR . '_action' => '{action}' ) );
}
}



        if ( array_key_exists( 'method', $atts ) && is_null( $atts[ 'method' ] ) ) {
            $atts[ 'method' ] = 'post';
}

        if ( array_key_exists( 'enctype', $atts ) && is_null( $atts[ 'enctype' ] ) ) {
            $tags[ 'enctype_html' ] = '';
} else {
            $tags[ 'enctype_html' ] = 'enctype = ' . $atts[ 'enctype' ];
}
/*
 * Hide Form
 * Hides Form on Successful Submission
 * Default to True
 */

        if ( array_key_exists( 'hide_form', $atts ) && is_null( $atts[ 'hide_form' ] ) ) {
            $atts[ 'hide_form' ]=true;
} 
            $local_vars['forms']['hide_form']=$atts[ 'hide_form' ];

/*
 * 
 * Response Fade
 * True will fade out the response. False will make it remain until the page is refreshed.
 */

        if ( array_key_exists( 'response_fadeout', $atts ) && is_null( $atts[ 'response_fadeout' ] ) ) {
            $atts[ 'response_fadeout' ]=true;
} 


/* localize it */


            $local_vars['forms']['response_fadeout']=$atts[ 'response_fadeout' ];

            
            /*
             * Set Local Variables
             */
            $this->plugin()->setLocalVars( $local_vars );
            
            
        $this->debug()->logVar( '$atts = ', $atts );

        return (compact( 'scid', 'atts', 'tags' ));
    }

    /**
     * Get Default Field Label
     *
     * Uses name to derive a label
     * * @param none
     * @return void
     */
    function getDefaultFieldLabel( $name ) {
        $this->debug()->t();



        $label = str_replace( $this->getFieldPrefix(), '', $name );
        $label = strtolower( $label );
        $label = str_replace( '_', ' ', $label );
        $label = ucwords( $label );
        return $label;
    }

    /**
     * Get Field Prefix - Read Only
     *
     * @param none
     * @return string
     */
    public function getFieldPrefix() {
        $this->debug()->t();


        $this->plugin()->getSlug() . '_';
    }

    /**
     * Spam Controls
     *
     * produces markup used by the {spam-controls} tag added to the form End element.
     *
     * @param none
     * @return string Html markup for input controls that are used for fighting spam
     */
    public function spamControls() {
        #initialize
        $anti_spam_controls = '';
        $space = ' ';
        /*
         * honey pot
         * if anything is in here, we know its spam.
         */

        /*
         * dont do anything if spam controls arent on
         */
        if ( $this->plugin()->getModule( 'Forms' )->BLOCK_FORM_SPAM === false ) {
            $this->debug()->log( 'Spam Controls turned off, not adding them', true );
            return '';

}



        $anti_spam_controls .=
                '<label'
        . $space . 'style="visibility:hidden;display:none;"'
                . $space . 'data-sf-as'
                . $space . 'for="sf-as-url"'
                . '>' //end of opening label tag
                . 'Do not enter anything in the last 2 fields or your form will be rejected as spam'
                . '</label>';  //end of closing label tab   


        $anti_spam_controls .=
                '<'
                . 'input'
                . $space . 'style="visibility:hidden;display:none;"'
                . $space . 'id="sf-as-url"'
                . $space . 'name="sf-as-url"'
                . $space . 'placeholder="Do not enter anything here"'
                . $space . 'type="text"'
                . $space . 'data-sf-as'
                . '>';

        /*
         * honey pot - if anything is in here, we know its spam
         */
        $anti_spam_controls .=
                '<label'
                . $space . 'style="visibility:hidden;display:none;"'
                . $space . 'data-sf-as'
                . $space . 'for="sf-as-comment"'
                . '>' //end of opening label tag
                . 'Do not enter anything in the last 2 fields or your form will be rejected as spam'
                . '</label>';  //end of closing label tab   

        $anti_spam_controls .=
                '<'
                . 'textarea'
                . $space . 'style="visibility:hidden;display:none;"'
                . $space . 'id="sf-as-comment"'
                . $space . 'name="sf-as-comment"'
                . $space . 'placeholder="Do not enter anything here"'
                . $space . 'data-sf-as'
                . '></textarea>';
        //     . $space . '</textarea>';



        /*
         * a time stamp in the future ( 2 days will replace this value) when the user
         * submits the form. when we check it server side, we will see if the date is still in the future. if it is, then we know its not spam.
         */

        $anti_spam_controls .= '<'
                . 'input'
                . $space . 'style="visibility:hidden;display:none;"'
                . $space . 'id="sf-as-time"'
                . $space . 'name="sf-as-time"'
                . $space . 'type="hidden"'
                . $space . 'value="' . time() . '"'
                . '>';

        $this->debug()->logVar( '$anti_spam_controls = ', $anti_spam_controls );
        return $anti_spam_controls;


    }
}
