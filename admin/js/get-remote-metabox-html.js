/**
 * Gets all remote metaboxes using ajax requests
 *
 * Mechanism:
 * An add_meta_box () function within a settings module includes a call to $this->getPlugin()->getModule('Admin'), 'meta_box_render') , which in turn includes  admin/templates/metabox/ajax.php script, which enqueues this script and passes onto it a $remote_metaboxes variable from which the urls are taken.
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */


jQuery(document).ready(function(jQuery) {


    /*
     * Retrieve any metaboxes that have been added to the nomstock.com.vars.remote_metaboxes array
     * See the end of this code for an example of how to add a metabox that uses a ajax call to get its html
     */


    nomstock.com.get_remote_meta_boxes();

})

/*
 * Define the function outside of the jquery ready loop or you'll receive reference errors.
 */
    nomstock.com.get_remote_meta_boxes = function() {
        for (var metabox_id in nomstock.com.vars.remote_metaboxes) {

            var spinner_image = jQuery('<img alt="Loading..." src="' + nomstock.com.vars.plugin.admin_url + '/images/wpspin_light.gif" class="spinner_image_class" />'); // this has to be inside the loop or the image will keep moving from metabox to metabox because you would be referencing the same spinner.


            jQuery('#' + metabox_id + ' .handlediv').append(spinner_image); // appends the spinner iamge to the metabox

            /*
             * For each metabox_id, make an ajax request
             * Note that the cache action is used.
             */
            jQuery.getJSON(ajaxurl, {
                action: nomstock.com.vars.plugin.slug + '_ajax_metabox_cache',
                ifModified: true, //required for caching to work correctly
                id: metabox_id,
                url: nomstock.com.vars.remote_metaboxes[metabox_id],
                _nonce: nomstock.com.vars.plugin.nonce,
            }, function(response) {
                jQuery('#' + response.metabox_id + ' .inside').html(response.html);
                jQuery('#' + response.metabox_id + ' .handlediv .spinner_image_class').fadeIn(0).fadeOut('fast');
            });



        }
    };






/*
 * Example of how to use this script

 In a settings module, use the WordPress add_meta_box function within the add_meta_boxes method. The last argument tells the Admin->meta_box_render to use the templates/metabox/ajax.php script to render the metabox using an ajax request to the url provided.
 *         add_meta_box(
 $this->getPlugin()->getSlug() . '_feedback' //HTML id attribute of metabox
 , __('Feedback',$this->getPlugin()->getTextDomain()) //title of the metabox
 , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
 , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
 , 'side'//normal advanced or side The part of the page where the metabox should show
 , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
 , array('metabox' => 'ajax', 'url' => 'http://www.nomstockwp.com/nomstock-framework/metabox-feedback-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
 );



 */