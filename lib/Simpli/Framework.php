<?php

/**
 * Simpli Framework Class
 *
 * This class is a simple wrapper around the versioned base loader. So as
 * to keep the api as simple as possible, any complexity should be pushed
 * to the base loader, to prevent the need to version this class.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 */
// Check that the class exists before trying to use it
if (class_exists('Simpli_Framework')) {
    return;
}

class Simpli_Framework {

    public static function load($plugin_slug, $plugin_file_path) {



        /*
         * Read WordPress Header to get the base class version number X.Y
         */
        add_filter('extra_simpli_headers', __CLASS__ . '::add_extra_wp_headers');


        $simpli_data = get_file_data($plugin_file_path, array(), 'simpli');

        $base_class_version = $simpli_data['Simpli Base Class Version']; // X.Y

        /*
         * build the base class versioned namespace vXcY
         * by adding v and c so we have vXcY
         * for version X.Y
         *
         */

        $parts = explode('.', $base_class_version); // X.Y
        $major = $parts[0]; // X
        $minor = $parts[1]; // Y

        $template = 'v{major}c{minor}'; //

        $template = str_replace('{major}', $major, $template); // vX.Y
        $vxcy = str_replace('{minor}', $minor, $template); // vXcY

        /*
         * require the base loader so we can use it to bootstrap the base classes
         */

        $base_loader_class_path = dirname($plugin_file_path) . '/lib/Simpli/Base' . $vxcy . '/Loader.php';

        require($base_loader_class_path);








        $loader_class = 'Simpli_Base' . $vxcy . '_Loader';


        $loader = new $loader_class();
        $plugin = $loader->load($plugin_slug, $plugin_file_path, $base_class_version);








        return ($plugin);
    }

    /**
     * Add Simpli Framework Headers
     * WordPress Hook extra_{$context}_headers
     * @param string $extra headers
     * @return void;
     */
    public static function add_extra_wp_headers($extra_headers) {

        $extra_headers[] = 'Simpli Framework Version';
        $extra_headers[] = 'Simpli Base Class Version';


        return ($extra_headers);
    }

}