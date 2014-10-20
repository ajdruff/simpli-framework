/**
 * Save Menu Options Javascript
 *
 * Binds the button actions for the menu metaboxes
 *
 * form-menu-events.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */

/*
 * create the script's namespace
 */
if (typeof simpli.frames.menu === 'undefined') {
    simpli.frames.menu = {};
}

jQuery(document).ready(function(jQuery) {


    /*
     * Bind form events
     */


    simpli.frames.menu.bind_events();

})





simpli.frames.menu.bind_events = function()
{
    //   var form = jQuery('#'+simpli.frames.vars.plugin.slug + '_' + simpli.frames.vars.metabox_forms[metabox_id].form_name).first();
    var form;


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

simpli.frames.log('clicked form submission button');


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


        form = jQuery(this).closest('form')[0]; //set the form variable to the form that the object lives within

        /*
         *
         * @string The 'action' attribute in the <form> tag
         */
        var form_action_attribute = jQuery(form).attr('action');



        /*
         * Prompt Hook
         *
         * Provide a Hook where users can add a prompt for an action
         * The prompt can toggle the _cancelSubmit variable to prevent the
         * form from submitting if the user cancels the form submission
         *
         */
        //   var userConfirmed = true;
        var userConfirmed = simpli.frames.do_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + form_action_slug,
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
         * Determine if Ajax
         * If the form action attribute is empty, we assume an ajax form handler.
         * If the form action attribute has the token {action} in it (put there by the filter), we repace that token with the
         * action supplied by the button.
         */
        if (form_action_attribute === '') {
            isAjax = true;
        }
        else {
            isAjax = false;

        }

        if (isAjax === false) {
            /*
             * Update the form action attribute by
             * replacing the {action} token with the form action supplied by the button
             * You also need to decode the uri or the replacements wont always work.
             */

            jQuery(form).attr('action', decodeURIComponent(form_action_attribute).replace('{action}', form_action_slug));//update action attribute in the form



        }





        /*
         * Prepare form , which adds any needed hidden elements
         */

        simpli.frames.submit.prepare_form(form); //adds hidden form elements

        /*
         * Pass on to the form handler
         */

        if (isAjax) {
            event.preventDefault();//to prevent being submitted as non-ajax.
            simpli.frames.submit.ajaxSubmit(form, event, form_action_slug); //submits the form

        } else {
            simpli.frames.submit.nonAjaxSubmit(form, event, form_action_slug); //submits the form

        }



        /*
         * debug messages
         */
        simpli.frames.log('Action of button you clicked = ' + id_parts.action);
        simpli.frames.log('Form action = ' + jQuery(form).attr('action'));



    });



}
