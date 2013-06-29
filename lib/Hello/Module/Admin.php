<?php
/**
 * Admin Module
 *
 * This module creates the admin panel
 *
 * @author Mike Ems
 * @package Hello
 *
 */

class Hello_Module_Admin extends Simpli_Plugin_Module {

	/**
	 * Initialize Module
	 *
	 * @param none
	 * @return void
	 */
	public function init() {
		// Load on plugins page
		if ( isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'plugins.php' ) {
			add_filter( 'plugin_row_meta', array(&$this, 'plugin_links'), 10, 2);
		}

		// Add global admin scripts
		add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
	}

	/**
	 * Adds javascript and stylesheets to admin panel
	 * WordPress Hook - admin_enqueue_scripts
	 *
	 * @param none
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-global', $this->getPlugin()->getPluginUrl() . '/admin/css/admin.css', array(), $this->getPlugin()->getVersion());
	}

	/**
	 * Renders a meta box
	 *
	 * @param string $module
	 * @param array $metabox
	 * @return void
	 */
	public function meta_box_render( $module, $metabox = array() ) {
           // if ($metabox['id']=='simpli-helloworld_example_ajax') {
//                echo '<pre>';
//               print_r($metabox);
//               echo '</pre>';
         //   die();
           // }
//
//             die('rendering');
//            print_r($metabox);
             // print_r($metabox);
		if ( isset($metabox['args']['metabox']) ) {
			include($this->getPlugin()->getDirectory() . '/admin/templates/metabox/' . $metabox['args']['metabox'] . '.php');
		}
	}

	/**
	 * Plugin links on Manage Plugins page in admin panel
	 * WordPress Hook - plugin_row_meta
	 *
	 * @param array $links
	 * @param string $file
	 * @return array $links
	 */
	public function plugin_links($links, $file) {
		if ( strpos($file, $this->getPlugin()->getSlug()) === false ) {
			return $links;
		}

		$links[] = '<a href="' . site_url() . '/wp-admin/admin.php?page='.$this->getPlugin()->getSlug().'" title=SIMPLI_HELLO_NAME . " Settings">Settings</a>';
		$links[] = '<a href="http://wordpress.org/extend/plugins/'.$this->getPlugin()->getSlug().'/faq/" title="Frequently Asked Questions">FAQ</a>';
		$links[] = '<a href="http://wordpress.org/tags/'.$this->getPlugin()->getSlug().'#postform" title="Support">Support</a>';
		$links[] = '<a href="your paypal url here" title="Support this plugin\'s development with a donation!">Donate</a>';
		return $links;
	}

}