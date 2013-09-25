/**
 * Save Menu Options Javascript
 *
 * Binds the button actions for the menu metaboxes
 *
 * save-menu-options.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 */


jQuery(document).ready(function(jQuery) {


    /*
     * Bind form events
     */


    simpli.hello.bind_ajax_actions_menu();

})





simpli.hello.bind_ajax_actions_menu = function()
{
    //   var form = jQuery('#'+simpli_hello.plugin.slug + '_' + simpli_hello.metabox_forms[metabox_id].form_name).first();
    var form;


    /*
     * Save with Reload Button
     */
    jQuery("input[id$='settings_save_with_reload']").click(function(event) {
        event.preventDefault();
        ajax_action_slug = 'settings_save_with_reload'
        form = jQuery(this).closest('form')[0];


        simpli.hello.prepare_form(form); //adds nonce and hidden form elements
        simpli.hello.ajaxSubmit(form, event, ajax_action_slug); //executes the ajax submission

    });

    /*
     * Save Button
     */
    jQuery("input[id$='settings_save']").click(function(event) {
        event.preventDefault();
        ajax_action_slug = 'settings_save'
        form = jQuery(this).closest('form')[0];


        simpli.hello.prepare_form(form); //adds nonce and hidden form elements
        simpli.hello.ajaxSubmit(form, event, ajax_action_slug); //executes the ajax submission

    });





    /*
     * Reset with Reload
     */
    jQuery("input[id$='settings_reset_with_reload']").click(function(event) {
        event.preventDefault();
        ajax_action_slug = 'settings_reset_with_reload'

        form = jQuery(this).closest('form')[0];

        if (!confirm(simpli_hello.metabox_forms.reset_message)) {
            return false;
        }
        simpli.hello.ajaxSubmit(form, event, ajax_action_slug);
    });

    /*
     * Reset Button
     */
    jQuery("input[id$='settings_reset']").click(function(event) {
        event.preventDefault();
        ajax_action_slug = 'settings_reset'

        form = jQuery(this).closest('form')[0];

        if (!confirm(simpli_hello.metabox_forms.reset_message)) {

            return false;
        }
        simpli.hello.ajaxSubmit(form, event, ajax_action_slug);
    });


    /*
     * Reset All
     */

    jQuery("input[id$='settings_reset_all']").click(function(event) {
        event.preventDefault();
        ajax_action_slug = 'settings_reset_all'

        form = jQuery(this).closest('form')[0];


        if (!confirm(simpli_hello.metabox_forms.reset_all_message)) {
            return false;
        }
        simpli.hello.ajaxSubmit(form, event, ajax_action_slug);
    });

    /*
     * Update All
     */

    jQuery("input[id$='settings_update_all']").click(function(event) {
        event.preventDefault();
        ajax_action_slug = 'settings_update_all'

        form = jQuery(this).closest('form')[0];

        simpli.hello.ajaxSubmit(form, event, ajax_action_slug);

    });





}
