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
class Simpli_Frames_Modules_Menu20AddDomains extends Simpli_Frames_Base_v1c2_Plugin_Menu {

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
         * 
         * Add the form handler
         * 
         */
        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_add_domain', array( $this, 'hookFormActionAjaxAddDomain' ) );

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
                $page_title = $this->plugin()->getName() . 'Add Domain Names'
                , $menu_title = array( 'menu' => $this->plugin()->getName(), 'sub_menu' => 'Add Domains' )
                , $capability = 'manage_options'
                , $icon_url = $this->plugin()->getUrl() . '/admin/images/menu.png'
                , $position = null
        );



        $this->metabox()->addMetaBox(
                'metabox_add_domains'  //Meta Box DOM ID
                , __( 'Add Domains', $this->plugin()->getTextDomain() ) //title of the metabox.
                , array( $this->metabox(), 'renderMetaBoxTemplate' ) //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );



    }

    /**
     * Hook - Form Action - Add Domain
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function hookFormActionAjaxAddDomain() {
         $message='';
        global $wpdb;
        $this->debug()->logVar( '$_REQUEST = ', $_REQUEST );

        $this->debug()->logVar( '$_POST = ', $_POST );
        
        
        
        /*
         * Check Nonces
         */
        if ( !$this->metabox()->wpVerifyNonce( __FUNCTION__ ) ) {

            return false;
        }

        //owner : Seller: logged in user/Sedo/Afternic/blah
        //
        //added by : logged in user (auto)

        $table = 'nstock_domains';

        $data = array(
            'subdomain' => $_POST[ 'subdomain' ]
            , 'tld' => $_POST[ 'tld' ]
            , 'bin' => ($_POST[ 'purchase_options' ][ 'bin' ] === 'yes') ? 'y' : 'n'
            , 'bid' => ($_POST[ 'purchase_options' ][ 'bid' ] === 'yes') ? 'y' : 'n'
            , 'price' => $_POST[ 'price' ]
            , 'currency' => $_POST[ 'currency' ]
            , 'featured' => ($_POST[ 'featured' ] === 'yes') ? 'y' : 'n'
            , 'seller' => $this->getSellerIdFromSource($this->plugin()->tools()->getRequestVar( 'source' ) )
            , 'approved' => 'y'
            , 'reg_available' => ($_POST[ 'source' ] === 'reg_search') ? 'y' : 'n'
            , 'source' => $_POST[ 'source' ]
            , 'added_by' => get_current_user_id()
            , 'list_status' => 'pending' //pending list going active. List Goes active when cron job kicks in and activates all approved.
            , 'time_added' =>current_time('mysql',1)
            , 'time_lastupdated' => null
            , 'time_approved' =>current_time('mysql',1)  // approved on submission since this is from the admin panel
            
        );

        
        /*
         * Note that passing functions via the wpdb class does not appear to work ( see discussion here: http://stackoverflow.com/questions/8566603/wordpress-wpdb-insert-mysql-now ) 
         * instead, you can use the mysql_query statement (see notes for example) or calculate the time prior to entering it in mysql . 
         * either : date('Y-m-d H:i:s')
         * or 
         * current_time('mysql',1)
         */
        

        $this->debug()->logVar( '$data = ', $data );
        //     $this->debug()->stop( true );

        $format = array(
            '%s'
            , '%s'
        );

        $wpdb->insert( $table, $data, $format );

$last_record_id=$wpdb->insert_id;
        



           
      //  die('<br><br>exiting' . __FILE__ . __LINE__ ) ; 
        
        if ( $wpdb->insert_id !== false ) {
            $message .= 'Domain Name Added Successfully';
} else{
            $message .= 'Domain Name Add Failed - please try again';
}
        $this->metabox()->showResponseMessage(
                $this->plugin()->getDirectory() . '/admin/templates/ajax_message_admin_panel.php', //string $template The path to the template to be used
                $message, // string $message The html or text message to be displayed to the user
                array(), //$errors Any error messages to display
                false, //boolean $logout Whether to force a logout after the message is displayed
                false //boolean $reload Whether to force a page reload after the message is displayed
        );

        // echo 'you submitted your form successfully';
        // exit(); //required
        }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function getSellerIdFromSource( $source_id ) {
        global $wpdb;
        $this->debug()->logVar( '$source_id = ', $source_id );
        
        /*
         * attempt to get seller from the source id
         * if the source id is member_inventory, then the seller is the member so use
         * the members username
         */
        $seller_id = ($source_id === 'member_inventory') ? get_current_user_id() : null;

        if ( !is_null( $seller_id ) ) {
            return $seller_id;
}
        $query = "select id from nstock_sources where id='$source_id'";
        $this->debug()->logVar( '$query = ', $query );
        $dbresult = $wpdb->get_row( $query, ARRAY_A );
        $this->debug()->logDatabaseError( );
        $this->debug()->logVar( '$dbresult = ', $dbresult );
        return $dbresult[ id ];
        }
        
        
     

}



?>