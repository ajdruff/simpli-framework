<?php
#todo: add class functions to handle select,radio, and checkbox markup. use the nstock.common.php code

//must give the form a unique name since there will be multiple forms on the page.
$form_name="general_settings";

$option_checkboxes=$this->getPlugin()->getSetting('option_checkbox');
//print_r($option_checkboxes);
//echo $option_checkboxes['\'table\''];
//die();
#todo: add class functions to handle select,radio, and checkbox markup. use the nstock.common.php code

?>
<form name="<?php echo $this->getPlugin()->getSlug(); ?><?php echo '_' . $form_name ; ?>" id="<?php echo $this->getPlugin()->getSlug() . "_$form_name"; ?>" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <?php wp_nonce_field($this->getPlugin()->getSlug()); ?>
    <input type="hidden" name="action" id="action" value="" />

    <table class="form-table">
        <tr valign="top" id="first_name_row">
            <th scope="row"><?php _e('First Name:',$this->getPlugin()->getTextDomain()); ?></th>
            <td>

                <!-- First and second Options -->
                <fieldset>
                    <label for="first_name" id="first_name_label">
                        <input name="first_name" type="text" id="first_name" class="regular-text code" value="<?php echo $this->getPlugin()->getSetting('first_name'); ?>" />
                    </label>
                    <label for="last_name" id="last_name_label"><?php _e('Last Name:',$this->getPlugin()->getTextDomain()); ?>
                        <input name="last_name" type="text" id="last_name" class="regular-text" value="<?php echo $this->getPlugin()->getSetting('last_name'); ?>" />
                    </label>
                </fieldset>
            </td>
        </tr>

        <!-- Options Checkbox Example  -->
        <tr valign="top" id="option_checkbox_row">
            <th scope="row"><?php _e('Do you want this option?',$this->getPlugin()->getTextDomain()); ?></th>
            <td>
                <fieldset>
                    <label for="option_checkbox">

                        <input type="checkbox" name="option_checkbox[table]"  id="option_checkbox" value="<?php echo 'yes'; ?>"<?php echo (($option_checkboxes['table'] == 'yes') ?  ' checked="checked"' : ''); ?> >




                        <p class="description">
                            <?php
                            printf(
                                    __(
                                            'Provide helpful hints on how to complete this option here. You can provide a link  %s here %s if you want', $this->getPlugin()->getSlug()
                                    ), '<a href="#" target="_blank">', '</a>'
                            );
                            ?>

                        </p>
                            </label>
   <label for="option_checkbox[chair]">
                                                 <p><input type="checkbox" name="option_checkbox[chair]"  id="option_checkbox" value="<?php echo 'yes'; ?>"<?php echo (($option_checkboxes['chair'] == 'yes') ?  ' checked="checked"' : ''); ?> > </p>

                                         <p class="description">
                            <?php
                            printf(
                                    __(
                                            'Provide helpful hints on how to complete this option here. You can provide a link  %s here %s if you want', $this->getPlugin()->getSlug()
                                    ), '<a href="#" target="_blank">', '</a>'
                            );
                            ?>

                        </p>

                    </label>
                </fieldset>
            </td>
        </tr>

          <!-- Options Radio Buttons  -->



        <tr valign="top" id="option_radio_row">
            <th scope="row"><?php _e('Which option would you like to select?',$this->getPlugin()->getTextDomain()); ?></th>
            <td>
                <fieldset>
                    <label for="option_radio" class="label-radio">
                        <label class="label-radio">
                        <input type="radio" name="option_radio" value="yes"  <?php echo (($this->getPlugin()->getSetting('option_radio') == 'yes') ?  ' checked="checked"' : ''); ?> /> <span><?php _e('Yes',$this->getPlugin()->getTextDomain()); ?></span></label>

                         <label class="label-radio">
                        <input type="radio" name="option_radio" value="no" <?php echo (($this->getPlugin()->getSetting('option_radio') == 'no') ?  ' checked="checked"' : ''); ?> /> <span><?php _e('No',$this->getPlugin()->getTextDomain()); ?></span>
                         </label>


                         <label class="label-radio">
                        <input type="radio" name="option_radio" value="maybe" <?php echo (($this->getPlugin()->getSetting('option_radio') == 'maybe') ?  ' checked="checked"' : ''); ?> /> <span><?php _e('Maybe',$this->getPlugin()->getTextDomain()); ?></span>
                    </label>

                    <p class="description">
 <?php
                            printf(
                                    __(
                                            'Provide helpful hints on how to complete this option here. You can provide a link  %s here %s if you want', $this->getPlugin()->getSlug()
                                    ), '<a href="#" target="_blank">', '</a>'
                            );
                            ?></p>
                              </label>
                </fieldset>
            </td>
        </tr>

            <!-- Option Admin Menu  -->



        <tr valign="top" id="admin_menu_row">
            <th scope="row"><?php _e('Admin Menu Location',$this->getPlugin()->getTextDomain()); ?></th>
            <td>
                <fieldset>
                    <label for="admin_menu_side" class="label-radio">
                        <input type="radio" name="admin_menu_side" id="admin_menu_side" value="side"<?php echo (($this->getPlugin()->getSetting('admin_menu_side') == 'side') ? ' checked="checked"' : ''); ?>/> <span><?php _e('Sidebar',$this->getPlugin()->getTextDomain()); ?></span>
                    </label>
                    <label for="admin_menu_settings" class="label-radio">
                        <input type="radio" name="admin_menu_side" id="admin_menu_settings" value="settings"<?php echo (($this->getPlugin()->getSetting('admin_menu_side') == 'settings') ? ' checked="checked"' : ''); ?>/> <span><?php _e('Settings',$this->getPlugin()->getTextDomain()); ?></span>
                    </label>
                </fieldset>
            </td>
        </tr>
         <!-- Option Select Menu  -->
                <tr valign="top" id="option_radio_row">
            <th scope="row"><?php _e('Which option would you like to select?',$this->getPlugin()->getTextDomain()); ?></th>
            <td>
                <fieldset>
                    <label for="option_select" class="label-radio">

                        <select name="option_select" id="option_select">

                            <option value="blue" <?php echo (($this->getPlugin()->getSetting('option_select') == 'blue') ? ' selected="selected"' : ''); ?>>Blue</option>
                             <option value="orange" <?php echo (($this->getPlugin()->getSetting('option_select') == 'orange') ? ' selected="selected"' : ''); ?>>Orange</option>
                              <option value="red" <?php echo (($this->getPlugin()->getSetting('option_select') == 'red') ? ' selected="selected"' : ''); ?>>Red</option>
                               <option value="yellow" <?php echo (($this->getPlugin()->getSetting('option_select') == 'yellow') ? ' selected="selected"' : ''); ?>>Yellow</option>
                        </select>
                        </label>
                    <label for="option_select_description" class="label-radio">
                    <p id="option_select_description" class="description">
 <?php
                            printf(
                                    __(
                                            'Provide helpful hints on how to complete this option here. You can provide a link  %s here %s if you want', $this->getPlugin()->getSlug()
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
        <input type="submit" id="<?php echo $form_name; ?>_settings-save" class="button-primary" value="Save Changes" name="<?php echo $form_name; ?>_settings-save">
        <img alt="<?php _e('Waiting...',$this->getPlugin()->getTextDomain()); ?>" src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />
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
            if (!confirm('<?php _e('Are you sure you want to reset this form?',$this->getPlugin()->getTextDomain()); ?>')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>