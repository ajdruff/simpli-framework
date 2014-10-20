/**
 * Simpli Framework namespace
 *
 * Creates a Javascript namespace for the current plugin and provides a few
 * common methods that are attached to the root namespace.
 * Always load this in the header and do not make dependent on jQuery or anything else.
 *
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */


/*
 * create the plugin's namespace
 */
if (typeof nomstock === 'undefined') {
    nomstock = {};
}
if (typeof simpli.frames === 'undefined') {
    simpli.frames = {};
}

/**
 * Log
 *
 * Logs to the javascript console
 * @param string message The text to be logged
 * @return void
 */



simpli.frames.log = function(message)
{

    if (typeof simpli.frames !== 'undefined') { //check if simpli_frames namespace is available . if it is, variables are also available
        if (simpli.frames.vars.plugin.debug === true) { //if variables are available, we can check preferences

            console.log(message);
        }
    }

};
simpli.frames.logError = function(message)
{

    if (typeof simpli.frames !== 'undefined') { //check if simpli_frames namespace is available . if it is, variables are also available
        if (simpli.frames.vars.plugin.debug === true) { //if variables are available, we can check preferences

            console.error(message);
        }
    }

};

simpli.frames.logWarn = function(message)
{

    if (typeof simpli.frames !== 'undefined') { //check if simpli_frames namespace is available . if it is, variables are also available
        if (simpli.frames.vars.plugin.debug === true) { //if variables are available, we can check preferences

            console.warn(message);
        }
    }

};

/**
 * do_action
 *
 * Wrapper around trigger, so jQuery trigger interface is WordPress Developer friendly.
 * Ref:http://codex.wordpress.org/Function_Reference/do_action
 * Instead of doing this :
 *  jQuery(document).trigger('nomstock_forms_submit_prompt_' + form_action_slug, form_action_slug);
 *
 *  You can do this :
 *  simpli.frames.do_action('nomstock_forms_submit_prompt_' + form_action_slug,form_action_slug);
 *
 * @param string $tag The name of the hook you wish to execute.
 * @param string $arg The list of arguments to send to this hook.
 */

simpli.frames.do_action = function(tag, arg)
{
    return  (jQuery(document).triggerHandler(tag, arg));

}

/**
 * add_action
 *
 *  Wrapper around bind, so jQuery bind interface is WordPress Developer friendly.
 * Ref:http://codex.wordpress.org/Function_Reference/add_action
 *
 * Instead of doing this :
 *  jQuery(document).bind('nomstock_forms_submit_prompt_' + 'upload_addon', uploadActionPrompt);
 *
 *  You can do this :
 *  simpli.frames.add_action('nomstock_forms_submit_prompt_' + 'upload_addon', uploadActionPrompt);
 *
 *
 * @param string $tag The name of the action to which $function_to_add is hooked
 * @param string $function_to_add The function object you wish to be hooked
 */
simpli.frames.add_action = function(trigger, function_object) {
    jQuery(document).bind(trigger, function_object);


}









