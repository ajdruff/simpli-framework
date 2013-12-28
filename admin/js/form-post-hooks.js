/**
 * Form Javascript Hooks
 *
 * Use this script to add hook into any form actions to prompt the user, or add any custom javascript.
 *
 * form-post-hooks.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */


/*
 * create the script's namespace
 */

if (typeof simpli.frames.post === 'undefined') {
    simpli.frames.post = {};
}


jQuery(document).ready(function() {

    simpli.frames.post.addHooks();


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



simpli.frames.post.addHooks = function()

{
    /*
     *
     * Add action hooks here
     * Usage:
     * simpli.frames.add_action('name_of_action_tag', function_name_without_quotes_around_it);
     *
     */

    /*
     * prompt the user
     */

    simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_save_post', simpli.frames.post.savePost);

}




/*
 *
 * Define your hook functions below
 *
 */




/**
 * Save Post
 *
 * Prompts the user as to whether he really wants to upload.
 *
 *
 * @param event trigger_event The jQuery eventObject of the triggerHandler
 * @param object element The jQuery Element that triggered the event
 * @return mixed args Parameter values provided by the triggerHandler. May be a string, an array, or an object
 */

simpli.frames.post.savePost = function(trigger_event, args) {

    if (!confirm('test')) { //if the user did not confirm upload, cancel it
        return (false);

    } else {
        return (true);
    }
    /*
     * example of how you would access the triggering element:
     */
    simpli.frames.log('you clicked element with id ' + args.element.attr('id') + ' in form with id = ' + args.form.attr('id'));



}
