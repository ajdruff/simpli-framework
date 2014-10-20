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
class Simpli_Frames_Modules_Menu22SimpliForms extends Simpli_Frames_Base_v1c2_Plugin_Menu {

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
                $page_title = $this->plugin()->getName() . ' Simpli Forms'
                , $menu_title = array( 'menu' => $this->plugin()->getName(), 'sub_menu' => 'Simpli Forms' )
                , $capability = 'manage_options'
                , $icon_url = $this->plugin()->getUrl() . '/admin/images/menu.png'
                , $position = null
        );



        $this->metabox()->addMetaBox(
                'metabox_manage'  //Meta Box DOM ID
                , __( 'Manage Forms', $this->plugin()->getTextDomain() ) //title of the metabox.
                , array( $this->metabox(), 'renderMetaBoxTemplate' ) //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );


        /*
         * {META_BOX}
         * Add additional Metaboxes here
         *
         */

    }

    /**
     * Prep Form Data
     *
     * Manipulates form records before rendering in HTML to :
     * 1)add form fields for editing/updates
     *  2) format time stamps to be human readable
     *  3) remove hidden fields from display
     *  4) adds a counter column
     * @param none
     * @return void
     */
    public function prepFormData( $forms, $hidden_fields, $hide_hidden = true ) {



        /*
         * get the Form Addon's Form Module so 
         * we can use it to set the theme before 
         * creating the dropdown so it uses the proper filter
         * and template
         */
        $f = $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' );



        /*
         * set filter and theme
         * If you dont do this, you'll be using the default theme or wont even see
         * any output if the filter doesnt exist.
         */
        $f->getTheme()->setTheme( 'Bootstrap' );
        $f->setFilter( 'Bootstrap' );


        /*
         * add a radio button to each form record
         * whose input name includes the form record's database id
         * that way, we can later use it to update the record's status
         */
        $form_counter = 0;
        foreach ( $forms as $key => $form ) {
            $this->debug()->logVar( '$form = ', $form );

            ob_start();
            $f->el( array(
                'el' => 'radio',
                'class' => 'btn btn-default btn-block',
                'style' => '',
                'options' => array( 'new' => 'New', 'saved' => 'Save', 'deleted' => 'Delete' ),
                'name' => 'status[' . $form[ 'ID' ] . ']',
                'selected' => $form[ 'Status' ],
                'heading' => '',
                'label' => '',
                'device_size' => 'medium', //must specify since in this case, the form tag cant provide it.
                'size' => '8',
                'layout'=>'bare',
                'template' => 'radio',
                'hint' => '',
                    )
            );
            $status_update_dropdown = ob_get_clean();
            $form[ 'Update Status' ] = $status_update_dropdown;
            $form[ 'Time Submitted' ] = $this->plugin()->tools()->getLocalTimeFromUTC( $form[ 'Time Submitted' ], //$time as a timestamp string
                    //  'Y-m-d h:i:P'  //e.g.:2013-11-26 08:39-0800 :P to show the timezone  h for 24 hour time
                    'Y-m-d g:i:A'  //e.g.: 2013-11-26 8:39 PM  :A for AM/PM  'g' for 12 hour time
            );

            /*
             * Now check for the fields array and filter out the fields we dont want
             */

            $form_fields_array = maybe_unserialize( $form[ 'Fields' ] );
            $this->debug()->logVar( '$form_fields_array = ', $form_fields_array );
            if ( !is_array( $form_fields_array ) ) {
                $form_fields_array = array();
}

            /*
             * Hide Hidden Fields
             */
$hide_hidden=(boolean)$hide_hidden;
            if ( $hide_hidden === true ){
                foreach ( $form_fields_array as $form_field_name => $form_field_value ) {


                    if ( in_array( $form_field_name, $hidden_fields ) ) {
                        unset( $form_fields_array[ $form_field_name ] );
}
                    if ( stripos( $form_field_name, '_nonce' ) !== false ) {
                        unset( $form_fields_array[ $form_field_name ] );
}
                    if ( stripos( $form_field_name, '_referer_url' ) !== false ) {
                        unset( $form_fields_array[ $form_field_name ] );
}

}

 }
            /*
             * now re-serialize the form fields without
             * the hidden fields.
             */
            $form[ 'Fields' ] = maybe_serialize( $form_fields_array );

          /*
           * add a row counter as the first column
           * ref:http://stackoverflow.com/a/5783777
           */
            $form = array('Form Count' => ++$form_counter) + $form;
          

            $forms[ $key ] = $form;








}
        $this->debug()->logVar( '$forms = ', $forms );

        return $forms;


        }

    /**
     * Get Forms Html Table
     *
     * Returns a list of forms that have been submitted as an html table
     *
     * @param $filters An associative array, the index is the column, the value is the value to filter on. These will be turned into 'where' mysql clauses. For example. array('status'=>'new') will turn into where `status`='new'
     * @param array $hidden_files An array of hidden fields, e.g.:
     * @param boolean $hide_hidden Whether to hide the hidden fields.
     * @return void
     */
    public function getFormsHtmlTable( $filters,$hidden_fields,$hide_hidden ) {
        $where_clause = '';
        $first_key = key( $filters );
        foreach ( $filters as $column => $filter_value ) {

            if ( $first_key === $column )
             {
                $where_clause = 'where `' . $column . '` = \'' . $filter_value . '\'';
} else{
                $where_clause .= ' and `' . $column . '` = \'' . $filter_value . '\'';
}
        }
        $this->debug()->logVar( '$where_clause = ', $where_clause );
        // $this->debug()->stop( true );
        global $wpdb;
        $query = "select "
                . "id as `ID`,"
                . "form_name,"
                . "fields as `Fields`,"
                . "status as `Status`,"
                . "time_added as `Time Submitted`"
                . "from `simpli_forms` "
                . $where_clause
        ;
        $db_records = $wpdb->get_results( $query, ARRAY_A );


        if ( empty( $db_records ) ) {
            return ' <div class="jumbotron">No ' . ucwords( $status ) . ' Forms</div>';

}


        /*
         * Show only the fields in the following headings array
         */
        $headings = array(
            'Form Count',
            'ID',
            'Fields',
            'Time Submitted',
            'Update Status'
        );



        $forms = $this->prepFormData(
                $db_records, //$forms,
                $hidden_fields,
                $hide_hidden
        );



        $this->debug()->logVar( '$forms = ', $forms );

        // $this->debug()->logVar( '$forms = ', $dbresult,true );
        /*
         * show the result as a table
         */
        $html = $this->plugin()->getModule( 'Core' )->WpdbResultsToHtml( $forms, $headings, 'bootstrap' );
        return $html;
    }
}

?>