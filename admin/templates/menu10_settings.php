<?php
require_once('includes/template.php'); // WordPress Dashboard Functions
//echo '<pre>';
//print_r($this);
//echo '</pre>';

/*

To add a metabox, you must add the do_metaboxes line as in the examples below, and then also have
 add_metabox statements in the matching Module/SettingsXXXX.php code.
 *
 * The html for each of the metaboxes are provided by the

 *  */



?>
<div class="simpli-hello">
<div class="simpli-message-wrap" id="message-wrap"><div id="message-body">



    </div>


</div>

<div class="wrap" id="simpli-main">
	<div id="icon-options-https" class="icon32"><br /></div>
	<h2><?php _e($this->getPlugin()->getName(),'simpli-hello'); ?></h2>

<?php
	wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
	wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );
?>
	<div id="poststuff" class="columns metabox-holder">
		<div class="postbox-container column-primary">
<?php do_meta_boxes('toplevel_page_' . $this->getPlugin()->getSlug(). '_' . $this->moduleSlug . '_group1', 'main', $this); ?>
		</div>
		<div class="postbox-container column-secondary">
<?php do_meta_boxes('toplevel_page_' . $this->getPlugin()->getSlug(). '_' . $this->moduleSlug . '_group1', 'side', $this); ?>
		</div>

            </div>


	<div id="poststuff" class="columns metabox-holder">




		<div class="postbox-container column-secondary">
                    <?php // these are all the boxes on the right side  ?>
<?php do_meta_boxes('toplevel_page_' . $this->getPlugin()->getSlug(). '_' . $this->moduleSlug . '_group2', 'side', $this); ?>
		</div>
	</div>








</div>

</simpli-hello>