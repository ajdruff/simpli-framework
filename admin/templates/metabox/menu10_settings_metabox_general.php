<?php
#todo: add class functions to handle select,radio, and checkbox markup. use the nstock.common.php code
//must give the form a unique name since there will be multiple forms on the page.
$form_name = "general_settings";


$checkbox_settings=$this->getPlugin()->getSetting('checkbox_setting');

?>
<form name="<?php echo $this->getPlugin()->getSlug(); ?><?php echo '_' . $form_name; ?>" id="<?php echo $this->getPlugin()->getSlug() . "_$form_name"; ?>" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<?php wp_nonce_field($this->getPlugin()->getSlug()); ?>
    <input type="hidden" name="action" id="action" value="" />



    <h4 class="title"><?php _e('Radio Button Setting Example', $this->getPlugin()->getSlug()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Do you want this option?', $this->getPlugin()->getSlug()); ?>

                </th>
                <td>
                    <fieldset>
                        <label>
                            <label>
                                <input type="radio" name="radio_setting" value="yes"  <?php echo (($this->getPlugin()->getSetting('radio_setting') == 'yes') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Yes', $this->getPlugin()->getSlug()); ?></span></label>

                            <label>
                                <input type="radio" name="radio_setting" value="no" <?php echo (($this->getPlugin()->getSetting('radio_setting') == 'no') ? ' checked="checked"' : ''); ?> /> <span><?php _e('No', $this->getPlugin()->getSlug()); ?></span>
                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Provide helpful text here describing this setting or provide a help %s link %s.  ' , $this->getPlugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>



        </tbody>
    </table>

 <h4 class="title"><?php _e('Text Setting Example', $this->getPlugin()->getSlug()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Your Name: ', $this->getPlugin()->getSlug()); ?>

                </th>
                <td>
                    <fieldset>
                        <label>
                            <label>

                                                        <input name="text_setting" type="text" id="first_name" class="regular-text code" value="<?php echo $this->getPlugin()->getSetting('text_setting'); ?>" />

                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Provide helpful text here describing this setting or provide a help %s link %s.  ' , $this->getPlugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>



        </tbody>
    </table>

 <h4 class="title"><?php _e('Dropdown Setting Example', $this->getPlugin()->getSlug()); ?></h4>


 <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Color: ', $this->getPlugin()->getSlug()); ?>

                </th>
                <td>
<fieldset>
                    <label>
  <label>
                        <select name="dropdown_setting" id="dropdown_setting">

                            <option value="blue" <?php echo (($this->getPlugin()->getSetting('dropdown_setting') == 'blue') ? ' selected="selected"' : ''); ?>>Blue</option>
                             <option value="orange" <?php echo (($this->getPlugin()->getSetting('dropdown_setting') == 'orange') ? ' selected="selected"' : ''); ?>>Orange</option>
                              <option value="red" <?php echo (($this->getPlugin()->getSetting('dropdown_setting') == 'red') ? ' selected="selected"' : ''); ?>>Red</option>
                               <option value="yellow" <?php echo (($this->getPlugin()->getSetting('dropdown_setting') == 'yellow') ? ' selected="selected"' : ''); ?>>Yellow</option>
                        </select>

                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Provide helpful text here describing this setting or provide a help %s link %s.  ' , $this->getPlugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                </fieldset>
                </td>
            </tr>



        </tbody>
    </table>

<h4 class="title"><?php _e('Checkbox Setting Example', $this->getPlugin()->getSlug()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Place a check mark next to each color you like', $this->getPlugin()->getSlug()); ?>

                </th>
                <td>
                      <fieldset>
<?php

                        foreach ($checkbox_settings as $color=>$checkbox_setting_value) {


                        ?>

                        <p>
                         <label style="padding: 18px 2px 5px;" for="checkbox_settings_<?php echo $color ?>"><span style="padding-left:5px" ><?php echo $color ?></span>
                        <input type="checkbox" name="checkbox_settings[<?php  echo $color ?>]"  id="checkbox_settings_<?php echo $color ?>"  value="<?php echo 'yes'; ?>"<?php echo (($checkbox_setting_value === 'yes') ? ' checked="checked"' : ''); ?> >


</label>
                        </p>



<?php
} // end
?>

                        <p class="description"><?php
printf(
        __('Provide helpful text here describing this setting or provide a help %s link %s.  ' , $this->getPlugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
                        ?>

                        </p>
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