<?php

/**
 * Utility Module
 *
 * General Utility Functions
 * Add any methods that can be shared across modules here.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * 
 *
 */
class Nomstock_Com_Modules_Tools extends Nomstock_Com_Base_v1c2_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();
    }

    /**
     * Validate Email Address
     *
     * Validates Email Using the filter_var function in php and further filtering it to make sure it has a domain.
     * http://www.electrictoolbox.com/php-email-validation-filter-var-updated/
     * @param string $email The email address to be validated
     * @return boolean True if validation passes, false otherwise
     */
    public function validateEmailAddress( $email ) {
        return filter_var( $email, FILTER_VALIDATE_EMAIL ) && preg_match( '/@.+\./', $email );
}

    /**
     * Reduce Array of Arrays
     *
     * converts an array of the form:
      array(
      array( 'date'=>1/2/2011,'data'=>12),
      array( 'date'=>1/3/2011,'data'=>15),
      array( 'date'=>1/4/2011,'data'=>20),
      array( 'date'=>1/5/2011,'data'=>25)
      )

      array(
      array( '1/2/2011','12'),
      array( '1/3/2011','15'),
      array( '1/4/2011','20'),
      array( '1/5/2011','25')
      )

      This is usful in charting since many charting packages require data points in [x,y] format.

     * 
     * 


     *
     * @param array $array_of_arrays An associative array in the form described in the above example
     * @param string $x_key The first key of the child arrays ('date' in the example)
     * @param string $y_key The second key of the child arrays *'data' in the example)
     *  
     * @return void
     */
    public function reduceArrayOfArrays( $array_of_arrays, $x_key, $y_key ) {
        $reduced_array = array();
        foreach ( $array_of_arrays as $point ) {
            $reduced_array[] = array( $point[ $x_key ], $point[ $y_key ] );
}

        return $reduced_array;
    }

    /**
     * remapAssocArray
     *
     *  Takes an array of the form:

      array(array([date] => 2014-02-11 ,[unique_clicks] => 1 ) ,[date] => 2014-02-12 ,[unique_clicks] => 15 ))
      and converts it to an array where the first value is used as the key,and the second value is used as the value
      ('2014-02-11' => 1 ) , ('2014-02-12' => 1 )

     * @param array $array The array to be re-mapped
     * @param string $key1 The key whose value will become the key of the converted array. In the example, this would be 'date'
     * @param $key2 The key whose value will become the value of the remapped array. In the example, this would be 'unique_clicks'
     * @return void
     */
    public function remapAssocArray( $array, $key1, $key2 ) {
        $this->debug()->logVar( '$array = ', $array );

        foreach ( $array as $element ) {

            $key = $element[ $key1 ];
            $value = $element[ $key2 ];
            $converted_array[ $key ] = $value;

}
        return ($converted_array);
    }

    private $_dbhCache = null; //pdo object so we only use one

    /**
     * mySQL Call Proc
     *
     * Provides a wrapper around calling a mySQL stored procedure to ensure against a 5.2.4 bug that 
     * causes procedure calls to fail.
     * Error:'can't return a result set in the given context'
     * 
     * references:
     * http://stackoverflow.com/questions/1200193/cant-return-a-result-set-in-the-given-context
     * http://php.net/pdo_mysql#69592  //i got empty result set but pointed me in the right direction
     * http://php.net/pdo_mysql#80306 //this worked, but returned 0-indexed and assoc, i edited it so it only returns assoc mimicking $wpdb->get_results(
     * http://www.php.net/manual/en/pdo.connections.php
     * http://www.php.net/manual/en/pdostatement.fetch.php explains about FETCH_ASSOC
     * 
     * @param string $proc The mySQL stored procedure string, including paramaters, but without the call statement. e.g.: "my_procedure_name('my_paramater')"; 
     * @return string The results of the procedure call
     */

    public function mySQLCallProc( $proc ) {
        global $wpdb;
        $query = "call $proc";
        $this->debug()->logVar( '$query = ', $query );
      

        try {

            /*
             * Attempt to call the procedure normally.
             * 
             */
                $this->debug()->logVar( '$query = ', $query );
            $query_result = $wpdb->get_results( $query, ARRAY_A );

            /*
             * Check for a database error
             * and throw an exception if one is found.
             * We can then attempt it again using a workaround.
             */
             $this->debug()->logVar( '$wpdb->last_error = ', $wpdb->last_error );
            if ( $wpdb->last_error !== '' ) {
                throw new Exception( 'Database Error While Calling Procedure' );
}

        } catch ( Exception $e ) {


            /*
             * Clear the exception so we can can check for reoccurrence on our next attempt
             */
            $e = null;
            //  $this->debug()->logVar( '$e->getMessage()  = ', $e->getMessage() ); //don't use debug()->logError or logDatabaseError here since we expect an error here, and normally don't want the debug module's output
            $this->debug()->log( 'Calling Proc failed, likely due to PHP 5.2.4 bug, now create a new pdo object as a workaround' ); //http://stackoverflow.com/questions/1200193/cant-return-a-result-set-in-the-given-context/21757134#21757134 
            try { //attempt to create a new pdo object to workaround php bug

                /*
                 * Create a PDO Object for the connection

                 * 
                 */
$dbh = new PDO( 'mysql:host=' . DB_HOST . ';port=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD, array( PDO::ATTR_PERSISTENT => false ) );  

           /*
            *  And Cache it so we don't keep creating a huge number of connections which will eventually break mysql
            * this code was removed when it started giving errors
          
                if ( is_null( $this->_dbhCache ) ) {
                    $dbh = new PDO( 'mysql:host=' . DB_HOST . ';port=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD, array( PDO::ATTR_PERSISTENT => false ) );  //do not persist connections 
                    $this->_dbhCache = $dbh;
} else{
                    $dbh = $this->_dbhCache;
}
  */


                /*
                 * Test procedure 
                 * $proc="nstock_stats_get_clicks_by_day('mydomain.com')";
                 */
                $this->debug()->logVar( '$proc = ', $proc );
                /*
                 * Prepare and call the procedure.
                 */
                $stmt = $dbh->prepare( "call $proc" );

                $pdoSuccess = $stmt->execute();


                /*
                 * Check for error in pdo but don't raise an exception since it might be due to duplicate entries or other harmless errors
                 */
                if ( $stmt->errorCode() !== '00000' ) {
                    $this->debug()->log( 'PDO Call was not successful, returned error' );
                    $this->debug()->logVar( 'PDO Error Info via $stmt->errorInfo() = ', $stmt->errorInfo() );
                    

} else{

                    $this->debug()->log( 'PDO workaround was successful' );

}

                /*
                 *  fetch all rows into an associative array.
                 */

                $query_result = $stmt->fetchAll( PDO::FETCH_ASSOC ); //FETCH_ASSOC gets results as an assoc array. without it, you'll receive both assoc and 0-indexed array

                $this->debug()->logVar( '$query_result = ', $query_result );





    } catch ( PDOException $e ) {

                $this->debug()->logError( $e->getMessage() );

                $this->debug()->logVar( '$stmt->errorInfo()= ', $stmt->errorInfo() );

    }


    }

        $this->debug()->logVar( '$query_result = ', $query_result );
        
        /*
         * close connection to prevent getting too many connections error
         * or follow this http://stackoverflow.com/a/14113114/3306354
         * http://stackoverflow.com/questions/1046614/do-sql-connections-opened-with-pdo-in-php-have-to-be-closed
  
        
        $stmt->closeCursor();
        $dbh=null;
        $stmt=null;
         *     
         */
        return ($query_result);


    }

    /**
     * Is Empty String
     *
     * Checks if string is empty or null, by default will change the passed argument to a trimmed version.
     *  ref: http://stackoverflow.com/a/381275/3306354
     * @param string &$string The string to be checked
     * @param boolean $trim True will update the referenced variable to trim, false will not
     * @return boolean True if empty, false if not
     */
    public function isEmptyString( &$string, $trim = true ) {

        if ( !is_string($string)) {
            return false;
            
}

        $result = (!isset( $string ) || trim( $string ) === '');

        /*
         * Optionally, remove spaces from the passed argument
         */
        if ( $trim === true ) {
            $string = trim( $string );
}

        return $result;

    }

   

}
