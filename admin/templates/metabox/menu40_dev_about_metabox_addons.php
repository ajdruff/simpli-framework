<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//$addon_headers = get_file_data($addon_file_path, array(), 'simpli');
//$base_class_version = $simpli_data['Simpli Base Class Version']; // X.Y

$addons = $this->plugin()->getAddons();
foreach ($addons as $addon) {
    ?><div><?php
        $addon_file_path = $addon->getFilePath();

        /*
         *
         * Set Default
         *
         * Element is index,
         * Right side is the index of the header value to be used.
         */
        $requested_headers = array(
            'Addon Name' => 'Addon Name',
            'Description' => 'Description',
            'Addon URI' => 'Addon URI',
            'Addon Slug' => 'Addon Slug',
            'Version' => 'Version',
            'Simpli Base Class Version' => 'Simpli Base Class Version',
            'Author' => 'Author',
            'AuthorURI' => 'Author URI',
            'License' => 'License',
        );

        $addon_headers = get_file_data($addon_file_path, $requested_headers, 'plugin');
        $exclude = array('Addon Name');
        ?><h4><?php
            echo $addon_headers['Addon Name'];
            ?></h3><div style="padding-left:10px"><ul><?php
                    foreach ($addon_headers as $header_text => $header_value) {
                        if (in_array($header_text, $exclude)) {
                            continue;
                        }
                        echo '<li><strong>' . $header_text . ':</strong> ' . $header_value . '</li>';
                    }
                    ?></ul></div><?php
        }//end addon loop
        ?></div><?php
        ?>

