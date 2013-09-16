<?php
$checkbox_settings=$this->plugin()->getUserOption('checkbox_settings');

?>
<form name="<?php echo $this->plugin()->getSlug(); ?><?php echo '_' . $metabox['id']; ?>" id="<?php echo $this->plugin()->getSlug() . $metabox['id']; ?>" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<?php wp_nonce_field($this->plugin()->getSlug()); ?>
    <input type="hidden" name="action" value="" />



    <h4 class="title"><?php _e('Radio Button Setting Example',$this->plugin()->getTextDomain()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Do you want this option?',$this->plugin()->getTextDomain()); ?>

                </th>
                <td>
                    <fieldset>
                        <label>
                            <label>
                                <input type="radio" name="radio_setting" value="yes"  <?php echo (($this->plugin()->getUserOption('radio_setting') == 'yes') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Yes',$this->plugin()->getTextDomain()); ?></span></label>

                            <label>
                                <input type="radio" name="radio_setting" value="no" <?php echo (($this->plugin()->getUserOption('radio_setting') == 'no') ? ' checked="checked"' : ''); ?> /> <span><?php _e('No',$this->plugin()->getTextDomain()); ?></span>
                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Provide helpful text here describing this setting or provide a help %s link %s.  ' , $this->plugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>



        </tbody>
    </table>

 <h4 class="title"><?php _e('Text Setting Example',$this->plugin()->getTextDomain()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Your Name: ',$this->plugin()->getTextDomain()); ?>

                </th>
                <td>
                    <fieldset>
                        <label>
                            <label>

                                                        <input name="text_setting" type="text" id="first_name" class="regular-text code" value="<?php echo $this->plugin()->getUserOption('text_setting'); ?>" />

                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Provide helpful text here describing this setting or provide a help %s link %s.  ' , $this->plugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                    </fieldset>
                </td>
            </tr>



        </tbody>
    </table>

 <h4 class="title"><?php _e('Dropdown Setting Example',$this->plugin()->getTextDomain()); ?></h4>


 <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Color: ',$this->plugin()->getTextDomain()); ?>

                </th>
                <td>
<fieldset>
                    <label>
  <label>
                        <select name="dropdown_setting" id="dropdown_setting">

                            <option value="blue" <?php echo (($this->plugin()->getUserOption('dropdown_setting') == 'blue') ? ' selected="selected"' : ''); ?>>Blue</option>
                             <option value="orange" <?php echo (($this->plugin()->getUserOption('dropdown_setting') == 'orange') ? ' selected="selected"' : ''); ?>>Orange</option>
                              <option value="red" <?php echo (($this->plugin()->getUserOption('dropdown_setting') == 'red') ? ' selected="selected"' : ''); ?>>Red</option>
                               <option value="yellow" <?php echo (($this->plugin()->getUserOption('dropdown_setting') == 'yellow') ? ' selected="selected"' : ''); ?>>Yellow</option>
                        </select>

                            </label>



                            <span class="description" style="padding-left:10px;">
<?php
printf(
        __('Provide helpful text here describing this setting or provide a help %s link %s.  ' , $this->plugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
);
?></span>
                        </label>
                </fieldset>
                </td>
            </tr>



        </tbody>
    </table>

<h4 class="title"><?php _e('Checkbox Setting Example',$this->plugin()->getTextDomain()); ?></h4>

    <table class="form-table">
        <tbody>

            <tr>
                <th>
<?php _e('Place a check mark next to each color you like',$this->plugin()->getTextDomain()); ?>

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
        __('Provide helpful text here describing this setting or provide a help %s link %s.  ' , $this->plugin()->getSlug() ), '<a href="#" target="_blank">', '</a>'
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
        <input type="submit" id="<?php echo $metabox['id']; ?>_settings_reset" class="button-secondary" value="Reset" name="<?php echo $metabox['id']; ?>_settings_reset">
        <input type="submit" id="<?php echo $metabox['id']; ?>_settings_save" class="button-primary" value="Save Changes" name="<?php echo $metabox['id']; ?>_settings_save">
        <img alt="<?php _e('Waiting...',$this->plugin()->getTextDomain()); ?>" src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />
    </p>


</form>