/**
 * Ajax Submit Form
 *
 * Handles the Plugin's ajax submission of a form. This includes the  insertion of the nonce and the management of the checkbox inputs
 * Must be called by an ajax action, examples of which can be found in ajax-actions-menu.js and ajax-actions-post.js
 *
 * ajax-submit-form.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 */


/**
 * Prep Form
 *
 * Prepare the submitted form by adding
 * the nonce and hidden inputs that track the unchecked checkboxes
 *
 * @param object form The form object
 * @returns void
 */



simpli.hello.prepare_form = function(form)
{



    /*
     * add the nonce for our post options
     */
//    $nonce_element = '<input type="hidden" name="' + simpli_hello.plugin.slug + '_nonce" value="' + simpli_hello.save_post_option_nonce + '">';
//
//    jQuery(form).append($nonce_element);


    /*
     * Get all the unchecked checkboxes
     *
     * Explanation of selector:
     * input[id^='" + simpli_hello.plugin.slug + "']  //find all inputs that begin with simpli_hello or whatever your plugin slug is
     *
     * :checkbox:not(:checked)   //of those, get all checkboxes that are not checked
     */

    $unchecked_checkboxes = jQuery(form).find("input[id^='" + simpli_hello.plugin.slug + "']:checkbox:not(:checked)");
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
simpli.hello.reset_checkboxes = function(form) {
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

simpli.hello.ajaxSubmit = function(form, event, ajax_action_slug) {
    console.log('ajax_action_slug=' + ajax_action_slug);
    event.preventDefault();
    ajax_action = simpli_hello.plugin.slug + '_' + ajax_action_slug; //set the ajax action for WordPress

    /*
     * if unique nonces are not configured, then use the default
     */
    if (!simpli_hello.forms.unique_action_nonces) {

        ajax_action_slug = 'default';

    }
    nonce_value = simpli_hello.forms.nonce_values[ajax_action_slug];
    console.log('ajax form submitted, used nonce value for \'' + ajax_action + '\' = ' + nonce_value);

    /*
     * Show the ajax spinner image
     * This appears when waiting for an ajax response
     */
    jQuery(form).find('.submit-waiting').show();



    /*
     * Submit the Ajax Request
     */
    jQuery.post(ajaxurl, //ajaxurl is a localized variable that WordPress inserts that is the url to admin-ajax.php
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
            + '&_ajax_referer_url=' + escape(jQuery(location).attr('pathname') + jQuery(location).attr('search'))

            /*
             * Form Nonce
             *
             * Pass the nonce that was created when we Enqueued the script
             * We pass it here so the user doesnt need to remember to
             * add it in the templates
             * you can find the localized statements where this script is enqueued.
             */
            + '&' + simpli_hello.forms.nonce_field_name + '=' + nonce_value


            , function(response) {

        if (response === false) {

        }


        simpli.hello.reset_checkboxes(form);

        jQuery(form).find('.submit-waiting').hide();

        jQuery('#message-body').html(response).fadeOut(0).fadeIn().delay(5000).fadeOut();
        console.log('response = ' + response);
        //  jQuery(form).find('.post-message-body').html(response).fadeOut(0).fadeIn().delay(2500).fadeOut();
        /*
         * Debugging only
         */
        if (true) {
            if (jQuery('#debug-messages').length === 0) {


                jQuery(form).after('<div id="debug-messages"></div>');
            }



            jQuery('#debug-messages').html(response);// dont fade out
        }
    });

    // });

};


