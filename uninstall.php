<?php

require_once('plugin.php');

if ( !defined('WP_UNINSTALL_PLUGIN') ) {
	die();
}


global $wpdb;
if ( is_multisite() && is_network_admin() ) {
	$blogs = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM " . $wpdb->blogs, NULL));
} else {
	$blogs = array($wpdb->blogid);
}

// Delete Plugin options
foreach ( $blogs as $blog_id ) {

		if ( is_multisite() ) {
			delete_blog_option($blog_id, 'simpli_helloworld_options');
		} else {
			delete_option('simpli_helloworld_options');
		}

}

// Delete plugin's custom_field from posts and pages
delete_metadata('post', null, SIMPLI_HELLO_SLUG, null, true);