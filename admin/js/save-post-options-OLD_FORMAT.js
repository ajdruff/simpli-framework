/**
 * Save Post Options Javascript
 *
 * Handles the Plugin's ajax *and* non-ajax submission (via the publish and update WordPress buttons) of the post user options, as added by the metaboxes. This includes the  insertion of the nonce and the management of the checkbox inputs
 *
 * save-post-options.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 */


jQuery(document).ready(function(jQuery) {


    /*
     * Bind form events
     */


    simpli.hello.bind_metabox_post_form_events();

})

/*
 * Prep Form
 *
 * Prepare the submitted form by adding
 * the nonce and hidden inputs that track the unchecked checkboxes
 *
 * @param none
 * @returns void
 */



simpli.hello.prepare_form = function(form)
{



    /*
     * add the nonce for our post options
     */
    $nonce_element = '<input type="hidden" name="' + simpli_hello.plugin.slug + '_nonce" value="' + simpli_hello.save_post_option_nonce + '">';

    jQuery(form).append($nonce_element);


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

}

simpli.hello.bind_metabox_post_form_events = function()
{

    var form;


    /*
     * Publish button
     */

    jQuery(document).ready(function() {


        var form = jQuery('#post');


        /*
         * Remove all the hidden fields holding
         * the unchecked checkboxes values
         */
        simpli.hello.reset_checkboxes(form);


        /*
         * Save Buttons ( publish , save , or our own ajax save )
         */


        jQuery('#publish,#save-post').click(function(event) {
            console.log('submitting via publish button');
            simpli.hello.prepare_form(form);
        });

    });


    /*
     * Ajax Save Button
     */

    jQuery('input[id$="_post_options_save"]').click(function(event) {
        console.log('submitting via ajax button');
        form = jQuery(this)[0].form; //gets the form surrounding the button that was clicked
        simpli.hello.prepare_form(form); //adds nonce and hidden form elements
        event.preventDefault(); //prevents a non-ajax submission
        ajaxSubmit(form, event); //executes the ajax submission
    });


    /*
     * Bind any dynamic events ( where the object is not known until run time)
     */


    /*
     * Ajax Form Submission
     *
     * Contains a Live Bind of the form submit action
     * Live bind is necessary since the form object is not known until a button is clicked.
     * Live bind must be within a a ready function
     *
     * @param object form The form containing the button that was clicked. This comes from the
     * form variable set when a button is clicked
     * @param event The event object of the form
     * @return void
     */
    ajaxSubmit = function(form, event) {
        event.preventDefault();

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
                + '&action=' + simpli_hello.plugin.slug + '_post_options_save'
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
                 */
                + '&' + simpli_hello.plugin.slug + '_nonce=' + simpli_hello.save_post_option_nonce


                , function(response) {



            simpli.hello.reset_checkboxes(form);

            jQuery(form).find('.submit-waiting').hide();


            /*
             * attach the response
             * to the element with the'.post-message-body' class
             */
            // jQuery(form).find('.post-message-body').html(response); //no fade in/fadeout
            jQuery(form).find('.post-message-body').html(response).fadeOut(0).fadeIn().delay(2500).fadeOut();

        });
        // });

    }


}
