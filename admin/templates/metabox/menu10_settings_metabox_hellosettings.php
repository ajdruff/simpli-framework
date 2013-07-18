<?php
#todo: add class functions to handle select,radio, and checkbox markup. use the nstock.common.php code
//must give the form a unique name since there will be multiple forms on the page.
$form_name = "hello_settings";


$checkbox_settings=$this->getPlugin()->getSetting('checkbox_setting');

?>
<form name="<?php echo $this->getPlugin()->getSlug(); ?><?php echo '_' . $form_name; ?>" id="<?php echo $this->getPlugin()->getSlug() . "_$form_name"; ?>" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<?php wp_nonce_field($this->getPlugin()->getSlug()); ?>
    <input type="hidden" name="action"  value="" />



    <h4 class="title"><?php _e('Text Placement', $this->getPlugin()->getSlug()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Where do you want to place the text?', $this->getPlugin()->getSlug()); ?>

                </th>
                <td>
                    <fieldset>
                        <label>
                            <label>
                                <input type="radio" name="hello_global_default_placement" value="before"  <?php echo (($this->getPlugin()->getSetting('hello_global_default_placement') == 'before') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Before the content', $this->getPlugin()->getSlug()); ?></span></label>

                            <label>
                                <input type="radio" name="hello_global_default_placement" value="after" <?php echo (($this->getPlugin()->getSetting('hello_global_default_placement') == 'after') ? ' checked="checked"' : ''); ?> /> <span><?php _e('After the content', $this->getPlugin()->getSlug()); ?></span>
                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Selecting \'Before\' will add your text<em> before</em> the content in the post. Selecting \'After\' will add your text <em>after</em> the content in the post.  ' , $this->getPlugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>



        </tbody>
    </table>

<h4 class="title"><?php _e('Global Enable', $this->getPlugin()->getSlug()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Enable or Disable Text Insertion for All Posts', $this->getPlugin()->getSlug()); ?>

                </th>
                <td>
                    <fieldset>
                        <label>
                            <label>
                                <input type="radio" name="hello_global_default_enabled" value="enabled"  <?php echo (($this->getPlugin()->getSetting('hello_global_default_enabled') == 'enabled') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Enabled', $this->getPlugin()->getSlug()); ?></span></label>

                            <label>
                                <input type="radio" name="hello_global_default_enabled" value="disabled" <?php echo (($this->getPlugin()->getSetting('hello_global_default_enabled') == 'disabled') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Disabled', $this->getPlugin()->getSlug()); ?></span>
                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Selecting \'Enabled\' will turn on text insertion for all posts that have it enabled. Selecting \'Disabled\' will prevent any text from being inserted even if your post settings allow it.  ' , $this->getPlugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>



        </tbody>
    </table>



 <h4 class="title"><?php _e('Hello World Text', $this->getPlugin()->getSlug()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Text to Insert into Post Content:', $this->getPlugin()->getSlug()); ?>

                </th>
                <td>
                    <fieldset>
                        <label>
                            <label>

                                                        <input name="hello_global_default_text" type="text" id="hello_global_default_text" class="regular-text code" value="<?php echo $this->getPlugin()->getSetting('hello_global_default_text'); ?>" />

                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Add the text that you want to insert into your post content. ' , $this->getPlugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>



        </tbody>
    </table>




<?php
/*

 *
 *
 * How this form works:
 * The input buttons of type 'submit' submit the form.
 * The form submission event makes an ajax request using the ajaxurl which is a wordpress defined variable for the location of the admin-ajax.php script
 * This script looks for the 'action' attribute, and matches it with the wp_ajax filter added to map the action with a function within your class
 * In this case , the action is defined in the 'settings-save' or 'settings-reset' click events, which redefine the form action to be the slug + '_settings_save or _settings_reset
 * So to process, the form, you need to make sure you have a method within your class that matches the action of each of the buttons
 *  */
?>


    <p class="button-controls">
        <input type="submit" id="<?php echo $form_name; ?>_settings-reset" class="button-secondary" value="Reset" name="<?php echo $form_name; ?>_settings-reset">
        <input type="submit" id="<?php echo $form_name; ?>_settings-save" class="button-primary" value="Save Changes" name="<?php echo $form_name; ?>_settings-save">
        <img alt="<?php _e('Waiting...', $this->getPlugin()->getSlug()); ?>" src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />
    </p>




</form>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var form = $('#<?php echo $this->getPlugin()->getSlug() . "_$form_name"; ?>').first();
        $('#<?php echo $form_name; ?>_settings-save').click(function() {

            $(form).find('input[name="action"]').val('<?php echo $this->getPlugin()->getSlug(); ?>_settings_save_with_reload');
            $(form).find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
            $('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + $('.hidden-checkbox').attr('name') + '">');
            //$('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
        });
        $('#<?php echo $form_name; ?>_settings-reset').click(function() {
            $(form).find('input[name="action"]').val('<?php echo $this->getPlugin()->getSlug(); ?>_settings_reset');
        });
        $(form).submit(function(e) {
            e.preventDefault();
            $(form).find('.submit-waiting').show();
            $.post(ajaxurl, $(form).serialize(), function(response) {

                //  $('.hidden-checkbox').get(0).type = 'checkbox'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
                $(form).find('.hidden-temp').remove();

                $(form).find('.hidden-checkbox').removeClass('hidden-checkbox');
                $(form).find('.submit-waiting').hide();
                $('#message-body').html(response).fadeOut(0).fadeIn().delay(5000).fadeOut();
            });
        });


        $('#<?php echo $form_name; ?>_settings-reset').click(function(e, el) {
            if (!confirm('<?php _e('Are you sure you want to reset this form?', $this->getPlugin()->getSlug()); ?>')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>