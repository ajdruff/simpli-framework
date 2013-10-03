
/**
 * Publish/Update Post Actions
 *
 * Handles the saving of post user options when a user clicks the WordPress 'publish' or 'update' button.
 * Required:ajax-submit-form.js to supply the reset_checkboxes(form) and prepare_form methods.
 *
 * publish-post-actions.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 */

/*
 * Publish button
 */

jQuery(document).ready(function() {


    var form = jQuery('#post');


    /*
     * Remove all the hidden fields holding
     * the unchecked checkboxes values
     */
    simpli.frames.reset_checkboxes(form);


    /*
     * Save Buttons ( publish , save , or our own ajax save )
     */


    jQuery('#publish,#save-post').click(function(event) {
        console.log('submitting via publish button');
        simpli.frames.prepare_form(form);
    });

});