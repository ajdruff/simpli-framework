<?php

/**
 * Utility Base Class
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 */
class Simpli_Frames_Base_v1c2_Plugin_Tools extends Simpli_Frames_Base_v1c2_Plugin_Helper {

    /**
     * Sort Dependent List
     *
     * Sorts a set of items in order of their dependencies on each other. Dependents come after the ones they rely on. Used for enqueuing javascript
     *           $list=array(
      'script1'
      ,'script4'
      ,'script3'
      ,'script6'
      ,'script5'
      ,'script2'
      );
     *   $dependencies=array(
      'script2'=>array('script6')
      ,'script1'=>array('script3','script4')
     * the final result set should contain a list of the handles in the order of dependence . see the inline comments below for a testing example.
     * @param array $list - An associative array containing a complete list of handles to be sorted
     * @param array $dependencies Ann associative array whose keys are a subset of $list, but whose values are an array of handles that it is dependent on.
     */
    public function sortDependentList( $list = array(), $dependencies = array() ) {
        /* testing
          $list=array(
          'script1'
          ,'script4'
          ,'script3'
          ,'script6'
          ,'script5'
          ,'script2'
          );
          $dependencies=array(
          'script2'=>array('script6')
          ,'script1'=>array('script3','script4')

          ,'script1'=>array('script3','script4')

          );

          $sorted_handles=$this->tools()->sortDependentList($list,$dependencies);
          echo '<br> ______________   FINAL RESULT _________';
          echo '<pre>';

          echo '</pre>';

          __ END CODE ___
         *
         * Output from Test Run
         *
          ______________ FINAL RESULT _________

          Array
          (
          [0] => script4
          [1] => script3
          [2] => script6
          [3] => script5
          [4] => script2
          [5] => script1
          )


         *
         */

        /*
         * return if either list or dependencies is empty
         */
        //   if (!is_array($list) || !is_array ($dependencies)){return (array());}
        //   if (empty($list) || empty ($dependencies)){return (array());}
//          echo '<br>list is empty? ' . empty($list);
//          echo '<br>list is  an array ? ' . is_array($list);
//
//          echo '<br>list is empty? ' . empty($dependencies);
//          echo '<br>list is  an array ? ' . is_array($dependencies);
//
//          echo '<pre>';
//
//          echo '</pre>';
//
//                    echo '<pre>';
//
//          echo '</pre>';
        // $dependent_handles = $dependencies;//
        $dependent_handles = array_keys( $dependencies ); //makes the keys in dependencies their own array
        $sorted_handles = array();

        $todo_list = array_flip( $list ); //flip to ensure unqueness and allows us to remove items easily



        while ( count( $todo_list ) > 0 ) {

            foreach ( $todo_list as $handle => $arbitrary ) { //we only care about the index, the handle
                if ( !in_array( $handle, $dependent_handles ) ) { //if the handle does not depend on anything,
                    array_push( $sorted_handles, $handle );  //add it to the final $sorted_handles array
                    unset( $todo_list[ $handle ] ); // and remove it from the todo list
                } else { //if the handle is dependent on others, check to see if its dependencies are in the final list
                    $requirements_met = true; //assume the best, toggle to false if even one requirement is not met

                    $missing_dependency = false; //assume the best, toggle to true if even one missing dependency found
                    foreach ( $dependencies[ $handle ] as $required_handle ) { //check dependent handles
                        if ( !in_array( $required_handle, $sorted_handles ) ) { //if dependent handle is not in final list yet,
                            $requirements_met = false; // then requirement is not met
                        }
                        if ( !in_array( $required_handle, array_keys( $todo_list ) ) && !in_array( $required_handle, $sorted_handles ) ) { //if required_handle isnt on todo list, flag missing dependency or we will loop forever since the handle dependent on it will never be satisfied.
                            $missing_dependency = true;
                        }
                    }
                    if ( $requirements_met === true && $missing_dependency === false ) { //if all the required dependents are in the final list, then
                        array_push( $sorted_handles, $handle );  //add the dependent handle to the final list

                        unset( $todo_list[ $handle ] ); //and remove from todo list
                    } elseif ( $missing_dependency === true ) {


                        unset( $todo_list[ $handle ] ); //if required handle isnt on hte list at all, we cant include the handle that relies on it, so remove it.
                    }
                }
            }
        }


        return ($sorted_handles);
    }

    /**
     * Url to Dir
     *
     * Converts a local url to an absolute directory path. Very useful to determine WordPress locations
     * Usage:
     * to find wordpress admin directory:
     *  $admin_dir= $this->plugin()->tools()->url2dir(admin_url());
     * @param string $url The absolute url to the local file
     * @return string The absolute directory path to the Directory
     */
    public function url2dir( $url ) {

        $url_parts = parse_url( $url );
        return($this->normalizePath( $_SERVER[ 'DOCUMENT_ROOT' ] . $url_parts[ 'path' ] ));
    }

    /**
     * Rebuild Url
     *
     * Returns the current or provided url, adding new or, replacing existing, $_GET url parameters
     *
     * @param array $new_get_args new values for $_GET . Existing values will remain
     * @param string $url The url . if not provided, uses the current url
     * @param boolean $replace_query_vars If false, merges the provided query vars with existing. if false, replaces them.
     * @return string $url The url
     */
    public function rebuildURL( $new_get_args, $url = null, $replace_query_vars = false ) {

        #init
        $old_get_vars = array();


        if ( is_null( $url ) ) {
            $url = $_SERVER[ 'REQUEST_URI' ];
        }

        $url = urldecode( $url );








        $defaults = array(
            'scheme' => null,
            'host' => null,
            'path' => null,
            'query' => null,
        );



        $old_url_parts = $this->screenDefaults( $defaults, parse_url( $url ) ); //creates an array of the different parts of the url
        //$url_parts = array_intersect_key(array_merge($defaults, $url_parts), $defaults); //make sure the indexes we need are there or use their defaults






        /*
         * take the query string in url parts
         * and create an array from it
         * by first splitting it by the ampersand
         * and then iterating through that array to and split its elements by the = sign
         */
        $old_query_parts = explode( '&', $old_url_parts[ 'query' ] ); //creates {'myvar=myval','myvar2=myval2',etc}

        foreach ( $old_query_parts as $old_query_part ) {
            $namevalue = explode( '=', $old_query_part ); //split into {'name'=>var}
            $name = isset( $namevalue[ 0 ] ) ? $namevalue[ 0 ] : null;
            $value = isset( $namevalue[ 1 ] ) ? $namevalue[ 1 ] : null;
            $old_get_vars[ $name ] = $value;
        }


        /*
         * set the new url parts equal to the old one, then start updating it to include the new query member
         */
        $new_url_parts = $old_url_parts;
        /*
         * update query portion of $new_url_parts
         */


        if ( !$replace_query_vars ) {
            $get_args_merged_with_old = array_merge( $old_get_vars, $new_get_args ); //merge existing GET parameters
            $new_url_parts[ 'query' ] = http_build_query( $get_args_merged_with_old );
        } else {
            $new_url_parts[ 'query' ] = http_build_query( $new_get_args ); //replace the query string part with a new query string using the new values
        }













        $result = $this->http_build_url( $new_url_parts ); //rebuild the url with the new parts;


        return $result;
    }

    /**
     * Rebuild Url
     *
     * Returns the current or provided url, adding new or, replacing existing, $_GET url parameters
     *
     * @param array new values for $_GET . Existing values will remain
     * @param string $url The url . if not provided, uses the current url
     * @param boolean $replace_query_vars If false, merges the provided query vars with existing. if false, replaces them.
     * @return string $url The url
     */
    public function rebuildURLOLD( $get_args, $url = null, $replace_query_vars = false ) {
        $url = '/wp-admin/edit.php';
        if ( is_null( $url ) ) {
            $url = $_SERVER[ 'REQUEST_URI' ];
        }

        $url = urldecode( $url );





        $existing_url_parts = parse_url( $url );


        $this->debug()->logVar( '$existing_url_parts = ', $existing_url_parts, true );

        $existing_get_vars = array();
        /*
         * take the query string in url parts
         * and create an array from it
         * by first splitting it by the ampersand
         * and then iterating through that array to and split its elements by the = sign
         */
        $arr_existing_query_parts = explode( '&', $existing_url_parts[ 'query' ] ); //creates {'myvar=myval','myvar2=myval2',etc}

        foreach ( $arr_existing_query_parts as $arr_existing_query_part ) {
            $namevalue = explode( '=', $arr_existing_query_part ); //split into {'name'=>var}
            $name = isset( $namevalue[ 0 ] ) ? $namevalue[ 0 ] : null;
            $value = isset( $namevalue[ 1 ] ) ? $namevalue[ 1 ] : null;
            $existing_get_vars[ $name ] = $value;
        }







        if ( !$replace_query_vars ) {
            $get_args = array_merge( $existing_get_vars, $get_args ); //merge existing GET parameters
        }




        $url_parts[ 'query' ] = http_build_query( $get_args ); //replace the query string part with a new query string using the new values








        $result = $this->http_build_url( $url_parts ); //rebuild the url with the new parts;


        return $result;
    }

    /**
     * screenDefaults
     *
     * Takes an array and takes only those elements whose indexes match one in the defaults array, then fills in any gaps with the default values
     *
     * @param array $defaults An associative array of default name/value pairs
     * @param array $variables An associative array of name/value pairs
     * @return array An array that has only the indexes that are listed in the defaults array and values that are supplied by either defaults or the provided $variables array
     */
public function screenDefaults( $defaults, $array ) {
        return (array_intersect_key( array_merge( $defaults, $array ), $defaults ));
    }

    /**
     * Given an array in the form of parse_url result, builds a url
     *
     * @param array $http_array
     */
    public function http_build_url( $url_parts ) {

        $defaults = array(
            'scheme' => null,
            'host' => null,
            'path' => null,
            'query' => null,
        );

        $url_parts = $this->screenDefaults( $defaults, $url_parts ); //make sure the indexes we need are there or use their defaults




        $scheme = (trim( $url_parts[ 'scheme' ] ) !== '') ? $url_parts[ 'scheme' ] . '://' : '';
        $host = (trim( $url_parts[ 'host' ] ) !== '') ? $url_parts[ 'host' ] : '';
        $url = $scheme . $host . $url_parts[ 'path' ] . '?' . $url_parts[ 'query' ];

        return($url);
    }

    /**
     *
     * Detects shortcode
     *
     *
     */
    public function detectShortcode( $haystack, $shortcode ) {

        global $post;
        $pattern = get_shortcode_regex();

        if ( preg_match_all( '/' . $pattern . '/s', $haystack, $matches ) && array_key_exists( 2, $matches ) && in_array( $shortcode, $matches[ 2 ] ) ) {
            return true;
        }

        return false;
    }

    /**
     * Validate Array Keys
     *
     * Usage: array_validate_keys($test,$valid); // checks that any keys in $test are contained in $valid();
     * Example#1 (returns true) : (array('apple'=>'yes','orange'=>'no'),array('apple','orange'))
     * Example#1 (returns false) : (array('apple'=>'yes','orange'=>'no','carrot'=>'yes'),array('apple','orange'))
     * Note that $valid_keys is always a non-associative array
     *
     * @param array $test An array of key/value pairs whose keys need to be checked to see if they are 'approved' e.g.: array('apple'=>'yes','orange'=>'no','carrot'=>'yes')
     * @param array $valid_keys An array of allowed keys. e.g.: $valid_keys=array('apple','orange');
     *
     * @return boolean True or False if keys are valid.
     */
    public function validateArrayKeys( $test_array, $valid_keys ) {
        $valid_keys_flipped = array_flip( $valid_keys ); // converts values in $valid_options to keys 'js'=>0
        $test_array_and_valid_keys_combined = array_keys( array_merge( $valid_keys_flipped, $test_array ) ); //results in an array of all the valid keys + any differing keys passed in debug. If nothing differs, then the combined array is the same
        $validity_result = ($valid_keys === $test_array_and_valid_keys_combined);
        return ($validity_result);
    }

    /**
     * Recursive Glob
     *
     * Returns an array of files under $dir that have the specified extension.
     * ref:http://stackoverflow.com/a/12172687
     * @param none
     * @return void
     */
    public function getGlobFilesOLD( $dir, $file_pattern, $max_levels = -1, $files = array() ) {

        static $levels = 0;
        $levels++;
        $globFiles = glob( "$dir/$file_pattern" );
        $globDirs = glob( "$dir/*", GLOB_ONLYDIR );

        if ( $max_levels === -1 || $max_levels === true ) {//true for backward compatibility (recursive)
            foreach ( $globDirs as $dir ) {
                $files = $this->getGlobFiles( $dir, $file_pattern, -1, $files );
            }
        }
        if ( !is_bool( $max_levels ) ) { //for backward compat, only do this if not boolean
            if ( $max_levels !== -1 ) {
                $max_levels = intVal( $max_levels );
            }
            if ( $max_levels > 0 && $levels <= $max_levels ) {
                foreach ( $globDirs as $dir ) {
                    $files = $this->getGlobFiles( $dir, $file_pattern, $max_levels, $files );
                }
            }
        }

        foreach ( $globFiles as $file ) {

            $files[] = $file; // Replace '\n' with '<br />' if outputting to browser
            $file = null;
        }

        if ( !empty( $files ) ) {
            $files = array_filter( $files, 'is_file' );


            return $files;
        }
    }

    /**
     * Recursive Glob
     *
     * Returns an array of files under $dir that have the specified extension.
     * ref:http://stackoverflow.com/a/12172687
     * @param none
     * @return void
     */
    public function getGlobFiles( $dir, $file_pattern, $recursive = true, $files = array(), $exclude_pattern = null ) {


        $globFiles = glob( "$dir/$file_pattern" );
        $globDirs = glob( "$dir/*", GLOB_ONLYDIR );

        if ( $recursive ) {
            foreach ( $globDirs as $dir ) {
                $files = $this->getGlobFiles( $dir, $file_pattern, true, $files, $exclude_pattern );
            }
        }



        foreach ( $globFiles as $file ) {

            $files[] = $file; // Replace '\n' with '<br />' if outputting to browser
            $file = null;
        }




        if ( !empty( $files ) ) {
            if ( !is_null( $exclude_pattern ) ) {

                $files = preg_grep( $exclude_pattern, $files, PREG_GREP_INVERT );
            }

            $files = array_filter( $files, 'is_file' );


            return $files;
        }
    }

    /**
     * Make Path Relative
     *
     * Removes base path from longer path. The resulting path will never contain a leading directory separator
     * If base path is not contained in longer path, the longer path will be returned.
     * Paths will be normalized
     * Ref:http://stackoverflow.com/a/6808275
     * @throws Exception
     * @param  $base_path
     * @param  $longer_path
     * @return string normalized relative path
     */
    function getRelativePath( $base_path, $longer_path ) {
        $base_path = $this->normalizePath( $base_path );
        $longer_path = $this->normalizePath( $longer_path );

        $this->debug()->logVar( '$base_path = ', $base_path );
        $this->debug()->logVar( '$longer_path = ', $longer_path );

        if ( false === strpos( $longer_path, $base_path ) ) {
            $this->debug()->log( 'Base Path Not found in longer path, returning longer path' );

            return ($longer_path);
            //throw new Exception("Can not make relative path, base path is not contained in longer path: `" . $base_path . "`, `" . $longer_path . "`");
        }
        $relative_path = substr( $longer_path, strlen( $base_path ) + 1 );
        $this->debug()->logVar( '$relative_path = ', $relative_path );

        return substr( $longer_path, strlen( $base_path ) + 1 );
    }

    /**
     * Normalize Path
     *
     * Normalizes the Path to always use forwards slash and to resolve indiration.
     * This function is a proper replacement for realpath
     * It will _only_ normalize the path and resolve indirections (.. and .)
     * Normalization includes:
     * - directiory separator is always /
     * - there is never a trailing directory separator
     * Ref:http://stackoverflow.com/a/6808275
     * @param  $path
     * @return String
     */
    function normalizePath( $path, $resolve_indirection = false ) {




        $parts = preg_split( ":[\\\/]:", $this->untrailingslashit( $path ) ); // split on known directory separators

        if ( $resolve_indirection ) {


            // resolve relative paths
            for ( $i = 0; $i < count( $parts ); $i +=1 ) {
                if ( $parts[ $i ] === ".." ) {          // resolve ..
                    if ( $i === 0 ) {
                        throw new Exception( "Cannot resolve path, path seems invalid: `" . $path . "`" );
                    }
                    unset( $parts[ $i - 1 ] );
                    unset( $parts[ $i ] );
                    $parts = array_values( $parts );
                    $i -= 2;
                } else if ( $parts[ $i ] === "." ) {    // resolve .
                    unset( $parts[ $i ] );
                    $parts = array_values( $parts );
                    $i -= 1;
                }
                if ( $i > 0 && $parts[ $i ] === "" ) {  // remove empty parts
                    unset( $parts[ $i ] );
                    $parts = array_values( $parts );
                }
            }
        }

        return implode( "/", $parts );
    }

    /**
     * In Include Path
     *
     * Determines whether a file is within the include path
     *
     * @param string $find The partial path to the file
     * @return boolean Whether the path can be included
     */
    public function inIncludePath( $find ) {


        $paths = explode( PATH_SEPARATOR, get_include_path() );
        $found = false;
        foreach ( $paths as $p ) {
            $fullname = $p . DIRECTORY_SEPARATOR . $find;
            if ( is_file( $fullname ) ) {
                $found = $fullname;
                break;
            }
        }
        return $found;
    }

    /**
     * Crunch Template
     *
     * Replaces the key's tokens within the $template with the array's value for that key
     * A key token is simple the key with a bracket around it.
     * @example
     * $tags=array('name'=>'Joe','role'=>'admin');
     * $template='{NAME} is a great {ROLE}';
     * $html=crunchTpl($tags,$template);
     * $html is 'Joe is a great admin';
     *
     *
     *
     * @param array $tags An associative array containing tokens as indexes and replacements as its values.
     * @param string $template A string containing tokens to be replaced
     * @return void
     */
    public function crunchTpl( $tags, $template ) {

        /*
         * Check that $tags is array
         * and set to empty array so as not to throw any errors
         */
        if ( !is_array( $tags ) ) {

            $tags = array();
}
        $this->debug()->t();

//        if (stripos($template, 'action') !== false) {
//            $this->debug()->logVar('$template = ', $template, true);
//            //$this->debug()->stop(true);
//        }

        /*
         * add a bracket around each key
         */
        foreach ( $tags as $key => $value ) {
            if ( is_array( $value ) || is_object( $value ) ) {
                $value = '<pre>' . print_r( $value, true ) . '</pre>';
            }

            $tags[ '{' . $key . '}' ] = $value;
            unset( $tags[ $key ] );
        }


        $html = str_ireplace( array_keys( $tags ), array_values( $tags ), $template );
        return $html;
    }

    /**
     * Strip HTML Whitespace
     *
     * Returns htmls without any unneccessary whitespace. Should not affect display strings
     * There are times this is required so that whitespace doesnt impact layout.
     * Be carefule when using this function! it will hang on some strings, especially if very long. try to limit its usage.
     * ref:http://stackoverflow.com/a/5324014
     * @param none
     * @return void
     */
    public function scrubHtmlWhitespace( $html ) { //
        //ini_set("pcre.recursion_limit", "16777");  // 8MB stack. *nix //you can try using this, but better just to use small strings
        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';
        $html = preg_replace( $re, " ", $html );
        if ( $html === null )
            exit( "PCRE Error! File too big.\n" );
        return $html;
    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function backtrace() {
        /*
         * A simple backtrace
         */

        $defaults = array(
            'file' => '',
            'line' => '',
            'class' => '',
            'function' => '',
            'args' => array(),
        );
        /*
         * get the backtrace
         */

        $arr_btrace = debug_backtrace();
        array_shift( $arr_btrace );
        // array_shift($arr_btrace);
        /*
         * get where the debug statement was located
         */



        $ds_line = (isset( $arr_btrace[ 0 ][ 'line' ] ) ? $arr_btrace[ 0 ][ 'line' ] : '');
        $ds_file = (isset( $arr_btrace[ 0 ][ 'file' ] ) ? $arr_btrace[ 0 ][ 'file' ] : '');
        $ds_class = (isset( $arr_btrace[ 1 ][ 'class' ] ) ? $arr_btrace[ 1 ][ 'class' ] : '');
        $ds_method = (isset( $arr_btrace[ 1 ][ 'function' ] ) ? $arr_btrace[ 1 ][ 'function' ] : '');


        /*
         * iterate through the loop so we can simplify each trace
         */

        foreach ( $arr_btrace as $key => $trace_properties ) {

            $trace_properties = array_intersect_key( array_merge( $defaults, $trace_properties ), $defaults ); //make sure the indexes we need are there or use their defaults
            $traces[] = $trace_properties;
        }
        $content = 'Simplified debug_backtrace() <pre>' . print_r( $traces, true ) . '</pre>';

        echo $content;
    }

//    /**
//     * Get Temp File
//     *
//     * Writes a string to a temporary file and returns the path to the temp file.
//     * Useful when functions expect a file path, not a string.
//     *
//     * ref: http://stackoverflow.com/a/14976027
//     * @param none
//     * @return void
//     */
//    public function getTempFile($string) {
//
//
//        $tmpfname = tempnam(sys_get_temp_dir(), $this->plugin()->getSlug());
//
//        $handle = fopen($tmpfname, "w");
//        fwrite($handle, $string);
//        fclose($handle);
//
//
//
//        /*
//         * create a temporary file
//         */
//        $tempHandle = tmpfile();
//        /*
//         * write the string to it
//         */
//        fwrite($tempHandle, $string);
//        /*
//         * return its path
//         */
//        $metaDatas = stream_get_meta_data($tempHandle);
//        $tmpFilename = $metaDatas['uri'];
//
//
//      //  echo file_get_contents($tmpFilename);
//
//        return $metaDatas['uri'];
//    }

    /**
     * New Line to Break
     *
     * Returns string with '<br />' or '<br>' inserted after all newlines (\r\n, \n\r, \n and \r).
     * A replacement for PHP's nl2br which doesnt seem to always work!
     *
     * @param string $string The input string
     * @param boolean $is_xhtml Whether to use XHTML compatible line breaks or not ( <br/> )
     * @return string The string with all new lines replaced with HTML equivilent of breaks
     */
    public function nl2br( $string, $is_xhtml = true ) {

        $break = ($is_xhtml) ? '<br/>' : '<br>';
        $result = str_replace( array( "\r\n", "\r", "\n" ), $break, $string );

        return $result;
    }

    /**
     * Lines to Array (or a more generic version of parse_str() )
     *
     * Creates an associative array of a string that contains name value pairs that are themselves separated by new lines ( or other characters). The result is an associative array where the indexes are the names.
     * Usage:
     * $string=
     * apple | red
     * grape | green
     *
     * $result=array('apple=>red','grape'=>'green')
     *
     * Note that this
     *
     * @param string $string The input string
     * @param $line_delimiter The new line characters separating each line. These can be new lines, which must be surrounded by double quotes, or they can be any other character that you want to divide the name value pairs, such as an ampersand in the case of parsing a query string.
     *
     * @param string $name_delimiter The delimiter used to segregate name from value
     * @return array The associative array
     */
    public function lines2array( $string, $line_delimiter = array( "\r\n", "\r", "\n" ), $name_delimiter = '|' ) {


        $normalized_lines = str_replace( $line_delimiter, "\n", $string );

        $array_lines = explode( "\n", $normalized_lines ); // now we have 'name|value'

        $result_array = array();
        foreach ( $array_lines as $line ) {
            $temp_array = explode( $name_delimiter, $line );
            if ( isset( $temp_array[ 1 ] ) ) {
                $result_array[ trim( $temp_array[ 0 ] ) ] = trim( $temp_array[ 1 ] );
            }
        }

        return $result_array;
    }

    /**
     * Parse String
     *
     * A replacement for php's parse_str function, but allowing you to specify delimiters. Defaults will act just like parse_str, assuming the string is delimited similarly as a query string with ampersands separating name value pairs, and within each pair, the name is separated by an equal sign from its value.
     * This is simply a wrapper around lines2array to account for the user's familiarity with the php builtin.
     *
     * @param string $string The input string
     * @param $pair_delimiter The character separating each pair of name/values. In a query stirng, this is the ampersand, which is the default.     *
     * @param string $name_delimiter The delimiter used to segregate name from value
     * @return array The associative array
     */
    public function parse_str( $string, $pair_delimiter = '&', $name_delimiter = '=' ) {
        return($this->lines2array( $string, $pair_delimiter, $name_delimiter ));
    }

    /**
     * Get Query Var From Url
     *
     * Returns the value of the Query Variable that is contained within a url
     *
     * @param string $query_var The name of the query variable
     * @param $url The url you need to retrieve the query variable's value from
     * @return string The value of the query variable. Null if it doesnt appear in the url
     */
    public function getQueryVarFromUrl( $query_var, $url ) {

        /*
         * split the url by the first question mark
         */
        $query_array = explode( '?', $url, 2 );

        /*
         * if no question mark, then make sure we don't error out with a 'no index' error
         */
        $query_string = (isset( $query_array[ 1 ] )) ? $query_array[ 1 ] : $query_array[ 0 ];

        /*
         * use our parse_str method to get an array of name value pairs
         */
        $query_vars_array = $this->plugin()->tools()->parse_str( $query_string );

        $this->debug()->logVar( '$query_vars_array = ', $query_vars_array );

        $result = (isset( $query_vars_array[ $query_var ] )) ? $query_vars_array[ $query_var ] : null;

        return $result;
    }

    /**
     * Html To Text
     *
     * A very crude attempt at changing html to text. Does not attempt to preserve formatting except for line breaks
     * Just replaces all block elements with a new line character, and strips all other html tags.
     * A decent reversal of nl2br since you can limit replacements to just br
     *
     * @param string $string The input string
     * @param string $new_line The replacement you want for new line. In some cases, you might want to replace with something like {NEW_LINE} and then
     * use a template replacement downstream to complete the transformation. This is especially useful for forwarding to javascript since its difficult
     * to avoid syntax errors without resorting to a template replacement just before output.
     * @param array $tags The array of tags you want replaced with new line. Do not surround with opening or closing brackets

     * @return string The string with all new lines replaced with HTML equivilent of breaks
     */
    public function html2text( $string, $new_line = "\n\r", $tags = array( 'br', 'div', 'p', 'li', 'ol', 'ul' ) ) {


        foreach ( $tags as $tag ) {
            if ( $tag === 'br' ) {
                $pattern = '/\<[\s]*br[\s]*[\/]*[\s]*>/'; //handles <br/> <br>  and all variants
            } else {
                $pattern = '/\<[\s]*' . $tag . '[\s]*[\s]*>/';  //handles opening tags <p>,<div>, etc. assumes each is a block element.
            }

            $string = preg_replace( $pattern, $new_line, $string );
        }

        $string = strip_tags( $string );

        return $string;
    }

    /**
     * Is Screen
     *
     * Checks whether the current admin screen is the one you want
     * Requires that this be invoked at any time after the 'current_screen' action. one way to do this on an init function is to check of 'get_current_screen' function exists or is null, and if not, to add an action that calls the current function where your check appears. See the Post module's initModuleAdmin method for an example. below is another example for function hookLoadOptions
      function hookLoadOptions(){
      if ((!function_exists('get_current_screen') || (get_current_screen()===null))){
      $this->debug()->t();

      add_action('current_screen',array($this,'hookLoadPostOptions'));
      return;

      }

      }


      Note that it will return false if the post type passed does not match the post type of the screen you are looking for. If you dont care which post type is matched, then pass null, and it will return true regardless of post type as long as the screen matches.
     *
     * @param string $screen_id Identifies which screen we want. Does not necessarily match current_screen->id. See Switch statement for current list.
     * @param string $post_type Post type that you want to have matched e.g.: 'post','page' or null (for all post types)
     * @param boolean $debug Will print our the entire screen object
     * @return boolean
     */
    public function isScreen( $screen_id, $post_type = null ) {
        $this->debug()->t();

#init
        $combined_result = false;
        $debug_message = '';

        $result = false;

        /*
         * Each screen has certain parameters ( base,id,action) that change depending on the admin page. check them against
         * known values and return result. If no post type is provided, then set the post type check to always be true, and remove the post_type check for isEdit and isAdd
         */

        if ( !function_exists( 'get_current_screen' ) ) {
            $this->debug()->logError( 'Current Screen isnt available, so cant use isScreen(), returning false' );
            return false;
        }
        $current_screen = get_current_screen();





        $this->debug()->logVar( '$current_screen = ', $current_screen );

        /*
         * if post type parameter is null, just set it to the same as the screen.
         * that way, our checks will still work for all post types
         */
        if ( is_null( $post_type ) ) {
            $post_type = $current_screen->post_type; //need to define so subsequent isList check works.
            $isPostType = true; //if post type is null, then thats really saying this check is for all post types.
            $debug_message_post_types = ' any post type ';
        } else {
            /*
             * if our post type parameter is not null, we check the current_screen post type to see if it matches
             *
             * If the current_screen is not defined (as in the event of a custom editor) , check to see if there is a post type within the url by using get{pstTypeQueryVar
             */

            if ( is_null( $current_screen->post_type ) ) {

                $isPostType = $this->plugin()->post()->getPostTypeRequestVar() === $post_type;
            } else {
                $isPostType = $current_screen->post_type === $post_type;
            }


            $debug_message_post_types = ' the ' . $post_type . ' post type ';
        }

        $isList = (($current_screen->base === 'edit') && ($current_screen->id === 'edit-' . $post_type) && ($current_screen->action === ''));

        /*
         * Check for a Custom Editor by checking for the 'edit_post' value in our query variable
         */
        $isCustomEditScreen = $this->getRequestVar( $this->plugin()->QUERY_VAR ) === $this->plugin()->QV_EDIT_POST;


        $isEditScreen = ($current_screen->base === 'post' && $current_screen->action === ''); //base will always be post regardless of post type. action will always be an empty string.


        $isAddScreen = ($current_screen->base === 'post' && $current_screen->action === 'add');

        /*
         * Check for a Custom Add Page by checking for the 'add_post' value in our query variable
         */
        $isCustomAddScreen = $this->getRequestVar( $this->plugin()->QUERY_VAR ) === $this->plugin()->QV_ADD_POST;



        if ( !is_array( $screen_id ) ) {
            $screen_id = array( $screen_id );
        }


        foreach ( $screen_id as $screen ) {


            switch ( $screen ) {
                case 'list': // the listing page for the post type provided
                    $result = ($isList && $isPostType) ? true : false;
                    $debug_message = 'Listing Screen';
                    break;

                case 'add': // the 'add new' page for the post type provided

                    $result = ($isAddScreen && $isPostType) ? true : false;

                    $debug_message = 'Add Screen';
                    break;
                case 'edit-add': //will return true if on either the 'edit' or 'add' page for the post type provided

                    $result = (($isEditScreen && $isPostType) || ($isAddScreen && $isPostType)) ? true : false;
                    $debug_message = 'Edit or Add Screen';

                    break;

                case 'edit': // the post editor page for the post type provided

                    $result = ($isEditScreen && $isPostType) ? true : false;
                    $debug_message = 'Edit Screen';
                    break;



                case 'custom_add': // the 'add new' page for the post type provided

                    $result = ($isCustomAddScreen && $isPostType) ? true : false;

                    $debug_message = 'Custom Add Screen';
                    break;
//            case 'CustomEdit-CustomAdd': //will return true if on a custom edit or custom add page for the post typeprovided
//
//                $result = (($isCustomEditScreen && $isPostType) || ($isCustomAddScreen && $isPostType)) ? true : false;
//                $debug_message = 'Custom Edit or Custom Add Screen';
//
//                break;

                case 'custom_edit': // the post editor page for the post type provided

                    $result = ($isCustomEditScreen && $isPostType) ? true : false;
                    $debug_message = 'Custom Edit Screen';
                    break;







                case 'plugins-list': //the plugins listing page
                    $result = ($current_screen->base === 'plugins');
                    $debug_message = 'Plugin Listing Screen';
                    break;


                default:
                    $result = false;
                    $debug_message = '<ERROR: Could Not Find Screen ID >' . $screen;
                    $this->debug()->logError( 'Unable to verify screen, could not find screen id ' . $screen );
                    break;
                //todo: add more here (media,comments,etc)
            }
            $previous_result = $result;
            $combined_result = $combined_result | $previous_result;
        }


        $debug_result = ($combined_result) ? ', and it is ' : ', and it is NOT ';
        $this->debug()->log( 'Checked to see if this was the ' . $debug_message . ' for ' . $debug_message_post_types . $debug_result );

        $this->debug()->logVars( get_defined_vars() );
        /*
         * for some reason, you have to cast to boolean
         * for the result to be recognized as boolean
         */
        return(( bool ) $combined_result);
    }

//    /**
//     * Get Post
//     *
//     * Gets the Post object from a wordpress request. if no object available,
//     * returns null. although you can try to use global $post, there are times
//     * when the post is only available by create the object from the post id, passed
//     * as a $_GET variable.
//     *
//     *
//     * @param none
//     * @return void
//     */
//    public function getPost() {
//        $this->debug()->t();
//        global $post;
//
//
//
//        if (is_object($post)) {
//            $this->debug()->log('Post already in global $post object');
//            return $post;
//        }
//
//        /*
//         * attempt to get post id from the GET variables or, if its an editor, get it via the getEditPostID method
//         */
//        $post_id_query_var = (isset($_GET['post'])) ? $_GET['post'] : null;
//
//
//        if (!is_null($post_id_query_var)) {
//            $this->debug()->log('Post taken from query var');
//            return get_post($post_id_query_var);
//        } elseif (!is_null($post_id = $this->plugin()->tools()->getEditPostID())) {
//            $this->debug()->log('Found post Id using getEditPostID()');
//            $post = get_post($post_id);
//            return $post;
//        } else {
//
//            $this->debug()->log('Checked query and post vars, still not found, returning null');
//            return null;
//        }
//        return $post;
//    }
//    /**
//     * Get Edit Post ID
//     *
//     * Returns the post id being edited or created. Checks all the most common places the the $post->id is provided
//     * during a post editor form submission
//     *
//     * @param none
//     * @return string The id of the post being edited or created.
//     */
//    public function getEditPostID() {
//        $this->debug()->t();
//
//        #init
//        $post_id = null;
//
//
//        /*
//         * Check $_POST['post_ID']
//         * When editing a WordPress Post, the editor submits post_id using
//         * the $_POST form field post_ID
//         *
//         */
//
//        $post_id = (isset($_POST['post_ID'])) ? $_POST['post_ID'] : null;
//
//        if (!is_null($post_id)) {
//            $this->debug()->logVar('Found $_POST[\'post_ID\'], $post_id=', $post_id);
//            return $post_id;
//        }
//
//
//        /*
//         * Check $_GET['post]
//         * The WordPress Post Editing page embeds the post id in the $_GET request during an edit,
//         * and the Custom Post Editor also embeds the post id in the $_GET request during the 'Add New' redirect to the Editor a
//         *
//         */
//        $post_id = $this->getRequestVar('post');
//
//        if (!is_null($post_id)) {
//            $this->debug()->logVar('Found post_id in $_GET, $post_id=', $post_id);
//            return $post_id;
//        }
//        /*
//         * Check $_POST['_wp_http_referer']
//         * Ajax requests have access to the _wp_referer $_POST variable
//         * Which represents the $_GET variables contained in the Editor page
//         * from which the ajax request was made from
//         *
//         * Use the getQueryVarFromUrl method to retrieve the query variable from the _wp_http_referer string
//         */
//
//        $post_id = (isset($_POST['_wp_http_referer'])) ? $this->plugin()->tools()->getQueryVarFromUrl('post', $_POST['_wp_http_referer']) : null;
//        if (!is_null($post_id)) {
//            $this->debug()->logVar('Found post_id in _wp_http_referer, $post_id=', $post_id);
//            return $post_id;
//        }
//        /*
//         * if still null, then its probably a custom edit page, which embeds it in the _simpli_forms_referer_url
//         *
//         * _simpli_forms_referer_url
//         */
//
//        $post_id = (isset($_POST['_simpli_forms_referer_url'])) ? $this->plugin()->tools()->getQueryVarFromUrl('post', $_POST['_simpli_forms_referer_url']) : null;
//        if (!is_null($post_id)) {
//            $this->debug()->logVar('Found post_id in _ajax_referal_url, $post_id=', $post_id);
//            return $post_id;
//        }
//
//
//
//        $this->debug()->log('Couldnt find post id anywhere, setting it to null');
//        return $post_id;
//
//        /*
//         * You can get rid of the remaining when you've tested everything.
//         */
//
//
//        /*
//         * if the post_id is in the $_GET query string of the editing page,
//         * an ajax request can find it in _wp_http_referer
//         */
//        if (!isset($_POST['_wp_http_referer'])) {
//            $this->debug()->log('Cant save post options with ajax - _wp_http_referer does not exist, so can\'t determine post id.');
//            $success = false;
//        } else {
//
//
//            /*
//             * If saving the option from a new post page, WordPress sends
//             * the new post_id in $_POST['post_ID'])
//             * Check for $_POST['post_ID'])
//             * attempt to get the $post_id from the post parameters. $post_id is set if its a new post...
//             */
//            $post_id = (isset($_POST['post_ID'])) ? $_POST['post_ID'] : null;
//
//            /*
//             * If that didnt work, then assume its an edit page and get it from the referrer
//             */
//            if (is_null($post_id)) {
//                /*
//                 * split the referer by the question mark so we get the query variables
//                 *
//                 */
//
//
//
//
//                //     $wp_http_referer_query_array = explode('?', $_POST['_wp_http_referer'], 2);
//
//                /*
//                 * if no question mark, then make sure we don't error out with a 'no index' error
//                 */
//                //   $wp_http_referer_query_string = (isset($wp_http_referer_query_array[1])) ? $wp_http_referer_query_array[1] : $wp_http_referer_query_array[0];
//
//                /*
//                 * use our parse_str method to get an array of name value pairs
//                 */
//                //   $wp_http_referer = $this->plugin()->tools()->parse_str($wp_http_referer_query_string);
//                ///   $this->debug()->logVar('$wp_http_referer = ', $wp_http_referer);
//                //   $post_id = $wp_http_referer['post'];
//
//                $post_id = $this->plugin()->tools()->getQueryVarFromUrl('post', $_POST['_wp_http_referer']);
//            }
//        }
//    }
//    /**
//     * Get Query Var
//     *
//     * Returns the value of the query variable if it is in the url, if it isnt, returns null. Similar to WordPress get_query_var but works in admin and with non-white listed query variables. Main advantage is that it saves you from checking whether its set first, allowing you to a direct comparison.
//     *
//     * @param string $query_var
//     * @return string
//     */
//    public function getRequestVar($query_var) {
//
//        if (isset($_GET[$query_var])) {
//            return $_GET[$query_var];
//        } else {
//            return null;
//        }
//    }
    /**
     * Get Word From Slug
     *
     * Takes a slug like my_slug and converts it to MySlug
     *
     * @param string $text Slug (any lowercase words connected by underscores)
     * @return string
     */
    public function getWordFromSlug( $slug, $wordpress_slug = false, $separator = null ) {
        if ( !$wordpress_slug ) {//  Not like WP
            if ( is_null( $separator ) ) {
                $separator = '_';
            }
        } else {
            $separator = '_';
        }

        $slug_array = explode( $separator, $slug );
        foreach ( $slug_array as $key => $value ) {
            $lvalue = strtolower( $value );
            $ucvalue = ucwords( $lvalue );
            $slug_array[ $key ] = $ucvalue;
        }

        $this->debug()->logVar( '$slug_array = ', $slug_array );


        //   $slug_array = array_filter($slug_array, 'ucwords');
        $word = implode( $slug_array, '' );

        $this->debug()->logVar( '$word = ', $word );

        return $word;
                            }

    /**
     * Get Slug from Word
     *
     * Takes a word like MyWord and makes it into my_word. Optionally creates WordPress like slugs.
     * Usage:
     * To Create a 'simpli' type , slug:
     * getSlugFromWord('MyAwesomePlugin');  // gives 'my_awesome_plugin'
     *
     * To create a WordPress slug:
     * getSlugFromWord('MyAwesome Plugin',true) //gives 'MyAwesome-Plugin'
     *
     *
     * @param string $text Text to turn into a slug
     * @param boolean $wordpress_slug Whether to use WordPress's slug conversion. This will turn 'My Slug' to 'my-slug'
     * @param string $regex Regex Pattern to be used for replacement. If not provided, will use one that identifies
     * capitalized word groups.
     * @param separator Seperator only used when $likeWP=false.
     * @return void
     */
    public function getSlugFromWord( $text, $wordpress_slug = false, $regex = null, $separator = null ) {

        $this->debug()->logVar( '$text = ', $text );

        if ( !$wordpress_slug ) {//  Not like WP
            /*
             * then use the regex and separators to create the slug
             */
            if ( is_null( $separator ) ) {
                $separator = '_';
            }
            if ( is_null( $regex ) ) {
                /*
                 * set regex pattern to find all capatilized word groups like 'MyPlugin'
                 */
                $regex = '/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/';
            }


            $slug = strtolower( preg_replace( $regex, $separator . '$1', $text ) );
        } else {
            $slug = sanitize_title( $text ); //users wordpress own conversion
        }
        return $slug;
    }

    /**
     * Get Request Variable
     *
     * Returns the value of the variable $_REQUEST[$var]
     * $_REQUEST contains $_GET,$_POST, and cookies
     * Will return null if the variable does not exist in the array
     * Similar to WordPress get_query_var but works in admin and with non-white listed query variables. Main advantage is that it works with $_POST variables as well, and saves you from checking whether its set first, allowing you to a direct comparison.
     * Ref:http://php.net/manual/en/reserved.variables.request.php
     *
     * @param none
     * @return void
     */
    public function getRequestVar( $request_var_in ) {
        //$this->debug()->setMethodFilter( __FUNCTION__, false );
        $this->debug()->logVar( '$request_var_in = ', $request_var_in );
        $this->debug()->logVar( '$_REQUEST = ', $_REQUEST );

        $request_var = trim( $request_var_in ); //trim it to ensure inadvertent spaces dont mess up value comparisons
        if ( array_key_exists( $request_var, $_REQUEST ) ) {
            return $_REQUEST[ $request_var ];
        } else {

        
/*
 * if we couldn't find the query variable, the key
 * might be in a multidimensional format, like [widget]['blurbit']['description'] we we use getArrayValue to make another attempt at finding its value. if no value found, the method wil return null
 */
            $result = $this->getArrayValue( $_REQUEST, $request_var );
            $this->debug()->logVar( '$result = ', $result );
     
        return $result;
        }
    }

    /**
     * Get Query Variable
     *
     * Returns the value of the variable $_GET[$var] or if it can be retrieved using get_query_var()

     * Will return null if the variable does not exist in the array
     * Similar to WordPress get_query_var but works in admin and with non-white listed query variables. Main advantage is that it works with $_POST variables as well, and saves you from checking whether its set first, allowing you to a direct comparison.
     * Ref:http://php.net/manual/en/reserved.variables.request.php
     *
     * @param none
     * @return void
     */
    public function getQueryVar( $var ) {
        $query_var = trim( $var ); //trim it to ensure inadvertent spaces dont mess up value comparisons
        if ( isset( $_GET[ $query_var ] ) ) {
            return $_GET[ $query_var ];
        } else {

            if ( get_query_var( $query_var ) !== '' )
            {
                return get_query_var( $query_var );
            } else
            {


                return null;
            }
        }
    }

    /**
     * Start Gzip Buffering
     *
     * Starts an output buffering session with gzip enabled.
     * Avoids the 'cannot be used twice' error by first checking
     * if it has already been enabled
     * Ref:http://stackoverflow.com/questions/6010403/how-to-determine-wether-ob-start-has-been-called-already
     *
     * @param none
     * @return void
     */
    public function startGzipBuffering() {

        if ( !in_array( 'ob_gzhandler', ob_list_handlers() ) ) {
            ob_start( 'ob_gzhandler' );
        }
    }

    protected $_is_ajax = null;

    /**
     * Is Ajax Request
     *
     * Checks to see if the current url request is an ajax request. Caches the result after the first request.
     *
     * @param none
     * @return boolean
     */
    public function isAjax() {
        if ( is_null( $this->_is_ajax ) ) {
            if ( isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) === 'xmlhttprequest' ) {
                $this->_is_ajax = true;
            } else {
                $this->_is_ajax = false;
            }
        }
        return $this->_is_ajax;
    }

    /**
     * Get Strings with Substring
     *
     * Search array elements for an array of substrings and returns an array of elements that contain the substring.
     * taken from : http://psoug.org/snippet/Search-array-elements-for-a-substring_923.htm
     * @param $substrings An array of substrings to match against
     * @param $strings The array of strings to match
     * @return an array of the elements that contain the substrings
     */
    public function getStringsWithSubstring( $substrings, $strings ) {



        /*
         * Method 1
         *
         */
        //   $method = 2;
        //    if ($method === 1) {
        $substrings = ( array ) $substrings;
        $matches = array();

        foreach ( $strings as $string ) {

            foreach ( $substrings as $substring ) {

                if ( stripos( $string, $substring ) !== false ) {
                    array_push( $matches, $string );
                }
            }
        }
        if ( count( $matches ) === 0 ) {
            return (array());
        } else {
            $this->debug()->logVar( '$matches = ', $matches );
            return ($matches);
        }
        //   }
//        if ($method === 2) {
//            $found = array();
//
//            // cast to array
//            $needle = (array) $substrings;
//
//            // map with preg_quote
//            $needle = array_map('preg_quote', $substrings);
//
//            // loop over  array to get the search pattern
//            foreach ($needle AS $pattern) {
//                if (count($found = preg_grep("/$pattern/", $strings)) > 0) {
//                    $this->debug()->logVar('$found = ', $found);
//                    return $found;
//                }
//            }
//
//            // if not found
//            return false;
//        }
    }

    /**
     * Get Method Names of a class
     *
     * Uses reflection to get an array of method names for a class
     *
     * @param string $className The name of the class that you want to get methods for
     * @param int $filter, any filters that you want to pass per http://php.net/manual/en/reflectionclass.getmethods.php
     * ref:  http://stackoverflow.com/a/3712754
     * @return void
     */
    function getMethodsNames( $className, $filters = null ) {

        $reflector = new ReflectionClass( $className );
        if ( !is_null( $filters ) ) {

            $methods_object = $reflector->getMethods( $filters );
        } else {
            $methods_object = $reflector->getMethods();
        }


        $methodNames = array();
        $lowerClassName = strtolower( $className );
        foreach ( $methods_object as $method ) {
            if ( strtolower( $method->class ) == $lowerClassName ) {
                $methodNames[] = $method->name;
            }
        }
        return $methodNames;
    }

    /**
     * Shorten Slug
     *
     * Shortens a 2 word slug by taking the first character of the first slug
     * and adding it to the last part of the slug.
     * Simpli_Hello becomes shello
     * Assumes a 2 word slug
     *
     * @param string $slug 2 words separated by underscores
     * @return string An abbreviated version of the slug
     */
    public function shortenSlug( $slug ) {

        $parts = explode( '_', $slug );


        $parts[ 'prefix' ] = (isset( $parts[ 0 ] )) ? $parts[ 0 ] : '';
        $parts[ 'suffix' ] = (isset( $parts[ 1 ] )) ? '_' . $parts[ 1 ] : '';


        $prefix_first_character = substr( $parts[ 'prefix' ], 1, 1 );
        return $prefix_first_character . $parts[ 'suffix' ];
    }

    /**
     * Shorten Slug More
     *
     * concatenates the first character of each part of the slug.
     * my_plugin becomes mp . Assumes a 2 word slug.
     *
     * @param string $slug 2 words separated by underscores
     * @return string An abbreviated version of the slug
     */
    public function shortenSlugMore( $slug ) {

        $parts = explode( '_', $slug );


        $parts[ 'prefix' ] = (isset( $parts[ 0 ] )) ? $parts[ 0 ] : '';
        $parts[ 'suffix' ] = (isset( $parts[ 1 ] )) ? $parts[ 1 ] : '';

        $prefix_first_character = substr( $parts[ 'prefix' ], 0, 1 );
        $suffix_first_character = substr( $parts[ 'suffix' ], 0, 1 );


        return $prefix_first_character . $suffix_first_character;
    }

    /**
     * Get Array Column
     *
     * Returns all the array elements in $array that have a specific associative index
     *
     * @param none
     * @return void
     */
    public function getArrayColumn( $array, $column ) {


        $ret = array();
        foreach ( $array as $row )
            $ret[] = $row[ $column ];
        return $ret;
}

    /**
     * Get Db Result Indexed To Column
     *
     * Returns an WordPress Database Query Result as an associative array, the
     * indexes of which are the values of the field represented by the $column paramater
     * 
     * Normally, results would return indexed to the primary index.
     * So instead of getting something like this :
     * array[0]=array('first_name'=>'joe','last_name'=>'smith');
     * array[1]=array('first_name'=>'jim','last_name'=>'jones');
     * 
     * You would get something like this : 
     * 
     * 
     * getDbResultIndexedToColumn( $query,'last_name' )
     * 
     * array['smith']=array('first_name'=>'joe','last_name'=>'smith');
     * array[1]=array('jones'=>'jim','last_name'=>'jones');
     * 
     * Caution: To work without overwriting , the values provided by $column must be unique for each record.
     * 
     * Example: 
     *
     * @param none
     * @return void
     */
    public function getDbResultIndexedToColumn( $query, $column ) {
        global $wpdb;
        $dbresult = $wpdb->get_results( $query, ARRAY_A );

        /*
         * Extract the values of each element that has $column as its index
         * and add them to the $keys array
         */

        $keys = $this->getArrayColumn( $dbresult, $column );

        /*
         * Combine the keys with the original array
         */
        $dbresult_indexed_to_column = array_combine( $keys, $dbresult );

        return $dbresult_indexed_to_column;

    }

    /**
     * Get Database Column
     *
     * Gets all the unique values of a given database field, and returns them as an array. This provides an array ready  to be used* for a dropdown,radio, or checkbox element within the Simpli Forms addon. The array is in the form of ['value']=Value
     * * Example Usage:
     * $this->plugin()->tools()->getDbColumn( 
      'simpli_forms',//$table,
      'form_name',//$field,
      null,//$query=null,
      true,//$assoc=true,
      true //$wordify=true
      );
     * 
     * For a column that contains multiple rows but with only 2 values, 'my_form_1' and 'my_form_2' , this function will produce the following results
     * 
     *  $assoc=true,$wordify=true 
     * 
     * Array
      (
      [my_form_1] => My Form 1
      [my_form_2] => My Form 2
      )
     * 
     *  $assoc=true,$wordify=false 
     * Array
      (
      [my_form_1] => my_form_1
      [my_form_2] => my_form_2
      )
     * 
     *  $assoc=false,$wordify=false 
     * 
     * Array
      (
      [0] => my_form_1
      [1] => my_form_2
      )
     * 
     * @param string $table The table to get the options
     * @param string $field The field name 
     * @param string $query Optional Query, otherwise a standard query will be used
     * @param boolean $assoc Whether to return an associative array or a non-associative array
     * @param boolean $wordify True will turn 'my_value' into 'My Value' , which is more human readable
     * @return void
     */
    public function getDbColumn( $table, $field, $query = null, $assoc = true, $wordify = true ) {
        global $wpdb;
        if ( is_null( $query ) ) {
            $query = "select `" . $field . "` from `" . $table . "` 
group by `" . $field . "`";


}

        $db_records = $wpdb->get_results( $query, ARRAY_A );


        foreach ( $db_records as $row ) {
            $value = $row[ $field ];
            $_display_text = $row[ $field ];
            if ( $wordify ) {
                $display_text = ucwords( str_replace( '_', ' ', $_display_text ) );
} else{
                $display_text = $_display_text;

}








            if ( $assoc ) {
                $options[ $value ] = $display_text;
} else{
                $options[] = $display_text;
}

}



        return $options;
    }

    /**
     * Get Current Wp URL
     *
     * Ref: inspired by http://kovshenin.com/2012/current-url-in-wordpress/ but different . Kovshenin's didnt work for me.
     *
     * @param none
     * @return void
     */
    public function getCurrentWPURL() {


        $current_url = add_query_arg( $_SERVER[ 'QUERY_STRING' ], '', home_url( //he home_url template tag retrieves the home URL for the current site, optionally with the $path argument appended. The function determines the appropriate protocol, "https" if is_ssl() and "http" otherwise.
                        $_SERVER[ 'SCRIPT_NAME' ] //SCRIPT_NAME is defined in the CGI 1.1 specification, PHP_SELF is created by PHP itself. See http://php.about.com/od/learnphp/qt/_SERVER_PHP.htm for tests.
                )
        );
        return $current_url;
    }

    /**
     * Appends a trailing slash.
     *
     * Ref: http://core.trac.wordpress.org/browser/tags/3.6.1/wp-includes/formatting.php#L0
     * Will remove trailing slash if it exists already before adding a trailing
     * slash. This prevents double slashing a string or path.
     *
     * @param string $string What to add the trailing slash to.
     * @return string String with trailing slash added.
     */
    function trailingslashit( $string ) {
        return untrailingslashit( $string ) . '/';
    }

    /**
     * Removes trailing slash if it exists.
     *
     * Ref: http://core.trac.wordpress.org/browser/tags/3.6.1/wp-includes/formatting.php#L0
     * @param string $string What to remove the trailing slash from.
     * @return string String without the trailing slash.
     */
    function untrailingslashit( $string ) {
        $result = rtrim( $string, '\\' ); //remove backslash
        $result = rtrim( $result, '/' ); //remove backslash
        return $result;
    }

    /**
     * get Local Time From UTC
     *
     * Returns Local Time Using WordPress Conversion Function for GMT To Local
     * Useful when retrieving a database time that is stored in UTC
     * Usage:        $this->plugin()->tools()->getLocalTimeFromUTC( 
      $dbrow->time_added, //$time as a timestamp string
      'Y-m-d H:i:sP'  //$format The time format in which you'd like the result
      );
     * // Assuming today is March 10th, 2001, 5:16:18 pm, and that we are in the
      // Mountain Standard Time (MST) Time Zone

      $today = date("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
      $today = date("m.d.y");                         // 03.10.01
      $today = date("j, n, Y");                       // 10, 3, 2001
      $today = date("Ymd");                           // 20010310
      $today = date('h-i-s, j-m-y, it is w Day');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
      $today = date('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
      $today = date("D M j G:i:s T Y");               // Sat Mar 10 17:16:18 MST 2001
      $today = date('H:m:s \m \i\s\ \m\o\n\t\h');     // 17:03:18 m is month
      $today = date("H:i:s");                         // 17:16:18
      $today = date("Y-m-d H:i:s");                   // 2001-03-10 17:16:18 (the MySQL DATETIME format)
     *
     * @param string $time The UTC time you want converted
     * @param string $format The resulting format that you want to see the time
     * @return string Local Time
     */
    public function getLocalTimeFromUTC( $time, $format ) {

        return( get_date_from_gmt( date( 'Y-m-d H:i:s', strtotime( $time ) ), $format ));



    }

    /**
     * Send Pear Email
     *
     * A Simple Wrapper To Send Email Using the Pear Library
     * 
     * Requires the installation of Pear Mail http://pear.php.net/
     * To verify Mail is installed: pear list
     * To install Mail:
     *  pear install Mail
     *  pear install -a Net_SMTP
     * 
     * 
     * Assumes the following paramaters are set within the Plugin.php config() method using setConfig():


      EMAIL_HOST
      EMAIL_AUTH
      EMAIL_PORT
      EMAIL_USERNAME

      example:

      $this->setConfig(
      'EMAIL_HOST'
      , 'ssl://in.mailjet.com'
      );

     *
     * @param string $to The 'to' address of the email
     * @param string $subject The Subject of the email
     * @param string $message The body of the email
     * @param boolean $simulate True to Simulate, dumping to stdout, False to send. Default to true as a safety measure so you won't spam during testing.
     * @return void
     */
    public function sendPearEmail( $from, $to, $subject, $message, $simulate = true ) {

        require_once "Mail.php";

        $host = $this->plugin()->EMAIL_HOST;
        $auth = $this->plugin()->EMAIL_AUTH;
        $port = $this->plugin()->EMAIL_PORT;
        $username = $this->plugin()->EMAIL_USERNAME;


        $headers = array( 'From' => $from,
            'Subject' => $subject );


        $smtp = Mail::factory(
                        'smtp', array( 'host' => $host,
                    'port' => $port,
                    'auth' => $auth,
                    'username' => $username,
                    'password' => $password
                ) );

        /*
         * will return true(1) if successful, error text if not
         */

        if ( $simulate ) {
            $result = "\n" . print_r( array( 'to' => $to, 'headers' => $headers, 'message' => $message ), true ) . "\n"; //debug     
} else{
            $result = $smtp->send( $to, $headers, $message );
}


        return($result);
    }

    /**
     * Get Array Value
     *
     * Returns the value of an array given its key or keys (if a multidimensional array) . 
     * usage: getArrayValue($myarray,'[key1][key2][key3]'
     * This seems easy to do by simply doing this : $myarray[key1[key2][key3]] but it doesnt work that way...
     * Especially useful when you are validating forms and all you know is the name of the multidimensional field
     *
     * @param array $array The array that contains the value
     * @param string $key A string that contains a key or keys in the following format 'key1' or '[key1][key2][key3]' . you must include in brackets if more than one key. single quotes are optional, double quotes are not supported.
     * 
     * @return void
     */
    public function getArrayValue( $array, $key ) {
        #initialize
        $result = array();
        $subkeys = array();
        $subkey = null;
        /*
         * first check to see if an opening bracket is contained in the key
         * if so, we'll have to iterate through all keys
         */

        if ( stripos( $key, '[' ) !== false ) { //if an opening bracket is detected

            /*
             * remove any single quotes. double quotes are not supported
             */

            $key_no_quotes = str_ireplace( "'", '', $key );
            /*
             * remove closing brackets
             */
            $subkeys = str_ireplace( ']', '', $key_no_quotes );
            /*
             * split by opening brackets
             */
            $subkeys = explode( '[', $subkeys );
            /*
             * remove null keys which sometimes happens when exploding
             */
            $subkeys = array_filter( $subkeys );


            $this->debug()->logVar( '$subkeys = ', $subkeys );
            $result = $_POST;
            foreach ( $subkeys as $subkey ) {
                $result = $result[ $subkey ];
}
            $this->debug()->logVar( '$result = ', $result );

            return $result;
} else{
            if ( isset( $array[ $key ] ) ) {
                return $array[ $key ];
} else{
                return null;
}

}
       }
}

?>