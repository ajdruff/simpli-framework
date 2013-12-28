/**
 * Ajax Actions Post
 *
 * Binds the button actions for the post editor metaboxes
 *
 * form-post-events.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */


/*
 * create the script's namespace
 */
if (typeof simpli.frames.post === 'undefined') {
    simpli.frames.post = {};
}



jQuery(document).ready(function(jQuery) {


    /*
     * Bind form events
     */


    simpli.frames.post.bind_events();
})




simpli.frames.post.bind_events = function()
{

    var form;
    /*
     * Publish button
     */

    jQuery(document).ready(function() {


        //      var form = jQuery('#post');


        /*
         * Remove all the hidden fields holding
         * the unchecked checkboxes values
         */
        simpli.frames.submit.reset_checkboxes(form);
        /*
         * Save Buttons ( publish , save , or our own ajax save )
         */


//        jQuery('#publish,#save-post').click(function(event) {
//
//            simpli.frames.log('submitting via publish button');
//            simpli.frames.submit.prepare_form(form);
//
//        });

    });
    /*
     * Save Button
     */
//    jQuery("input[id$='post_options_save']").click(function(event) {
//        event.preventDefault();
//        ajax_action_slug = 'save_post'
//        form = jQuery(this).closest('form')[0];
//
//
//        simpli.frames.submit.prepare_form(form); //adds nonce and hidden form elements
//        simpli.frames.submit.ajaxSubmit(form, event, ajax_action_slug); //executes the ajax submission
//
//    });

    /**
     * parseButtonId
     *
     * parses the button id and returns an array of parts
     * The button id is assumed to be in the format id="{form_name}_{form_counter}_{action}" where 'action' is
     * either an ajax or non-ajax action.
     *
     * matches:
     * Group 0 - the input string
     * Group 1 - {form_name}
     * Group 2 - {form_counter}
     * Group 3 - {action}
     ** @param none
     * @return object The individual groups of the match
     */

    function parseButtonId(id) {

        /*
         * uses regex
         * /pattern/modifiers
         *
         * .*  any character
         * _([0-9])+_   matches _1_, _99_, _999_ ,etc
         * (,*)  any character
         * i for case insensitivity
         *
         * no g modifier, so not global - it will only make a single match
         *
         * Ref:http://w3schools.com/js/js_obj_regexp.asp ,see the 'try it yourself'
         */
        var pattern = /(.*)_([0-9]+)_(.*)/i;
        parts = id.match(pattern);
        result = {};
        result.form_name = parts[1];
        result.form_counter = parts[2];
        result.action = parts[3];
        return result;
    }
    /*
     * Button Event Handler
     *
     * Binds to any button and parses its id for a form action.
     * it then determines whether the form is ajax or non-ajax, and passes it to
     * the appropriate ajax or non-ajax submit function.
     */

    jQuery("input[type$='submit'],button").click(function(event) {

        /*
         *
         * @var boolean Tracks whether the form should use ajax. Whether the form does or not is determined by whether there is an action attribute in the form html. If it is an empty string, then isAjax is set to true.
         */
        var isAjax = false;
        /*
         * Form uses ajax if the button clicked is not the #post or $publish
         */

        if ((jQuery(this).attr('id') === 'save-post') || (jQuery(this).attr('id') === 'publish')) {
            form = jQuery('#post');
            isAjax = false;
        } else {
            form = jQuery(this).closest('form')[0]; //set the form variable to the form that the object lives within
            isAjax = true;
        }



        if (isAjax) {
            simpli.frames.log('post options are being saved as ajax');
            /*
             *
             * @var object The parts of the id. Includes form_namae, form_counter, and action_slug
             */

            var id_parts = parseButtonId(jQuery(this).attr('id'));
            /*
             *
             * @var string The action slug, which identifies the method in php action slug my_action triggers hookFormActionMyAction
             */
            var form_action_slug = id_parts.action; //extract the action slug from the id





            /*
             * Prompt Hook
             *
             * Provide a Hook where users can add a prompt for an action
             * The prompt can toggle the _cancelSubmit variable to prevent the
             * form from submitting if the user cancels the form submission
             *
             */
            //   var userConfirmed = true;

            var userConfirmed = simpli.frames.do_action(simpli.frames.vars.plugin.slug + '_save_post',
                    {
                        form: jQuery(form)
                                , event: event
                                , element: jQuery(this) //
                    }
            );
            if (userConfirmed === true || userConfirmed === false) {
                if (!userConfirmed) {
                    userConfirmed = true;
                    return (false);
                }
            }







            /*
             * Prepare form , which adds any needed hidden elements
             */

            simpli.frames.submit.prepare_form(form); //adds hidden form elements

            /*
             * Pass on to the form handler
             */


            event.preventDefault(); //to prevent being submitted as non-ajax.
            simpli.frames.submit.ajaxSubmit(form, event, form_action_slug); //submits the form

            /*
             * debug messages
             */
            simpli.frames.log('Action of button you clicked = ' + id_parts.action);
            return(false);
        } else {
            var userConfirmed = simpli.frames.do_action(simpli.frames.vars.plugin.slug + '_save_post',
                    {
                        form: jQuery(form)
                                , event: event
                                , element: jQuery(this) //
                    }
            );
            if (userConfirmed === true || userConfirmed === false) {
                if (!userConfirmed) {
                    userConfirmed = true;
                    return (false);
                }
            }

            /*
             * Prepare form , which adds any needed hidden elements
             */

            simpli.frames.submit.prepare_form(form); //adds hidden form elements

            return (true);
        }



        /*
         * debug messages
         */

        simpli.frames.log('Form action = ' + jQuery(form).attr('action'));
    });
}
