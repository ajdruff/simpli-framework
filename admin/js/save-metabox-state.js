/**
 * Save Meta Box State
 *
 * Saves the state of custom metaboxes (both the position and closed state) for the admin screens
 *
 *save-metabox-state.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */


jQuery(document).ready(function($) {

    simpli.frames.log('the menu slug is ' + simpli_frames.menu_slug);
    simpli.frames.log('the screen_id is ' + simpli_frames.screen_id);
    simpli.frames.log('the plugin name is ' + simpli_frames.plugin.name);


    $(".if-js-closed").removeClass("if-js-closed").addClass("closed");

    postboxes.add_postbox_toggles(simpli_frames.menu_slug);  //e.g.: 'simpli_frames_menu10_settings'
    postboxes.add_postbox_toggles(simpli_frames.screen_id); //e.g.



});