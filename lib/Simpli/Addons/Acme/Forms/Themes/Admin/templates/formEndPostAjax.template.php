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

        <input type="submit" id="{form_name}_{form_counter}_settings_save" class="button-primary" value="Save" name="{form_name}_{form_counter}_settings_save">
        <img alt="<?php _e('Waiting...',$this->getPlugin()->getTextDomain()); ?>" src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />
    </p>




</form>
<form>
  <div class="message-body"></div>
</form>