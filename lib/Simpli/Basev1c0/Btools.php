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
          print_r($sorted_handles);
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
//          print_r($list);
//          echo '</pre>';
//
//                    echo '<pre>';
//
//          print_r($dependencies);
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
     * Given an array in the form of parse_url result, builds a url
     *
     * @param array $http_array
     */
    public function http_build_url($url_parts) {


        $url = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
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

            // echo '<pre>', print_r($files, true), '</pre>';
            return $files;
        }
    }

    /**
     * Make Path Relative
     *
     * Removes base path from longer path. The resulting path will never contain a leading directory separator
     * Base path must occur in longer path
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
            throw new Exception("Can not make relative path, base path does not occur at 0 in longer path: `" . $base_path . "`, `" . $longer_path . "`");
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

}

?>