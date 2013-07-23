/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function($){
//simpli.hello.message = function(message)
//{
//    alert( message );
//};
//
simpli.hello.log('the menu slug is ' + simpli_hello.menu_slug);
simpli.hello.log('the screen_id is ' + simpli_hello.screen_id);
simpli.hello.log('the plugin name is ' + simpli_hello.plugin.name);


        $(".if-js-closed").removeClass("if-js-closed").addClass("closed");

postboxes.add_postbox_toggles( simpli_hello.menu_slug);  //e.g.: 'simpli_hello_menu10_settings'
postboxes.add_postbox_toggles( simpli_hello.screen_id ); //e.g.: 'toplevel_page_simpli_hello_menu10_settings'


            });