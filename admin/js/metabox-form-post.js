/**
 * Metabox Form Save and Reset for the Post Editor
 *
 * metabox-form-post.js
 * @package SimpliFramework
 * @subpackage SimpliHello
 */


jQuery(document).ready(function(jQuery) {


    /*
     * Bind form events
     */


    simpli.hello.bind_metabox_post_form_events();

})








simpli.hello.bind_metabox_post_form_events = function()
{
    //   var form = jQuery('#'+simpli_hello.plugin.slug + '_' + simpli_hello.metabox_forms[metabox_id].form_name).first();
    var form;



    /*
     * Save Button
     */
    jQuery("input[id$='_post_options_save']").click(function(event) {
        form = jQuery(this)[0].form;

        //    event.preventDefault();
        console.log('clicked save button');
        jQuery(form).find('input[name="action"]').val(simpli_hello.plugin.slug + '_post_options_save');
        jQuery(form).find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
        jQuery('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + jQuery('.hidden-checkbox').attr('name') + '">');
        //jQuery('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
        submit(form, event);
    });




    /*
     * Bind any dynamic events ( where the object is not known until run time)
     */
    // jQuery(document).ready(function() {

    /*
     * Do a live bind of the form submit action. Live bind is necessary since the form object is not known until a button is clicked.
     * Live bind must be within a a ready functin
     */
    submit = function(form, event) {
        event.preventDefault();
        console.log('form submitted');

//return;
        jQuery(form).find('.submit-waiting').show();
        jQuery.post(ajaxurl, jQuery(form).serialize(), function(response) {

            //  jQuery('.hidden-checkbox').get(0).type = 'checkbox'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
            jQuery(form).find('.hidden-temp').remove();

            jQuery(form).find('.hidden-checkbox').removeClass('hidden-checkbox');
            jQuery(form).find('.submit-waiting').hide();
            //jQuery(form).find('.post-message-body').html(response).fadeOut(0).fadeIn().delay(2500).fadeOut();
            jQuery(form).find('.post-message-body').html(response);
            //jQuery(form).find('.post-message-body').html(response)
            //console.log(jQuery(form).children().find('.message-body').html(response));
            //  jQuery(form).find('.message-body').html(response);

            // console.log(jQuery(form).children().find('.message-body').response);
        });

        // });

    }


}
