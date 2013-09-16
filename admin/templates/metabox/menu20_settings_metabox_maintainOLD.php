<form name="<?php echo $this->plugin()->getSlug(); ?><?php echo '_' . $metabox['id']; ?>" id="<?php echo $this->plugin()->getSlug() . $metabox['id']; ?>" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <?php wp_nonce_field($this->plugin()->getSlug()); ?>
    <input type="hidden" name="action" id="action" value="" />

    <table class="form-table">




        <!-- Disable Plugin  -->



        <tr valign="top" id="plugin_enabled_row">
            <th scope="row"><?php _e('Enable Plugin', $this->plugin()->getTextDomain()); ?></th>
            <td>
                <fieldset>
                    <label for="plugin_enabled" class="label-radio">

                        <label class="label-radio">
                            <input type="radio" name="plugin_enabled" value="enabled" <?php echo (($this->plugin()->getUserOption('plugin_enabled') == 'enabled') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Yes', $this->plugin()->getTextDomain()); ?></span>
                        </label>

                        <label class="label-radio">
                            <input type="radio" name="plugin_enabled" value="disabled"  <?php echo (($this->plugin()->getUserOption('plugin_enabled') == 'disabled') ? ' checked="checked"' : ''); ?> /> <span><?php _e('No', $this->plugin()->getTextDomain()); ?></span></label>





                        <p class="description">
                            <?php
                            printf(
                                    __(
                                            'Use this setting to temporarily disable ' . $this->plugin()->getName() . ' for troubleshooting or maintenance. \'No\' will disable all plugin functionality except for this Administrative area, allowing you continued access to these settings. To completely remove ' . $this->plugin()->getName() . ', de-activate it from the plugins menu.', $this->plugin()->getSlug()
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
     * In this case , the action is defined in the 'settings_save' or 'settings_reset' click events, which redefine the form action to be the slug + '_settings_save or _settings_reset
     * So to process, the form, you need to make sure you have a method within your class that matches the action of each of the buttons
     *  */
    ?>

    <p class="button-controls">
        <input type="submit" id="<?php echo $metabox['id']; ?>_settings_reset" class="button-secondary" value="Reset" name="<?php echo $metabox['id']; ?>_settings_reset">

        <input type="submit" id="<?php echo $metabox['id']; ?>_settings_reset_all" class="button-secondary" value="Reset All" name="<?php echo $metabox['id']; ?>_settings_reset_all">

        <input type="submit" id="<?php echo $metabox['id']; ?>_settings_update_all" class="button-secondary" value="Update All Settings" name="<?php echo $metabox['id']; ?>_settings_update_all">


        <input type="submit" id="<?php echo $metabox['id']; ?>_settings_save" class="button-primary" value="Save Changes" name="<?php echo $metabox['id']; ?>_settings_save">
        <img alt="<?php _e('Waiting...', $this->plugin()->getTextDomain()); ?>" src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />


    </p>

    <p><strong>Reset</strong> will reset the settings in this metabox to their defaults.</p>
    <p><strong>Reset All </strong> will reset <em>all settings</em> for the plugin to their defaults when first installed.</p>
    <p><strong>Update All Settings </strong> will retain any existing setting values, but will add any new settings and their defaults that may have been manually added by editing the plugins source code in the Plugin class. </p>
</form>
