<?php

/**
 * Cron Email Alerts
 *
 * Sends out emails as directed by a Cron job.
 * See http://simpliwp.com/plugin-builder/ for examples
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @property string $TIME_FORMAT The format in which you want the time to appear in Emails. Sell all formats here: http://www.php.net/manual/en/function.date.php
 *
 *
 */
class Nomstock_Com_Modules_CronEmailAlerts extends Nomstock_Com_Base_v1c2_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t(); //trace provides a information about the method and arguments, and provides a backtrace in an expandable box. A visual trace is also provided if graphiviz is enabled.



        /*
         * TIME_FORMAT
         *
         * The format in which you want the time to appear in Emails. Sell all formats here: http://www.php.net/manual/en/function.date.php
         */
        $this->setConfig(
                'TIME_FORMAT'
                , 'M j, Y, g:i A (T)' //Jan 1, 1970, 5:07 PM (PST)
        );

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

    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - hookEnqueueScripts
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {
        
    }

    /**
     * Send Listing Start Emails (Called from Cron)
     *
     * Sends the Emails to Each Submitter When their Listings have Started.
     * This is a wrapper around _sendEmailToQuery()
     *
     * @param boolean $simulate True to output to stdout instead of actually sending Emails.
     * @return void
     */
    public function sendListingStartEmails( $simulate = true ) {

        $query = "select "
                . "`email`,"
                . "concat(`subdomain`,'.',`tld`) as `domain`,"
                . "`bin`,"
                . "`bid`,"
                . "`price`,"
                . "`currency`,"
                . "`time_added`,"
                . "`time_approved`,"
                . "`time_list_start`,"
                . "`seller`,"
                . "`list_status`"
                . " from `" . DB_NAME . "`.`nstock_domains` "
                . " where `list_status`='pending'"
                . " and `email_sent_listed`='n'"
                . " and `email` IS NOT NULL"
                . " and `email` !=''"

        ;



        $this->_sendEmailToQuery(
                $query, //the database query. Must include an 'email' field with the 'to' address
                'email-domain-list-notify-listed', //$template_name The name of the template to be used for the email
                'Your domain {domain} has been listed', //$subject
                $simulate //$simulate - set to true if want to just output to stdout instead of actually sending email
        );
    }

    /**
     * Send Listing Ended Emails (Called from Cron)
     *
     * Sends the Emails to Each Submitter When their Listings have Ended.
     * This is a wrapper around _sendEmailToQuery()
     *
     * @param boolean $simulate True to output to stdout instead of actually sending Emails.
     * @return void
     */
    public function sendListingEndEmails( $simulate = true ) {

        /*
         * Will get all those who were not emailed if their listing
         * ended in the past 48 hours. We have to limit how far back we go
         * we limit the risk of spamming. For example, if the email field was never updated,
         * we could end up spamming people multiple times.
         */

        $query = "select "
                . "`email`,"
                . "concat(`subdomain`,'.',`tld`) as `domain`,"
                . "`bin`,"
                . "`bid`,"
                . "`price`,"
                . "`currency`,"
                . "`time_added`,"
                . "`time_approved`,"
                . "`time_list_start`,"
                . "`time_list_stop`,"
                . "`seller`,"
                . "`list_status`"
                . " from `" . DB_NAME . "`.`nstock_domains` "
                . " where `list_status`='archived'"
                . " and `time_list_stop`>= DATE_SUB(NOW(), INTERVAL 48 HOUR)"
                . " and `email_sent_list_end`='n'"
                . " and `email` IS NOT NULL"
                . " and `email` !=''"

        ;



        $this->_sendEmailToQuery(
                $query, //the database query. Must include an 'email' field with the 'to' address
                'email-domain-list-notify-ended', //$template_name The name of the template to be used for the email
                '{domain} has ended its Nomstock Ticker Listing', //$subject
                $simulate //$simulate - set to true if want to just output to stdout instead of actually sending email
        );
        }

    /**
     * Send Not Listed Email (Called from Cron)
     *
     * Sends Emails To Approved Listings Whose Names didn't make it out of the queue.
     * This is a wrapper around _sendEmailToQuery()
     *
     * @param boolean $simulate True to output to stdout instead of actually sending Emails.
     * @return void
     */
    public function sendNotListedEmails( $simulate = true ) {

        /*
         * Will find all those email addresses where the listing
         * was approved but never listed due to queue or other reasons
         */

        $query = "select "
                . "`email`,"
                . "concat(`subdomain`,'.',`tld`) as `domain`,"
                . "`bin`,"
                . "`bid`,"
                . "`price`,"
                . "`currency`,"
                . "`time_added`,"
                . "`time_approved`,"
                . "`time_list_start`,"
                . "`time_list_stop`,"
                . "`seller`,"
                . "`approved`,"
                . "`list_status`"
                . " from `" . DB_NAME . "`.`nstock_domains` "
                . " where `list_status`='not listed'"
                . " and `time_added`>= DATE_SUB(NOW(), INTERVAL 72 HOUR)"
                . " and `email_sent_not_listed`='n'"
                . " and `email` IS NOT NULL"
                . " and `email` !=''"

        ;



        $this->_sendEmailToQuery(
                $query, //the database query. Must include an 'email' field with the 'to' address
                'email-domain-list-notify-not-listed', //$template_name The name of the template to be used for the email
                '{domain} wasn\'t listed due to excessive requests', //$subject
                $simulate //$simulate - set to true if want to just output to stdout instead of actually sending email
        );
    }

    /**
     * Send Not Listed Email (Called from Cron)
     *
     * Sends Emails To Approved Listings Whose Names didn't make it out of the queue.
     * This is a wrapper around _sendEmailToQuery()
     *
     * @param boolean $simulate True to output to stdout instead of actually sending Emails.
     * @return void
     */
    public function sendRejectedEmails( $simulate = true ) {

        /*
         * Will find all those email addresses where the listing
         * was approved but never listed due to queue or other reasons
         */

        $query = "select "
                . "`email`,"
                . "concat(`subdomain`,'.',`tld`) as `domain`,"
                . "`approved`,"
                . "`bin`,"
                . "`bid`,"
                . "`price`,"
                . "`currency`,"
                . "`time_added`,"
                . "`time_approved`,"
                . "`time_list_start`,"
                . "`time_list_stop`,"
                . "`seller`,"
                . "`rejected_reason`,"
                . "`list_status`,"
                . "`reviewer_public_comments`"
                . " from `" . DB_NAME . "`.`nstock_domains` "
                . " where `approved`='n'"
 . " and `list_status`='archived'"
                . " and `time_added`>= DATE_SUB(NOW(), INTERVAL 72 HOUR)"
                . " and `email_sent_rejected`='n'"
                . " and `email` IS NOT NULL"
                . " and `email` !=''"

        ;



        $this->_sendEmailToQuery(
                $query, //the database query. Must include an 'email' field with the 'to' address
                'email-domain-list-notify-rejected', //$template_name The name of the template to be used for the email
                '{domain} was not approved for listing on the Nomstock Ticker', //$subject
                $simulate //$simulate - set to true if want to just output to stdout instead of actually sending email
        );
    }

    /**
     * Send Email To Query
     *
     * Sends Emails to a Query , taking the 'to' address from the 'email' field in the query
     *
     * @param $query The MySQL Query that contains at a minimum the following:

      . "`time_added`,"
      . "`time_approved`,"
      . "`time_list_start`,"

     * @return void
     */
    private function _sendEmailToQuery( $query, $template_name, $subject, $simulate = true ) {

        global $wpdb;

        /*
         * Make the query and dump into an array
         */
        $db_records = $wpdb->get_results( $query, ARRAY_A ); // 

        $this->debug()->logVar( '$db_records = ', $db_records );

        /*
         * Create a templates object from Nomstock's Templates Module
         */
        $templates = $this->plugin()->getModule( 'Templates' );

        /*
         * get the template as a string
         */
        $template = $templates->getTemplate( $template_name );

        $tags = array();


        /*
         * Send an email for each record it finds
         */
        foreach ( $db_records as $record ) {

            $tags = $record;



            /*
             * In case the time didn't populate correctly,
             * replace with 'Not Available'
             */

            /*
             * Checks to see if the specified time field is included in the record, and if so, converts its format and returns the array with the time converted to the format we want
             */
            $tags = $this->_reformatTimeField( $tags, 'time_added' );
            $tags = $this->_reformatTimeField( $tags, 'time_approved' );
            $tags = $this->_reformatTimeField( $tags, 'time_list_start' );
            $tags = $this->_reformatTimeField( $tags, 'time_list_stop' );
            if ( array_key_exists( 'bid', $tags ) ) {
                $tags[ 'bid' ] = strtoupper( $tags[ 'bid' ] );
 }
            if ( array_key_exists( 'bin', $tags ) ) {
                $tags[ 'bin' ] = strtoupper( $tags[ 'bin' ] );
 }



            if ( array_key_exists( 'currency', $tags ) ) {
                $tags[ 'currency_symbol' ] = ($tags[ 'currency' ] === 'USD') ? '&#36;' : '&#8364;';
 }


            if ( array_key_exists( 'seller', $tags ) ) {
                $tags[ 'seller' ] = ucwords( $tags[ 'seller' ] );
  }
            $email_result = $this->plugin()->tools()->sendPearEmail(
                    $this->plugin()->EMAIL_FROM_DEFAULT, // $from
                    $tags[ 'email' ], // $to
                    $this->plugin()->tools()->crunchTpl( $tags, $subject ), // $subject
                    $this->plugin()->tools()->crunchTpl( $tags, $template ), //$message body
                    $simulate  //$simulate flag . true to simulate, dumping to stdout, false for sending actual email
            ); //

            if ( $simulate ) {


                echo $email_result;
}

}

    }

    /**
     * Reformat Time Field
     *
     * Checks the $record for a field with name $time_field. If exists, it converts the field to "Not Available" if all zeros. If an actual time, then it converts it the time format that is specified in the configuration. It then returns the modified $record array.
     * 
     *
     * @param array $record An Database Results associative array
     * @param string $time_field The name of the field for the time being reformatted
     * @return void
     */
    private function _reformatTimeField( $record, $time_field ) {


        if ( array_key_exists( $time_field, $record ) ) {
            $record[ $time_field ] = ($record[ $time_field ] === '0000-00-00 00:00:00') ? 'Not Available' : $record[ $time_field ];
            /*
             * Convert Times to Local Time , in format we want
             */
            $record[ $time_field ] = ($record[ $time_field ] === 'Not Available') ? $record[ $time_field ] : $this->plugin()->tools()->getLocalTimeFromUTC(
                            $record[ $time_field ], //$time,
                            $this->TIME_FORMAT//$format
            );
        }
        return ($record);
    }


}
