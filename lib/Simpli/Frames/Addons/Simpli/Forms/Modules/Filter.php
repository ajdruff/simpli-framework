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
    public function filter($properties) {
        $this->debug()->t();
        $this->debug()->logVars(get_defined_vars());
        $this->debug()->log('Filtering using filter method in base class');
        $method = 'filter' . ucwords($properties['scid']);




        /*
         * apply the common filter
         */
        $properties = $this->_commonFilter($properties);

        /*
         * apply the element filter
         */
        if (method_exists($this, $method)) {
            $this->debug()->log('base class is calling filter for ' . $method);
            $properties = $this->$method($properties);
        } else {
            $this->debug()->log('No filter for ' . $method . ' exists');
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
    protected function _commonFilter($properties) {
        $this->debug()->t();
        $this->debug()->log('applying the common filters of the base class');

        extract($properties);
        /*
         * Return error if required arguments are not found
         */
        if (array_key_exists('name', $atts) && is_null($atts['name'])) {

            $atts ['_error'][] = 'Name attribute is required';
        }






        /*
         * Add a unique prefix to the name so we dont conflict with other plugins that might be on the same form
         */

        if (array_key_exists('name', $atts) && is_null($atts['name'])) {
            $atts['name'] = $this->getFieldPrefix() . $atts['name'];
        }



        /*
         * Add a default label if one wasnt provided
         */


        if (array_key_exists('label', $atts) && is_null($atts['label'])) {

            $atts['label'] = $this->getDefaultFieldLabel($atts['name']);
        }

        $this->debug()->logVar('$atts = ', $atts);

        $tags['form_counter'] = $this->addon()->getModule('Form')->form_counter;
        if (isset($this->addon()->getModule('Form')->form['form']['name'])) {
            $tags['form_name'] = $this->addon()->getModule('Form')->form['form']['name'];
        }



        return (compact('scid', 'atts', 'tags'));
    }

    /**
     * Filter Text
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterText($properties) {
        $this->debug()->t();

        extract($properties);


        return (compact('scid', 'atts', 'tags'));
    }

    /**
     * Filter Response
     *
     * Filters the Response tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterResponse($properties) {
        $this->debug()->t();

        extract($properties);
        $tags['response_html'] = apply_filters('simpli_forms_response', '');

        return (compact('scid', 'atts', 'tags'));
    }

    /**
     * Filter File
     *
     * Filters the File Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterFile($properties) {
        $this->debug()->t();

        extract($properties);


        return (compact('scid', 'atts', 'tags'));
    }

    /**
     * Filter Dropdown
     *
     * Filters the Dropdown Attributes
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterDropdown($properties) {
        $this->debug()->t();

        extract($properties);

        /*
         * use the shared code for radio,dropdown, and checkbox elements
         */
        return($this->_filterOptions($properties));
    }

    protected function filterDropdownOld($properties) {
        $this->debug()->t();

        extract($properties);



        /*
         * Create the options_html
         *
         */
        $options_html = '';
        $tokens = $atts;



        foreach ($atts['options'] as $option_value => $option_text) {


            $tokens['selected_html'] = (($atts['selected'] == $option_value) ? ' selected = "selected"' : '');
            $tokens['option_value'] = $option_value;
            $tokens['option_text'] = $option_text;
            $option_template = '<option {selected_html} value = "{option_value}">{option_text}</option>';
            $options_html.=$this->plugin()->tools()->crunchTpl($tokens, $option_template);
        }


        $tags['options_html'] = $options_html;





        return (compact('scid', 'atts', 'tags'));
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
    protected function filterCheckbox($properties) {
        $this->debug()->t();

        extract($properties);

        /*
         * use the shared code for radio,dropdown, and checkbox elements
         */
        return($this->_filterOptions($properties));
    }

    protected function filterCheckboxOld($properties) {
        $this->debug()->t();

        extract($properties);



        /*
         * Create the options_html
         *
         */
        $options_html = '';
        $tokens = $atts;

        $this->debug()->logVar('$atts = ', $atts);

        foreach ($atts['options'] as $option_value => $option_text) {


            $tokens['checked_html'] = (($atts['selected'][$option_value] == 'yes') ? ' checked = "checked" selected = "selected" ' : '');
            $tokens['option_value'] = $option_value;
            $tokens['option_text'] = $option_text;
            $option_template = ' <p>
<label style = "padding: 18px 2px 5px;" for = "checkbox_settings_{OPTION_VALUE}"><span style = "padding-left:5px" >{OPTION_TEXT}</span>
<input type = "checkbox" name = "checkbox_settings[{OPTION_VALUE}]" id = "checkbox_settings_{OPTION_VALUE}" value = "yes" {CHECKED_HTML} >


</label>
</p>';

            $options_html.=$this->plugin()->tools()->crunchTpl($tokens, $option_template);
        }


        $tags['options_html'] = $options_html;


        $properties = compact('scid', 'atts', 'tags');
        $this->debug()->logVar('$properties = ', $properties);

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
    private function _filterOptions($properties) {

        $this->debug()->t();

        extract($properties);
//dropdown
//$tokens['selected_html'] = (($atts['selected'] == $option_value) ? ' selected="selected"' : '');
//checkbox
//$tokens['checked_html'] = (($atts['selected'][$option_value] == 'yes') ? ' checked="checked"  selected="selected" ' : '');


        /*
         * Create the options_html
         *
         */
        $options_html = '';
        $tokens = $atts;
        foreach ($atts['options'] as $option_value => $option_text) {
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
            if (is_array($atts['selected'])) {
                if (isset($atts['selected'][$option_value])) {


                    $tokens['checked'] = (($atts['selected'][$option_value] == 'yes') ? ' checked="checked"' : '');
                } else {
                    $tokens['checked'] = '';
                }
            } else {

                $tokens['checked'] = (($atts['selected'] == $option_value) ? ' checked="checked"' : '');
            }

            /*
             * dropdown
             */
            if (is_array($atts['selected'])) {
                if (isset($atts['selected'][$option_value])) {
                    $tokens['selected'] = (($atts['selected'][$option_value] == 'yes') ? ' selected="selected" ' : '');
                    $this->debug()->logVar('$option_value = ', $option_value);
                    $this->debug()->logVar('$selected[$option_value] = ', $atts['selected'][$option_value]);
                    $this->debug()->logVar('$tokens[selected] = ', $tokens['selected']);
                } else {
                    $tokens['selected'] = '';
                }
            } else {

                $tokens['selected'] = (($atts['selected'] == $option_value) ? ' selected="selected" ' : '');
            }

//dropdown - works
//     $tokens['selected'] = (($atts['selected'] == $option_value) ? ' selected="selected" ' : '');

            $tokens['option_value'] = $option_value;
            $tokens['option_text'] = $option_text;
            $option_template = $this->addon()->getModule('Form')->getTheme()->getTemplate($atts['template_option']);

            $options_html.=$this->plugin()->tools()->crunchTpl($tokens, $option_template);
            $this->debug()->logVar('$tokens = ', $tokens);
        }

        $this->debug()->logVar('$options_html = ', $options_html);

        $tags['options_html'] = $options_html;





        return (compact('scid', 'atts', 'tags'));
    }

    /**
     * Filter Radio
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterRadio($properties) {
        $this->debug()->t();

        extract($properties);

        /*
         * use the shared code for radio,dropdown, and checkbox elements
         */
        return($this->_filterOptions($properties));
    }

    /**
     * Post Editor Filter
     *
     * Provides the php required for the post editor to work. The code is taken directly from wp-admin/edit-form-advanced.php , just search for 'postdivrich' or 'wp_editor'
     *
     * @param none
     * @return void
     */
    public function filterPostEditor($properties) {
        $this->debug()->t();

        extract($properties);
        $post = $this->plugin()->post()->getPost();
        $post_type = $post->post_type;
        $this->debug()->logVar('$post_type = ', $post_type);
        $post_ID = $post->ID;
        $this->debug()->logVar('$post_ID = ', $post_ID);

        if (post_type_supports($post_type, 'editor')) {
            $this->debug()->log('Post type ' . $post_type . ' supports editor, displaying editor');

            /*
             * capture the output of wp_editor so
             * we can assign it to a tag
             */
            ob_start();
            wp_editor($post->post_content, 'content', array('dfw' => true, 'tabindex' => 1));
            $tags['wp_editor'] = ob_get_clean();


            $tags['word_count'] = sprintf(__('Word count: %s'), '<span class="word-count">0</span>');
            $tags['last_edit'] = '';
            if ('auto-draft' != $post->post_status) {
                $this->debug()->log('not an auto draft');
                $this->debug()->log('adding last edit');
                $tags['last_edit'].= '<span id="last-edit">';
                if ($last_id = get_post_meta($post_ID, '_edit_last', true)) {
                    $last_user = get_userdata($last_id);
                    $tags['last_edit'].=sprintf(__('Last edited by %1$s on %2$s at %3$s'), esc_html($last_user->display_name), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));
                } else {
                    $this->debug()->log('auto draft');
                    $this->debug()->log('adding last edit');
                    $tags['last_edit'].=sprintf(__('Last edited on %1$s at %2$s'), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));
                }
                $tags['last_edit'].= '</span>';
            }
        } else {
            $this->debug()->logError('Post type ' . $post_type . ' doesnt support editor, not showing editor');

            /*
             * If Editor Not supported
             * Output nothing
             */
            $atts['content_override'] = '';
        }
        /*
         * Ensure default editor id.
         * if id is duplicate, the editor wont display
         */
        if (is_null($atts['id'])) {
            $atts['id'] = 'postdivrich';
        }


        $properties = compact('scid', 'atts', 'tags');

        return ($properties);
    }

    /**
     * Form Start
     *
     * Filters the Text Tag Attribute
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterFormStart($properties) {
        $this->debug()->t();

        extract($properties);


        if (array_key_exists('name', $atts) && is_null($atts['name'])) {

            $atts['name'] = 'simpli_forms';
        }


        /*
         * Make Ajax the default form behavior ( if no 'ajax' attribute is provided)
         */

        if (
                !array_key_exists('ajax', $atts)  //if ajax is not provided ( default is true)
                || array_key_exists('ajax', $atts) && $atts['ajax'] !== false  // or if ajax is true
        ) {

            $atts['ajax'] === true;
        }

        /*
         *
         * For ajax forms, make the form action attribute an empty string
         * This will allow javascript to use an empty action attribute as a flag
         * to tell it to use an ajax script to submit the form
         */
        if (array_key_exists('ajax', $atts) && $atts['ajax'] === true) {

            $atts['action'] = ''; //form action attribute must be empty string if ajax
        } elseif (array_key_exists('ajax', $atts) && $atts['ajax'] === false) {

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

                    array_key_exists('action', $atts) && is_null($atts['action']) //if no action property provided
                    || trim($atts['action']) === ''
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
                $atts['action'] = $this->plugin()->tools()->rebuildUrl(array($this->plugin()->QUERY_VAR . '_action' => '{action}'));
            }
        }



        if (array_key_exists('method', $atts) && is_null($atts['method'])) {
            $atts['method'] = 'post';
        }

        if (array_key_exists('enctype', $atts) && is_null($atts['enctype'])) {
            $tags['enctype_html'] = '';
        } else {
            $tags['enctype_html'] = 'enctype = ' . $atts['enctype'];
        }



        return (compact('scid', 'atts', 'tags'));
    }

    /**
     * Get Default Field Label
     *
     * Uses name to derive a label
     * * @param none
     * @return void
     */
    function getDefaultFieldLabel($name) {
        $this->debug()->t();



        $label = str_replace($this->getFieldPrefix(), '', $name);
        $label = strtolower($label);
        $label = str_replace('_', ' ', $label);
        $label = ucwords($label);
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

}

