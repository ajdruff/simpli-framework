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
class Simpli_Addons_Simpli_Forms_Module_Filter extends Simpli_Basev1c0_Plugin_Module {

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
//        $hook_name = $this->getAddon()->getSlug() . '_' . $this->getSlug(); //e.g.: simpli_addons_simpli_forms_filters
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
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    protected function _commonFilter($properties) {
        $this->debug()->t();
        $this->debug()->log('applying the common filters of the base class');
        extract($properties);
        /*
         * Return error if required arguments are not found
         */
        if ((!isset($atts['name'])) || (is_null($atts['name']))) {

            $atts ['_error'][] = 'Name attribute is required';
        }




        /*
         * Add a unique prefix to the name so we dont conflict with other plugins that might be on the same form
         */

        if ((isset($atts['name']))) {
            $atts['name'] = $this->getFieldPrefix() . $atts['name'];
        }



        /*
         * Add a default label if one wasnt provided
         */

        if (!isset($atts['label']) || is_null($atts['label'])) {
            $atts['label'] = $this->getDefaultFieldLabel($atts['name']);
        }

        $tags['form_counter'] = $this->getAddon()->getModule('Form')->form_counter;
        if (isset($this->getAddon()->getModule('Form')->form['form']['name'])) {
            $tags['form_name'] = $this->getAddon()->getModule('Form')->form['form']['name'];
        }

        $this->debug()->logVar('$this->getAddon()->getModule(\'Form\')->form = ', $this->getAddon()->getModule('Form')->form);


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


            $tokens['selected_html'] = (($atts['selected'] == $option_value) ? ' selected="selected"' : '');
            $tokens['option_value'] = $option_value;
            $tokens['option_text'] = $option_text;
            $option_template = '<option {selected_html} value="{option_value}">{option_text}</option>';
            $options_html.=$this->getPlugin()->getTools()->crunchTpl($tokens, $option_template);
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


            $tokens['checked_html'] = (($atts['selected'][$option_value] == 'yes') ? ' checked="checked"  selected="selected" ' : '');
            $tokens['option_value'] = $option_value;
            $tokens['option_text'] = $option_text;
            $option_template = '                            <p>
                         <label style="padding: 18px 2px 5px;" for="checkbox_settings_{OPTION_VALUE}"><span style="padding-left:5px" >{OPTION_TEXT}</span>
                        <input type="checkbox" name="checkbox_settings[{OPTION_VALUE}]"  id="checkbox_settings_{OPTION_VALUE}"  value="yes" {CHECKED_HTML} >


</label>
                        </p>';

            $options_html.=$this->getPlugin()->getTools()->crunchTpl($tokens, $option_template);
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
            $option_template = $this->getAddon()->getModule('Form')->getTheme()->getTemplate($atts['template_option']);

            $options_html.=$this->getPlugin()->getTools()->crunchTpl($tokens, $option_template);
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
        $post = $this->getPlugin()->getTools()->getPost();
        $post_type = $post->post_type;

        $post_ID = $post->ID;

        if (post_type_supports($post_type, 'editor')) {
            /*
             * capture the output of wp_editor so
             * we can assign it to a tag
             */
ob_start();
wp_editor($post->post_content, 'content', array('dfw' => true, 'tabindex' => 1));
$tags['wp_editor']=ob_get_clean();


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
   $atts['id']='postdivrich';
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

        if (!isset($atts['name']) || is_null($atts['name'])) {
            $atts['name'] = 'simpli_forms';
        }
        if (!isset($atts['action']) || is_null($atts['action'])) {
            $atts['action'] = $_SERVER['REQUEST_URI'];
        }

        if (!isset($atts['method']) || is_null($atts['method'])) {
            $atts['method'] = 'post';
        }




        return (compact('scid', 'atts', 'tags'));
    }

    /**
     * Get Default Field Label
     *
     * Uses name to derive a label
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
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


        $this->getPlugin()->getSlug() . '_';
    }

}

