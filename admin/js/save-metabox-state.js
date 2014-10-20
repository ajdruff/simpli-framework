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

    nomstock.com.log('the menu slug is ' + nomstock.com.vars.menu_slug);
    nomstock.com.log('the screen_id is ' + nomstock.com.vars.screen_id);
    nomstock.com.log('the plugin name is ' + nomstock.com.vars.plugin.name);
    nomstock.com.log('Toggled postbox state menu slug = ' + nomstock.com.vars.menu_slug);
    nomstock.com.log('Toggled postbox state nomstock.com.vars.screen_id = ' + nomstock.com.vars.screen_id);

    $(".if-js-closed").removeClass("if-js-closed").addClass("closed");

    postboxes.add_postbox_toggles(nomstock.com.vars.menu_slug);  //e.g.: 'nomstock.com.vars_menu10_settings'
    postboxes.add_postbox_toggles(nomstock.com.vars.screen_id); //e.g.



});