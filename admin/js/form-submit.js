/**
 * Ajax Submit Form
 *
 * Handles the Plugin's submission of a form either with or without ajax. This includes the  insertion of the nonce and the management of the checkbox inputs
 * called by event bindings in the form-menu-events.js and form-post-events.js scripts.
 * Loaded by the Metabox class
 *
 * form-submit.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */


/*
 * create the script's namespace
 */

if (typeof simpli.frames.submit === 'undefined') {
    simpli.frames.submit = {};
}


/**
 * Prep Form
 *
 * Prepare the submitted form by adding
 * the nonce and hidden inputs that track the unchecked checkboxes
 *
 * @param object form The form object
 * @returns void
 */



simpli.frames.submit.prepare_form = function(form)
{



    /*
     * add the nonce for our post options
     */
//    $nonce_element = '<input type="hidden" name="' + simpli.frames.vars.plugin.slug + '_nonce" value="' + simpli.frames.vars.save_post_option_nonce + '">';
//
//    jQuery(form).append($nonce_element);


    /*
     * Get all the unchecked checkboxes
     *
     * Explanation of selector:
     * input[id^='" + simpli.frames.vars.plugin.slug + "']  //find all inputs that begin with simpli.frames.vars or whatever your plugin slug is
     *
     * :checkbox:not(:checked)   //of those, get all checkboxes that are not checked
     */

    $unchecked_checkboxes = jQuery(form).find("input[id^='" + simpli.frames.vars.plugin.slug + "']:checkbox:not(:checked)");
    //jQuery('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
    /*
     * Insert a hidden input field containing the unchecked checkbox's value
     */
    $unchecked_checkboxes.each(function() {

        jQuery(this).after('<input class="hidden-temp" type="hidden" name="' + jQuery(this).attr('name') + '">');
    })
}


/*
 * Reset Checkboxes
 *
 * Removes the hidden input fields that were
 * added on the previous submission
 *
 * @param {type} form
 * @returns {undefined}
 */
simpli.frames.submit.reset_checkboxes = function(form) {
    /*
     * remove any temporary hidden fields that were previously added.
     */
    jQuery(form).find('.hidden-temp').remove();
};
/*
 * Ajax Submit
 *
 * Submits form using ajax and available nonces.
 *
 * @param {type} form
 * @returns {undefined}
 */

simpli.frames.submit.ajaxSubmit = function(form, event, ajax_action_slug) {
    simpli.frames.log('ajax_action_slug=' + ajax_action_slug);
    event.preventDefault();
    var ajax_action = simpli.frames.vars.plugin.slug + '_' + ajax_action_slug; //set the ajax action for WordPress


    var output_element = {};
    
    /*
     * WordPress normally makes ajaxurl available within Admin.
     * If it hasn't been defined, we are likely trying to make
     * an ajax request outside of admin. In that case,
     * we can use the localized version
     */
    if (typeof ajaxurl ==='undefined') {
        ajaxurl=simpli.frames.vars.forms['ajaxurl'];
        simpli.frames.log('ajaxurl = ' + ajaxurl);

    }
    
    
    /*
     * if unique nonces are not configured, then use the default
     */
    if (!simpli.frames.vars.forms.unique_action_nonces) {

        ajax_action_slug = 'default';
    }
    nonce_value = simpli.frames.vars.forms.nonce_values[ajax_action_slug];
    simpli.frames.log('ajax form submitted, used nonce value for \'' + ajax_action + '\' = ' + nonce_value);
    /*
     * Show the ajax spinner image
     * This appears when waiting for an ajax response
     */
    jQuery(form).find('.submit-waiting').show();
    /*
     * Submit the Ajax Request
     */



    data =
            /*
             * Form Fields
             *
             * Serialize is used to turn all the form's fields
             * into a query string that is ready for posting
             */
            jQuery(form).serialize()
            /*
             * Ajax Action
             *
             * This will tell WordPress ajax script which hook to call
             */
            + '&action=' + ajax_action
            /*
             * HTTP Referer URL
             *
             * Pass the current page relative url as the referring url
             * This uses escape() so the string wont be interpreted as fields
             * jQuery(location).attr('search') is the query string
             */
            + '&_simpli_forms_referer_url=' + escape(jQuery(location).attr('pathname') + jQuery(location).attr('search'))

            /*
             * Form Nonce
             *
             * Pass the nonce that was created when we Enqueued the script
             * We pass it here so the user doesnt need to remember to
             * add it in the templates
             * you can find the localized statements where this script is enqueued.
             */
            + '&' + simpli.frames.vars.forms.nonce_field_name + '=' + nonce_value;


    success = function(response) {




        simpli.frames.submit.reset_checkboxes(form);
        jQuery(form).find('.submit-waiting').hide();
        /*
         * display the response differently, depending
         * on whether we are debugging
         *
         */

        simpli.frames.log('debug is ' + 'on');
        simpli.frames.log('simpli.frames.vars.plugin.debug =  ' + simpli.frames.vars.plugin.debug);



        /*
         * If an element exists within the form with class ='.simpli_forms_response',
         * then use it. otherwise, use the popup if available.
         */

        if (typeof simpli.frames.vars.forms['target'] !== 'undefined') {
            output_element = jQuery('#' + simpli.frames.vars.forms['target']);
        }
        else if (jQuery(form).find('.simpli_forms_response').length > 0) {
            output_element = jQuery(form).find('.simpli_forms_response');
        } else {

            if (jQuery('#message-body').length > 0) {
                output_element = jQuery('#message-body');
                if (simpli.frames.vars.plugin.debug) {
                    /*
                     * don't use message-body if debugging since it floats and is difficult to read. instead add a debugging container.
                     */
                    if (jQuery('#debug-messages').length === 0) {//if a debug message container doesnt exist

                        /*
                         * then add it
                         */
                        jQuery(form).after('<div id="debug-messages"></div>');
                    }

                    output_element = jQuery('#debug-messages');
                    output_element = jQuery('#message-body');
                }
            } else {// if there is no other target found, replace the form itself with the message

                output_element = jQuery(form);

            }

        }

        simpli.frames.log('output element = ' + jQuery(output_element).html());
        /*
         * If debugging, then don't clear the response message
         * once its displayed. This is because the response may be
         * quite long and the user will need time to analyze it
         */
        if (simpli.frames.vars.plugin.debug) {

            output_element.html(response).fadeOut(0).fadeIn().delay(5000);
        }

        else {

            output_element.html(response).fadeOut(0).fadeIn().delay(5000).fadeOut();

        }



        return false;
    }



    /*
     * Set Method based on Form's method attribute
     */
    if (jQuery(form).attr('method').toUpperCase() === 'GET') {
        simpli.frames.log('submitting via ajax and GET method');
        method = 'GET';
    } else {
        simpli.frames.log('submitting via ajax and POST method');
        method = 'POST';
    }

    jQuery.ajax({
        type: method,
        url: ajaxurl,
        data: data,
        success: success,
        dataType: 'html' //xml,html,json,jsonp,text http://api.jquery.com/jQuery.ajax/
    });





}; //end of the definition of ajaxSubmit
/*
 * Non-Ajax Submit
 *
 * Submits form using ajax and available nonces.
 *
 * @param  object form
 * @param  object event
 * @param string form_action_slug
 * @returns {undefined}
 */

simpli.frames.submit.nonAjaxSubmit = function(form, event, form_action_slug) {

    var nonce_index = form_action_slug;
    simpli.frames.log('form_action_slug=' + form_action_slug);
    form_action = simpli.frames.vars.plugin.slug + '_' + form_action_slug; //set the form action
    simpli.frames.log('submitting the form using a non-ajax request');
    /*
     * if unique nonces are not configured, then use the default
     */
    if (!simpli.frames.vars.forms.unique_action_nonces) {

        nonce_index = 'default';
    }
    nonce_value = simpli.frames.vars.forms.nonce_values[nonce_index];
    simpli.frames.log('Non-Ajax form submitted, used nonce value for \'' + nonce_index + '\' = ' + nonce_value);
    /*
     * Show the spinner image
     * It will spin until the form submits and refreshes the page,
     * so no need to clear it ( remember, this is a non-ajax request)
     */
    jQuery(form).find('.submit-waiting').show();
    /*
     * update the action attribute
     */
    form_action_attribute = jQuery(form).attr('action');
    /*
     * if using a GET method, we must make sure the action attribute is added to
     * the form submission so it gets added back to the url. otherwise, it will
     * be replaced by the form's field values.
     */
//preserveActionParms();
    if (jQuery(form).attr('method').toUpperCase() === 'GET') {
        actionParms = jQuery.url(form_action_attribute).param();

//        jQuery.each(actionParms, function(key, value) {
//            var_name = key;
//            var_value = value;
//            /*
//             * append the GET action value to the form as a hidden value
//             */
//            jQuery(form).append('<input type="hidden" name="' + var_name + '" value="' + var_value + '">');
//
//
//        }
//
//
//
//        ) //end for each

        /*
         * add action
         */
        simpli.frames.vars.query_var_action = 'simpli_frames_action';
        jQuery(form).append('<input type="hidden" name="' + simpli.frames.vars.query_var_action + '" value="' + form_action_slug + '">');


        var _simpli_forms_referer_url = escape(jQuery(location).attr('pathname') + jQuery(location).attr('search'));
        simpli.frames.log('_simpli_forms_referer_url = ' + _simpli_forms_referer_url);
        jQuery(form).append('<input name="_simpli_forms_referer_url" value="' + _simpli_forms_referer_url + '" type="hidden" >');
    }

//    simpli.frames.logWarn('bailed out of form submission');
//    event.preventDefault();
//    return(false);
    /*
     * append the nonce field to the form
     */
    jQuery(form).append('<input type="hidden" name="' + simpli.frames.vars.forms.nonce_field_name + '" value="' + nonce_value + '">');
    /*
     * append the id of the form
     */
    jQuery(form).append('<input type="hidden" name="simpli_forms_id" value="' + jQuery(form).attr('id') + '">');
    
    
    /*
     * submit the form
     */
    form.submit();
    
    
    /*
     * Add the nonce value to the form , if not already added.
     */
//+ '&action=' + ajax_action
// + '&' + simpli.frames.vars.forms.nonce_field_name + '=' + nonce_value

};
