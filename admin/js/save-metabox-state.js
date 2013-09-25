/**
 * Save Meta Box State
 *
 * Saves the state of custom metaboxes for the admin screens
 *
 *save-metabox-state.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */


jQuery(document).ready(function($) {

    simpli.hello.log('the menu slug is ' + simpli_hello.menu_slug);
    simpli.hello.log('the screen_id is ' + simpli_hello.screen_id);
    simpli.hello.log('the plugin name is ' + simpli_hello.plugin.name);


    $(".if-js-closed").removeClass("if-js-closed").addClass("closed");

    postboxes.add_postbox_toggles(simpli_hello.menu_slug);  //e.g.: 'simpli_hello_menu10_settings'
    postboxes.add_postbox_toggles(simpli_hello.screen_id); //e.g.
    if (false) {
        postboxes.add_postbox_toggles(simpli_hello.screen_id); //e.g.: 'toplevel_page_simpli_hello_menu10_settings' . required for closed boxes to close when clicked

        if (simpli_hello.menu_slug !== '') {
            postboxes.add_postbox_toggles(simpli_hello.menu_slug);  //e.g.: 'simpli_hello_menu10_settings'
        } else {
            postboxes.add_postbox_toggles(simpli_hello.screen_id);

        }
    }
//    if (simpli_hello.screen_id !== '') {
//        postboxes.add_postbox_toggles(simpli_hello.screen_id);  //e.g.: 'toplevel_page_simpli_hello_menu10_settings'
//    }


});