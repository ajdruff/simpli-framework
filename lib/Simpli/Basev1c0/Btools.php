<?php

/**
 * Utility Base Class
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
class Simpli_Basev1c0_Btools {

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
    public function sortDependentList($list = array(), $dependencies = array()) {
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

          $sorted_handles=$this->getTools()->sortDependentList($list,$dependencies);
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
        $dependent_handles = array_keys($dependencies); //makes the keys in dependencies their own array
        $sorted_handles = array();

        $todo_list = array_flip($list); //flip to ensure unqueness and allows us to remove items easily



        while (count($todo_list) > 0) {

            foreach ($todo_list as $handle => $arbitrary) { //we only care about the index, the handle
                if (!in_array($handle, $dependent_handles)) { //if the handle does not depend on anything,
                    array_push($sorted_handles, $handle);  //add it to the final $sorted_handles array
                    unset($todo_list[$handle]); // and remove it from the todo list
                } else { //if the handle is dependent on others, check to see if its dependencies are in the final list
                    $requirements_met = true; //assume the best, toggle to false if even one requirement is not met

                    $missing_dependency = false; //assume the best, toggle to true if even one missing dependency found
                    foreach ($dependencies[$handle] as $required_handle) { //check dependent handles
                        if (!in_array($required_handle, $sorted_handles)) { //if dependent handle is not in final list yet,
                            $requirements_met = false; // then requirement is not met
                        }
                        if (!in_array($required_handle, array_keys($todo_list)) && !in_array($required_handle, $sorted_handles)) { //if required_handle isnt on todo list, flag missing dependency or we will loop forever since the handle dependent on it will never be satisfied.
                            $missing_dependency = true;
                        }
                    }
                    if ($requirements_met === true && $missing_dependency === false) { //if all the required dependents are in the final list, then
                        array_push($sorted_handles, $handle);  //add the dependent handle to the final list

                        unset($todo_list[$handle]); //and remove from todo list
                    } elseif ($missing_dependency === true) {


                        unset($todo_list[$handle]); //if required handle isnt on hte list at all, we cant include the handle that relies on it, so remove it.
                    }
                }
            }
        }


        return ($sorted_handles);
    }

    /**
     * Rebuild Url
     *
     * Returns the current or provided url, adding new or, replacing existing, $_GET url paramaters
     *
     * @param array new values for $_GET . Existing values will remain
     * @return string $url The url
     */
    public function rebuildURL($get_args, $url = null) {

        if (is_null($url)) {
            $url = $_SERVER['REQUEST_URI'];
        }
        $existing_url_parts = parse_url($url);



        $existing_get_vars = array();
        /*
         * take the query string in url parts
         * and create an array from it
         * by first splitting it by the ampersand
         * and then iterating through that array to and split its elements by the = sign
         */
        $arr_existing_query_parts = explode('&', $existing_url_parts['query']); //creates {'myvar=myval','myvar2=myval2',etc}

        foreach ($arr_existing_query_parts as $arr_existing_query_part) {
            $namevalue = explode('=', $arr_existing_query_part); //split into {'name'=>var}
            $name = $namevalue[0];
            $value = $namevalue[1];
            $existing_get_vars[$name] = $value;
        }



        $defaults = array(
            'scheme' => null,
            'host' => null,
            'path' => null,
            'query' => null,
        );
        $url_parts = parse_url($url); //creates an array of the different parts of the url
        //$url_parts = array_intersect_key(array_merge($defaults, $url_parts), $defaults); //make sure the indexes we need are there or use their defaults

        $url_parts = $this->defaultsScreen($defaults, $url_parts);






        $get_args = array_merge($existing_get_vars, $get_args); //merge existing GET paramaters



        $url_parts['query'] = http_build_query($get_args); //replace the query string part with a new query string using the new values








        $result = $this->http_build_url($url_parts); //rebuild the url with the new parts;


        return $result;
    }

    /**
     * defaultsScreen
     *
     * Takes an array and takes only those elements whose indexes match one in the defaults array, then fills in any gaps with the default values
     *
     * @param array $defaults An associative array of default name/value pairs
     * @param array $variables An associative array of name/value pairs
     * @return array An array that has only the indexes that are listed in the defaults array and values that are supplied by either defaults or the provided $variables array
     */
    public function defaultsScreen($defaults, $array) {
        return (array_intersect_key(array_merge($defaults, $array), $defaults));
    }

    /**
     * Given an array in the form of parse_url result, builds a url
     *
     * @param array $http_array
     */
    public function http_build_url($url_parts) {

        $defaults = array(
            'scheme' => null,
            'host' => null,
            'path' => null,
            'query' => null,
        );

        $url_parts = $this->defaultsScreen($defaults, $url_parts); //make sure the indexes we need are there or use their defaults




        $scheme = (trim($url_parts['scheme']) !== '') ? $url_parts['scheme'] . '://' : '';
        $host = (trim($url_parts['host']) !== '') ? $url_parts['host'] : '';
        $url = $scheme . $host . $url_parts['path'] . '?' . $url_parts['query'];

        return($url);
    }

    /**
     *
     * Detects shortcode
     *
     *
     */
    public function detectShortcode($haystack, $shortcode) {

        global $post;
        $pattern = get_shortcode_regex();

        if (preg_match_all('/' . $pattern . '/s', $haystack, $matches) && array_key_exists(2, $matches) && in_array($shortcode, $matches[2])) {
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
    public function validateArrayKeys($test_array, $valid_keys) {
        $valid_keys_flipped = array_flip($valid_keys); // converts values in $valid_options to keys 'js'=>0
        $test_array_and_valid_keys_combined = array_keys(array_merge($valid_keys_flipped, $test_array)); //results in an array of all the valid keys + any differing keys passed in debug. If nothing differs, then the combined array is the same
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
    public function getGlobFiles($dir, $file_pattern, $recursive = true, $files = array()) {


        $globFiles = glob("$dir/$file_pattern");
        $globDirs = glob("$dir/*", GLOB_ONLYDIR);

        if ($recursive) {
            foreach ($globDirs as $dir) {
                $files = $this->getGlobFiles($dir, $file_pattern, true, $files);
            }
        }



        foreach ($globFiles as $file) {

            $files[] = $file; // Replace '\n' with '<br />' if outputting to browser
            $file = null;
        }

        if (!empty($files)) {
            $files = array_filter($files, 'is_file');


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
    function makePathRelative($base_path, $longer_path) {
        $base_path = $this->normalizePath($base_path);
        $longer_path = $this->normalizePath($longer_path);
        if (0 !== strpos($longer_path, $base_path)) {
            return ($longer_path);
            //throw new Exception("Can not make relative path, base path is not contained in longer path: `" . $base_path . "`, `" . $longer_path . "`");
        }
        return substr($longer_path, strlen($base_path) + 1);
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
    function normalizePath($path, $resolve_indirection = false) {
        $parts = preg_split(":[\\\/]:", $path); // split on known directory separators

        if ($resolve_indirection) {


            // resolve relative paths
            for ($i = 0; $i < count($parts); $i +=1) {
                if ($parts[$i] === "..") {          // resolve ..
                    if ($i === 0) {
                        throw new Exception("Cannot resolve path, path seems invalid: `" . $path . "`");
                    }
                    unset($parts[$i - 1]);
                    unset($parts[$i]);
                    $parts = array_values($parts);
                    $i -= 2;
                } else if ($parts[$i] === ".") {    // resolve .
                    unset($parts[$i]);
                    $parts = array_values($parts);
                    $i -= 1;
                }
                if ($i > 0 && $parts[$i] === "") {  // remove empty parts
                    unset($parts[$i]);
                    $parts = array_values($parts);
                }
            }
        }

        return implode("/", $parts);
    }

    /**
     * In Include Path
     *
     * Determines whether a file is within the include path
     *
     * @param string $find The partial path to the file
     * @return boolean Whether the path can be included
     */
    public function inIncludePath($find) {


        $paths = explode(PATH_SEPARATOR, get_include_path());
        $found = false;
        foreach ($paths as $p) {
            $fullname = $p . DIRECTORY_SEPARATOR . $find;
            if (is_file($fullname)) {
                $found = $fullname;
                break;
            }
        }
        return $found;
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
    public function getHtmlWithoutWhitespace($html) { //
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
        $html = preg_replace($re, " ", $html);
        if ($html === null)
            exit("PCRE Error! File too big.\n");
        return $html;
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
//        $tmpfname = tempnam(sys_get_temp_dir(), $this->getPlugin()->getSlug());
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
}

?>