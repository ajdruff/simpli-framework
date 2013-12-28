/**
 * Save Meta Box State
 * #### DEPRECATED ####
 * As long as you enqueue the 'post' script that WordPress provides, you shouldn't need this script. 'post' is enqueued by the metabox class and handles all the management of the metaboxes.
 * Saves the state of custom metaboxes (both the position and closed state) for the admin screens
 *
 *save-metabox-state.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliFrames
 *
 */


jQuery(document).ready(function($) {

    simpli.frames.log('the menu slug is ' + simpli.frames.vars.menu_slug);
    simpli.frames.log('the screen_id is ' + simpli.frames.vars.screen_id);
    simpli.frames.log('the plugin name is ' + simpli.frames.vars.plugin.name);
    simpli.frames.log('Toggled postbox state menu slug = ' + simpli.frames.vars.menu_slug);
    simpli.frames.log('Toggled postbox state simpli.frames.vars.screen_id = ' + simpli.frames.vars.screen_id);

    $(".if-js-closed").removeClass("if-js-closed").addClass("closed");

    postboxes.add_postbox_toggles(simpli.frames.vars.menu_slug);  //e.g.: 'simpli.frames.vars_menu10_settings'
    postboxes.add_postbox_toggles(simpli.frames.vars.screen_id); //e.g.



});