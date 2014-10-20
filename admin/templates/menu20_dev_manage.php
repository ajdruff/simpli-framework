<?php
require_once('includes/template.php'); // WordPress Dashboard Functions
//echo '<pre>';
//echo '</pre>';

/*

  To add a metabox, you must add the do_metaboxes line as in the examples below, and then also have
  add_metabox statements in the matching Module/SettingsXXXX.php code.
 *
 * The html for each of the metaboxes are provided by the

 *  */
?>
<div class="simpli-frames">
    <div class="simpli-message-wrap" id="message-wrap"><div id="message-body">



        </div>


    </div>

    <div class="wrap" id="simpli-main">
        <div   class="icon32 menu-icon"><br /></div>
        <h2><?php _e($this->plugin()->getName(), $this->plugin()->getTextDomain()); ?></h2>




        <div id="poststuff" class="columns metabox-holder">

            <form action="" method="">

                <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); ?>
                <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false); ?>
            </form>


            <div class="postbox-container column-primary">
                <?php do_meta_boxes($this->getScreenId(), 'normal', $this); ?>
            </div>







            <div class="postbox-container column-secondary">
                <?php // these are all the boxes on the right side   ?>

                <?php do_meta_boxes($this->getScreenId(), 'side', $this); ?>

            </div>



        </div>







    </div>

</div>

