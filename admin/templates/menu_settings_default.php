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
<div class="nomstock-com">
    <div class="simpli-message-wrap" id="message-wrap"><div id="message-body">



        </div>


    </div>

    <div class="wrap" id="simpli-main">
        <div   class="icon32 menu-icon"><br /></div>
        <h2><?php _e($this->plugin()->getName(), $this->plugin()->getTextDomain()); ?></h2>

        <?php
        /*
         *
         * Add Metabox nonces
         *
         * The
         * wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
         * and
         * wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
         *
         * function calls must be placed in their own form outside of the metaboxes.
         *
         * They are required so that WordPress remembers metabox closed and order settings
         *
         */
        ?>

        <form action="" method="">

            <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); ?>
            <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false); ?>
        </form>


        <div id="poststuff" class="columns metabox-holder">




            <div class="postbox-container column-primary">
                <?php do_meta_boxes($this->getScreenId(), 'normal', $this); ?>
            </div>







            <div class="postbox-container column-secondary">
                <?php // these are all the boxes on the right side    ?>

                <?php do_meta_boxes($this->getScreenId(), 'side', $this); ?>

            </div>



        </div>







    </div>

</div>

