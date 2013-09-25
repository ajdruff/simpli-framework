<!DOCTYPE html>
<!--[if IE 8]>
<html xmlns="http://www.w3.org/1999/xhtml" class="ie8 wp-toolbar"  dir="ltr" lang="en-US">
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" class="wp-toolbar"  dir="ltr" lang="en-US">
    <!--<![endif]-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Edit Post &lsaquo; wpdev.com &#8212; WordPress</title>
        <script type="text/javascript">
            addLoadEvent = function(func) {
                if (typeof jQuery != "undefined")
                    jQuery(document).ready(func);
                else if (typeof wpOnload != 'function') {
                    wpOnload = func;
                } else {
                    var oldonload = wpOnload;
                    wpOnload = function() {
                        oldonload();
                        func();
                    }
                }
            };
            var userSettings = {
                'url': '/',
                'uid': '1',
                'time': '1379400645'
            },
            ajaxurl = '/wp-admin/admin-ajax.php',
                    pagenow = 'post',
                    typenow = 'post',
                    adminpage = 'post-php',
                    thousandsSeparator = ',',
                    decimalPoint = '.',
                    isRtl = 0;
        </script>
        <link rel='stylesheet' id='admin-bar-css'  href='http://wpdev.com/wp-includes/css/admin-bar.dev.css?ver=3.4.1' type='text/css' media='all' />
        <link rel='stylesheet' id='thickbox-css'  href='http://wpdev.com/wp-includes/js/thickbox/thickbox.css?ver=3.4.1' type='text/css' media='all' />
        <link rel='stylesheet' id='wp-admin-css'  href='http://wpdev.com/wp-admin/css/wp-admin.dev.css?ver=3.4.1' type='text/css' media='all' />
        <link rel='stylesheet' id='colors-css'  href='http://wpdev.com/wp-admin/css/colors-fresh.dev.css?ver=3.4.1' type='text/css' media='all' />
        <!--[if lte IE 7]>
        <link rel='stylesheet' id='ie-css'  href='http://wpdev.com/wp-admin/css/ie.dev.css?ver=3.4.1' type='text/css' media='all' />
        <![endif]-->
        <link rel='stylesheet' id='simpli_hello-admin-global-css'  href='http://wpdev.com/wp-content/plugins/simpli-framework/admin/css/admin.css?ver=1.2.1' type='text/css' media='all' />
        <script type="text/javascript">

            /**
             * Simpli Framework namespace
             *
             * Creates a Javascript namespace for the current plugin
             * Always load this in the header and do not make dependent on jQuery or anything else.
             * Ref: http://www.zachleat.com/web/namespacing-outside-of-the-yahoo-namespace/
             * @package SimpliFramework
             * @subpackage SimpliHello
             */



            //
            //if(typeof jQuery.namespace !== 'function'){
            //
            //    jQuery.namespace = function() {
            //    var a=arguments, o=null, i, j, d;
            //    for (i=0; i<a.length; i=i+1) {
            //        d=a[i].split(".");
            //        o=window;
            //        for (j=0; j<d.length; j=j+1) {
            //            o[d[j]]=o[d[j]] || {};
            //            o=o[d[j]];
            //        }
            //    }
            //    return o;
            //};
            //
            //
            //}

            if (typeof simpli_namespace !== 'function') {

                simpli_namespace = function() {
                    var a = arguments, o = null, i, j, d;
                    for (i = 0; i < a.length; i = i + 1) {
                        d = a[i].split(".");
                        o = window;
                        for (j = 0; j < d.length; j = j + 1) {
                            o[d[j]] = o[d[j]] || {};
                            o = o[d[j]];
                        }
                    }
                    return o;
                };


            }


            //this to be replaced by the plugin company and plugin name
            simpli_namespace('simpli.hello');

            /*
             *  usage :
             simpli.hello.message = function(message)
             {
             alert( message );
             };

             simpli.hello.message('hello world!');


             */

            simpli.hello.log = function(message)
            {

                if (typeof simpli_hello != 'undefined') { //check if simpli_hello namespace is available . if it is, variables are also available
                    if (simpli_hello.plugin.debug === true) { //if variables are available, we can check preferences

                        console.log(message);
                    } else {
                        console.log(message); //output to log anyway if there are no footer variables. this is in event of a fatal php error where localvars will not be printed.
                    }
                }

            };




        </script> <script type="text/javascript">
            window.onload = function() {
                /**
                 * Debug Trace
                 *
                 * Functions supporting the Debug module trace method
                 * Always load this in the header
                 * @package SimpliFramework
                 * @subpackage SimpliHello
                 */



                /**
                 * Binds a collapsible item's click events
                 *
                 * This script will toggle collapse/expand divs between hidden and visible states
                 * Usage: Use HTML in the following format (Example below). The content must be surrounded by a hidden div which is in turn surrounded by a div that contains an anchor element, and 2 spans which contain the anchor text that holds the expand/collapse text.
                 * Note that the Expand Span element must always come before the Collapse element
                 * For a working example, see the v() method in the Debug module.
                 *         <div style="display:inline-block;">
                 <a class="simpli_debug_citem" href="#"><span>More</span><span style="visibility:hidden;display:none">Less</span></a>
                 <div style="visibility:hidden;display:none;">
                 {CONTENT}
                 </div>
                 </div>
                 * @package MintForms
                 * @since 0.1.1
                 * @uses
                 * @param string $content The shortcode content
                 * @return string The parsed output of the form body tag
                 */

                simpli.hello.debug_bind_collapse_expand_events =
                        function()
                        {




                            jQuery("a.simpli_debug_citem").click(function(e) {
                                e.preventDefault();


                                el = jQuery(this).parent().find('div:first');//get the child div of the parent div of the <a> tag >
                                anchor_text_expand_element = jQuery(this).find('span:first');//.html();

                                anchor_text_collapse_element = anchor_text_expand_element.next('span');




                                if (el.css('visibility') === 'visible') {
                                    /*
                                     * If already visible, hide it and update the anchor text
                                     */
                                    el.css('visibility', 'hidden').css('display', 'none');
                                    anchor_text_collapse_element.css('visibility', 'hidden').css('display', 'none');
                                    anchor_text_expand_element.css('visibility', 'visible').css('display', 'inline');
                                }
                                else {
                                    /*
                                     * If not visible, make it visible and update the anchor text
                                     */


                                    el.css('visibility', 'visible').css('display', 'block')

                                    anchor_text_collapse_element.css('visibility', 'visible').css('display', 'inline');
                                    anchor_text_expand_element.css('visibility', 'hidden').css('display', 'none');
                                }

                            });

                        }




                /*
                 * Bind the collapse/expand events for the $this->debug()->v function
                 */



                simpli.hello.debug_bind_collapse_expand_events();


                /**
                 * Multiple Select
                 *
                 * Ref: http://www.senamion.com/blog/jmultiselect2side.html
                 * This works , but the click events do not, so I am no longer using the multi-select


                 simpli.hello.multiselect2side =
                 function()
                 {

                 jQuery('#first').multiselect2side({
                 optGroupSearch: "Group: ",
                 search: "<img src='/img/search.gif' />"
                 });
                 }


                 // Create the multi select boxes for the debug module

                 simpli.hello.multiselect2side();


                 */

            }
        </script> <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/jquery.js?ver=1.7.2'></script>
        <script type='text/javascript' src='http://wpdev.com/wp-admin/js/utils.dev.js?ver=3.4.1'></script>
        <script type='text/javascript' src='http://wpdev.com/wp-content/plugins/simpli-framework/js/simpli-wp-common.js'></script>
        <style type="text/css" media="print">#wpadminbar { display:none; }</style>
    </head>
    <body class="wp-admin no-js  post-php admin-bar branch-3-4 version-3-4-1 admin-color-fresh locale-en-us no-customize-support">
        <script type="text/javascript">
            document.body.className = document.body.className.replace('no-js', 'js');
        </script>


        <div id="wpwrap">

            <div id="adminmenuback"></div>
            <div id="adminmenuwrap">
                <div id="adminmenushadow"></div>
                <ul id="adminmenu" role="navigation">


                    <li class="wp-first-item wp-has-submenu wp-not-current-submenu menu-top menu-top-first menu-icon-dashboard menu-top-last" id="menu-dashboard">
                        <div class='wp-menu-image'><a href='index.php' aria-label='Dashboard'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='index.php' class="wp-first-item wp-has-submenu wp-not-current-submenu menu-top menu-top-first menu-icon-dashboard menu-top-last" tabindex="1" aria-haspopup="true">Dashboard</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Dashboard</div><ul><li class="wp-first-item"><a href='index.php' class="wp-first-item" tabindex="1">Home</a></li><li><a href='update-core.php' tabindex="1">Updates <span class='update-plugins count-14' title='1 WordPress Update, 7 Plugin Updates, 6 Theme Updates'><span class='update-count'>14</span></span></a></li></ul></div></div></li>
                    <li class="wp-not-current-submenu wp-menu-separator"><div class="separator"></div></li>
                    <li class="wp-has-submenu wp-has-current-submenu wp-menu-open open-if-no-js menu-top menu-icon-post menu-top-first" id="menu-posts">
                        <div class='wp-menu-image'><a href='edit.php' aria-label='Posts'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='edit.php' class="wp-has-submenu wp-has-current-submenu wp-menu-open open-if-no-js menu-top menu-icon-post menu-top-first" tabindex="1">Posts</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Posts</div><ul><li class="wp-first-item current"><a href='edit.php' class="wp-first-item current" tabindex="1">All Posts</a></li><li><a href='post-new.php' tabindex="1">Add New</a></li><li><a href='edit-tags.php?taxonomy=category' tabindex="1">Categories</a></li><li><a href='edit-tags.php?taxonomy=post_tag' tabindex="1">Tags</a></li></ul></div></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-media" id="menu-media">
                        <div class='wp-menu-image'><a href='upload.php' aria-label='Media'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='upload.php' class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-media" tabindex="1" aria-haspopup="true">Media</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Media</div><ul><li class="wp-first-item"><a href='upload.php' class="wp-first-item" tabindex="1">Library</a></li><li><a href='media-new.php' tabindex="1">Add New</a></li></ul></div></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-links" id="menu-links">
                        <div class='wp-menu-image'><a href='link-manager.php' aria-label='Links'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='link-manager.php' class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-links" tabindex="1" aria-haspopup="true">Links</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Links</div><ul><li class="wp-first-item"><a href='link-manager.php' class="wp-first-item" tabindex="1">All Links</a></li><li><a href='link-add.php' tabindex="1">Add New</a></li><li><a href='edit-tags.php?taxonomy=link_category' tabindex="1">Link Categories</a></li></ul></div></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-page" id="menu-pages">
                        <div class='wp-menu-image'><a href='edit.php?post_type=page' aria-label='Pages'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='edit.php?post_type=page' class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-page" tabindex="1" aria-haspopup="true">Pages</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Pages</div><ul><li class="wp-first-item"><a href='edit.php?post_type=page' class="wp-first-item" tabindex="1">All Pages</a></li><li><a href='post-new.php?post_type=page' tabindex="1">Add New</a></li></ul></div></div></li>
                    <li class="wp-not-current-submenu menu-top menu-icon-comments menu-top-last" id="menu-comments">
                        <div class='wp-menu-image'><a href='edit-comments.php' aria-label='Comments 0'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='edit-comments.php' class="wp-not-current-submenu menu-top menu-icon-comments menu-top-last" tabindex="1">Comments <span class='awaiting-mod count-0'><span class='pending-count'>0</span></span></a></li>
                    <li class="wp-not-current-submenu wp-menu-separator"><div class="separator"></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-appearance menu-top-first" id="menu-appearance">
                        <div class='wp-menu-image'><a href='themes.php' aria-label='Appearance'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='themes.php' class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-appearance menu-top-first" tabindex="1" aria-haspopup="true">Appearance</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Appearance</div><ul><li class="wp-first-item"><a href='themes.php' class="wp-first-item" tabindex="1">Themes</a></li><li><a href='widgets.php' tabindex="1">Widgets</a></li><li><a href='nav-menus.php' tabindex="1">Menus</a></li><li><a href='themes.php?page=theme_options' tabindex="1">Theme Options</a></li><li><a href='themes.php?page=custom-header' tabindex="1">Header</a></li><li><a href='themes.php?page=custom-background' tabindex="1">Background</a></li><li><a href='theme-editor.php' tabindex="1">Editor</a></li></ul></div></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-plugins" id="menu-plugins">
                        <div class='wp-menu-image'><a href='plugins.php' aria-label='Plugins 7'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='plugins.php' class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-plugins" tabindex="1" aria-haspopup="true">Plugins <span class='update-plugins count-7'><span class='plugin-count'>7</span></span></a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Plugins <span class='update-plugins count-7'><span class='plugin-count'>7</span></span></div><ul><li class="wp-first-item"><a href='plugins.php' class="wp-first-item" tabindex="1">Installed Plugins</a></li><li><a href='plugin-install.php' tabindex="1">Add New</a></li><li><a href='plugin-editor.php' tabindex="1">Editor</a></li></ul></div></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_simpli_hello_menu001_general" id="toplevel_page_simpli_hello_menu001_general"><div class='wp-menu-image'><a href='admin.php?page=simpli_hello_menu001_general' aria-label='Simpli Hello'><img src="http://wpdev.com/wp-content/plugins/simpli-framework/admin/images/menu.png" alt="" /></a></div><div class="wp-menu-arrow"><div></div></div><a href='admin.php?page=simpli_hello_menu001_general' class="wp-has-submenu wp-not-current-submenu menu-top toplevel_page_simpli_hello_menu001_general" tabindex="1" aria-haspopup="true">Simpli Hello</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Simpli Hello</div><ul><li class="wp-first-item"><a href='admin.php?page=simpli_hello_menu001_general' class="wp-first-item" tabindex="1">General Settings</a></li><li><a href='admin.php?page=simpli_hello_menu20_advanced' tabindex="1">Advanced Settings</a></li><li><a href='admin.php?page=simpli_hello_menu30_test' tabindex="1">Test Menu</a></li></ul></div></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-users" id="menu-users">
                        <div class='wp-menu-image'><a href='users.php' aria-label='Users'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='users.php' class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-users" tabindex="1" aria-haspopup="true">Users</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Users</div><ul><li class="wp-first-item"><a href='users.php' class="wp-first-item" tabindex="1">All Users</a></li><li><a href='user-new.php' tabindex="1">Add New</a></li><li><a href='profile.php' tabindex="1">Your Profile</a></li></ul></div></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-tools" id="menu-tools">
                        <div class='wp-menu-image'><a href='tools.php' aria-label='Tools'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='tools.php' class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-tools" tabindex="1" aria-haspopup="true">Tools</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Tools</div><ul><li class="wp-first-item"><a href='tools.php' class="wp-first-item" tabindex="1">Available Tools</a></li><li><a href='import.php' tabindex="1">Import</a></li><li><a href='export.php' tabindex="1">Export</a></li></ul></div></div></li>
                    <li class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-settings menu-top-last" id="menu-settings">
                        <div class='wp-menu-image'><a href='options-general.php' aria-label='Settings'><br /></a></div><div class="wp-menu-arrow"><div></div></div><a href='options-general.php' class="wp-has-submenu wp-not-current-submenu menu-top menu-icon-settings menu-top-last" tabindex="1" aria-haspopup="true">Settings</a>
                        <div class='wp-submenu'><div class='wp-submenu-wrap'><div class='wp-submenu-head'>Settings</div><ul><li class="wp-first-item"><a href='options-general.php' class="wp-first-item" tabindex="1">General</a></li><li><a href='options-writing.php' tabindex="1">Writing</a></li><li><a href='options-reading.php' tabindex="1">Reading</a></li><li><a href='options-discussion.php' tabindex="1">Discussion</a></li><li><a href='options-media.php' tabindex="1">Media</a></li><li><a href='options-privacy.php' tabindex="1">Privacy</a></li><li><a href='options-permalink.php' tabindex="1">Permalinks</a></li></ul></div></div></li><li id="collapse-menu" class="hide-if-no-js"><div id="collapse-button"><div></div></div><span>Collapse menu</span></li></ul>
            </div>
            <div id="wpcontent">

                <div id="wpadminbar" class="nojq nojs" role="navigation">
                    <div class="quicklinks">
                        <ul id="wp-admin-bar-root-default" class="ab-top-menu">
                            <li id="wp-admin-bar-wp-logo" class="menupop"><a class="ab-item" tabindex="10" aria-haspopup="true" href="http://wpdev.com/wp-admin/about.php" title="About WordPress"><span class="ab-icon"></span></a><div class="ab-sub-wrapper"><ul id="wp-admin-bar-wp-logo-default" class="ab-submenu">
                                        <li id="wp-admin-bar-about" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/about.php">About WordPress</a>		</li></ul><ul id="wp-admin-bar-wp-logo-external" class="ab-sub-secondary ab-submenu">
                                        <li id="wp-admin-bar-wporg" class=""><a class="ab-item" tabindex="10" href="http://wordpress.org/">WordPress.org</a>		</li>
                                        <li id="wp-admin-bar-documentation" class=""><a class="ab-item" tabindex="10" href="http://codex.wordpress.org/">Documentation</a>		</li>
                                        <li id="wp-admin-bar-support-forums" class=""><a class="ab-item" tabindex="10" href="http://wordpress.org/support/">Support Forums</a>		</li>
                                        <li id="wp-admin-bar-feedback" class=""><a class="ab-item" tabindex="10" href="http://wordpress.org/support/forum/requests-and-feedback">Feedback</a>		</li></ul></div>		</li>
                            <li id="wp-admin-bar-site-name" class="menupop"><a class="ab-item" tabindex="10" aria-haspopup="true" href="http://wpdev.com/">wpdev.com</a><div class="ab-sub-wrapper"><ul id="wp-admin-bar-site-name-default" class="ab-submenu">
                                        <li id="wp-admin-bar-view-site" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/">Visit Site</a>		</li></ul></div>		</li>
                            <li id="wp-admin-bar-updates" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/update-core.php" title="1 WordPress Update, 7 Plugin Updates, 6 Theme Updates"><span class="ab-icon"></span><span class="ab-label">14</span></a>		</li>
                            <li id="wp-admin-bar-comments" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/edit-comments.php" title="0 comments awaiting moderation"><span class="ab-icon"></span><span id="ab-awaiting-mod" class="ab-label awaiting-mod pending-count count-0">0</span></a>		</li>
                            <li id="wp-admin-bar-new-content" class="menupop"><a class="ab-item" tabindex="10" aria-haspopup="true" href="http://wpdev.com/wp-admin/post-new.php" title="Add New"><span class="ab-icon"></span><span class="ab-label">New</span></a><div class="ab-sub-wrapper"><ul id="wp-admin-bar-new-content-default" class="ab-submenu">
                                        <li id="wp-admin-bar-new-post" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/post-new.php">Post</a>		</li>
                                        <li id="wp-admin-bar-new-media" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/media-new.php">Media</a>		</li>
                                        <li id="wp-admin-bar-new-link" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/link-add.php">Link</a>		</li>
                                        <li id="wp-admin-bar-new-page" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/post-new.php?post_type=page">Page</a>		</li>
                                        <li id="wp-admin-bar-new-user" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/user-new.php">User</a>		</li></ul></div>		</li>
                            <li id="wp-admin-bar-view" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/test25/">View Post</a>		</li></ul><ul id="wp-admin-bar-top-secondary" class="ab-top-secondary ab-top-menu">
                            <li id="wp-admin-bar-my-account" class="menupop"><a class="ab-item" tabindex="10" aria-haspopup="true" href="http://wpdev.com/wp-admin/profile.php" title="My Account">Howdy, ns_admin</a><div class="ab-sub-wrapper"><ul id="wp-admin-bar-user-actions" class=" ab-submenu">
                                        <li id="wp-admin-bar-user-info" class=""><a class="ab-item" tabindex="-1" href="http://wpdev.com/wp-admin/profile.php"><span class='display-name'>ns_admin</span></a>		</li>
                                        <li id="wp-admin-bar-edit-profile" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-admin/profile.php">Edit My Profile</a>		</li>
                                        <li id="wp-admin-bar-logout" class=""><a class="ab-item" tabindex="10" href="http://wpdev.com/wp-login.php?action=logout&#038;_wpnonce=7105919366">Log Out</a>		</li></ul></div>		</li></ul>			</div>
                </div>


                <div id="wpbody">

                    <div id="wpbody-content">
                        <div id="screen-meta" class="metabox-prefs">

                            <div id="contextual-help-wrap" class="hidden">
                                <div id="contextual-help-back"></div>
                                <div id="contextual-help-columns">
                                    <div class="contextual-help-tabs">
                                        <ul>

                                            <li id="tab-link-customize-display" class="active">
                                                <a href="#tab-panel-customize-display">
                                                    Customizing This Display								</a>
                                            </li>

                                            <li id="tab-link-title-post-editor">
                                                <a href="#tab-panel-title-post-editor">
                                                    Title and Post Editor								</a>
                                            </li>

                                            <li id="tab-link-publish-box">
                                                <a href="#tab-panel-publish-box">
                                                    Publish Box								</a>
                                            </li>

                                            <li id="tab-link-discussion-settings">
                                                <a href="#tab-panel-discussion-settings">
                                                    Discussion Settings								</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="contextual-help-sidebar">
                                        <p>You can also create posts with the <a href="options-writing.php">Press This bookmarklet</a>.</p><p><strong>For more information:</strong></p><p><a href="http://codex.wordpress.org/Posts_Add_New_Screen" target="_blank">Documentation on Writing and Editing Posts</a></p><p><a href="http://wordpress.org/support/" target="_blank">Support Forums</a></p>					</div>

                                    <div class="contextual-help-tabs-wrap">

                                        <div id="tab-panel-customize-display" class="help-tab-content active">
                                            <p>The title field and the big Post Editing Area are fixed in place, but you can reposition all the other boxes using drag and drop, and can minimize or expand them by clicking the title bar of each box. Use the Screen Options tab to unhide more boxes (Excerpt, Send Trackbacks, Custom Fields, Discussion, Slug, Author) or to choose a 1- or 2-column layout for this screen.</p>							</div>

                                        <div id="tab-panel-title-post-editor" class="help-tab-content">
                                            <p><strong>Title</strong> - Enter a title for your post. After you enter a title, you&#8217;ll see the permalink below, which you can edit.</p><p><strong>Post editor</strong> - Enter the text for your post. There are two modes of editing: Visual and HTML. Choose the mode by clicking on the appropriate tab. Visual mode gives you a WYSIWYG editor. Click the last icon in the row to get a second row of controls. The HTML mode allows you to enter raw HTML along with your post text. You can insert media files by clicking the icons above the post editor and following the directions. You can go to the distraction-free writing screen via the Fullscreen icon in Visual mode (second to last in the top row) or the Fullscreen button in HTML mode (last in the row). Once there, you can make buttons visible by hovering over the top area. Exit Fullscreen back to the regular post editor.</p>							</div>

                                        <div id="tab-panel-publish-box" class="help-tab-content">
                                            <p><strong>Publish</strong> - You can set the terms of publishing your post in the Publish box. For Status, Visibility, and Publish (immediately), click on the Edit link to reveal more options. Visibility includes options for password-protecting a post or making it stay at the top of your blog indefinitely (sticky). Publish (immediately) allows you to set a future or past date and time, so you can schedule a post to be published in the future or backdate a post.</p><p><strong>Post Format</strong> - This designates how your theme will display a specific post. For example, you could have a <em>standard</em> blog post with a title and paragraphs, or a short <em>aside</em> that omits the title and contains a short text blurb. Please refer to the Codex for <a href="http://codex.wordpress.org/Post_Formats#Supported_Formats">descriptions of each post format</a>. Your theme could enable all or some of 10 possible formats.</p><p><strong>Featured Image</strong> - This allows you to associate an image with your post without inserting it. This is usually useful only if your theme makes use of the featured image as a post thumbnail on the home page, a custom header, etc.</p>							</div>

                                        <div id="tab-panel-discussion-settings" class="help-tab-content">
                                            <p><strong>Send Trackbacks</strong> - Trackbacks are a way to notify legacy blog systems that you&#8217;ve linked to them. Enter the URL(s) you want to send trackbacks. If you link to other WordPress sites they&#8217;ll be notified automatically using pingbacks, and this field is unnecessary.</p><p><strong>Discussion</strong> - You can turn comments and pings on or off, and if there are comments on the post, you can see them here and moderate them.</p>							</div>
                                    </div>
                                </div>
                            </div>
                            <div id="screen-options-wrap" class="hidden">
                                <form id="adv-settings" action="" method="post">
                                    <h5>Show on screen</h5>
                                    <div class="metabox-prefs">
                                        <label for="post_user_options_metabox_ajax_options-hide"><input class="hide-postbox-tog" name="post_user_options_metabox_ajax_options-hide" type="checkbox" id="post_user_options_metabox_ajax_options-hide" value="post_user_options_metabox_ajax_options" checked="checked" />Post Options box but with ajax PostUserOptions.php</label>
                                        <label for="formatdiv-hide"><input class="hide-postbox-tog" name="formatdiv-hide" type="checkbox" id="formatdiv-hide" value="formatdiv" checked="checked" />Format</label>
                                        <label for="categorydiv-hide"><input class="hide-postbox-tog" name="categorydiv-hide" type="checkbox" id="categorydiv-hide" value="categorydiv" checked="checked" />Categories</label>
                                        <label for="tagsdiv-post_tag-hide"><input class="hide-postbox-tog" name="tagsdiv-post_tag-hide" type="checkbox" id="tagsdiv-post_tag-hide" value="tagsdiv-post_tag" checked="checked" />Tags</label>
                                        <label for="postimagediv-hide"><input class="hide-postbox-tog" name="postimagediv-hide" type="checkbox" id="postimagediv-hide" value="postimagediv" checked="checked" />Featured Image</label>
                                        <label for="postexcerpt-hide"><input class="hide-postbox-tog" name="postexcerpt-hide" type="checkbox" id="postexcerpt-hide" value="postexcerpt" checked="checked" />Excerpt</label>
                                        <label for="trackbacksdiv-hide"><input class="hide-postbox-tog" name="trackbacksdiv-hide" type="checkbox" id="trackbacksdiv-hide" value="trackbacksdiv" checked="checked" />Send Trackbacks</label>
                                        <label for="postcustom-hide"><input class="hide-postbox-tog" name="postcustom-hide" type="checkbox" id="postcustom-hide" value="postcustom" checked="checked" />Custom Fields</label>
                                        <label for="commentstatusdiv-hide"><input class="hide-postbox-tog" name="commentstatusdiv-hide" type="checkbox" id="commentstatusdiv-hide" value="commentstatusdiv" checked="checked" />Discussion</label>
                                        <label for="commentsdiv-hide"><input class="hide-postbox-tog" name="commentsdiv-hide" type="checkbox" id="commentsdiv-hide" value="commentsdiv" checked="checked" />Comments</label>
                                        <label for="slugdiv-hide"><input class="hide-postbox-tog" name="slugdiv-hide" type="checkbox" id="slugdiv-hide" value="slugdiv" checked="checked" />Slug</label>
                                        <label for="authordiv-hide"><input class="hide-postbox-tog" name="authordiv-hide" type="checkbox" id="authordiv-hide" value="authordiv" checked="checked" />Author</label>
                                        <br class="clear" />
                                    </div>
                                    <h5 class="screen-layout">Screen Layout</h5>
                                    <div class='columns-prefs'>Number of Columns:				<label class="columns-prefs-1">
                                            <input type='radio' name='screen_columns' value='1'
                                                   checked='checked' />
                                            1				</label>
                                        <label class="columns-prefs-2">
                                            <input type='radio' name='screen_columns' value='2'
                                                   />
                                            2				</label>
                                    </div>
                                    <div><input type="hidden" id="screenoptionnonce" name="screenoptionnonce" value="1425e5a2ff" /></div>
                                </form>
                            </div>
                        </div>
                        <div id="screen-meta-links">
                            <div id="contextual-help-link-wrap" class="hide-if-no-js screen-meta-toggle">
                                <a href="#contextual-help-wrap" id="contextual-help-link" class="show-settings">Help</a>
                            </div>
                            <div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
                                <a href="#screen-options-wrap" id="show-settings-link" class="show-settings">Screen Options</a>
                            </div>
                        </div>
                        <div class='update-nag'><a href="http://codex.wordpress.org/Version_3.6.1">WordPress 3.6.1</a> is available! <a href="http://wpdev.com/wp-admin/update-core.php">Please update now</a>.</div>
                        <div class="wrap">
                            <div id="icon-edit" class="icon32 icon32-posts-post"><br /></div><h2>Edit Post <a href="post-new.php" class="add-new-h2">Add New</a></h2>
                            <form name="post" action="post.php" method="post" id="post">
                                <input type="hidden" id="_wpnonce" name="_wpnonce" value="9a16e76aa5" /><input type="hidden" name="_wp_http_referer" value="/wp-admin/post.php?post=68&amp;action=edit" /><input type="hidden" id="user-id" name="user_ID" value="1" />
                                <input type="hidden" id="hiddenaction" name="action" value="editpost" />
                                <input type="hidden" id="originalaction" name="originalaction" value="editpost" />
                                <input type="hidden" id="post_author" name="post_author" value="1" />
                                <input type="hidden" id="post_type" name="post_type" value="post" />
                                <input type="hidden" id="original_post_status" name="original_post_status" value="publish" />
                                <input type="hidden" id="referredby" name="referredby" value="http://wpdev.com/wp-admin/edit.php" />
                                <input type="hidden" id="active_post_lock" value="1379400645:1" />
                                <input type="hidden" name="_wp_original_http_referer" value="http://wpdev.com/wp-admin/edit.php" /><input type='hidden' id='post_ID' name='post_ID' value='68' /><input type="hidden" id="autosavenonce" name="autosavenonce" value="363275f8d0" /><input type="hidden" id="meta-box-order-nonce" name="meta-box-order-nonce" value="e140f8c110" /><input type="hidden" id="closedpostboxesnonce" name="closedpostboxesnonce" value="9abd41ebbd" />
                                <div id="poststuff">

                                    <div id="post-body" class="metabox-holder columns-1">
                                        <div id="post-body-content">
                                            <div id="titlediv">
                                                <div id="titlewrap">
                                                    <label class="hide-if-no-js" style="visibility:hidden" id="title-prompt-text" for="title">Enter title here</label>
                                                    <input type="text" name="post_title" size="30" tabindex="1" value="Test Post Title" id="title" autocomplete="off" />
                                                </div>
                                                <div class="inside">
                                                    <div id="edit-slug-box">
                                                        <strong>Permalink:</strong>
                                                        <span id="sample-permalink">http://wpdev.com/<span id="editable-post-name" title="Click to edit this part of the permalink">test25</span>/</span>
                                                        &lrm;<span id="edit-slug-buttons"><a href="#post_name" class="edit-slug button hide-if-no-js" onclick="editPermalink(68);
                return false;">Edit</a></span>
                                                        <span id="editable-post-name-full">test25</span>
                                                        <span id='view-post-btn'><a href='http://wpdev.com/test25/' class='button' target='_blank'>View Post</a></span>
                                                        <input id="shortlink" type="hidden" value="http://wpdev.com/?p=68" /><a href="#" class="button" onclick="prompt( & #39; URL: & #39; , jQuery('#shortlink').val()); return false;">Get Shortlink</a>	</div>
                                                </div>
                                                <input type="hidden" id="samplepermalinknonce" name="samplepermalinknonce" value="7d649455c6" /></div>

                                            <div id="postdivrich" class="postarea">

                                                <div id="wp-content-wrap" class="wp-editor-wrap tmce-active"><link rel='stylesheet' id='editor-buttons-css'  href='http://wpdev.com/wp-includes/css/editor.dev.css?ver=3.4.1' type='text/css' media='all' />
                                                    <div id="wp-content-editor-tools" class="wp-editor-tools"><a id="content-html" class="hide-if-no-js wp-switch-editor switch-html" onclick="switchEditors.switchto(this);">HTML</a>
                                                        <a id="content-tmce" class="hide-if-no-js wp-switch-editor switch-tmce" onclick="switchEditors.switchto(this);">Visual</a>
                                                        <div id="wp-content-media-buttons" class="hide-if-no-js wp-media-buttons"><a href="http://wpdev.com/wp-admin/media-upload.php?post_id=68&#038;TB_iframe=1" class="thickbox add_media" id="content-add_media" title="Add Media" onclick="return false;">Upload/Insert <img src="http://wpdev.com/wp-admin/images/media-button.png?ver=20111005" width="15" height="15" /></a></div>
                                                    </div>
                                                    <div id="wp-content-editor-container" class="wp-editor-container"><textarea class="wp-editor-area" rows="20" tabindex="1" cols="40" name="content" id="content">&lt;p&gt;Post Content.&lt;/p&gt;
&lt;p&gt;&amp;nbsp;&lt;/p&gt;
                                                        </textarea></div>
                                                </div>


                                                <table id="post-status-info" cellspacing="0"><tbody><tr>
                                                            <td id="wp-word-count">Word count: <span class="word-count">0</span></td>
                                                            <td class="autosave-info">
                                                                <span class="autosave-message">&nbsp;</span>
                                                                <span id="last-edit">Last edited by ns_admin on September 16, 2013 at 11:47 pm</span>	</td>
                                                        </tr></tbody></table>

                                            </div>
                                        </div><!-- /post-body-content -->

                                        <div id="postbox-container-1" class="postbox-container">
                                            <div id="side-sortables" class="meta-box-sortables"><div id="submitdiv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Publish</span></h3>
                                                    <div class="inside">
                                                        <div class="submitbox" id="submitpost">

                                                            <div id="minor-publishing">

                                                                <div style="display:none;">
                                                                    <p class="submit"><input type="submit" name="save" id="save" class="button" value="Save"  /></p></div>

                                                                <div id="minor-publishing-actions">
                                                                    <div id="save-action">
                                                                        <img src="http://wpdev.com/wp-admin/images/wpspin_light.gif" class="ajax-loading" id="draft-ajax-loading" alt="" />
                                                                    </div>
                                                                    <div id="preview-action">
                                                                        <a class="preview button" href="http://wpdev.com/test25/" target="wp-preview" id="post-preview" tabindex="4">Preview Changes</a>
                                                                        <input type="hidden" name="wp-preview" id="wp-preview" value="" />
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>
                                                                <div id="misc-publishing-actions">

                                                                    <div class="misc-pub-section"><label for="post_status">Status:</label>
                                                                        <span id="post-status-display">
                                                                            Published</span>
                                                                        <a href="#post_status" class="edit-post-status hide-if-no-js" tabindex='4'>Edit</a>

                                                                        <div id="post-status-select" class="hide-if-js">
                                                                            <input type="hidden" name="hidden_post_status" id="hidden_post_status" value="publish" />
                                                                            <select name='post_status' id='post_status' tabindex='4'>
                                                                                <option selected='selected' value='publish'>Published</option>
                                                                                <option value='pending'>Pending Review</option>
                                                                                <option value='draft'>Draft</option>
                                                                            </select>
                                                                            <a href="#post_status" class="save-post-status hide-if-no-js button">OK</a>
                                                                            <a href="#post_status" class="cancel-post-status hide-if-no-js">Cancel</a>
                                                                        </div>

                                                                    </div>
                                                                    <div class="misc-pub-section" id="visibility">
                                                                        Visibility: <span id="post-visibility-display">Public</span>
                                                                        <a href="#visibility" class="edit-visibility hide-if-no-js">Edit</a>

                                                                        <div id="post-visibility-select" class="hide-if-js">
                                                                            <input type="hidden" name="hidden_post_password" id="hidden-post-password" value="" />
                                                                            <input type="checkbox" style="display:none" name="hidden_post_sticky" id="hidden-post-sticky" value="sticky"  />
                                                                            <input type="hidden" name="hidden_post_visibility" id="hidden-post-visibility" value="public" />
                                                                            <input type="radio" name="visibility" id="visibility-radio-public" value="public"  checked='checked' /> <label for="visibility-radio-public" class="selectit">Public</label><br />
                                                                            <span id="sticky-span"><input id="sticky" name="sticky" type="checkbox" value="sticky"  tabindex="4" /> <label for="sticky" class="selectit">Stick this post to the front page</label><br /></span>
                                                                            <input type="radio" name="visibility" id="visibility-radio-password" value="password"  /> <label for="visibility-radio-password" class="selectit">Password protected</label><br />
                                                                            <span id="password-span"><label for="post_password">Password:</label> <input type="text" name="post_password" id="post_password" value="" /><br /></span>
                                                                            <input type="radio" name="visibility" id="visibility-radio-private" value="private"  /> <label for="visibility-radio-private" class="selectit">Private</label><br />

                                                                            <p>
                                                                                <a href="#visibility" class="save-post-visibility hide-if-no-js button">OK</a>
                                                                                <a href="#visibility" class="cancel-post-visibility hide-if-no-js">Cancel</a>
                                                                            </p>
                                                                        </div>

                                                                    </div>
                                                                    <div class="misc-pub-section curtime">
                                                                        <span id="timestamp">
                                                                            Published on: <b>Sep 3, 2013 @ 16:28</b></span>
                                                                        <a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" tabindex='4'>Edit</a>
                                                                        <div id="timestampdiv" class="hide-if-js"><div class="timestamp-wrap"><select id="mm" name="mm" tabindex="4">
                                                                                    <option value="01">01-Jan</option>
                                                                                    <option value="02">02-Feb</option>
                                                                                    <option value="03">03-Mar</option>
                                                                                    <option value="04">04-Apr</option>
                                                                                    <option value="05">05-May</option>
                                                                                    <option value="06">06-Jun</option>
                                                                                    <option value="07">07-Jul</option>
                                                                                    <option value="08">08-Aug</option>
                                                                                    <option value="09" selected="selected">09-Sep</option>
                                                                                    <option value="10">10-Oct</option>
                                                                                    <option value="11">11-Nov</option>
                                                                                    <option value="12">12-Dec</option>
                                                                                </select><input type="text" id="jj" name="jj" value="03" size="2" maxlength="2" tabindex="4" autocomplete="off" />, <input type="text" id="aa" name="aa" value="2013" size="4" maxlength="4" tabindex="4" autocomplete="off" /> @ <input type="text" id="hh" name="hh" value="16" size="2" maxlength="2" tabindex="4" autocomplete="off" /> : <input type="text" id="mn" name="mn" value="28" size="2" maxlength="2" tabindex="4" autocomplete="off" /></div><input type="hidden" id="ss" name="ss" value="18" />

                                                                            <input type="hidden" id="hidden_mm" name="hidden_mm" value="09" />
                                                                            <input type="hidden" id="cur_mm" name="cur_mm" value="09" />
                                                                            <input type="hidden" id="hidden_jj" name="hidden_jj" value="03" />
                                                                            <input type="hidden" id="cur_jj" name="cur_jj" value="16" />
                                                                            <input type="hidden" id="hidden_aa" name="hidden_aa" value="2013" />
                                                                            <input type="hidden" id="cur_aa" name="cur_aa" value="2013" />
                                                                            <input type="hidden" id="hidden_hh" name="hidden_hh" value="16" />
                                                                            <input type="hidden" id="cur_hh" name="cur_hh" value="23" />
                                                                            <input type="hidden" id="hidden_mn" name="hidden_mn" value="28" />
                                                                            <input type="hidden" id="cur_mn" name="cur_mn" value="50" />

                                                                            <p>
                                                                                <a href="#edit_timestamp" class="save-timestamp hide-if-no-js button">OK</a>
                                                                                <a href="#edit_timestamp" class="cancel-timestamp hide-if-no-js">Cancel</a>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="clear"></div>
                                                            </div>

                                                            <div id="major-publishing-actions">
                                                                <div id="delete-action">
                                                                    <a class="submitdelete deletion" href="http://wpdev.com/wp-admin/post.php?post=68&amp;action=trash&amp;_wpnonce=fea813db75">Move to Trash</a></div>

                                                                <div id="publishing-action">
                                                                    <img src="http://wpdev.com/wp-admin/images/wpspin_light.gif" class="ajax-loading" id="ajax-loading" alt="" />
                                                                    <input name="original_publish" type="hidden" id="original_publish" value="Update" />
                                                                    <input name="save" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="Update" />
                                                                </div>
                                                                <div class="clear"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div id="formatdiv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Format</span></h3>
                                                    <div class="inside">
                                                        <div id="post-formats-select">
                                                            <input type="radio" name="post_format" class="post-format" id="post-format-0" value="0"  checked='checked' /> <label for="post-format-0">Standard</label>
                                                            <br /><input type="radio" name="post_format" class="post-format" id="post-format-aside" value="aside"  /> <label for="post-format-aside">Aside</label>
                                                            <br /><input type="radio" name="post_format" class="post-format" id="post-format-link" value="link"  /> <label for="post-format-link">Link</label>
                                                            <br /><input type="radio" name="post_format" class="post-format" id="post-format-gallery" value="gallery"  /> <label for="post-format-gallery">Gallery</label>
                                                            <br /><input type="radio" name="post_format" class="post-format" id="post-format-status" value="status"  /> <label for="post-format-status">Status</label>
                                                            <br /><input type="radio" name="post_format" class="post-format" id="post-format-quote" value="quote"  /> <label for="post-format-quote">Quote</label>
                                                            <br /><input type="radio" name="post_format" class="post-format" id="post-format-image" value="image"  /> <label for="post-format-image">Image</label>
                                                            <br />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="categorydiv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Categories</span></h3>
                                                    <div class="inside">
                                                        <div id="taxonomy-category" class="categorydiv">
                                                            <ul id="category-tabs" class="category-tabs">
                                                                <li class="tabs"><a href="#category-all" tabindex="3">All Categories</a></li>
                                                                <li class="hide-if-no-js"><a href="#category-pop" tabindex="3">Most Used</a></li>
                                                            </ul>

                                                            <div id="category-pop" class="tabs-panel" style="display: none;">
                                                                <ul id="categorychecklist-pop" class="categorychecklist form-no-clear" >

                                                                    <li id="popular-category-1" class="popular-category">
                                                                        <label class="selectit">
                                                                            <input id="in-popular-category-1" type="checkbox" checked="checked" value="1" />
                                                                            Uncategorized			</label>
                                                                    </li>


                                                                    <li id="popular-category-4" class="popular-category">
                                                                        <label class="selectit">
                                                                            <input id="in-popular-category-4" type="checkbox"  value="4" />
                                                                            Featured			</label>
                                                                    </li>

                                                                </ul>
                                                            </div>

                                                            <div id="category-all" class="tabs-panel">
                                                                <input type='hidden' name='post_category[]' value='0' />			<ul id="categorychecklist" class="list:category categorychecklist form-no-clear">

                                                                    <li id='category-1' class="popular-category"><label class="selectit"><input value="1" type="checkbox" name="post_category[]" id="in-category-1" checked='checked' /> Uncategorized</label></li>

                                                                    <li id='category-4' class="popular-category"><label class="selectit"><input value="4" type="checkbox" name="post_category[]" id="in-category-4" /> Featured</label></li>
                                                                </ul>
                                                            </div>
                                                            <div id="category-adder" class="wp-hidden-children">
                                                                <h4>
                                                                    <a id="category-add-toggle" href="#category-add" class="hide-if-no-js" tabindex="3">
                                                                        + Add New Category					</a>
                                                                </h4>
                                                                <p id="category-add" class="category-add wp-hidden-child">
                                                                    <label class="screen-reader-text" for="newcategory">Add New Category</label>
                                                                    <input type="text" name="newcategory" id="newcategory" class="form-required form-input-tip" value="New Category Name" tabindex="3" aria-required="true"/>
                                                                    <label class="screen-reader-text" for="newcategory_parent">
                                                                        Parent Category:					</label>
                                                                    <select name='newcategory_parent' id='newcategory_parent' class='postform'  tabindex="3">
                                                                        <option value='-1'>&mdash; Parent Category &mdash;</option>
                                                                        <option class="level-0" value="4">Featured</option>
                                                                        <option class="level-0" value="1">Uncategorized</option>
                                                                    </select>
                                                                    <input type="button" id="category-add-submit" class="add:categorychecklist:category-add button category-add-submit" value="Add New Category" tabindex="3" />
                                                                    <input type="hidden" id="_ajax_nonce-add-category" name="_ajax_nonce-add-category" value="fbc4ad8002" />					<span id="category-ajax-response"></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="tagsdiv-post_tag" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Tags</span></h3>
                                                    <div class="inside">
                                                        <div class="tagsdiv" id="post_tag">
                                                            <div class="jaxtag">
                                                                <div class="nojs-tags hide-if-js">
                                                                    <p>Add or remove tags</p>
                                                                    <textarea name="tax_input[post_tag]" rows="3" cols="20" class="the-tags" id="tax-input-post_tag" ></textarea></div>
                                                                <div class="ajaxtag hide-if-no-js">
                                                                    <label class="screen-reader-text" for="new-tag-post_tag">Tags</label>
                                                                    <div class="taghint">Add New Tag</div>
                                                                    <p><input type="text" id="new-tag-post_tag" name="newtag[post_tag]" class="newtag form-input-tip" size="16" autocomplete="off" value="" />
                                                                        <input type="button" class="button tagadd" value="Add" tabindex="3" /></p>
                                                                </div>
                                                                <p class="howto">Separate tags with commas</p>
                                                            </div>
                                                            <div class="tagchecklist"></div>
                                                        </div>
                                                        <p class="hide-if-no-js"><a href="#titlediv" class="tagcloud-link" id="link-post_tag">Choose from the most used tags</a></p>
                                                    </div>
                                                </div>
                                                <div id="postimagediv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Featured Image</span></h3>
                                                    <div class="inside">
                                                        <p class="hide-if-no-js"><a title="Set featured image" href="http://wpdev.com/wp-admin/media-upload.php?post_id=68&#038;type=image&#038;TB_iframe=1" id="set-post-thumbnail" class="thickbox">Set featured image</a></p></div>
                                                </div>
                                            </div></div>
                                        <div id="postbox-container-2" class="postbox-container">
                                            <div id="normal-sortables" class="meta-box-sortables"><div id="postexcerpt" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Excerpt</span></h3>
                                                    <div class="inside">
                                                        <label class="screen-reader-text" for="excerpt">Excerpt</label><textarea rows="1" cols="40" name="excerpt" tabindex="6" id="excerpt"></textarea>
                                                        <p>Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="http://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a></p>
                                                    </div>
                                                </div>
                                                <div id="trackbacksdiv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Send Trackbacks</span></h3>
                                                    <div class="inside">
                                                        <p><label for="trackback_url">Send trackbacks to:</label> <input type="text" name="trackback_url" id="trackback_url" class="code" tabindex="7" value="" /><br /> (Separate multiple URLs with spaces)</p>
                                                        <p>Trackbacks are a way to notify legacy blog systems that you&#8217;ve linked to them. If you link other WordPress sites they&#8217;ll be notified automatically using <a href="http://codex.wordpress.org/Introduction_to_Blogging#Managing_Comments" target="_blank">pingbacks</a>, no other action necessary.</p>
                                                    </div>
                                                </div>
                                                <div id="postcustom" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Custom Fields</span></h3>
                                                    <div class="inside">
                                                        <div id="postcustomstuff">
                                                            <div id="ajax-response"></div>
                                                            <table id="list-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="left">Name</th>
                                                                        <th>Value</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id='the-list' class='list:meta'>
                                                                </tbody>
                                                            </table>
                                                            <p><strong>Add New Custom Field:</strong></p>
                                                            <table id="newmeta">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="left"><label for="metakeyselect">Name</label></th>
                                                                        <th><label for="metavalue">Value</label></th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    <tr>
                                                                        <td id="newmetaleft" class="left">
                                                                            <select id="metakeyselect" name="metakeyselect" tabindex="7">
                                                                                <option value="#NONE#">&mdash; Select &mdash;</option>

                                                                                <option value='simpli-photos_options'>simpli-photos_options</option>
                                                                                <option value='simpli-thumbs_options'>simpli-thumbs_options</option>
                                                                                <option value='simpli_hello_options'>simpli_hello_options</option></select>
                                                                            <input class="hide-if-js" type="text" id="metakeyinput" name="metakeyinput" tabindex="7" value="" />
                                                                            <a href="#postcustomstuff" class="hide-if-no-js" onclick="jQuery('#metakeyinput, #metakeyselect, #enternew, #cancelnew').toggle();
                return false;">
                                                                                <span id="enternew">Enter new</span>
                                                                                <span id="cancelnew" class="hidden">Cancel</span></a>
                                                                        </td>
                                                                        <td><textarea id="metavalue" name="metavalue" rows="2" cols="25" tabindex="8"></textarea></td>
                                                                    </tr>

                                                                    <tr><td colspan="2" class="submit">
                                                                            <input type="submit" name="addmeta" id="addmetasub" class="add:the-list:newmeta" value="Add Custom Field" tabindex="9"  /><input type="hidden" id="_ajax_nonce-add-meta" name="_ajax_nonce-add-meta" value="9c3b4c03fc" /></td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <p>Custom fields can be used to add extra metadata to a post that you can <a href="http://codex.wordpress.org/Using_Custom_Fields" target="_blank">use in your theme</a>.</p>
                                                    </div>
                                                </div>
                                                <div id="commentstatusdiv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Discussion</span></h3>
                                                    <div class="inside">
                                                        <input name="advanced_view" type="hidden" value="1" />
                                                        <p class="meta-options">
                                                            <label for="comment_status" class="selectit"><input name="comment_status" type="checkbox" id="comment_status" value="open"  /> Allow comments.</label><br />
                                                            <label for="ping_status" class="selectit"><input name="ping_status" type="checkbox" id="ping_status" value="open"  /> Allow <a href="http://codex.wordpress.org/Introduction_to_Blogging#Managing_Comments" target="_blank">trackbacks and pingbacks</a> on this page.</label>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div id="commentsdiv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Comments</span></h3>
                                                    <div class="inside">
                                                        <input type="hidden" id="add_comment_nonce" name="add_comment_nonce" value="23d2c901f1" />	<p class="hide-if-no-js" id="add-new-comment"><a href="#commentstatusdiv" onclick="commentReply.addcomment(68);
                return false;">Add comment</a></p>
                                                        <input type="hidden" id="_ajax_fetch_list_nonce" name="_ajax_fetch_list_nonce" value="d1e89fc0a4" /><input type="hidden" name="_wp_http_referer" value="/wp-admin/post.php?post=68&amp;action=edit" /><table class="widefat fixed comments comments-box" cellspacing="0" style="display:none;">
                                                            <tbody id="the-comment-list" class='list:comment'>
                                                            </tbody>
                                                        </table>
                                                        <p id="no-comments">No comments yet.</p><div class="hidden" id="trash-undo-holder">
                                                            <div class="trash-undo-inside">Comment by <strong></strong> moved to the trash. <span class="undo untrash"><a href="#">Undo</a></span></div>
                                                        </div>
                                                        <div class="hidden" id="spam-undo-holder">
                                                            <div class="spam-undo-inside">Comment by <strong></strong> marked as spam. <span class="undo unspam"><a href="#">Undo</a></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="slugdiv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Slug</span></h3>
                                                    <div class="inside">
                                                        <label class="screen-reader-text" for="post_name">Slug</label><input name="post_name" type="text" size="13" id="post_name" value="test25" />
                                                    </div>
                                                </div>
                                                <div id="authordiv" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Author</span></h3>
                                                    <div class="inside">
                                                        <label class="screen-reader-text" for="post_author_override">Author</label>
                                                        <select name='post_author_override' id='post_author_override' class=''>
                                                            <option value='1' selected='selected'>ns_admin</option>
                                                        </select></div>
                                                </div>
                                            </div><div id="advanced-sortables" class="meta-box-sortables"><div id="post_user_options_metabox_ajax_options" class="postbox " >
                                                    <div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span>Post Options box but with ajax PostUserOptions.php</span></h3>
                                                    <div class="inside">
                                                        <div id="simpli-hello">
                                                            <form id="simpli_forms_ajaxoptions_1" action="options_save" method="post">
                                                                <input type="hidden" id="_wpnonce" name="_wpnonce" value="0b80148dfc" /><input type="hidden" name="_wp_http_referer" value="/wp-admin/post.php?post=68&amp;action=edit" />    <input type="hidden" name="action"  value="" />








                                                                <input type="hidden" id="simpli_hello_nonce" name="simpli_hello_nonce" value="2e223d33ea" /><input type="hidden" name="_wp_http_referer" value="/wp-admin/post.php?post=68&amp;action=edit" /><label>
                                                                    Enabled for this post:
                                                                </label>


                                                                <span><label> <input type="radio" name="simpli_hello[enabled]" value="enabled"   checked="checked" > Yes</label></span>
                                                                <span><label> <input type="radio" name="simpli_hello[enabled]" value="disabled"   > No</label></span>




                                                                <p class="description" style="padding-left:10px;">
                                                                </p>





                                                                <label>
                                                                    Placement:<br>
                                                                </label>


                                                                <span><label> <input type="radio" name="simpli_hello[placement]" value="before"   > Before Content</label></span>
                                                                <span><label> <input type="radio" name="simpli_hello[placement]" value="after"   checked="checked" > After Content</label></span>
                                                                <span><label> <input type="radio" name="simpli_hello[placement]" value="default"   > Default</label></span>




                                                                <p class="description" style="padding-left:10px;">
                                                                </p>





                                                                <label>
                                                                    Text
                                                                </label>


                                                                <span><label> <input type="radio" name="simpli_hello[use_global_text]" value="false"   > Custom</label></span>
                                                                <span><label> <input type="radio" name="simpli_hello[use_global_text]" value="true"   > Default</label></span>
                                                                <span><label> <input type="radio" name="simpli_hello[use_global_text]" value="snippet"   checked="checked" > Snippet</label></span>




                                                                <p class="description" style="padding-left:10px;">
                                                                </p>





                                                                <label>
                                                                    Custom Text:

                                                                </label>

                                                                <input name="simpli_hello[text]" type="text" id="{ID}" class="" value="custom text today" />
                                                                <h4 class="title"></h4>

                                                                <table class="form-table">
                                                                    <tbody>

                                                                        <tr>
                                                                            <th>
                                                                                Simpli Hello Snippets:

                                                                            </th>
                                                                            <td>
                                                                                <fieldset>
                                                                                    <label>


                                                                                        <select name='simpli_hello[snippet]'>

                                                                                            <option  selected="selected"  value="197">my-custom-snippet2</option><option  value="195">my-first-snippet</option>

                                                                                        </select>

                                                                                        <span class="description" style="padding-left:10px;">
                                                                                            <a href="#http://wpdev.com/wp-admin//wp-admin/edit.php?post_type=simpli_hello_snippet">View/Edit Snippets</a></span>
                                                                                    </label>
                                                                                </fieldset>
                                                                            </td>
                                                                        </tr>



                                                                    </tbody>
                                                                </table>





                                                                <p class="button-controls">

                                                                    <input type="submit" id="simpli_forms_ajaxoptions_1_post_options_save" class="button-primary" value="Save" name="simpli_forms_ajaxoptions_1_settings_save"> <span  class="post-message-body"></span>
                                                                        <img alt="Waiting..." src="http://wpdev.com/wp-admin/images/wpspin_light.gif" class="waiting submit-waiting" />
                                                                </p>




                                                            </form>
                                                            <form>
                                                                <div class="message-body"></div>
                                                            </form>



                                                        </div></div>
                                                </div>
                                            </div></div>
                                    </div><!-- /post-body -->
                                    <br class="clear" />
                                </div><!-- /poststuff -->
                            </form>
                        </div>

                        <form method="get" action="">
                            <table style="display:none;"><tbody id="com-reply"><tr id="replyrow" style="display:none;"><td colspan="2" class="colspanchange">
                                            <div id="replyhead" style="display:none;"><h5>Reply to Comment</h5></div>
                                            <div id="addhead" style="display:none;"><h5>Add new Comment</h5></div>
                                            <div id="edithead" style="display:none;">
                                                <div class="inside">
                                                    <label for="author">Name</label>
                                                    <input type="text" name="newcomment_author" size="50" value="" tabindex="101" id="author" />
                                                </div>

                                                <div class="inside">
                                                    <label for="author-email">E-mail</label>
                                                    <input type="text" name="newcomment_author_email" size="50" value="" tabindex="102" id="author-email" />
                                                </div>

                                                <div class="inside">
                                                    <label for="author-url">URL</label>
                                                    <input type="text" id="author-url" name="newcomment_author_url" size="103" value="" tabindex="103" />
                                                </div>
                                                <div style="clear:both;"></div>
                                            </div>

                                            <div id="replycontainer">
                                                <div id="wp-replycontent-wrap" class="wp-editor-wrap html-active"><div id="wp-replycontent-editor-container" class="wp-editor-container"><textarea class="wp-editor-area" rows="20" tabindex="104" cols="40" name="replycontent" id="replycontent"></textarea></div>
                                                </div>

                                            </div>

                                            <p id="replysubmit" class="submit">
                                                <a href="#comments-form" class="cancel button-secondary alignleft" tabindex="106">Cancel</a>
                                                <a href="#comments-form" class="save button-primary alignright" tabindex="104">
                                                    <span id="addbtn" style="display:none;">Add Comment</span>
                                                    <span id="savebtn" style="display:none;">Update Comment</span>
                                                    <span id="replybtn" style="display:none;">Submit Reply</span></a>
                                                <img class="waiting" style="display:none;" src="http://wpdev.com/wp-admin/images/wpspin_light.gif" alt="" />
                                                <span class="error" style="display:none;"></span>
                                                <br class="clear" />
                                            </p>

                                            <input type="hidden" name="user_ID" id="user_ID" value="1" />
                                            <input type="hidden" name="action" id="action" value="" />
                                            <input type="hidden" name="comment_ID" id="comment_ID" value="" />
                                            <input type="hidden" name="comment_post_ID" id="comment_post_ID" value="" />
                                            <input type="hidden" name="status" id="status" value="" />
                                            <input type="hidden" name="position" id="position" value="1" />
                                            <input type="hidden" name="checkbox" id="checkbox" value="0" />
                                            <input type="hidden" name="mode" id="mode" value="single" />
                                            <input type="hidden" id="_ajax_nonce-replyto-comment" name="_ajax_nonce-replyto-comment" value="d9a520fc35" /><input type="hidden" id="_wp_unfiltered_html_comment" name="_wp_unfiltered_html_comment" value="8d93203c11" /></td></tr></tbody></table>
                        </form>


                        <div class="clear"></div></div><!-- wpbody-content -->
                    <div class="clear"></div></div><!-- wpbody -->
                <div class="clear"></div></div><!-- wpcontent -->

            <div id="footer">
                <p id="footer-left" class="alignleft"><span id="footer-thankyou">Thank you for creating with <a href="http://wordpress.org/">WordPress</a>.</span></p>
                <p id="footer-upgrade" class="alignright"><strong><a href="http://wpdev.com/wp-admin/update-core.php">Get Version 3.6.1</a></strong></p>
                <div class="clear"></div>
            </div>
            <script type='text/javascript'>list_args = {"class": "WP_Post_Comments_List_Table", "screen": {"id": "post", "base": "post"}};</script>
            <script type='text/javascript'>list_args = {"class": "WP_Post_Comments_List_Table", "screen": {"id": "post", "base": "post"}};</script>
            <link rel='stylesheet' id='wp-jquery-ui-dialog-css'  href='http://wpdev.com/wp-includes/css/jquery-ui-dialog.dev.css?ver=3.4.1' type='text/css' media='all' />
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/admin-bar.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/hoverIntent.dev.js?ver=r6'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var commonL10n = {"warnDelete": "You are about to permanently delete the selected items.\n  'Cancel' to stop, 'OK' to delete."};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-admin/js/common.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/jquery.color.dev.js?ver=2.0-4561m'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/jquery.schedule.js?ver=20m'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var wpAjax = {"noPerm": "You do not have permission to do that.", "broken": "An unidentified error has occurred."};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/wp-ajax-response.dev.js?ver=3.4.1'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var autosaveL10n = {"autosaveInterval": "600", "savingText": "Saving Draft\u2026", "saveAlert": "The changes you made will be lost if you navigate away from this page."};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/autosave.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/wp-lists.dev.js?ver=3.4.1'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var quicktagsL10n = {"wordLookup": "Enter a word to look up:", "dictionaryLookup": "Dictionary lookup", "lookup": "lookup", "closeAllOpenTags": "Close all open tags", "closeTags": "close tags", "enterURL": "Enter the URL", "enterImageURL": "Enter the URL of the image", "enterImageDescription": "Enter a description of the image", "fullscreen": "fullscreen", "toggleFullscreen": "Toggle fullscreen mode", "textdirection": "text direction", "toggleTextdirection": "Toggle Editor Text Direction"};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/quicktags.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/jquery.query.js?ver=2.1.7'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var adminCommentsL10n = {"hotkeys_highlight_first": "", "hotkeys_highlight_last": "", "replyApprove": "Approve and Reply", "reply": "Reply"};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-admin/js/edit-comments.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/suggest.dev.js?ver=1.1-20110113'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.core.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.widget.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.mouse.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.sortable.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-admin/js/postbox.dev.js?ver=3.4.1'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var postL10n = {"ok": "OK", "cancel": "Cancel", "publishOn": "Publish on:", "publishOnFuture": "Schedule for:", "publishOnPast": "Published on:", "showcomm": "Show more comments", "endcomm": "No more comments found.", "publish": "Publish", "schedule": "Schedule", "update": "Update", "savePending": "Save as Pending", "saveDraft": "Save Draft", "private": "Private", "public": "Public", "publicSticky": "Public, Sticky", "password": "Password Protected", "privatelyPublished": "Privately Published", "published": "Published", "comma": ","};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-admin/js/post.dev.js?ver=3.4.1'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var thickboxL10n = {"next": "Next >", "prev": "< Prev", "image": "Image", "of": "of", "close": "Close", "noiframes": "This feature requires inline frames. You have iframes disabled or your browser does not support them.", "loadingAnimation": "http:\/\/wpdev.com\/wp-includes\/js\/thickbox\/loadingAnimation.gif", "closeImage": "http:\/\/wpdev.com\/wp-includes\/js\/thickbox\/tb-close.png"};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/thickbox/thickbox.js?ver=3.1-20111117'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-admin/js/media-upload.dev.js?ver=3.4.1'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var wordCountL10n = {"type": "w"};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-admin/js/word-count.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-admin/js/editor.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.resizable.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.draggable.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.button.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.position.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/jquery/ui/jquery.ui.dialog.min.js?ver=1.8.20'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/tinymce/plugins/wpdialogs/js/wpdialog.dev.js?ver=3.4.1'></script>
            <script type='text/javascript'>
                /* <![CDATA[ */
                var wpLinkL10n = {"title": "Insert\/edit link", "update": "Update", "save": "Add Link", "noTitle": "(no title)", "noMatchesFound": "No matches found."};
                /* ]]> */
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/wplink.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/tinymce/plugins/wpdialogs/js/popup.dev.js?ver=3.4.1'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-admin/js/wp-fullscreen.dev.js?ver=3.4.1'></script>
            <script type='text/javascript'>

                var simpli_hello = {"plugin": {"slugparts": {"prefix": "simpli", "suffix": "hello"}, "slug": "simpli_hello", "name": "Simpli Hello", "url": "http:\/\/wpdev.com\/wp-content\/plugins\/simpli-framework", "version": "1.2.1", "directory": "C:\/wamp\/www\/wpdev.com\/public_html\/wp-content\/plugins\/simpli-framework", "debug": true, "admin_url": "http:\/\/wpdev.com\/wp-admin\/", "nonce": "0b80148dfc"}, "menu_slug": "", "screen_id": ""}
            </script>

            <script type="text/javascript">

                /**
                 * Save Meta Box State
                 *
                 * Saves the state of custom metaboxes for the admin screens
                 *
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
                    postboxes.add_postbox_toggles(simpli_hello.screen_id); //e.g.: 'toplevel_page_simpli_hello_menu10_settings'



                });

            </script> <script type="text/javascript">

                /**
                 * Metabox Form Save and Reset for the Post Editor
                 *
                 * save-post-options.js
                 * @package SimpliFramework
                 * @subpackage SimpliHello
                 */


                jQuery(document).ready(function(jQuery) {


                    /*
                     * Bind form events
                     */


                    simpli.hello.bind_metabox_post_form_events();

                })








                simpli.hello.bind_metabox_post_form_events = function()
                {
                    //   var form = jQuery('#'+simpli_hello.plugin.slug + '_' + simpli_hello.metabox_forms[metabox_id].form_name).first();
                    var form;



                    /*
                     * Save Button
                     */
                    jQuery("input[id$='_post_options_save']").click(function(event) {
                        form = jQuery(this)[0].form;

                        //    event.preventDefault();
                        console.log('clicked save button');
                        jQuery(form).find('input[name="action"]').val(simpli_hello.plugin.slug + '_post_options_save');
                        jQuery(form).find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
                        jQuery('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + jQuery('.hidden-checkbox').attr('name') + '">');
                        //jQuery('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
                        submit(form, event);
                    });




                    /*
                     * Bind any dynamic events ( where the object is not known until run time)
                     */
                    // jQuery(document).ready(function() {

                    /*
                     * Do a live bind of the form submit action. Live bind is necessary since the form object is not known until a button is clicked.
                     * Live bind must be within a a ready functin
                     */
                    submit = function(form, event) {
                        event.preventDefault();
                        console.log('form submitted');

                        //return;
                        jQuery(form).find('.submit-waiting').show();
                        jQuery.post(ajaxurl, jQuery(form).serialize(), function(response) {

                            //  jQuery('.hidden-checkbox').get(0).type = 'checkbox'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
                            jQuery(form).find('.hidden-temp').remove();

                            jQuery(form).find('.hidden-checkbox').removeClass('hidden-checkbox');
                            jQuery(form).find('.submit-waiting').hide();
                            jQuery(form).find('.post-message-body').html(response).fadeOut(0).fadeIn().delay(2500).fadeOut();
                            //jQuery(form).find('.post-message-body').html(response);
                            //jQuery(form).find('.post-message-body').html(response)
                            //console.log(jQuery(form).children().find('.message-body').html(response));
                            //  jQuery(form).find('.message-body').html(response);

                            // console.log(jQuery(form).children().find('.message-body').response);
                        });

                        // });

                    }


                }


            </script>
            <script type="text/javascript">
                tinyMCEPreInit = {
                    base: "http://wpdev.com/wp-includes/js/tinymce",
                    suffix: "_src",
                    query: "ver=349-20805",
                    mceInit: {'content': {mode: "exact", width: "100%", theme: "advanced", skin: "wp_theme", language: "en", spellchecker_languages: "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv", theme_advanced_toolbar_location: "top", theme_advanced_toolbar_align: "left", theme_advanced_statusbar_location: "bottom", theme_advanced_resizing: true, theme_advanced_resize_horizontal: false, dialog_type: "modal", formats: {
                                alignleft: [
                                    {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'left'}},
                                    {selector: 'img,table', classes: 'alignleft'}
                                ],
                                aligncenter: [
                                    {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'center'}},
                                    {selector: 'img,table', classes: 'aligncenter'}
                                ],
                                alignright: [
                                    {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'right'}},
                                    {selector: 'img,table', classes: 'alignright'}
                                ],
                                strikethrough: {inline: 'del'}
                            }, relative_urls: false, remove_script_host: false, convert_urls: false, remove_linebreaks: true, gecko_spellcheck: true, fix_list_elements: true, keep_styles: false, entities: "38,amp,60,lt,62,gt", accessibility_focus: true, tabfocus_elements: "title,publish", media_strict: false, paste_remove_styles: true, paste_remove_spans: true, paste_strip_class_attributes: "all", paste_text_use_dialog: true, spellchecker_rpc_url: "http://wpdev.com/wp-includes/js/tinymce/plugins/spellchecker/rpc.php", extended_valid_elements: "article[*],aside[*],audio[*],canvas[*],command[*],datalist[*],details[*],embed[*],figcaption[*],figure[*],footer[*],header[*],hgroup[*],keygen[*],mark[*],meter[*],nav[*],output[*],progress[*],section[*],source[*],summary,time[*],video[*],wbr", wpeditimage_disable_captions: false, wp_fullscreen_content_css: "http://wpdev.com/wp-includes/js/tinymce/plugins/wpfullscreen/css/wp-fullscreen.css", plugins: "inlinepopups,spellchecker,tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen", content_css: "http://wpdev.com/wp-content/themes/twentyeleven/editor-style.css", elements: "content", wpautop: true, apply_source_formatting: false, theme_advanced_buttons1: "bold,italic,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,wp_more,|,spellchecker,wp_fullscreen,wp_adv", theme_advanced_buttons2: "formatselect,underline,justifyfull,forecolor,|,pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,undo,redo,wp_help", theme_advanced_buttons3: "", theme_advanced_buttons4: "", body_class: "content post-type-post"}},
                    qtInit: {'content': {id: "content", buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,spell,close,fullscreen"}, 'replycontent': {id: "replycontent", buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close"}},
                    ref: {plugins: "inlinepopups,spellchecker,tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen", theme: "advanced", language: "en"},
                    load_ext: function(url, lang) {
                        var sl = tinymce.ScriptLoader;
                        sl.markDone(url + '/langs/' + lang + '.js');
                        sl.markDone(url + '/langs/' + lang + '_dlg.js');
                    }
                };
            </script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/tinymce/tiny_mce.js?ver=349-20805'></script>
            <script type='text/javascript' src='http://wpdev.com/wp-includes/js/tinymce/langs/wp-langs-en.js?ver=349-20805'></script>

            <script type="text/javascript">
                (function() {
                    var init, ed, qt, first_init, mce = true;

                    if (typeof(tinymce) == 'object') {
                        // mark wp_theme/ui.css as loaded
                        tinymce.DOM.files[tinymce.baseURI.getURI() + '/themes/advanced/skins/wp_theme/ui.css'] = true;

                        for (ed in tinyMCEPreInit.mceInit) {
                            if (first_init) {
                                init = tinyMCEPreInit.mceInit[ed] = tinymce.extend({}, first_init, tinyMCEPreInit.mceInit[ed]);
                            } else {
                                init = first_init = tinyMCEPreInit.mceInit[ed];
                            }

                            if (mce)
                                try {
                                    tinymce.init(init);
                                } catch (e) {
                                }
                        }
                    }

                    if (typeof(QTags) == 'function') {
                        for (qt in tinyMCEPreInit.qtInit) {
                            try {
                                quicktags(tinyMCEPreInit.qtInit[qt]);
                            } catch (e) {
                            }
                        }
                    }
                })();

                var wpActiveEditor;

                jQuery('.wp-editor-wrap').mousedown(function(e) {
                    wpActiveEditor = this.id.slice(3, -5);
                });

                (function() {
                    var t = tinyMCEPreInit, sl = tinymce.ScriptLoader, ln = t.ref.language, th = t.ref.theme, pl = t.ref.plugins;
                    sl.markDone(t.base + '/langs/' + ln + '.js');
                    sl.markDone(t.base + '/themes/' + th + '/langs/' + ln + '.js');
                    sl.markDone(t.base + '/themes/' + th + '/langs/' + ln + '_dlg.js');
                    sl.markDone(t.base + '/themes/advanced/skins/wp_theme/ui.css');
                    tinymce.each(pl.split(','), function(n) {
                        if (n && n.charAt(0) != '-') {
                            sl.markDone(t.base + '/plugins/' + n + '/langs/' + ln + '.js');
                            sl.markDone(t.base + '/plugins/' + n + '/langs/' + ln + '_dlg.js');
                        }
                    });
                })();
            </script>
            <div style="display:none;">
                <form id="wp-link" tabindex="-1">
                    <input type="hidden" id="_ajax_linking_nonce" name="_ajax_linking_nonce" value="b3b4933ded" />	<div id="link-selector">
                        <div id="link-options">
                            <p class="howto">Enter the destination URL</p>
                            <div>
                                <label><span>URL</span><input id="url-field" type="text" tabindex="10" name="href" /></label>
                            </div>
                            <div>
                                <label><span>Title</span><input id="link-title-field" type="text" tabindex="20" name="linktitle" /></label>
                            </div>
                            <div class="link-target">
                                <label><input type="checkbox" id="link-target-checkbox" tabindex="30" /> Open link in a new window/tab</label>
                            </div>
                        </div>
                        <p class="howto toggle-arrow " id="internal-toggle">Or link to existing content</p>
                        <div id="search-panel" style="display:none">
                            <div class="link-search-wrapper">
                                <label>
                                    <span>Search</span>
                                    <input type="search" id="search-field" class="link-search-field" tabindex="60" autocomplete="off" />
                                    <img class="waiting" src="http://wpdev.com/wp-admin/images/wpspin_light.gif" alt="" />
                                </label>
                            </div>
                            <div id="search-results" class="query-results">
                                <ul></ul>
                                <div class="river-waiting">
                                    <img class="waiting" src="http://wpdev.com/wp-admin/images/wpspin_light.gif" alt="" />
                                </div>
                            </div>
                            <div id="most-recent-results" class="query-results">
                                <div class="query-notice"><em>No search term specified. Showing recent items.</em></div>
                                <ul></ul>
                                <div class="river-waiting">
                                    <img class="waiting" src="http://wpdev.com/wp-admin/images/wpspin_light.gif" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submitbox">
                        <div id="wp-link-cancel">
                            <a class="submitdelete deletion" href="#">Cancel</a>
                        </div>
                        <div id="wp-link-update">
                            <input type="submit" tabindex="100" value="Add Link" class="button-primary" id="wp-link-submit" name="wp-link-submit">
                        </div>
                    </div>
                </form>
            </div>
            <div id="wp-fullscreen-body">
                <div id="fullscreen-topbar">
                    <div id="wp-fullscreen-toolbar">
                        <div id="wp-fullscreen-close"><a href="#" onclick="fullscreen.off();
                    return false;">Exit fullscreen</a></div>
                        <div id="wp-fullscreen-central-toolbar" style="width:606px;">

                            <div id="wp-fullscreen-mode-bar"><div id="wp-fullscreen-modes">
                                    <a href="#" onclick="fullscreen.switchmode('tinymce');
                    return false;">Visual</a>
                                    <a href="#" onclick="fullscreen.switchmode('html');
                    return false;">HTML</a>
                                </div></div>

                            <div id="wp-fullscreen-button-bar"><div id="wp-fullscreen-buttons" class="wp_themeSkin">

                                    <div>
                                        <a title="Bold (Ctrl + B)" onclick="fullscreen.b();
                    return false;" class="mceButton mceButtonEnabled mce_bold" href="#" id="wp_fs_bold" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_bold"></span>
                                        </a>
                                    </div>

                                    <div>
                                        <a title="Italic (Ctrl + I)" onclick="fullscreen.i();
                    return false;" class="mceButton mceButtonEnabled mce_italic" href="#" id="wp_fs_italic" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_italic"></span>
                                        </a>
                                    </div>
                                    <div><span aria-orientation="vertical" role="separator" class="mceSeparator"></span></div>

                                    <div>
                                        <a title="Unordered list (Alt + Shift + U)" onclick="fullscreen.ul();
                    return false;" class="mceButton mceButtonEnabled mce_bullist" href="#" id="wp_fs_bullist" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_bullist"></span>
                                        </a>
                                    </div>

                                    <div>
                                        <a title="Ordered list (Alt + Shift + O)" onclick="fullscreen.ol();
                    return false;" class="mceButton mceButtonEnabled mce_numlist" href="#" id="wp_fs_numlist" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_numlist"></span>
                                        </a>
                                    </div>
                                    <div><span aria-orientation="vertical" role="separator" class="mceSeparator"></span></div>

                                    <div>
                                        <a title="Blockquote (Alt + Shift + Q)" onclick="fullscreen.blockquote();
                    return false;" class="mceButton mceButtonEnabled mce_blockquote" href="#" id="wp_fs_blockquote" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_blockquote"></span>
                                        </a>
                                    </div>

                                    <div class="wp-fullscreen-both">
                                        <a title="Insert/edit image (Alt + Shift + M)" onclick="fullscreen.medialib();
                    return false;" class="mceButton mceButtonEnabled mce_image" href="#" id="wp_fs_image" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_image"></span>
                                        </a>
                                    </div>
                                    <div><span aria-orientation="vertical" role="separator" class="mceSeparator"></span></div>

                                    <div class="wp-fullscreen-both">
                                        <a title="Insert/edit link (Alt + Shift + A)" onclick="fullscreen.link();
                    return false;" class="mceButton mceButtonEnabled mce_link" href="#" id="wp_fs_link" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_link"></span>
                                        </a>
                                    </div>

                                    <div>
                                        <a title="Unlink (Alt + Shift + S)" onclick="fullscreen.unlink();
                    return false;" class="mceButton mceButtonEnabled mce_unlink" href="#" id="wp_fs_unlink" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_unlink"></span>
                                        </a>
                                    </div>
                                    <div><span aria-orientation="vertical" role="separator" class="mceSeparator"></span></div>

                                    <div>
                                        <a title="Help (Alt + Shift + H)" onclick="fullscreen.help();
                    return false;" class="mceButton mceButtonEnabled mce_help" href="#" id="wp_fs_help" role="button" aria-pressed="false">
                                            <span class="mceIcon mce_help"></span>
                                        </a>
                                    </div>

                                </div></div>

                            <div id="wp-fullscreen-save">
                                <span>Updated.</span>
                                <img src="http://wpdev.com/wp-admin/images/wpspin_light.gif" alt="" />
                                <input type="button" class="button-primary" value="Update" onclick="fullscreen.save();" />
                            </div>

                        </div>
                    </div>
                </div>

                <div id="wp-fullscreen-wrap" style="width:606px;">
                    <label id="wp-fullscreen-title-prompt-text" for="wp-fullscreen-title">Enter title here</label>
                    <input type="text" id="wp-fullscreen-title" value="" autocomplete="off" />

                    <div id="wp-fullscreen-container">
                        <textarea id="wp_mce_fullscreen"></textarea>
                    </div>

                    <div id="wp-fullscreen-status">
                        <div id="wp-fullscreen-count">Word count: <span class="word-count">0</span></div>
                        <div id="wp-fullscreen-tagline">Just write.</div>
                    </div>
                </div>
            </div>

            <div class="fullscreen-overlay" id="fullscreen-overlay"></div>
            <div class="fullscreen-overlay fullscreen-fader fade-600" id="fullscreen-fader"></div>

            <div class="clear"></div></div><!-- wpwrap -->
        <script type="text/javascript">if (typeof wpOnload == 'function')
                    wpOnload();</script>
    </body>
</html>
