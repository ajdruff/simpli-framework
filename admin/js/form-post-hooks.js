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

if (typeof nomstock.com.post === 'undefined') {
    nomstock.com.post = {};
}


jQuery(document).ready(function() {

    nomstock.com.post.addHooks();


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



nomstock.com.post.addHooks = function()

{
    /*
     *
     * Add action hooks here
     * Usage:
     * nomstock.com.add_action('name_of_action_tag', function_name_without_quotes_around_it);
     *
     */

    /*
     * prompt the user
     */
/*
 * To add a javascript prompt , just add an action like this : 
 *     nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_save_post', nomstock.com.post.savePost);
 *     
 *     That will fire the nomstock.com.post.savePost event.
 */


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

nomstock.com.post.savePost = function(trigger_event, args) {

    if (!confirm('test')) { //if the user did not confirm upload, cancel it
        return (false);

    } else {
        return (true);
    }
    /*
     * example of how you would access the triggering element:
     */
    nomstock.com.log('you clicked element with id ' + args.element.attr('id') + ' in form with id = ' + args.form.attr('id'));



}
