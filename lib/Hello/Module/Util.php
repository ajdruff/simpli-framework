<?php

/**
 * Core Module
 *
 * Plugin's core functionality
 *
 * @author Andrew Druffner
 * @package Hello
 *
 */
class Hello_Module_Util extends Simpli_Plugin_Module {

    private $moduleName;
    private $moduleSlug;

    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {


        /*
         *
         * Set the Module Name based on the name of this file
         * for MyModule.php , moduleName=MyModule
         *
         */


        $this->moduleName = basename(__FILE__, ".php");

        /*
         * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
         * for for MyModule.php , moduleName=MyModule , moduleSlug='my_module'
         * http://stackoverflow.com/q/8611617
         */
        $regex = '/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/';
        $this->moduleSlug = strtolower(preg_replace($regex, '_$1', $this->moduleName));
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
     * Returns a new url with the specified query paramater replaced with a new value
     *
     * @param string $url Url that you want to contain new paramater values
     *  @param string $query_varname Query variable that holds the value that you want replaced
     *  @param string $new_query_value New query variable value
     *
     */
    public function http_replace_query_param($url, $query_varname, $new_query_value) {


        //split url into component parts
        $url_parts = parse_url($url);

// if there is no query paramater, return the url as it is since its assumed that we dont need one and it will just cause an error below 
        if (!isset($url_parts['query'])) {
             return ($url);
        }

//create an array just from the query string
        parse_str($url_parts['query'], $query_array);

        //replace the query variable with the value we want;
        $query_array[$query_varname] = $new_query_value;

        // rebuild the query string using the new array values
        $query_string = http_build_query($query_array);

        //replace the query part with the new query string
        $url_parts['query'] = $query_string;
        //rebuild the url.


        $url = $this->http_build_url($url_parts);

        return ($url);
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

}