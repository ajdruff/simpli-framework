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
simpli.hello.log('the menu group name is ' + simpli_hello.menu_group_name);
simpli.hello.log('the menu post type is ' + simpli_hello.menu_post_type);
simpli.hello.log('the plugin name is ' + simpli_hello.plugin.name);


        $(".if-js-closed").removeClass("if-js-closed").addClass("closed");

postboxes.add_postbox_toggles( simpli_hello.menu_post_type );  //e.g.: 'simpli_hello_menu10_settings'
postboxes.add_postbox_toggles( simpli_hello.menu_group_name ); //e.g.: 'toplevel_page_simpli_hello_menu10_settings'


            });