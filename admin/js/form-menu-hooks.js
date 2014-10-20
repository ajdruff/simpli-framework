/**
 * Form Javascript Hooks
 *
 * Use this script to add hook into any form actions to prompt the user, or add any custom javascript.
 *
 * form-menu-hooks.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */


/*
 * create the script's namespace
 */
if (typeof nomstock.com.menu === 'undefined') {
    nomstock.com.menu = {};
}

nomstock.com.menu.add_prompt = function(action_slug, fnc) {


    nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_submit_prompt_' + action_slug, fnc);




}

jQuery(document).ready(function() {
    nomstock.com.menu.addHooks();
});



/**
 * Add Hooks
 *
 * Add Hooks here
 * Hooks act the same way that WordPress action hooks do - they map events
 * ( triggered by nomstock.com.do_action ) to functions you want to occur.
 * The only exception is that they your hooked functions can return values back
 * to the hook trigger
 *
 * @param object form The form object
 * @returns void
 */



nomstock.com.menu.addHooks = function()

{
    /*
     *
     * Add action hooks here
     * Usage:
     * nomstock.com.add_action('name_of_action_tag', function_name_without_quotes_around_it);
     *
     */

    /*
     * prompt the user on upload
     */
    // nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_submit_prompt_' + 'upload_addon', nomstock.com.menu.uploadActionPrompt);

    /*
     * Example:
     *
     * Use generic add_action or nomstockfied add_prompt:

     * nomstock.com.menu.add_prompt('upload_addon', nomstock.com.menu.uploadActionPrompt);
     *
     * or:
     *
     * nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_submit_prompt_' + 'upload_addon', nomstock.com.menu.uploadActionPrompt);
     *
     */



    nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_submit_prompt_' + 'settings_reset_all', nomstock.com.menu.resetAllPrompt);

    nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_submit_prompt_' + 'settings_reset', nomstock.com.menu.resetPrompt);
    nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_submit_prompt_' + 'settings_reset_with_reload', nomstock.com.menu.resetPrompt);


/* add saveWidgetCode hook only if on home page and the widgets.js was loaded to define nomstock.com.widgets.saveWidgetCode */
if (typeof nomstock.com.widgets !== 'undefined') {
        nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_submit_prompt_' + 'submit_domain', nomstock.com.widgets.saveWidgetCode);
}



    
}




/*
 *
 * Define your hook functions below
 *
 */




nomstock.com.menu.resetAllPrompt = function(trigger_event, args) {

    if (!confirm(nomstock.com.vars.metabox_forms.reset_all_message)) { //if the user did not confirm upload, cancel it
        return false;
    } else {
        return true;
    }

}

nomstock.com.menu.resetPrompt = function(trigger_event, args) {

    if (!confirm(nomstock.com.vars.metabox_forms.reset_message)) { //if the user did not confirm upload, cancel it
        return false;
    } else {
        return true;
    }

}



    
