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
if (typeof simpli.frames.menu === 'undefined') {
    simpli.frames.menu = {};
}

simpli.frames.menu.add_prompt = function(action_slug, fnc) {


    simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + action_slug, fnc);




}

jQuery(document).ready(function() {
    simpli.frames.menu.addHooks();
});



/**
 * Add Hooks
 *
 * Add Hooks here
 * Hooks act the same way that WordPress action hooks do - they map events
 * ( triggered by simpli.frames.do_action ) to functions you want to occur.
 * The only exception is that they your hooked functions can return values back
 * to the hook trigger
 *
 * @param object form The form object
 * @returns void
 */



simpli.frames.menu.addHooks = function()

{
    /*
     *
     * Add action hooks here
     * Usage:
     * simpli.frames.add_action('name_of_action_tag', function_name_without_quotes_around_it);
     *
     */

    /*
     * prompt the user on upload
     */
    // simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + 'upload_addon', simpli.frames.menu.uploadActionPrompt);

    /*
     * Example:
     *
     * Use generic add_action or nomstockfied add_prompt:

     * simpli.frames.menu.add_prompt('upload_addon', simpli.frames.menu.uploadActionPrompt);
     *
     * or:
     *
     * simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + 'upload_addon', simpli.frames.menu.uploadActionPrompt);
     *
     */



    simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + 'settings_reset_all', simpli.frames.menu.resetAllPrompt);

    simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + 'settings_reset', simpli.frames.menu.resetPrompt);
    simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + 'settings_reset_with_reload', simpli.frames.menu.resetPrompt);


/* add saveWidgetCode hook only if on home page and the widgets.js was loaded to define simpli.frames.widgets.saveWidgetCode */
if (typeof simpli.frames.widgets !== 'undefined') {
        simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + 'submit_domain', simpli.frames.widgets.saveWidgetCode);
}



    
}




/*
 *
 * Define your hook functions below
 *
 */




simpli.frames.menu.resetAllPrompt = function(trigger_event, args) {

    if (!confirm(simpli.frames.vars.metabox_forms.reset_all_message)) { //if the user did not confirm upload, cancel it
        return false;
    } else {
        return true;
    }

}

simpli.frames.menu.resetPrompt = function(trigger_event, args) {

    if (!confirm(simpli.frames.vars.metabox_forms.reset_message)) { //if the user did not confirm upload, cancel it
        return false;
    } else {
        return true;
    }

}



    
