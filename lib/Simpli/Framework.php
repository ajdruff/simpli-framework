<?php

/**
 * Simpli Framework Class
 *
 * This class is a simple wrapper around the versioned base loader. So as
 * to keep the api as simple as possible, any complexity should be pushed
 * to the base loader, to prevent the need to version this class.
 * Note: this is a shared class file, meaning that any 2 plugins installed on the same server
 * will use the class file loaded by the first plugin that is loaded. It is critical you
 * do not store any data on this class that you want to be specific to your plugin, since
 * it will also be used by any other plugin using the Simpli Framework.
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
         * Derive the namespace parts so we can use them in our directory paths
         */
        $plugin_slug_parts = self::getSlugParts($plugin_slug);
        $slug_ucprefix = ucwords($plugin_slug_parts[0]); //uc for uppercase -(first letter of slug prefix is uppercase)
        $slug_ucsuffix = ucwords($plugin_slug_parts[1]);


        /*
         * require the base loader so we can use it to bootstrap the base classes
         */

        $base_loader_class_path = dirname($plugin_file_path) . '/lib/' . $slug_ucprefix . '/' . $slug_ucsuffix . '/Base' . $vxcy . '/Loader.php';

        require($base_loader_class_path);








        $loader_class = self::getClassNamespace($plugin_slug) . '_Base' . $vxcy . '_Loader';


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

    /**
     * Get Slug Parts - Read Only
     *
     * @param string $plugin_slug The slug of the plugin
     * @return arrayReadOnly
     */
    public function getSlugParts($plugin_slug) {


        $parts = explode('_', $plugin_slug);

        $parts['prefix'] = $parts[0];
        $parts['suffix'] = $parts[1];




        return $parts;
    }

    /**
     * Get Class Namespace - Read Only
     *
     * @param string $plugin_slug The slug of the plugin
     * @return stringReadOnly
     */
    public function getClassNamespace($plugin_slug) {

        /*
         * derive namespace from slug
         * just Title Case each word
         */

        $array_class = explode('_', $plugin_slug);
        $namespace = ucwords($array_class[0]) . '_' . ucwords($array_class[1]);



        return $namespace;
    }

}

