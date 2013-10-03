<?php

/**
 * Post Helper
 *
 * Provides access to the current post,and to several small utilities to manage posts
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 */
class Simpli_Frames_Basev1c2_Plugin_Post extends Simpli_Frames_Basev1c2_Plugin_Helper {

    /**
     * Config
     *
     * Add any method calls required when the object is first created
     *
     * @param none
     * @return void
     */
    public function config() {
        //   $this->debug()->t();
    }

    /**
     * Add Hooks
     *
     * Adds any actions needed for the class
     *
     * @param none
     * @return void
     */
    public function addHooks() {
        //  $this->debug()->t();

        /*
         * Use the wp_insert_post to detect when we are in the editor.
         */

        add_action('wp_insert_post', array($this, 'hookNewPost'), 10, 2);
    }

    protected $_new_post_id = null;
    protected $_new_post = null;

    /**
     * Hook New Post
     *
     * Hook to new post. Will detect when user is on a 'add new' post editing page.
     * We use it here to set the new post Id
     *
     * @param none
     * @return void
     */
    public function hookNewPost($post_ID, $post) {
        $this->debug()->t();
        if ($this->plugin()->tools()->isScreen(array('add'), null, false)) {

            $this->_new_post_id = $post_ID;
            $this->_new_post = $post;
        }
    }

    /**
     * Get Post
     *
     * Gets the Post object from a wordpress request. if no object available,
     * returns null. although you can try to use global $post, there are times
     * when the post is only available by create the object from the post id, passed
     * as a $_GET variable.
     *
     *
     * @param none
     * @return void
     */
    public function getPost() {
        $this->debug()->t();
        global $global_post;



        if (is_object($global_post)) {
            $this->debug()->log('Post already in global $post object');
            return $global_post;
        }

        /*
         * Get Post from 'New post' hook
         * If a new post was just created (user is within the new post editor)
         * return the new post object.
         */
        if (is_object($this->_new_post)) {
            return($this->_new_post);
        }
        /*
         * attempt to get post id from the GET variables or, if its an editor, get it via the getEditPostID method
         */
        //    $post_id_query_var = (isset($_GET['post'])) ? $_GET['post'] : null;
        $post_id = $this->_getEditorPostID();

        if (!is_null($post_id)) {
            $this->debug()->log('Found post_id using getEditPostID()');
            return get_post($post_id);
        } else {

            $this->debug()->log('Checked query and post vars, still not found, returning null');
            return null;
        }
    }

    /**
     * Get Editor Post ID
     *
     * Returns the post id being edited or created. Checks all the most common places that the $post->id is provided
     * during a post editor form submission
     *
     * @param none
     * @return string The id of the post being edited or created.
     */
    private function _getEditorPostID() {
        $this->debug()->t();



        #init
        $post_id = null;


        /*
         * Check $_POST['post_ID']
         * When editing a WordPress Post, the editor submits post_id using
         * the $_POST form field post_ID
         *
         */

        $post_id = (isset($_POST['post_ID'])) ? $_POST['post_ID'] : null;

        if (!is_null($post_id)) {
            $this->debug()->logVar('Found $_POST[\'post_ID\'], $post_id=', $post_id);
            return $post_id;
        }

        /*
         * if a new post was just created,
         * return the post id captured from hookNewPost
         */
        if (!is_null($this->_new_post_id)) {
            return($this->_new_post_id);
        }

        /*
         * Check $_GET['post]
         * The WordPress Post Editing page embeds the post id in the $_GET request during an edit,
         * and the Custom Post Editor also embeds the post id in the $_GET request during the 'Add New' redirect to the Editor a
         *
         */
        $post_id = $this->plugin()->tools()->getRequestVar('post');

        if (!is_null($post_id)) {
            $this->debug()->logVar('Found post_id in $_GET, $post_id=', $post_id);
            return $post_id;
        }
        /*
         * Check $_POST['_wp_http_referer']
         * Ajax requests have access to the _wp_referer $_POST variable
         * Which represents the $_GET variables contained in the Editor page
         * from which the ajax request was made from
         *
         * Use the getQueryVarFromUrl method to retrieve the query variable from the _wp_http_referer string
         */

        $post_id = (isset($_POST['_wp_http_referer'])) ? $this->plugin()->tools()->getQueryVarFromUrl('post', $_POST['_wp_http_referer']) : null;
        if (!is_null($post_id)) {
            $this->debug()->logVar('Found post_id in _wp_http_referer, $post_id=', $post_id);
            return $post_id;
        }
        /*
         * if still null, then its probably a custom edit page, which embeds it in the _ajax_referer_url
         *
         * _ajax_referer_url
         */

        $post_id = (isset($_POST['_ajax_referer_url'])) ? $this->plugin()->tools()->getQueryVarFromUrl('post', $_POST['_ajax_referer_url']) : null;
        if (!is_null($post_id)) {
            $this->debug()->logVar('Found post_id in _ajax_referal_url, $post_id=', $post_id);

            return $post_id;
        }



        $this->debug()->log('Couldn\'t find post id anywhere, setting it to null');
        return $post_id;
    }

    /**
     * Get Post Type Request Variable
     *
     * When called with no argument, it will return the post_type query variable that is in $_GET, but removes a preceeding obfuscation string if one exists. This obfuscation is sometimes added to prevent wordpress from erroring out in the event we are using a custom edit page. WordPress will not load a custom edit page  if it sees that the post_type is a query variable and its value is a registered post type.
     *
     * Usage:
     * assume we are on a page with url : ?post_type=___my_post_type&simpli_frames=edit_post
     * $post_type=getPostTypeRequestVar() // returns 'my_post_type'
     *
     * Now assume we need to build a url that will redirect to a custom edit page, and we need to pass the post type
     * $url='http://example.com?post_type='.getPostTypeRequestVar('my_post_type').'&simpli_frames=edit_post // will result in $url = http://example.com?post_type=___my_post_type&simpli_frames=edit_post
     *
     *
     *
     *
     * When called with an argument, it will return the input string but 'obfuscated' , so it can then be used in the query string.
     *
     * @param string $post_type The post type value that needs to be obfuscated e.g.: 'my_custom_post_type'
     * @return string The obfuscated string if a $post_type parameter was passed, otherwise, the value of the $_GET['post_type'] without the obfuscation if it has it.
     */
    public function getPostTypeRequestVar($post_type = null) {

        /*
         * Return an obfuscated post type if called with an argument
         */
        $obfuscation = '___';
        if (!is_null($post_type)) {
            return $obfuscation . $post_type;
        }
        /*
         * with no argument, return the value of 'post_type' if
         * detected as a $_GET or $_POST variable.
         */
        $post_type = $this->plugin()->tools()->getRequestVar('post_type');

        if (substr($post_type, 0, strlen($obfuscation)) === $obfuscation) {
            $this->debug()->logVar('$post_type found as obfuscated request variable, $post_type = ', $post_type);

            $post_type = str_replace($obfuscation, '', $post_type);
        }
        return $post_type;
    }

    /**
     * Get Post Type
     *
     * Tries several different methods to return the post type of
     * current post object.
     *
     * @param none
     * @return void
     */
    public function getPostType() {

        /*
         * attempt to get the post type
         * from the post object
         */
        if (!is_null($post = $this->getPost())) {
            $this->debug()->logVar('$post_type found from post object, $post_type = ', $post->post_type);
            return $post->post_type;
        }

        /*
         * attempt to get the post type from the
         * $_GET or $_POST variables
         */

        $post_type = $this->getPostTypeRequestVar();
        if (!is_null($post_type)) {
            $this->debug()->logVar('$post_type found from $_GET or $_POST variables, $post_type = ', $post_type);
            return $post_type;
        }


        /*
         * Get Current Screen Method
         */

        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
            $post_type = $screen->post_type;
            if (!is_null($post_type)) {
                $this->debug()->logVar('Post type found from screen object, $post_type = ', $post_type);
                return $post_type;
            }
        }

        $this->debug()->log('Post type couldn\'t be found');
        return null;
    }

}

?>