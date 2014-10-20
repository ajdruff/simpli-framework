/**
 * Form Javascript Hooks
 *
 * This script is used by the metabox()->showResponseMessage() method
 * to display the response of a non-ajax submitted form. An ajax submission
 * response is handled by form-submit.js
 *
 * form-response.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */


jQuery(document).ready(function() {

    /*
     * Find the form from the submitted id
     */
    form = jQuery('#' + simpli.frames.vars.forms.submitted_form_id)[0];

simpli.frames.log('form response is ' + simpli.frames.vars.forms.response);
    if (jQuery(form).find('.simpli_forms_response').length > 0) {
        jQuery(form).find('.simpli_forms_response').html(simpli.frames.vars.forms.response).fadeOut(0).fadeIn().delay(5000).fadeOut();
    } else {
        if (jQuery('#message-body').length > 0) {
            jQuery('#message-body').html(simpli.frames.vars.forms.response).fadeOut(0).fadeIn().delay(5000).fadeOut();
        }

    }







});