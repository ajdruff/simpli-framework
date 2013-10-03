/**
 * Ajax Actions Post
 *
 * Binds the button actions for the post editor metaboxes
 *
 * ajax-actions-post.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 */


jQuery(document).ready(function(jQuery) {


    /*
     * Bind form events
     */


    simpli.frames.bind_ajax_actions_post();

})





simpli.frames.bind_ajax_actions_post = function()
{
    //   var form = jQuery('#'+simpli_frames.plugin.slug + '_' + simpli_frames.metabox_forms[metabox_id].form_name).first();
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
        simpli.frames.reset_checkboxes(form);


        /*
         * Save Buttons ( publish , save , or our own ajax save )
         */


        jQuery('#publish,#save-post').click(function(event) {
            console.log('submitting via publish button');
            simpli.frames.prepare_form(form);
        });

    });

    /*
     * Save Button
     */
    jQuery("input[id$='post_options_save']").click(function(event) {
        event.preventDefault();
        ajax_action_slug = 'save_post'
        form = jQuery(this).closest('form')[0];


        simpli.frames.prepare_form(form); //adds nonce and hidden form elements
        simpli.frames.ajaxSubmit(form, event, ajax_action_slug); //executes the ajax submission

    });







}
