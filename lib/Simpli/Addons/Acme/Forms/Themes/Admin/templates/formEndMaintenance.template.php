    <p class="button-controls">
        <input type="submit" id="{form_name}_{form_counter}_settings_reset" class="button-secondary" value="Reset" name="{form_name}_{form_counter}_settings_reset">

        <input type="submit" id="{form_name}_{form_counter}_settings_reset_all" class="button-secondary" value="Reset All" name="{form_name}_{form_counter}_settings_reset_all">

        <input type="submit" id="{form_name}_{form_counter}_settings_update_all" class="button-secondary" value="Update All Settings" name="{form_name}_{form_counter}_settings_update_all">


        <input type="submit" id="{form_name}_{form_counter}_settings_save" class="button-primary" value="Save Changes" name="{form_name}_{form_counter}_settings_save">
        <img alt="<?php _e('Waiting...', $this->getPlugin()->getTextDomain()); ?>" src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />


    </p>

    <p><strong>Reset</strong> will reset the settings in this metabox to their defaults.</p>
    <p><strong>Reset All </strong> will reset <em>all settings</em> for the plugin to their defaults when first installed.</p>
    <p><strong>Update All Settings </strong> will retain any existing setting values, but will add any new settings and their defaults that may have been manually added by editing the plugins source code in the Plugin class. </p>
</form>
