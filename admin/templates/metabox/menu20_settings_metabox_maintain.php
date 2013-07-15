<?php

//must give the form a unique name since there will be multiple forms on the page.
$form_name="settings_enabled";

#todo: add class functions to handle select,radio, and checkbox markup. use the nstock.common.php code

?>
<form name="<?php echo $this->getPlugin()->getSlug(); ?><?php echo '_' . $form_name ; ?>" id="<?php echo $this->getPlugin()->getSlug() . "_$form_name"; ?>" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<?php wp_nonce_field($this->getPlugin()->getSlug()); ?>
    <input type="hidden" name="action" id="action" value="" />

    <table class="form-table">




          <!-- Disable Plugin  -->



        <tr valign="top" id="plugin_enabled_row">
             <th scope="row"><?php _e('Enable Plugin',$this->getPlugin()->getTextDomain()); ?></th>
            <td>
                <fieldset>
                    <label for="plugin_enabled" class="label-radio">

                         <label class="label-radio">
                        <input type="radio" name="plugin_enabled" value="enabled" <?php echo (($this->getPlugin()->getSetting('plugin_enabled') == 'enabled') ?  ' checked="checked"' : ''); ?> /> <span><?php _e('Yes',$this->getPlugin()->getTextDomain()); ?></span>
                         </label>

                        <label class="label-radio">
                        <input type="radio" name="plugin_enabled" value="disabled"  <?php echo (($this->getPlugin()->getSetting('plugin_enabled') == 'disabled') ?  ' checked="checked"' : ''); ?> /> <span><?php _e('No',$this->getPlugin()->getTextDomain()); ?></span></label>





                    <p class="description">
 <?php
                            printf(
                                    __(
                                            'Use this setting to temporarily disable ' . $this->getPlugin()->getName() .' for troubleshooting or maintenance. \'No\' will disable all plugin functionality except for this Administrative area, allowing you continued access to these settings. To completely remove ' . $this->getPlugin()->getName() .', de-activate it from the plugins menu.', $this->getPlugin()->getSlug()
                                    ), '<a href="#" target="_blank">', '</a>'
                            );
                            ?></p>
                              </label>
                </fieldset>
            </td>
        </tr>


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

                     <input type="submit" id="<?php echo $form_name; ?>_settings-reset-all" class="button-secondary" value="Reset All" name="<?php echo $form_name; ?>_settings-reset-all">

                                          <input type="submit" id="<?php echo $form_name; ?>_settings-update-all" class="button-secondary" value="Update All Settings" name="<?php echo $form_name; ?>_settings-update-all">


    <input type="submit" id="<?php echo $form_name; ?>_settings-save" class="button-primary" value="Save Changes" name="<?php echo $form_name; ?>_settings-save">
        <img alt="<?php _e('Waiting...',$this->getPlugin()->getTextDomain()); ?>" src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />


    </p>

    <p><strong>Reset</strong> will reset the settings in this metabox to their defaults.</p>
<p><strong>Reset All </strong> will reset <em>all settings</em> for the plugin to their defaults when first installed.</p>
<p><strong>Update All Settings </strong> will retain any existing setting values, but will add any new settings and their defaults that may have been manually added by editing the plugins source code in the Plugin class. </p>
</form>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var form = $('#<?php echo $this->getPlugin()->getSlug()."_$form_name"; ?>').first();
        $('#<?php echo $form_name; ?>_settings-save').click(function() {
            $(form).find('input[name="action"]').val('<?php echo $this->getPlugin()->getSlug(); ?>_settings_save_with_reload');
        });
        $('#<?php echo $form_name; ?>_settings-reset').click(function() {
            $(form).find('input[name="action"]').val('<?php echo $this->getPlugin()->getSlug(); ?>_settings_reset');
        });
        $('#<?php echo $form_name; ?>_settings-reset-all').click(function() {
            $(form).find('input[name="action"]').val('<?php echo $this->getPlugin()->getSlug(); ?>_settings_reset_all');
        });
        $('#<?php echo $form_name; ?>_settings-update-all').click(function() {
            $(form).find('input[name="action"]').val('<?php echo $this->getPlugin()->getSlug(); ?>_settings_update_all');
        });

        $(form).submit(function(e) {
            e.preventDefault();
            $(form).find('.submit-waiting').show();
            $.post(ajaxurl, $(form).serialize(), function(response) {
                $(form).find('.submit-waiting').hide();
                $('#message-body').html(response).fadeOut(0).fadeIn().delay(5000).fadeOut();
            });
        });


       $('#<?php echo $form_name; ?>_settings-reset').click(function(e, el) {
            if ( ! confirm('<?php _e('Are you sure you want to reset this form?',$this->getPlugin()->getTextDomain()); ?>') ) {
                e.preventDefault();
                return false;
            }
        });

       $('#<?php echo $form_name; ?>_settings-reset-all').click(function(e, el) {
            if ( ! confirm('<?php _e('Are you sure you want to reset all the settings for this plugin to installed defaults?',$this->getPlugin()->getTextDomain()); ?>') ) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>