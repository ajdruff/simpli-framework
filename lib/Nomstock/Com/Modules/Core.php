<?php

/**
 * Core Module
 *
 * Plugin's core functionality
 * See http://simpliwp.com/plugin-builder/ for examples
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 *
 */
class Nomstock_Com_Modules_Core extends Nomstock_Com_Base_v1c2_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $metabox = new Nomstock_Com_Base_v1c2_Plugin_Module_Metabox( $this );
        $metabox->config();
        $this->debug()->t(); //trace provides a information about the method and arguments, and provides a backtrace in an expandable box. A visual trace is also provided if graphiviz is enabled.



    }

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();

        /*
         * add ajax handlers
         */
//add_action('wp_ajax_' . $this->plugin()->getSlug() . '_contact_seller', array($this, 'hookFormActionAjaxContactSeller'));
//  add_action('wp_ajax_nopriv_' . $this->plugin()->getSlug() . '_contact_seller', array($this, 'hookFormActionAjaxContactSeller'));     
//  


        /*
         * add scripts
         *  */

        add_action( 'wp_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );


        /*
         *  add custom ajax handlers
         * this is where you map any form actions with the class method that handles the ajax request
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->plugin()->getSlug() . '_xxxx'
          see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         *
         * Example:
         * add_action('wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array($this, 'save'));
         *
         */
    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - hookEnqueueScripts
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {
        $this->debug()->t();
        /* Example
          wp_enqueue_style($this->plugin()->getSlug() . '-admin-page', $this->plugin()->getAdminUrl() . '/css/settings.css', array(), $this->plugin()->getVersion());
          wp_enqueue_script('jquery');
          wp_enqueue_script('jquery-form');
          wp_enqueue_script('post');
         *
         */

        /* Example
          $handle = $this->plugin()->getSlug() . '_core.js';
          $src = $this->plugin()->getUrl() . '/js/' . $this->plugin()->getSlug() . '_core.js';
          $deps = 'jquery';
          $ver = '1.0';
          $in_footer = false;
          wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
         *
         */



        /*
         * Jscroll Jquery Plugin
         */
        /*
         * If not the home page, then don't load these scripts and return
         */
        if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) //only if on home page
            wp_enqueue_script(
                    'jscroll' //$handle, 
                    , $this->plugin()->getUrl() . '/js/jscroll-infinite-scolling/jscroll/jquery.jscroll.js' //$src, 
                    , 'jquery'//$deps, 
                    , '1.0'//$ver, 
                    , false //$in_footer
            );


        /*
         * Jscroll Config
         * 
         */
        if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) //only if on home page
            $this->plugin()->enqueueInlineScript(
                    $this->plugin()->getSlug() . '_jscroll-config.js', //$handle, 
                    $this->plugin()->getDirectory() . '/js/jscroll-config.js', //absolute file path, 
                    array(), //$inline_deps, 
                    array( 'jquery' ) //$external_deps, 
            );

        /*
         * Widgets
         * 
         */
        if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) //only if on home page
            $this->plugin()->enqueueInlineScript(
                    $this->plugin()->getSlug() . '_widgets.js', //$handle, 
                    $this->plugin()->getDirectory() . '/js/widgets.js', //absolute file path, 
                    array(), //$inline_deps, 
                    array( 'jquery' ) //$external_deps, 
            );


        /*
         * Markitup - the jquery textbox used for creating the widget description
         * http://markitup.jaysalvat.com/documentation/ 
         * 
         */
        if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) //only if on home page
            wp_enqueue_script(
                    'markitup' //$handle, 
                    , $this->plugin()->getUrl() . '/js/markitup/markitup/jquery.markitup.js' //$src, 
                    , 'jquery'//$deps, 
                    , '1.1'//$ver, 
                    , false //$in_footer
            );
        /*
         * Markitup Markdown set javascript
         */
        if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) //only if on home page
            wp_enqueue_script(
                    'markitup-markdown' //$handle, 
                    , $this->plugin()->getUrl() . '/js/markitup/markitup/sets/markdown/set.js' //$src, 
                    , 'jquery'//$deps, 
                    , '1.1'//$ver, 
                    , false //$in_footer
            );
        if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) //only if on home page
            wp_enqueue_style(
                    'markitup-skin', //handle
                    $this->plugin()->getUrl() . '/js/markitup/markitup/skins/markitup/style.css', //path 
                    array(), //dependents
                    '1.1' //version
            );
        /*
         * Markitup Style Set for Markdown
         */
        if ( $_SERVER[ 'REQUEST_URI' ] === '/' ) //only if on home page
            wp_enqueue_style(
                    'markitup-markdown', //handle
                    $this->plugin()->getUrl() . '/js/markitup/markitup/sets/markdown/style.css', //path 
                    array(), //dependents
                    '1.1' //version
            );

    }

    /**
     * List Domains
     *
     * Gets formatted ticker listings
     *
     * @param none
     * @return void
     */
    public function listDomains() {

        echo $this->_getTickerListings();

    }

    /**
     * List Domains
     *
     * Long Description
     * * @param none
     * @return void
     */
    function _getTickerListings() {




        $this->debug()->t();


        $templates = $this->plugin()->getModule( 'Templates' );

        $cell_template = $templates->getTemplate( 'domains_cell' );
        $table_template = $templates->getTemplate( 'domains_table' );
        $row_template = $templates->getTemplate( 'domains_row' );

        $max_columns = 1;
        $max_rows = 500;

        $row = 0;
        $cell = 0;
        $row_html = '';
        $tags = array();
        $columns = 0;
        $total = 0;
        global$wpdb;
        $expiration_hours = $this->plugin()->LISTING_EXPIRATION_HOURS;
        $query = "select  concat(nstock_domains.name,'.',tld) as 'domain_name',bin,bid,price,price_note from nstock_domains  WHERE nstock_domains.time_added >= DATE_SUB(NOW(), INTERVAL " . $expiration_hours . " HOUR) and  approved='y' and featured='n'
";

        $query = "select  DISTINCT concat(nstock_domains.name,'.',tld)  as 'domain_name',nstock_domains.id as 'domain_id',bin,bid,price,price_note from nstock_domains
ORDER BY (nstock_domains.time_added >= DATE_SUB(NOW(), INTERVAL 1 HOUR)),nstock_domains.time_added DESC Limit 0,100";

        // There is a bug in PHP 5.24 that gives a context error when calling a procedure. So this does not work  $query ="do call `nstock_get_listings`(5);"; Instead we create a view and select from the view. THe view has a large limit on it, you can limit it further by passing a limit

        /*
         * Update 2/11/14 - we do not want to allow limiting the number of maximum displayed as an option in the plugin, since
         * the stats MySQL events need to know which domains are appearing on the home page.
         * for this reason I am deprecating DOMAIN_LISTING_MAX.
         * if you have to change domain listing max, do so from within the defined view_domain_listings view.
         */

        /*
         * Get Ticker Page 1
         * 
         * Calls the MySQL Stored Procedure to get the first page of the ticker
         * 
         * To change the nubmer of results returned,
         * you must edit nstock_get_ticker_page stored procedure
         * or view_domain_listings view.
         */

        $next_ticker_id = $this->plugin()->tools()->getQueryVar( 'next_tickerid' );

        /*
         * if no ticker id is found in the url query variable,
         * then set it to 0
         */
        $next_ticker_id = (is_null( $next_ticker_id )) ? 0 : $next_ticker_id;


        $this->debug()->logVar( '$next_ticker_id = ', $next_ticker_id );

        $domains = $this->plugin()->getModule( 'Tools' )->mySQLCallProc(
                "nstock_get_ticker_page($next_ticker_id)"// $stored_procedure 
        );

        $this->debug()->logVar( '$domains = ', $domains );


        /*
         * Get the minimum id of the records returned,
         * so we can set the 'next-page' url
         */

        /*
         * first get all the ids returned
         */
        $ids = $this->plugin()->tools()->getArrayColumn( $domains, 'id' );

        /*
         * make sure we have an  array
         */
        $ids = (is_array( $ids )) ? $ids : array();

        /*
         * get the minimum ticker id
         * from the set of records we retrieved
         * this will set the starting point for the 
         * next request.
         */


        if ( empty( $ids ) ) {
            return; // if no more records, we can return.
} else{
            /*
             * otherwise, se tthe ticker id
             */
            $ticker_id = (min( $ids ));

}

        /*
         * The number of total pages
         *  is calculated and returned by 
         * the nstock_get_ticker_page procedure.
         * 
         * get  it, and set the
         * javascript varible for our jscroll infinite paging 
         * script to read
         */
        $table[ 'ticker-total-pages' ] = $domains[ 0 ][ 'total_pages' ];
        $vars = array();
        $vars[ 'plugin' ][ 'ticker' ][ 'ticker_total_pages' ] = $domains[ 0 ][ 'total_pages' ];
        $this->plugin()->setLocalVars( $vars );

        /*
         * Insert the last record's id into our template, which
         * will use it for the 'next-page' url. 
         * the next-page url will send it to our database, which will 
         * then pull records starting with that id.
         */


        $table_tags[ 'last-ticker-id' ] = $ticker_id;

        /*
         * Build the table by using column templates until
         * the max column is reached, then close the row, and start a new one
         */

        foreach ( $domains as $domain ) {




            /*
             * Get the tag values needed for each column
             */
            $listing_links = $this->_getDomainNameMarketplaceLinks( $domain[ 'domain_name' ] );

            // $this->debug()->logVar( '$listing_links = ', $listing_links,true );
            // $this->debug()->logVar( 'domain_sales_page_url = ', $this->getDomainSalesPageUrl($domain[ 'domain_name' ]),true);
            // $this->debug()->stop( true );


            /*
             * Seller Links
             */
            $seller_info = $this->getSellerData( $domain[ 'seller' ] );
            $this->debug()->logVar( '$seller_info = ', $seller_info );

            /*
             * Tag - Domain Sales Page Url 
             * Target Landing Page for Sales Inquiry
             * Example: http://sedo.com?domain=mydomain.com
             */

            //  $cell_tags[ 'domain_sales_page_url' ] = '/stats/clicks/domain-sales-page/' . $domain[ 'domain_name' ] . '/';

            $cell_tags[ 'domain_sales_page_url' ] = '/domain/' . $domain[ 'domain_name' ] . '/';



            /*
             * **************************
             * Tag - Seller Display Name
             * **************************
             * Examples: Afternic , Nomstock
             * 
             */
            $cell_tags[ 'seller_display_name' ] = $seller_info->display_name;

            /*             * **************************
             * Tag - Seller User Name
             * **************************
             * Examples: nomstock,sedo,afternic
             * 
             */
            $cell_tags[ 'user_name' ] = $seller_info->user_name;


            /*
             * **************************
             * Tag - Seller URL (Home Page)
             * **************************
             * Main 'Home Page' of the seller
             * For a user, this would be his inventory
             * Example: http://afternic.com
             */

            $cell_tags[ 'seller_url' ] = $this->plugin()->tools()->crunchTpl( $cell_tags, $seller_info->seller_url );

            /*
             * Other Tags Needed for the templates
             */

            $cell_tags[ 'sedo_link' ] = $listing_links[ 'sedo' ];
            $cell_tags[ 'afternic_link' ] = $listing_links[ 'afternic' ];
            $cell_tags[ 'contact_owner_link' ] = $listing_links[ 'contact_owner' ];

            $cell_tags[ 'domain_name' ] = $domain[ 'domain_name' ];
            $cell_tags[ 'type' ] = ($domain[ 'bin' ] === 'y') ? 'BIN' : 'BID';
            $cell_tags[ 'price' ] = $domain[ 'price' ];
            $cell_tags[ 'price_note' ] = $domain[ 'price_note' ];
            $cell_tags[ 'domain_id' ] = $domain[ 'id' ];
            $cell_tags[ 'total_clicks' ] = $domain[ 'total_clicks' ];
            $cell_tags[ 'total_impressions' ] = $domain[ 'total_impressions' ];

            if ( (intval( $domain[ 'total_impressions' ] ) === 0) || (is_null( $domain[ 'total_impressions' ] )) ) {

                $cell_tags[ 'popularity' ] = '<span class="label label-default">New</span> ';
} else{

                $cell_tags[ 'popularity' ] = 'Popularity: ' . sprintf( "%.0f%%", $domain[ 'total_clicks' ] / intval( $domain[ 'total_impressions' ] ) * 100 );


}

            $this->debug()->logVar( '$cell_tags = ', $cell_tags );

            /*
             * Render a column
             * 
             * Use a Cell Template if We havent added all the columns for the row
             */
            if ( $columns < $max_columns ) {
                $row_html.=$this->plugin()->tools()->crunchTpl( $cell_tags, $cell_template );

                $columns++;
            } else {

                /*
                 * 
                 * Render the Row
                 * 
                 * If we've added all the columns for the row, close out the row
                 * by adding the row html we just built to total rows
                 * $row_html is added to the $rows_html collective
                 */
                $columns = 0;
                $row_tags[ 'row_html' ] = $row_html;

                $rows_html.=$this->plugin()->tools()->crunchTpl( $row_tags, $row_template );

                /*
                 * start a new row
                 */
                $row_html = $this->plugin()->tools()->crunchTpl( $cell_tags, $cell_template );
                $columns++;
            }
            $total++;

        }

        $this->debug()->logVar( '$columns = ', $columns );
        $this->debug()->logVar( '$total = ', $total );


        /*
         * Wrap up the last row if it has less
         * than the total number of columns needed to
         * make a full row.
         */
        if ( $columns <= $max_columns ) {
            $row_tags[ 'row_html' ] = $row_html;
            $rows_html.=$this->plugin()->tools()->crunchTpl( $row_tags, $row_template );
        }



        /*
         * 
         * Render the table
         * 
         * Now that we have all the rows, crunch the table template
         */

        $table_tags[ 'rows_html' ] = $rows_html;



        $table_html = $this->plugin()->tools()->crunchTpl( $table_tags, $table_template );

        return $table_html;


    }

    /**
     * Get Domain Name Marketplace Links
     *
     * Returns an array of links to the sales page of the domain name for each of the supported marketplaces.
     *
     * @param string $domain_name The domain name without subdomain , e.g. : otctrades.com
     * @return array
     */
    private function _getDomainNameMarketplaceLinks( $domain_name ) {
#init
        $result_links = array();

        $tags_link_template[ 'domain_name' ] = $domain_name;



        $result_links[ 'sedo' ] = $this->plugin()->tools()->crunchTpl( $tags_link_template, $this->plugin()->SEDO_LINK_TEMPLATE );

        $result_links[ 'afternic' ] = $this->plugin()->tools()->crunchTpl( $tags_link_template, $this->plugin()->AFTERNIC_LINK_TEMPLATE );
        $result_links[ 'contact_owner' ] = $this->plugin()->tools()->crunchTpl( $tags_link_template, $this->plugin()->CONTACT_OWNER_LINK_TEMPLATE );

        return $result_links;
    }

    /**
     * Get Domain Info
     *
     * Returns the seller and domain name info of the domain
     *
     * @param none
     * @return void
     */
    public function getDomainInfo( $domain_name ) {

        global $wpdb;
        $query = "Select * from `view_domain_listings` where `domain_name`='" . $domain_name . "';";

        $domain_record = $wpdb->get_row( $query, ARRAY_A );

        //$tags[ 'domain_name' ] = $domain_name;
        //   $this->debug()->logDatabaseError();



        $seller_info = $this->plugin()->getModule( 'Core' )->getSellerData( $domain_record[ 'seller' ] );
        $domain_record[ 'seller_info' ] = ( array ) $seller_info;
        $domain_record[ 'domain_sales_page_url' ] = $this->plugin()->tools()->crunchTpl( $domain_record, $seller_info->domain_sales_page_url );
        $domain_record[ 'seller_display_name' ] = $seller_info->display_name;
        $domain_record[ 'user_name' ] = $seller_info->user_name;
        $tags = $domain_record;
        $tags[ 'user_name' ] = $seller_info->user_name;
        $domain_record[ 'seller_url' ] = $this->plugin()->tools()->crunchTpl( $tags, $seller_info->seller_url );

        /*
         * 
         * 
         * 
         * add the dollar or euro sign
         * dollar: &#36;
         * euro: &#8364;
         */
        
             

        $domain_record[ 'currency_symbol' ] = ($domain_record[ 'currency' ] === 'USD') ? '&#36;' : '&#8364;';



        $this->debug()->logVar( '$domain_record = ', $domain_record );
        return($domain_record);

}

    /**
     * Get Domain Sales Page Url
     *
     * Returns the url to the landing page of the domain where you submit a bid
     *
     * @param none
     * @return void
     */
    public function getDomainSalesPageUrl( $domain_name ) {

        global $wpdb;
        $query = "Select `seller` from `view_domain_listings` where `domain_name`='" . $domain_name . "';";

        $domain_record = $wpdb->get_row( $query, ARRAY_A );
        $this->debug()->logVar( '$domain_record = ', $domain_record );
        $tags = $domain_record;
        $tags[ 'domain_name' ] = $domain_name;
        $this->debug()->logDatabaseError();
        $seller_info = $this->plugin()->getModule( 'Core' )->getSellerData( $domain_record[ 'seller' ] );

        $this->debug()->logVar( '$seller_info = ', $seller_info );
        $salesPageUrl = $this->plugin()->tools()->crunchTpl( $tags, $seller_info->domain_sales_page_url );
        $this->debug()->logVar( '$salesPageUrl = ', $salesPageUrl );
        return($salesPageUrl);

 }

    /**
     * Show Contact Seller Form
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function showContactSellerPageOLD() {

        //  echo '<div>This is the contact seller Form</div>';

        $templates = $this->plugin()->getModule( 'Templates' );
        /*
         * get the template as a string
         */
        $template = $templates->getTemplate( 'form-contact-seller' );

        $tags = array();
        /*
         * Substitute the template tags
         */
        //    $lp_tags[ 'domain_name' ] = $this->plugin()->tools()->getQueryVar( 'domain_name' );
//$lp_tags[ 'price' ] = $this->plugin()->tools()->getQueryVar( 'price' );
        $html.=$this->plugin()->tools()->crunchTpl( $tags, $template );
        echo $html;

        exit();

    }

    /**
     * Show Add Domain Form
     *
     * Displays the Add Domain Form. Fired by the showAddDomainForm action added in queryvars.php
     *
     * @param none
     * @return void
     */
    public function showAddDomainFormOLD() {

        //  echo '<div>This is the contact seller Form</div>';

        $templates = $this->plugin()->getModule( 'Templates' );
        /*
         * get the template as a string
         */
        $template = $templates->getTemplate( 'form-add-domain' );

        $tags = array();
        /*
         * Substitute the template tags
         */
        //    $lp_tags[ 'domain_name' ] = $this->plugin()->tools()->getQueryVar( 'domain_name' );
//$lp_tags[ 'price' ] = $this->plugin()->tools()->getQueryVar( 'price' );
        $html.=$this->plugin()->tools()->crunchTpl( $tags, $template );
        echo $html;

        exit();

     }

    /**
     * Get Template Tags For Domain
     *
     * Returns the domain information in an array for consumption by a template
     *
     * @param none
     * @return void
     */
    public function getTagsForDomain() {

        /*
         * grab the domain name
         */
        $domain_name = $this->plugin()->tools()->getQueryVar( 'domain_name' );

        $tags=$this->getDomainInfo($domain_name);



        return ($tags);
}


 /**
     * Get Template Tags For Buy With PayPal
     *
     * Returns the domain information in an array for consumption by a template
     *
     * @param none
     * @return void
     */
    public function getTagsForBuyWithPaypal() {

        /*
         * grab the domain name
         */
        $domain_name = $this->plugin()->tools()->getQueryVar( 'domain_name' );
 

        $tags=$this->getDomainInfo($domain_name);
     
        /*
         * add currency codes
         * // $tags['price']= number_format($tags['price']); // 1,200 for twelve hundred 
         */
        $this->debug()->logVar( '$tags = ', $tags );
        
        if ( $tags['currency']!=='USD' ) {
            $tags['paypal-currency-code']='EUR';
}else {
     $tags['paypal-currency-code']='USD';
    
}
        
/*
 * add a comma for comma separation
 */
 $tags['price']= number_format($tags['price']); // 1,200 for twelve hundred 


        return ($tags);
}


    

    /**
     * Get Tags for Nomstock Sales Page
     *
     * Get Tags for Nomstock Sales Page
     * Example Output
     * $tags =

Array
(
    [domain_name] => zzask.com
    [id] => 2457
    [subdomain] => zzask
    [tld] => com
    [bin] => y
    [bid] => y
    [price] => 2,000
    [currency] => USD
    [time_added] => 2014-07-10 03:00:00
    [featured] => n
    [seller] => 1
    [approved] => y
    [time_lastupdated] => 2014-07-09 20:00:00
    [time_approved] => 2014-07-10 03:00:00
    [reg_available] => n
    [time_list_start] => 2014-07-09 20:30:00
    [time_list_stop] => 0000-00-00 00:00:00
    [list_status] => active
    [not_listed_reason] => (null)
    [price_note] => (null)
    [source] => member_inventory
    [added_by] => 1
    [concat_test] => (null)
    [email] => (null)
    [email_sent_list_start] => n
    [email_sent_list_end] => n
    [email_sent_rejected] => n
    [email_sent_not_listed] => n
    [rejected_reason] => (null)
    [reviewer_public_comments] => (null)
    [on_ticker] => y
    [total_clicks] => 8
    [total_impressions] => 43
    [seller_info] =>  
Array 
[seller_info]=> Array ( [id] => member_inventory [description] => Inventory [user_name] => nomstock [display_name] => Nomstock [domain_sales_page_url] => http://nomstock-dev.com/domain/{domain_name} [seller_url] => http://nomstock-dev.com/domains/{user_name} )
 
    [domain_sales_page_url] => http://nomstock-dev.com/domain/zzask.com
    [seller_display_name] => Nomstock
    [user_name] => nomstock
    [seller_url] => http://nomstock-dev.com/domains/nomstock
    [currency_symbol] => &#36;
    [reg_search-sales-page-url] => http://www.namecheap.com/domains/domain-name-search/results.aspx?f=31328&domain=zzask.com
    [member_inventory-sales-page-url] => http://nomstock-dev.com/domain/zzask.com
    [sedo-sales-page-url] => http://www.sedo.de/search/details.php4?domain=zzask.com&amp;language=us
    [afternic-sales-page-url] => http://www.afternic.com/name.php?domain=zzask.com
    [godaddy-sales-page-url] => https://auctions.godaddy.com/trpItemListing.aspx?isc=GPPT05K222&ci=81260&domain=zzask.com
    [snapnames-sales-page-url] => https://www.snapnames.com/domain/zzask.com.action
    [moniker-sales-page-url] => https://www.snapnames.com/domain/zzask.com.action
    [droplist-sales-page-url] => https://www.snapnames.com/domain/zzask.com.action
    [paypal-button-html] => <a href="/buy-with-paypal/{domain_name}" class="btn btn-success">Buy With PayPal</a>
    [paypal-icon-class] => glyphicon-ok icon-success
)

     * @param none
     * @return void
     */
    public function getTagsForNomstockSalesPage() {


        /*
         * Add Basic Domain Info
         * (Get the Domain from the Requested URL)
         */
        $tags = $this->getDomainInfo( $this->plugin()->tools()->getQueryVar( 'domain_name' ) );
        


  
        /*
         * Add Sales URLs
         */
        $sources = $this->getSources();

        foreach ( $sources as $seller => $seller_info ) {
            $tags[ $seller . '-sales-page-url' ] = $this->plugin()->tools()->crunchTpl( $tags, $sources[ $seller ]->domain_sales_page_url );

}


/*
 * Paypal button
 * This determines whether to add a minus icon or make it appear active to show that you can buy with Paypal
 */



        if ( $tags[ 'price' ] <= 500 ) {
    

            $tags[ 'paypal-button-html' ] = '<a href="/buy-with-paypal/' . $tags['domain_name'] . '" class="btn btn-success">Buy With PayPal</a>';
            $tags[ 'paypal-icon-class' ] = 'glyphicon-ok icon-success';

} else{

            

            $tags[ 'paypal-button-html' ] = '';
            $tags[ 'paypal-icon-class' ] = 'glyphicon-minus';
}

 
        
                /*
         * add a thousands separator
         * Ignore European format since its not consistent
         * across countries
         * Do this right before returning tags or comparisons or calculations using $tags['price'] will be wrong.
         */
        $tags['price']= number_format($tags['price']); // 1,200 for twelve hundred 
        
        return $tags;

    }

    /**
     * Show Domain Landing Page
     *
     * Shows the landing sales page for a domain
     *
     * @param none
     * @return void
     */
    public function showDomainLandingPageOLD() {
        global $query_vars;
        global $wpdb;

        /*
         * grab the domain name
         */
        $domain_name = $this->plugin()->tools()->getQueryVar( 'domain_name' );

        /*
         * get the attributes of the domain name from the active listings
         * use the result array as the $lp_tags array that will be used in the template
         */

        $query = 'Select * from `view_domain_listings` where `domain_name`=\'' . $domain_name . '\'' . ';'; //use this instead

        $domain_record = $wpdb->get_row( $query, ARRAY_A );


        $lp_tags = $domain_record;
        /*
         * add the dollar or euro sign
         * dollar: &#36;
         * euro: &#8364;
         */
        $lp_tags[ 'currency_symbol' ] = ($lp_tags[ 'currency' ] === 'USD') ? '&#36;' : '&#8364;';

        $this->debug()->logVar( '$domain_record = ', $domain_record );
        //  $this->debug()->stop( true );
        $this->debug()->logVar( 'query_vars = ', $query_vars );

        $this->debug()->logVar( 'get_query_var(domain_name) = ', get_query_var( 'domain_name' ) );
        $this->debug()->logVar( 'get_query_var(page) = ', get_query_var( 'page' ) );
        $this->debug()->logVar( '$_GET = ', $_GET );

        $templates = $this->plugin()->getModule( 'Templates' );
        /*
         * get the template as a string
         */
        $lp_template = $templates->getTemplate( 'nomstock-domain-sales-page' );

        /*
         * Substitute the template tags
         */
        //    $lp_tags[ 'domain_name' ] = $this->plugin()->tools()->getQueryVar( 'domain_name' );
//$lp_tags[ 'price' ] = $this->plugin()->tools()->getQueryVar( 'price' );
        $lp_html.=$this->plugin()->tools()->crunchTpl( $lp_tags, $lp_template );
        echo $lp_html;

        exit();
    }

    /**
     * Get Template Tags For Buy With Escrow
     *
     * Shows the Form to Buy With Escrow
     *
     * @param none
     * @return void
     */
    public function getTagsForBuyWithEscrowOLD() {
        global $query_vars;
        global $wpdb;

        /*
         * grab the domain name
         */
        $domain_name = $this->plugin()->tools()->getQueryVar( 'domain_name' );

        /*
         * get the attributes of the domain name from the active listings
         * use the result array as the $tags array that will be used in the template
         */

        $query = 'Select * from `view_domain_listings` where `domain_name`=\'' . $domain_name . '\'' . ';'; //use this instead

        $domain_record = $wpdb->get_row( $query, ARRAY_A );


        $tags = $domain_record;
        /*
         * add the dollar or euro sign
         * dollar: &#36;
         * euro: &#8364;
         */
        $tags[ 'currency_symbol' ] = ($tags[ 'currency' ] === 'USD') ? '&#36;' : '&#8364;';

        $this->debug()->logVar( '$domain_record = ', $domain_record );
        //  $this->debug()->stop( true );
        $this->debug()->logVar( 'query_vars = ', $query_vars );

        $this->debug()->logVar( 'get_query_var(domain_name) = ', get_query_var( 'domain_name' ) );
        $this->debug()->logVar( 'get_query_var(page) = ', get_query_var( 'page' ) );
        $this->debug()->logVar( '$_GET = ', $_GET );

        $this->showTemplate( $tags, 'form-buy-with-escrow' );

        exit();
    }

    /**
     * Permalink Action  - Show Template
     *
     * Handles a Pretty Url that shows a template
     * This is the generic method that Queryvars.php uses to render a template
     * 
     * to use: 
     * 1) create the tag callback method
     * 2) create the template
     * 3) add the pretty url in Queryvars
     * 
     *
     * @param none
     * @return void
     */
    public function permalinkActionShowTemplate() {



        $this->showTemplate(
                array( $this, get_query_var( 'nstock_tag_callback' ) ) //$tags callback
                , get_query_var( 'nstock_template' ) //$template_name 
        );


    }

    /**
     * Permalink Action  - Show Sales Page
     *
     * Wrapper around showTemplate that Handles a Pretty Url that shows the Sales Page

     * 
     *
     * @param none
     * @return void
     */
    public function permalinkActionShowSalesPage() {

        /*
         * get the domain name so we can determine where to redirect
         */
        $domain_name = $this->plugin()->tools()->getQueryVar( 'domain_name' );

        /*
         * get the domain info
         */
        $domain_info = $this->getDomainInfo( $domain_name );

        /*
         *  Count the click
         */

        $this->plugin()->getModule( 'NomstockStats' )->countDomainSalesPageClick();

        /*
         * if the seller does not have a numeric id, 
         * we can assume it has a domain sales page url that we redirect to
         */

        if ( !is_numeric( $domain_info[ 'seller' ] ) ) {


            Header( "Location: " . $domain_info[ 'domain_sales_page_url' ] );
            exit;

}

        /*
         * otherwise, instead of redirecting, we show the user's sales page template
         */

        //  $cell_tags[ 'domain_sales_page_url' ] = $this->getDomainSalesPageUrl( $domain[ 'domain_name' ] );
        //   $this->plugin()->getModule( 'Core' )->getSellerData();



        $this->showTemplate(
                array( $this, 'getTagsForNomstockSalesPage' ) //$tags callback
                , 'nomstock-domain-sales-page' //$template_name 
        );


}

    /**
     * Show Template
     *
     * Populates and displays the template held in our content directory
     *
     * @param mixed $tags Either an array of $tags or a callback function that returns the $tags array. The $tags array is a set of name value pairs with
     * the name being the associative index of the array, and the value is the value that should populate the template wherever that tag name appears.
     * @param string $template_name The name of the template file minus the extension (which must be .tpl)
     * @return void
     */
    public function showTemplate( $tags, $template_name ) {


        $this->debug()->logVar( '$template_name = ', $template_name );

        /*
         * Check whether the $tags array is actually a callback function
         * that returns the $tags we need
         */
        if ( is_callable( $tags ) ) {
            $tags = call_user_func( $tags );
            $this->debug()->logVar( '$tags = ', $tags );
} else{
            /*
             * make sure array $tags is an array if its not callable. if not set it to empty
             */
            if ( !is_array( $tags ) ) {
                $tags = array();
}

}



        $this->debug()->logVar( '$template_name = ', $template_name );
        /*
         * get the template as a string
         */
        $template = $this->plugin()->getModule( 'Templates' )->getTemplate( $template_name );

        /*
         * Populate and display the template
         */

        $template_html.=$this->plugin()->tools()->crunchTpl( $tags, $template );
        echo $template_html;
        exit();
    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function makeListingImage() {


        header( 'Content-Type: image/png' );

        $img = imagecreatefrompng( 'C:\wamp\www\nomstock-dev.com\public_html\wp-content\content\published\images\banner-125x50.png' );
        $bg = imagecreatefrompng( 'C:\wamp\www\nomstock-dev.com\public_html\wp-content\content\published\images\listing-800x150.png' );
        $bg = imagecreatefrompng( 'http://placehold.it/800x150.png/FFFFFF/&text=hello' );
//$text="hello there";
//
//// get image dimensions
//list($img_width, $img_height,,) = getimagesize('C:\wamp\www\nomstock-dev.com\public_html\wp-content\content\published\images\listing-800x150.png');
//
//
//// find font-size for $txt_width = 80% of $img_width...
//$font_size = 1; 
//$txt_max_width = intval(0.8 * $img_width);    
// // Allocate A Color For The Text
//  $white = imagecolorallocate($bg, 0, 0, 0);
//   // Set Path to Font File
//  $font_path = 'C:\wamp\www\nomstock-dev.com\public_html\wp-content\content\published\images\ELEPHNT.TTF';
//  
//do {
//
//    $font_size++;
//    $p = imagettfbbox($font_size,0,$font_path,$text);
//    $txt_width=$p[2]-$p[0];
//    // $txt_height=$p[1]-$p[7]; // just in case you need it
//
//} while ($txt_width <= $txt_max_width);
//
//// now center text...
//$y = $img_height * 0.9; // baseline of text at 90% of $img_height
//$x = ($img_width - $txt_width) / 2;
//imageantialias($bg, true);
//imagettftext($bg, $font_size, 0, $x, $y, $white, $font_path, $text . "\n" .$text);
//
//
//
//imagepng($bg, null);
//exit();



        /*
         * 
         * Add text to background
         */
        // imagettftext($bg, 25, 0, 75, 300, $white, $font_path, $text);

        /*
         * 
         * Overlay banner over background
         * 
         */
        imagecopymerge( $bg, $img, 0, 0, 0, 0, imagesx( $img ), imagesy( $img ), 100 );

        imagepng( $bg, null );
        exit();


}

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function txt2imgr() {
        echo '<div>this is the text to image api stub</div>';

}

    protected $sources_info = null;

    /**
     * Get Sources
     *
     * Retrieves the nstock_sources table in an array 
     * The nstock_sources table contains information about sellers such as 
     * display_name,domain_sales_page_url, description and seller url
     *
     * @param none
     * @return void
     */
    public function getSources() {
        global $wpdb;
        if ( is_null( $sources_info ) ) {
            $query = "select * from nstock_sources";
            $this->debug()->logVar( '$query = ', $query );
            $this->sources_info = $wpdb->get_results( $query, OBJECT_K );
            $this->debug()->logVar( '$dbresult = ', $dbresult );

}
        return $this->sources_info;
}

    /**
     * Get Domain Sales Page Link
     *  DEPRECATED - this was removed since now I'm just using the sales_page_url and formatting the links within the template
     * Returns a fully formated <a href> tag and anchor text that includes the url of the sales landing page
     * 
     * 
     * @param none
     * @return void
     */
//    public function getDomainSalesPageLink( $seller_id, $domain_name ) {
//
//        /*
//         * setup the template values
//         */
//        $tags[ 'domain_name' ] = $domain_name;
//
//        /* getDomainSalesPageLink
//         * get the array of values from the nstock_source table
//         * we'll use this to get the link information
//         */
////$seller_info=$this->getSources();
//        $seller_info = $this->getSellerData( $seller_id );
//    
//         $tags = array_merge($tags,(array)$seller_info);
//         
//          
//        $this->debug()->logVar( '$seller_info = ', $seller_info );
//        $this->debug()->logVar( '$seller_id = ', $seller_id );
//        $this->debug()->logVar( 'Template is  = ', $seller_info->seller_url );
//
//
//        if ( $seller_id === 'register' ) {
//            $link_template = '<a href="' . $seller_info->seller_url . '">Unregistered - Register it Now</a>';
//} else
//{
//            $link_template = 'Seller: <a href="' . $seller_info->seller_url . '">' . $seller_info->display_name . '</a>';
//    }
//
//        return($this->plugin()->tools()->crunchTpl( $tags, $link_template ));
//
//}
//    /**
//     * Short Description
//     *
//     * Long Description
//     *
//     * @param none
//     * @return void
//     */
//    public function getSellerDisplayName( $seller_id ) {
//        global $wpdb;
//        $this->debug()->logVar( '$source_id = ', $source_id );
//        
//        /*
//         * attempt to get seller from the source id
//         * if the source id is member_inventory, then the seller is the member so use
//         * the members username
//         */
//        $seller_id = ($source_id === 'member_inventory') ? get_current_user_id() : null;
//
//        if ( is_numeric($seller_id)) {
//            $user_info = get_userdata($seller_id);
//       $display_name=$user_info->display_name;      
//       return $display_name;
//}
//
//  
//    $seller_info=$this->getSources();    
//        return $seller_info[$seller_id]->display_name;
//        
//
//        }

    /**
     * Get Seller Data
     *
     * Given a seller_id from the domain table, provides display name, user name, of seller.
     * The seller_id is the wordpress user id if the seller is a member of the wordpress site,
     * otherwise, if the id is not numeric, indicating a marketplace, it uses 
     *
     * @param none
     * @return void
     */
    public function getSellerData( $seller_id ) {
        $seller_data = array();

        /* Get Sources
         * Retrieve the information from the domain sources
         * Domain Sources are contained in the nstock_sources table
         * Information includes user_name, display_name, seller_url_template,etc.
         */
        $source_data = $this->getSources();
        if ( is_numeric( $seller_id ) ) {
            $seller_data = $source_data[ 'member_inventory' ];
            $user_data = get_userdata( $seller_id );
            $seller_data->display_name = $user_data->display_name;
            $seller_data->user_name = $user_data->user_login;
} else{
            $source_id = $seller_id;
            $seller_data = $source_data[ $source_id ];

}
        return $seller_data;

    }

    /**
     * Show Contact Seller Form
     *
     * Displays the 'Contact Seller Form' We need this in a function
     * and not in a template because its used in multiple places
     *
     * @param none
     * @return void
     */
    public function showContactSellerForm() {
        /*
         * colors:
         * blue from original : #3E9CE3
         * blue from image generator: 318DD7
         * blue from bootstrap: #428BCA
         */




        /*
         * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
         * and output of any of the fields by modifying the attributes before they are processed in the template
         */
        $f = $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' );

        $domain_name = $_POST[ 'domain_name' ];
        ?><!-- response start to replace form -->






        <table class="table" style="border:5px solid #428BCA;border-radius:10px">
            <tr><td class="text-center" ><p ><img  class="img-thumbnail pull-center" src="/wp-content/content/published/_jekyll-output/images/no-index/joel-3-150x150.jpg"> </p> <p class="small">Andrew Druffner</p><p class="lead" style="margin:20px;margin-top:5px;">"Let me help you with your purchase questions on pricing, escrow, and domain name transfer."</p>

                </td></tr>
            <tr><td  >
                    <?php
                    $f->formStart( array(
                        'theme' => 'Bootstrap',
                        'ajax' => true,
                        'container_class' => 'none',
                        'filter' => 'Bootstrap',
                        //   'style' => 'border:5px solid #428BCA;border-radius:10px',
                        //  'class' => "col-md-0",
                        //  'label_size' => '2',
                        'size' => '3',
                        'response_fadeout' => false,
                        'hide_form' => true,
                        'name' => 'contact_domain_seller',
                        'layout' => 'bare'
                            )
                    );
                    ?> 
                    <table >

                        <tr><th >Name</th><td >       <?php
                                $f->el( array(
                                    'el' => 'text',
                                    'name' => 'name',
                                    'placeholder' => '',
                                    'value' => '',
                                    'label' => 'Name*',
                                    'size' => 1,
                                    'hint' => '',
                                    'heading' => ''
                                        )
                                );
                                ?> <p data-sf-valid="name"></p> </td></tr>
                        <tr><th>Domain Name*</th><td>       

                                <?php
                                $f->el( array(
                                    'el' => 'text',
                                    'name' => 'domain_name',
                                    'placeholder' => 'example.com',
                                    'value' => '',
                                    'label' => 'Domain Name*',
                                    'hint' => '',
                                    'heading' => ''
                                        )
                                );
                                ?><p data-sf-valid="domain_name"></p> </td></tr>
                        <tr><th>Email Address*</th><td>       <?php
                                $f->el( array(
                                    'el' => 'text',
                                    'name' => 'email',
                                    'placeholder' => '',
                                    'value' => '',
                                    'label' => 'Email*',
                                    'hint' => '',
                                    'heading' => ''
                                        )
                                );
                                ?><p data-sf-valid="email"></p> </td></tr>
                        <tr><th>Question or Comment*</th><td>       <?php
                                $f->el( array(
                                    'el' => 'textarea',
                                    'rows' => 5,
                                    'cols' => 5,
                                    'name' => 'question',
                                    'placeholder' => '',
                                    'value' => '',
                                    'label' => 'Question or Comment*',
                                    'hint' => '',
                                    'heading' => ''
                                        )
                                );
                                ?> <p data-sf-valid="question"></p></td></tr>


                        <tr><td></td><td style="padding:10px"> <?php
                                $f->el( array(
                                    'el' => 'button',
                                    'value' => 'Contact Me',
                                    'action' => 'contact_seller',
                                    'class' => 'btn-warning btn-block'
                                        )
                                );
                                ?></td></tr>
                        <tr><td colspan="2" style="text-align:center" class="small"><p>We will never sell your email address to any 3rd party or send you nasty spam. Promise.</p><p class="pull-right">*Required Fields</p>  </td><td>  </td></tr>                                                

                    </table>
                </td></tr></table>




        <div style="float:none;margin:0 auto;margin-top:20px;" class="simpli_forms_response"></div>
        <?php
        $f->formEnd();
    }

    /**
     * Show Contact Seller Form2
     *
     * Displays the 'Contact Seller Form' We need this in a function
     * and not in a template because its used in multiple places
     *
     * @param none
     * @return void
     */
    public function showContactSellerFormOLD() {
        /*
         * colors:
         * blue from original : #3E9CE3
         * blue from image generator: 318DD7
         * blue from bootstrap: #428BCA
         */




        /*
         * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
         * and output of any of the fields by modifying the attributes before they are processed in the template
         */
        $f = $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' );

        $domain_name = $_POST[ 'domain_name' ];
        ?><!-- response start to replace form -->
        <div style="border:5px solid #428BCA;border-radius:10px">

            <div  style="margin-top:25px;" class="pull-left">
                <img style="display:block;" class="img-thumbnail pull-left" src="/wp-content/content/published/_jekyll-output/images/no-index/joel-3-150x150.jpg"> <div >Andrew Druffner</div>
            </div>
            <div style="margin-top:5px;" class="">
                <strong  >Questions? </strong>
                <p class="pull-left text-justify"> Since I make the final decision on the terms of all Nomstock domain name sales, I'm able to give you fast answers and help you obtain your domain quickly without any 3rd party negotiation.  </p>
            </div> 


            <?php
            $f->formStart( array(
                'theme' => 'Bootstrap',
                'ajax' => true,
                'container_class' => 'container',
                'filter' => 'Bootstrap',
                //   'style' => 'border:5px solid #428BCA;border-radius:10px',
                'class' => "col-md-0",
                'label_size' => '2',
                'size' => '2',
                'response_fadeout' => false,
                'name' => 'contact_domain_seller',
                'layout' => 'horiz'
                    )
            );
            ?> 
            <div style="float:none;margin:0 auto;margin-top:20px;" >
                <?php
                $f->el( array(
                    'el' => 'text',
                    'name' => 'name',
                    'placeholder' => '',
                    'value' => '',
                    'label' => 'Name*',
                    'hint' => '',
                    'heading' => ''
                        )
                );
                $f->el( array(
                    'el' => 'text',
                    'name' => 'domain_name',
                    'placeholder' => 'example.com',
                    'value' => '',
                    'label' => 'Domain Name*',
                    'hint' => '',
                    'heading' => ''
                        )
                );
                ?><?php
                $f->el( array(
                    'el' => 'text',
                    'name' => 'email',
                    'placeholder' => '',
                    'value' => '',
                    'label' => 'Email*',
                    'hint' => '',
                    'heading' => ''
                        )
                );


                $f->el( array(
                    'el' => 'textarea',
                    'rows' => 5,
                    'cols' => 5,
                    'name' => 'question',
                    'placeholder' => '',
                    'value' => '',
                    'label' => 'Question or Comment*',
                    'hint' => '',
                    'heading' => ''
                        )
                );
                ?><!-- Button Start --> 
                <div class="row">
                    <div class="col-md-1"> &nbsp; </div>
                    <?php
                    $f->el( array(
                        'el' => 'button',
                        'value' => 'Contact Me',
                        'action' => 'contact_seller',
                        'class' => 'btn-warning btn-block'
                            )
                    );
                    ?>     </div> <!-- Button End -->  

                <div class="row">
                    <div class="col-md-4" style="text-align:center">
                        <hr><p>We will never sell your email address to any 3rd party or send you nasty spam. Promise.</p><p class="pull-right">*Required Fields</p></div>
                </div>
            </div><!-- response end to replace form -->
        </div>

        <div style="float:none;margin:0 auto;margin-top:20px;" class="simpli_forms_response"></div>
        <?php
        $f->formEnd();
    }

    /**
     * Wpdb Results To Html
     *
     * Returns the results of a WordPress $wpdb query as a html table. Can also be used for other arrays that are structured similarly.
     *
     * @param none
     * @return void
     */
    public function WpdbResultsToHtml( $data, $headings, $template = 'bootstrap' ) {
#init
        $col_html = '';
        $cols_html = '';
        $tags = array();
        $row_htm = '';
        $rows_htm = '';
        $row_counter = 0;
        $templates = $this->plugin()->getModule( 'Templates' );

        $column_template = $templates->getTemplate( 'tables_' . $template . '_column' );
        $table_template = $templates->getTemplate( 'tables_' . $template . '_table' );
        $row_template = $templates->getTemplate( 'tables_' . $template . '_row' );

        $rows = $data;
        $this->debug()->logVar( '$rows = ', $rows );
        /*
         * Insert a first row of values into the array
         * that contains the names of the columns. we'll
         * then use these values as headings in the table.
         * Note that they will be non-associative array elements,
         * unlike the remaining rows. This is why we have to compare them
         * differently when determining whether to skip
         */
        array_unshift( $rows, array_keys( $rows[ 0 ] ) );
        $this->debug()->logVar( '$rows = ', $rows );
//$this->debug()->stop( true );
        foreach ( $rows as $row ) {

            $tags = array();
            $col_html = '';
            $cols_html = '';

            foreach ( $row as $field_name => $field_value ) {
                /*
                 * The first row's field values are used as headings.
                 * if its the first row, and the field value is not a heading we want displayed, then skip it
                 * Note that the first row is the row we inserted earlier that is a non-associative array that 
                 * contains the column names.
                 */
                if ( ($row_counter === 0 && (!in_array( $field_value, $headings ))) || ($row_counter !== 0 && (!in_array( $field_name, $headings ))) ) {
                    $this->debug()->logVar( '$field_name = ', $field_name );
                    $this->debug()->logVar( '$field_value = ', $field_value );
                    $this->debug()->logVar( '$headings = ', $headings );
                    continue;
}

                $tags[ 'data' ] = $field_value;

                if ( is_serialized( $field_value ) ) {
                    $field_value_array = maybe_unserialize( $field_value );
                    $arrays_row_html = '';
                    $array_row_html = '';
                    foreach ( $field_value_array as $name => $value ) {
                        $array_row_html = $this->plugin()->tools()->crunchTpl( array( 'data' => '<strong>' . $name . '</strong>' ), $column_template );
                        $array_row_html .= $this->plugin()->tools()->crunchTpl( array( 'data' => $value ), $column_template );
                        $arrays_row_html .= $this->plugin()->tools()->crunchTpl( array( 'row_html' => $array_row_html ), $row_template );

}
                    $tags[ 'data' ] = $this->plugin()->tools()->crunchTpl( array( 'rows_html' => $arrays_row_html ), $table_template );
                    //  $tags[ 'data' ] = '<pre>'. print_r( $field_value_array, true ). '</pre>';


}


                $col_html = $this->plugin()->tools()->crunchTpl( $tags, $column_template );
                $cols_html .=$col_html;
}
            $tags = array();
            $row_html = '';
            $tags[ 'row_html' ] = $cols_html;
            $row_html = $this->plugin()->tools()->crunchTpl( $tags, $row_template );
            $rows_html.=$row_html;
            $row_counter++;
}


        $tags = array();
        $tags[ 'rows_html' ] = $rows_html;
        $table_html = $this->plugin()->tools()->crunchTpl( $tags, $table_template );
        $this->debug()->logVar( '$table_html = ', $table_html );
        return $table_html;

    }

    function flip_row_col_array( $array ) {
        $out = array();
        foreach ( $array as $rowkey => $row ) {
            foreach ( $row as $colkey => $col ){
                $out[ $colkey ][ $rowkey ] = $col;
        }
    }
        return $out;
}

    /**
     * Get Tags for User Inventory
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function getTagsForUserInventory() {

        global $wpdb;




        /*
         * Get the user name
         */
        $user_login = $this->plugin()->tools()->getQueryVar( 'user' );
        //    $user_login='sedo';
        $this->debug()->logVar( '$user_login = ', $user_login );
        /*
         * derive user data
         */
        $user_data = get_userdatabylogin( $user_login );
        $this->debug()->logVar( '$user_data = ', $user_data );

        /*
         * check to see if the user was found.
         * if not, check to see if its a source. if it is, redirect to the source url
         * if it still can't be found, display a 'cannot find user' message
         */
        if ( $user_data === false ) {





//                            $query = 'Select user_name,seller_url from nstock_sources'; //use this instead
//
//         
//$sources = $wpdb->get_results( $query, ARRAY_A );
//
//
//$source_user_names=$this->plugin()->tools()->getArrayColumn($sources,'user_name');
//
//$sources=array_combine($source_user_names,$sources);
//

            $query = 'Select user_name,seller_url from nstock_sources';
            $sources = $this->plugin()->tools()->getDbResultIndexedToColumn( $query, 'user_name' );
            $this->debug()->logVar( '$sources = ', $sources );




            /*
             * Check to see if the user is one of the sources, if not, then the user doesnt exist
             */
            if ( in_array( $user_login, array_keys( $sources ) ) ) { //if the user is one of the sources, redirect to the seller url
                $this->debug()->logVar( 'seller_url = ', $sources[ $user_login ][ 'seller_url' ] );



                wp_redirect( $sources[ $user_login ][ 'seller_url' ] );
                exit();
} else{ //if the user isnt found as a member or as a source, then display an error message
                $tags[ 'user_login' ] = ucwords( $user_login );
                $tags[ 'display_name' ] = ucwords( $user_login );
                $tags[ 'domain_inventory_html' ] = '<div class="alert alert-danger">Inventory cannot be displayed. User \'' . $user_login . '\' was not found. </div>';

                return $tags;




}




}

        /*
         * Look up the user's domain inventory
         */
        $user_id = $user_data->ID;
        $display_name = $user_data->display_name;

        $query = 'Select DISTINCT ' . "concat(`subdomain`,'.',`tld`)" . ' as `Domain Name` , `price` as \'Price\' , currency from `nstock_domains`  where `list_status` = \'archived\' and  `seller`=\'' . $user_id . '\'' . ' ;'; //use this instead

        $query = 'Select DISTINCT ' . "concat(`subdomain`,'.',`tld`)" . ' as `Domain Name` , `price` as \'Price\' , currency from `nstock_inventory`  where `seller`=\'' . $user_id . '\'' . ' ;'; //use this instead
        
        
        $inventory_records = $wpdb->get_results( $query, ARRAY_A );



        $this->debug()->logDatabaseError( true );

        /*
         * Show only the fields in the following headings array
         */
        $headings = array(
            'Domain Name',
            'Price',
            'Details'
        );

        /*
         * if you need to do any massaging of data, this is an example of how you'd do it.
         */

//        $forms = $this->prepFormData(
//                $db_records, //$forms,
//                $hidden_fields,
//                $hide_hidden
//        );
        /*
         * Template Tags
         */
        $tags[ 'user_login' ] = ucwords( $username );
        $tags[ 'display_name' ] = ucwords( $display_name );
        $inventory_records = $this->prepInventoryData( $inventory_records );
        $tags[ 'domain_inventory_html' ] = $this->plugin()->getModule( 'Core' )->WpdbResultsToHtml(
                $inventory_records, $headings, 'bootstrap'
        );





        /* PrepData - for the prepdata routine, use this
         * add the dollar or euro sign
         * dollar: &#36;
         * euro: &#8364;
         * 
         *  $tags[ 'currency_symbol' ] = ($tags[ 'currency' ] === 'USD') ? '&#36;' : '&#8364;';
         */




        return $tags;


    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function showUserInventoryOLD() {

        global $wpdb;


        $template_name = 'domain-inventory';

        /*
         * Get the Templates Module Class as an object
         */
        $templates = $this->plugin()->getModule( 'Templates' );


        /*
         * Get the user name
         */
        $user_login = $this->plugin()->tools()->getQueryVar( 'user' );
        //    $user_login='sedo';
        $this->debug()->logVar( '$user_login = ', $user_login );
        /*
         * derive user data
         */
        $user_data = get_userdatabylogin( $user_login );
        $this->debug()->logVar( '$user_data = ', $user_data );

        /*
         * check to see if the user was found.
         * if not, check to see if its a source. if it is, redirect to the source url
         * if it still can't be found, display a 'cannot find user' message
         */
        if ( $user_data === false ) {





//                            $query = 'Select user_name,seller_url from nstock_sources'; //use this instead
//
//         
//$sources = $wpdb->get_results( $query, ARRAY_A );
//
//
//$source_user_names=$this->plugin()->tools()->getArrayColumn($sources,'user_name');
//
//$sources=array_combine($source_user_names,$sources);
//

            $query = 'Select user_name,seller_url from nstock_sources';
            $sources = $this->plugin()->tools()->getDbResultIndexedToColumn( $query, 'user_name' );
            $this->debug()->logVar( '$sources = ', $sources );




            /*
             * Check to see if the user is one of the sources, if not, then the user doesnt exist
             */
            if ( in_array( $user_login, array_keys( $sources ) ) ) { //if the user is one of the sources, redirect to the seller url
                $this->debug()->logVar( 'seller_url = ', $sources[ $user_login ][ 'seller_url' ] );



                wp_redirect( $sources[ $user_login ][ 'seller_url' ] );
                exit();
} else{ //if the user isnt found as a member or as a source, then display an error message
                $tags[ 'user_login' ] = ucwords( $user_login );
                $tags[ 'display_name' ] = ucwords( $user_login );
                $tags[ 'domain_inventory_html' ] = '<div class="alert alert-danger">Inventory cannot be displayed. User \'' . $user_login . '\' was not found. </div>';

                /*
                 * get the template as a string
                 */
                $template = $templates->getTemplate( $template_name );

                /*
                 * Populate the template with the tags and display
                 */

                $page_html = $this->plugin()->tools()->crunchTpl( $tags, $template );
                echo $page_html;

                exit();



                exit();

}




}

        /*
         * Look up the user's domain inventory
         */
        $user_id = $user_data->ID;
        $display_name = $user_data->display_name;

        $query = 'Select DISTINCT ' . "concat(`subdomain`,'.',`tld`)" . ' as `Domain Name` , `price` as \'Price\' , currency from `nstock_domains`  where `list_status` = \'archived\' and  `seller`=\'' . $user_id . '\'' . ' LIMIT 10;'; //use this instead


        $inventory_records = $wpdb->get_results( $query, ARRAY_A );



        $this->debug()->logDatabaseError( true );

        /*
         * Show only the fields in the following headings array
         */
        $headings = array(
            'Domain Name',
            'Price',
            'Details'
        );

        /*
         * if you need to do any massaging of data, this is an example of how you'd do it.
         */

//        $forms = $this->prepFormData(
//                $db_records, //$forms,
//                $hidden_fields,
//                $hide_hidden
//        );
        /*
         * Template Tags
         */
        $tags[ 'user_login' ] = ucwords( $username );
        $tags[ 'display_name' ] = ucwords( $display_name );
        $inventory_records = $this->prepInventoryData( $inventory_records );
        $tags[ 'domain_inventory_html' ] = $this->plugin()->getModule( 'Core' )->WpdbResultsToHtml(
                $inventory_records, $headings, 'bootstrap'
        );





        /* PrepData - for the prepdata routine, use this
         * add the dollar or euro sign
         * dollar: &#36;
         * euro: &#8364;
         * 
         *  $tags[ 'currency_symbol' ] = ($tags[ 'currency' ] === 'USD') ? '&#36;' : '&#8364;';
         */




        /*
         * get the template as a string
         */
        $template = $templates->getTemplate( $template_name );

        /*
         * Populate the template with the tags and display
         */

        $page_html = $this->plugin()->tools()->crunchTpl( $tags, $template );
        echo $page_html;

        exit();


    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function prepInventoryData( $domains ) {
        $query = 'select `domain_sales_page_url` from nstock_sources where `user_name`=\'user_login\''; //use this instead

        global $wpdb;
        $record = $wpdb->get_row( $query, ARRAY_A );
        $this->debug()->logVar( '$record = ', $record );
        $url_template = $record[ 'domain_sales_page_url' ];


        foreach ( $domains as $key => $domain ) {
            $tags[ 'domain_name' ] = $domain[ 'Domain Name' ];
            $url = $this->plugin()->tools()->crunchTpl( $tags, $url_template );
            $this->debug()->logVar( '$url = ', $url );

            $currency_symbol = ($domain[ 'currency' ] === 'USD') ? '&#36;' : '&#8364;';
            
            /*
             * use thousands separator only if USD
             */
            $domain[ 'Price' ] = ($domain[ 'currency' ] === 'USD')? $currency_symbol . number_format($domain[ 'Price' ]) :  $currency_symbol . $domain[ 'Price' ];

            $domain[ 'Domain Name' ] = '<a href="' . $url . '">' . $domain[ 'Domain Name' ] . '</a>';
            $domain[ 'Details' ] = '<a href="' . $url . '">Read More</a>';
            $domains[ $key ] = $domain;
}
        return($domains);
    }

    /**
     * Get Next Ticker Page
     *
     * url:
     * nomstock-dev.com/index.php?nomstock_com_action=getNextTickerPage&next_tickerid=1

     * 
     * Returns a list of results in json 
     *
     * @param none
     * @return void
     */
    public function getNextTickerPage() {



        echo $this->_getTickerListings();

        exit();
    }
}
