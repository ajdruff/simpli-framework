<?php
require_once('includes/template.php'); // WordPress Dashboard Functions


/*

  To add a metabox, you must add the do_metaboxes line as in the examples below, and then also have
  add_metabox statements in the matching Module/SettingsXXXX.php code.
 *
 * The html for each of the metaboxes are provided by the
 *
 *
 * Note:
 * @todo this is a stub for custom editor. There is much more work to do to make this feasable. You need to code in publish/preview/title,etc.
 * You also need to build a 'new' feature that correctly configures the editor for a new .
 * The advantage of all this is that you will have complete control over the editor, which is not provided by WordPress today.

 *  */




$post = $this->plugin()->tools()->getPost();
$this->debug()->logVar('$post = ', $post);
/*
 * If the user accesses this page using a link to this menu page (instead of via a redirect after they clicked the edit link, there wont be any post object, and errors will result.
 * Instead, simply tell the user to hit the back button to continue editing (although previous changes will be likely lost).
 */
if (!is_object($post)) {

    echo '<div class="updated below-h2"> <h2>Click the back button on your browser to continue editing your post.</h2>
         You are seeing this message because you inadvertently clicked the editor menu link while already editing a post. To avoid seeing this message, always access the editor by first clicking the menu post\'s edit link.</div>';
    return;
}
?>
<div class="simpli-hello">
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

                <!-- Start Editor -->
                <?php
                $f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');



                $f->getTheme()->setTheme('Admin');



//    $f->el(array(
//        'el' => 'postEditor',
//        'name' => 'MyCustomPostEditor',
//        'label' => '',
//        'hint' => '',
//            )
//    );
                ?>


                <!-- alternately, use the shortcode: simpli_hello_form el='postEditor'
                DO NOT PLACE MORE THAN ONE postEditor on the page at one time or they will not work.
                Remember that even if you comment out the shortcode, it will still be parsed!
                shortcode format (place in brackets and move outside of comments to see it parsed ):
                simpli_hello_form el='postEditor'

                ->


                <!-- End Editor -->






<?php
do_meta_boxes($this->getScreenId(), 'advanced', $this);
?>
                <?php
                do_meta_boxes($this->getScreenId(), 'normal', $this);
                ?>

                [simpli_hello_form el='postEditor']
            </div>







            <div class="postbox-container column-secondary">


<?php
do_meta_boxes($this->getScreenId(), 'side', $this);
?>

            </div>



        </div>







    </div>

</div>

