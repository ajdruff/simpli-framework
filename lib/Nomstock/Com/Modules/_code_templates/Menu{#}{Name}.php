<?php

/**
 * Admin Menu
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 *
 */
class Simpli_Frames_Modules_{MODULE_NAME} extends Simpli_Frames_Base_v1c2_Plugin_Menu {

/**
 * Add Hooks
 *
 * Adds WordPress Hooks, triggered during module initialization
 * @param none
 * @return void
 */
public function addHooks() {
$this->debug()->t();

parent::addHooks();



/*
 *  Add Custom Ajax Handlers
 *
 * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->plugin()->getSlug() . '_xxxx'
  see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29

  Example ( this is included in base class so no need to add it here
  //add_action('wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array($this, 'save'));
 *
 *
 *
 */


/*
 * Add any other hooks you need - see base class for examples
 *
 */



}

/**
 * Config
 *
 * Long Description
 * * @param none
 * @return void
 */
public function config() {
$this->debug()->t();

/*
 * call parent configuration first
 * this is required or menus wont load
 */
parent::config();



/*
 * Add the Menu Page
 */

$this->addMenuPage
(
$page_title = $this->plugin()->getName() . '{PAGE_TITLE}'
, $menu_title = array('menu' => $this->plugin()->getName(), 'sub_menu' => '{MENU_TITLE}')
, $capability = 'manage_options'
, $icon_url = $this->plugin()->getUrl() . '/admin/images/menu.png'
, $position = null
);



$this->metabox()->addMetaBox(
'metabox_{METABOX_ID_SUFFIX}'  //Meta Box DOM ID
, __('Plugin Settings', $this->plugin()->getTextDomain()) //title of the metabox.
, array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
, $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
, '{METABOX_SECTION}' //normal advanced or side The part of the page where the metabox should show
, 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
, null //$metabox['args'] in callback function
);


/*
 * {META_BOX}
 * Add additional Metaboxes here
 *
 */

}





}
?>