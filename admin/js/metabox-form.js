/**
 * Metabox Form Save and Reset
 *
 *
 * @package SimpliFramework
 * @subpackage SimpliHello
 */


jQuery(document).ready(function(jQuery) {


    /*
     * Bind form events
     */


    simpli.hello.bind_metabox_form_events();

})








simpli.hello.bind_metabox_form_events = function()
{
    //   var form = jQuery('#'+simpli_hello.plugin.slug + '_' + simpli_hello.metabox_forms[metabox_id].form_name).first();
    var form;


    /*
     * Save with Reload Button
     */
    jQuery("input[id$='_settings_save_with_reload']").click(function(event) {
        form = jQuery(this).closest('form')[0];
        jQuery(form).find('input[name="action"]').val(simpli_hello.plugin.slug + '_settings_save_with_reload');
        jQuery(form).find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
        jQuery('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + jQuery('.hidden-checkbox').attr('name') + '">');
        //jQuery('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
        submit(form,event);
    });
    /*
     * Save Button
     */
    jQuery("input[id$='_settings_save']").click(function(event) {
        form = jQuery(this).closest('form')[0];
     //    event.preventDefault();
         console.log('clicked save button');
        jQuery(form).find('input[name="action"]').val(simpli_hello.plugin.slug + '_settings_save');
        jQuery(form).find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
        jQuery('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + jQuery('.hidden-checkbox').attr('name') + '">');
        //jQuery('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
        submit(form,event);
    });


    /*
     * Reset with Reload
     */

    jQuery("input[id$='_settings_reset_with_reload']").click(function(event) {
       form = jQuery(this).closest('form')[0];
        jQuery(form).find('input[name="action"]').val(simpli_hello.plugin.slug + '_settings_reset_with_reload');

        if (!confirm(simpli_hello.metabox_forms.reset_message)) {
            eventpreventDefault();
            return false;
        }
        submit(form,event);
    });

    /*
     * Reset Button
     */

    jQuery("input[id$='_settings_reset']").click(function(event) {
        form = jQuery(this).closest('form')[0];
        jQuery(form).find('input[name="action"]').val(simpli_hello.plugin.slug + '_settings_reset');

        if (!confirm(simpli_hello.metabox_forms.reset_message)) {
            event.preventDefault();
            return false;
        }
        submit(form,event);
    });


    /*
     * Reset All
     */

    jQuery("input[id$='_settings_reset_all']").click(function(event) {
        form = jQuery(this).closest('form')[0];
        jQuery(form).find('input[name="action"]').val(simpli_hello.plugin.slug + '_settings_reset_all');

        if (!confirm(simpli_hello.metabox_forms.reset_all_message)) {
            event.preventDefault();
            return false;
        }
        submit(form,event);
    });

        /*
     * Update All
     */

    jQuery("input[id$='_settings_update_all']").click(function(event) {
       form = jQuery(this).closest('form')[0];
        jQuery(form).find('input[name="action"]').val(simpli_hello.plugin.slug + '_settings_update_all');
        submit(form,event);

    });


/*
 * Bind any dynamic events ( where the object is not known until run time)
 */
   // jQuery(document).ready(function() {

        /*
         * Do a live bind of the form submit action. Live bind is necessary since the form object is not known until a button is clicked.
         * Live bind must be within a a ready functin
         */
       submit=function(form,event){
            event.preventDefault();
console.log('form submitted');
//return;
            jQuery(form).find('.submit-waiting').show();
            jQuery.post(ajaxurl, jQuery(form).serialize(), function(response) {
console.log('received response');
                //  jQuery('.hidden-checkbox').get(0).type = 'checkbox'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
                jQuery(form).find('.hidden-temp').remove();

                jQuery(form).find('.hidden-checkbox').removeClass('hidden-checkbox');
                jQuery(form).find('.submit-waiting').hide();
                jQuery('#message-body').html(response).fadeOut(0).fadeIn().delay(5000).fadeOut();
            });

   // });

       }


}
