<?php

/**
 * Top Level (Main) Admin Menu
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 *
 */
class Simpli_Frames_Modules_Menu20DevManage extends Simpli_Frames_Base_v1c2_Plugin_Menu {

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
         * add a hook that will redirect a request with the query variable
         * This is made possible because the Queryvars module white lists
         * the query variable and does a 'do_action' when the query variable and value is detected.
         */
        //   add_action($this->plugin()->QUERY_VAR . '_action' . '_upload_addon', array($this, 'hookFormActionUploadAddon')); // ?mycompany_myplugin_action=upload_addon will execute this action

        $this->metabox()->addFormAction('upload_addon');

        add_action('post_edit_form_tag', array($this, 'post_edit_form_tag'));

        //  add_action('filesystem_method', array($this, 'fileSystemMethod'));


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
         * Add a Hook to Handle the Addon Form's submission
         */
        add_action('wp_ajax_' . $this->plugin()->getSlug() . '_upload_addon', array($this, 'hookFormActionUploadAddon'));

        /*
         * Add any other hooks you need - see base class for examples
         *
         */


        /*
         * Add an admin notice if disabled
         *
         */
        add_action('admin_notices', array($this, 'showDisabledMessage'));
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
         * Set the Meta Box Initial Open/Close state
         */
        $this->metabox()->setOpenState
                (
                'metabox_about'
                , false
                , true
        );

        /*
         * Add the Menu Page
         */

        $this->addMenuPage
                (
                $page_title = $this->plugin()->getName() . ' - Dev Control Panel'
                , $menu_title = array('menu' => $this->plugin()->getName(), 'sub_menu' => 'Dev Control Panel')
                , $capability = 'manage_options'
                , $icon_url = $this->plugin()->getUrl() . '/admin/images/menu.png'
                , $position = null
        );


        $this->metabox()->addMetaBox(
                'metabox_addons'  //Meta Box DOM ID
                , __('Install or Export Your Addons', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );

        $this->metabox()->addMetaBox(
                'metabox_release'  //Meta Box DOM ID
                , __('Build Release', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );





        /*
         * Lets use unique nonces for extra security :)
         */
        $this->setConfig('UNIQUE_ACTION_NONCES', false);
    }

    /**
     * Shows a disabled message if the plugin is disabled via the settings
     * This will only appear when first switching to the general settings page. Its assumed that the settings that trigger
     * it are set on a different (advanced) menu page.
     *
     */
    public function showDisabledMessage() {
        $this->debug()->t();

        if (!$this->pageCheckMenu()) {
            return;
        }



//dont show if the plugin is enabled
        if (($this->plugin()->getUserOption('plugin_enabled') == 'enabled')) {
            return;
        }
        ?>



        <div class="error">
            <p><strong>You have disabled <?php echo $this->plugin()->getName() ?> functionality.</strong> To re-enable <?php echo $this->plugin()->getName() ?> , set  'Maintenance -> Enable Plugin' to 'Yes'.</p>
        </div>

        <?php
    }

    /**
     * Hook Ajax Upload Addon
     *
     * Hook Ajax - Uploads an Addon and installs it.
     *
     * @param none
     * @return void
     */
    public function hookFormActionUploadAddon() {





        $this->debug()->t();
        $this->debug()->log('upload addon form action submitted');
        $this->debug()->logVar('$_POST = ', $_POST);
        $this->debug()->logVar('$_GET = ', $_GET);
        $this->debug()->logVar('$_FILES = ', $_FILES);
        $this->debug()->logVar('$_REQUEST = ', $_REQUEST);
        /*
         * pageCheck
         * Skip the pageCheck, since this is an ajax request and wont contain the $_GET page variable
         *
         */

        /*
         * Check Nonces
         */
        if (!$this->metabox()->wpVerifyNonce(__FUNCTION__)) {
            die('nonce failed' . __LINE__ . __FILE__);
            return false;
        }

        //   die('uploading addon - stub - submission \'worked\'' . __LINE__ . __FILE__);





        /*
         * Install the zip file
         *
         */

        //do something here to install it (stub)

        $install_result = $this->installAddon();

        if ($install_result['success'] === true) {
            $message = __('The addon installed successfully', $this->plugin()->getTextDomain());
        } else {

            $message = __('The addon could not be installed. ', $this->plugin()->getTextDomain()) . $install_result['message'];
        }


        /*
         * display the message
         */

        $this->metabox()->showResponseMessage(
                $this->plugin()->getAdminDirectory() . '/templates/ajax_message_admin_panel.php', //string $template The path to the template to be used
                $message, // string $message The html or text message to be displayed to the user
                array(), //$errors Any error messages to display
                false, //boolean $logout Whether to force a logout after the message is displayed
                false //boolean $reload Whether to force a page reload after the message is displayed
        );
    }

    /**
     * Install Addon
     *
     * Unzips and copies the uploaded addon to the addon directory.
     *
     * @param none
     * @return void
     */
    public function installAddon() {
        $addons_directory_path_absolute = $this->plugin()->getAddonsDirectory();
        $install_result = false;
        $unzip_result = false;
        $upload_result = false;
        $temp_directory_absolute = $this->plugin()->tools()->normalizePath($this->plugin()->getAddonsDirectory() . '/temp');
        $found_dirs = array();



        $this->debug()->logVar('$_REQUEST = ', $_REQUEST);
        $this->debug()->logVar('$_FILES = ', $_FILES);


        $file_path = isset($_FILES['addon_zip_file']['tmp_name']) ? $_FILES['addon_zip_file']['tmp_name'] : null;
        if (is_null($file_path)) {
            $this->debug()->log('Uploaded file was not found - did the user enter a file path?');
            $this->debug()->logVar('$_REQUEST = ', $_REQUEST);
            $this->debug()->logVar('$_FILES = ', $_FILES);

            $install_result['message'] = __('Please select a file to upload', $this->plugin()->getTextDomain());
            $install_result['success'] = false;

            return $install_result;
        }



        /*
         * use the WordPress function unzip_file to handle
         */
        $uploaded_file = $_FILES['addon_zip_file'];
        // $file_path = isset($uploaded_file['tmp_name']) ? $uploaded_file['tmp_name'] : null;
        $upload_overrides = array('test_form' => false); //required by wordpress
        $upload_result = wp_handle_upload(
                $uploaded_file, //Reference to a single element of $_FILES. e.g.:$_FILES['addon_zip_file'];
                $upload_overrides  //an associative array to override default variables with extract( $overrides, EXTR_OVERWRITE ). If you don’t pass 'test_form' => FALSE the upload will be rejected.
        );
        $upload_result['file'] = $this->plugin()->tools()->normalizePath($upload_result['file']);
        $this->debug()->logVar('$upload_result = ', $upload_result);
        /*
         * Return if there is an error handling the upload
         */
        if (isset($upload_result['error'])) {// if there was a problem handling the upload, return with error
            $this->debug()->logError('Problem with upload:' . $upload_result['error']);
            $install_result['message'] = $upload_result['error'];
            $install_result['success'] = false;
            return $install_result;
        }


        /*
         * If the Upload Handler succeeded, Unzip the file
         */ else { //if the upload handler succeeded, attempt to unzip the fil

            /*
             * Get any credentials passed by the request_filesystem_credentials() function call
             * They are stored in $_POST[$this->plugin()->getSlug()]['creds']
             * and may be an array of credential information , or true/false
             */

            $form_creds_array = $this->plugin()->tools()->getRequestVar($this->plugin()->getSlug());
            $creds = (isset($form_creds_array['creds'])) ? $form_creds_array['creds'] : null;
            $this->debug()->logVar('$creds = ', $creds);



            /*
             *
             * Instantiate the WP_Filesystem
             *
             */



            $file_system_access_result = WP_Filesystem($creds);
            global $wp_filesystem;







            $upload_handler_file_path_absolute = $upload_result['file'];
            $upload_handler_file_path = $this->getNormalizedFileSystemPath($upload_handler_file_path_absolute, $wp_filesystem->method);

            $this->debug()->logVar('Upload Handler Result array, $upload_result = ', $upload_result);


            $this->debug()->logVar('WP Filesystem Class = ', get_class($wp_filesystem));
            $this->debug()->logVar('$file_system_access_result = ', $file_system_access_result);
            /*
             * Return Error if there was a problem Using File System Credentials
             */

            if ($file_system_access_result === false) {
                $install_result['message'] = __('Your username or password is incorrect for file access. Please try again.', $this->plugin()->getTextDomain());
                $install_result['success'] = false;
                return $install_result;
            }




            /*
             * Delete and then create the temp directory
             */
            global $wp_filesystem;
            $temp_directory = $this->getNormalizedFilesystemPath($temp_directory_absolute, $wp_filesystem->method);
            $addons_directory_path = $this->getNormalizedFilesystemPath($this->plugin()->getAddonsDirectory(), $wp_filesystem->method);
            $result_delete = $this->deleteTempAddonsDirectory($temp_directory);
            if ($result_delete['success'] === false) {
                $install_result = $result_delete;
                $this->debug()->logError('Deletion of the temporary directory failed');
                $this->debug()->logVar('$result_delete = ', $result_delete);

                return $install_result;
            }
            $this->debug()->logVar('$result_delete = ', $result_delete);

            /*
             * Delete Temporary Addons Directory
             * We will first verify it exists, and also verifying we
             * are deleting a directory within the addons directory.
             */

            /*
             * Create Temp Directory (again)
             */
            // WP_Filesystem($creds);
            $this->debug()->logVar('get_class($wp_filesystem) = ', get_class($wp_filesystem));
            $this->debug()->logVar('$creds = ', $creds);

            $this->debug()->logVar('$temp_directory = ', $temp_directory);
            $result_make_temp_directory = $wp_filesystem->mkdir($temp_directory);

            $this->debug()->logVar('$result_make_temp_directory = ', $result_make_temp_directory);

            /*
             * A good test is to run $wp_filesystem->touch()
             *  $result_touch = $wp_filesystem->touch(dirname($temp_directory) . '/test.text');
             * $this->debug()->logVar('$result_touch = ' . dirname($temp_directory) . '/test.text', $result_touch);
             */





            /*
             *
             * Unzip
             *
             * Unzip the Addon to a Temporary Directory
             *
             *
             */

            $unzip_result = unzip_file($upload_handler_file_path_absolute, $temp_directory);
            /*
             * Read the Addon file information header
             */
            $addon_headers = get_file_data($addon_file_path, array(), 'simpli');
            $base_class_version = $simpli_data['Simpli Base Class Version']; // X.Y

            $addon_base_class_version = 'v1c2';
            $addon_plugin_class_namespace = 'Mycompany_Myplugin'; //use a regex to find the namespace.
            $addon_class_namespace = 'Mycompany_Myaddon'; //use a regex to find the namespace.
            /*
             * Get the files using glob
             */


            /*
             *
             */


            $addon_files = $this->plugin()->tools()->getGlobFiles($temp_directory_absolute, '*', true);
            $this->debug()->logVar('$addon_files = ', $addon_files);

            foreach ($addon_files as $addon_file_path) {
                $file_path = $this->getNormalizedFilesystemPath($addon_file_path, $wp_filesystem->method);
                $old_file_contents = $wp_filesystem->get_contents($file_path);
                $new_file_contents = str_replace(array($addon_plugin_class_namespace), array($this->plugin()->getClassNamespace()), $old_file_contents); //use a regex replace instead

                $file_conversion_result = $wp_filesystem->put_contents($file_path, $new_file_contents);
                $this->debug()->logVar('$file_conversion_result = ', $file_conversion_result);
            }



            /*
             * Check that the correct library is installed, if not repeate the procedure above with the directory
             * and flag that we'll need to install the new library
             */
            $this->debug()->stop(true);



            $this->debug()->logVar('unzip_file() called to unzip file  ' . $upload_handler_file_path_absolute . ' to extraction location: ', $temp_directory);
            $unzip_result_array = (array) $unzip_result;
            $this->debug()->logVar('$unzip_result_array = ', $unzip_result_array);

            /*
             * If errors during Unzip, return with errors
             */
            if (isset($unzip_result->errors)) {
                $unzip_result_error = $unzip_result_array['errors'][key($unzip_result_array['errors'])][0];
                $unzip_result_error_data = $unzip_result_array['error_data'][key($unzip_result_array['errors'])];

                // $this->debug()->logVar('$unzip_result_errors = ', $unzip_result_errors);
                $this->debug()->logError('<p style="padding:10px"><strong>Error Message:</strong> ' . $unzip_result_error . ' <br> <strong>Data :</strong>' . $unzip_result_error_data . '</p><p> , while trying to use unzip_file() function on file : ' . basename($upload_handler_file_path) . '</p>');

                $install_result = false;
                $install_result['message'] = $unzip_result_error . ' ' . $unzip_result_error_data;
                $install_result['success'] = false;
                $this->deleteTempAddonsDirectory($temp_directory);
                return $install_result;
            }




            /*
             * Now get the name of the first directory containing Addon.php  and
             * check for its existance in Addons.
             */

            $uploaded_add_on_files = $this->plugin()->tools()->getGlobFiles(
                    $temp_directory_absolute //where we are looking for it
                    , 'Addon.php' //the file we are looking for
                    , true //recursive
            );



            $this->debug()->logVar('$uploaded_add_on_files = ', $uploaded_add_on_files);

            /*
             * Make sure that exactly one Addon Directory is found after unzipping
             *
             * Errors here mean that either the zip file has more or less than one Addon.php
             * OR the result is null, meaning the zip file extracted to the wrong location.
             * The ftp user must be able to 'cd $temp_directory' from their root directory.
             *
             */
            if (count($uploaded_add_on_files) !== 1) {


                $install_result['message'] = __('Corrupted Zip file or bad unzip destination.
               \' file', $this->plugin()->getTextDomain());
                $install_result['success'] = false;
                $this->debug()->logVar('$install_result = ', $install_result);
                $this->deleteTempAddonsDirectory($temp_directory);
                $this->debug()->logError('Unzip failed. Check for a corrupted zip file and that the FTP or SSH user account being used to upload the Addon can reach the Addons directory. On a command line, login to the FTP or SSH server using the same account credentials you provided for your upload, and verify that you can reach ' . $addons_directory_path_absolute . ' by using the command \'cd ' . $addons_directory_path . '\' from the account\' home directory.  If the cd command fails, then define(\'FTP_BASE\',\'/path/to/home/directory)\' within wp-config.php and make sure that the user account\'s home directory contains the WordPress home directory.Alternately to defining FTP_BASE, you can change the FTP/SSH user account\s home directory to be the same as the WordPress directory: ' . ABSPATH);
                return $install_result;
            }

            /*
             * To prevent overwrites, make sure that no other Addon Directory contains the same name
             */
            $extracted_files_location_absolute = dirname(dirname($uploaded_add_on_files[0]));
            $extracted_files_location = $this->getNormalizedFilesystemPath($extracted_files_location_absolute, $wp_filesystem->method); //e.g.:C:/wamp/www/wpdev.com/public_html/wp-content/plugins/simpli-frames/lib/Acme/Test/Addons/temp/Mycompany or a absolute path from the ftp or ssh directory
            $this->debug()->logVar('$extracted_files_location = ', $extracted_files_location);

            $addon_dir_name = basename($extracted_files_location);
            $dest_path = $addons_directory_path . '/' . $addon_dir_name;
            $dest_path_absolute = $addons_directory_path_absolute . '/' . $addon_dir_name;
            $this->debug()->logVar('$dest_path = ', $dest_path);



            /*
             *
             *
             * Move
             *
             *
             * Move Extracted Files to the final directory
             *
             *
             *
             *
             */
            $this->debug()->log('Moving Extracted files from <br> ' . $extracted_files_location . ' <br> to : ' . $dest_path);
            $this->debug()->logVar('$wp_filesystem = ', $wp_filesystem); //be careful about adding any debug for individual properties, like options or sftplink, because some $wp_filesystem classes do not support them and you'll receive an error if debug is left on.


            $this->debug()->logVar('$wp_filesystem->method = ', $wp_filesystem->method);
            $resource_type_link = isset($wp_filesystem->link) ? get_resource_type($wp_filesystem->link) : null;
            $resource_type_sftp_link = isset($wp_filesystem->sftp_link) ? get_resource_type($wp_filesystem->sftp_link) : null;
            $this->debug()->logVar('$wp_filesystem->link resource type = ', $resource_type_link);
            $this->debug()->logVar('$wp_filesystem->sftp_link resource type = ', $resource_type_sftp_link);
            /*
             * Dont use the $wp_filesystem->move command as it uses the wrong link due to a (bug)
             * $result_move = $wp_filesystem->move($extracted_files_location, $dest_path, false); // produces bug :
             * This doesnt work ( no move takes place, but since the wrapper removes error checking, doesnt throw an error)
             * The actual error thrown is : Warning: ssh2_sftp_rename(): supplied resource is not a valid SSH2 SFTP resource in C:\wamp\www\wpdev.com\public_html\wp-admin\includes\class-wp-filesystem-ssh2.php on line 267
             *
             * To fix/workaround, do this instead:
             * $result_move = ssh2_sftp_rename($wp_filesystem->sftp_link, $extracted_files_location, $dest_path);
             * note the use of sftp_link instead of 'link'
             * instead use the ssh2_sftp_rename directly and use the sftp_link resource instead.
             */
            if (strtolower($wp_filesystem->method) === 'ssh2') {
                /*
                 * workaround for the ssh2 move bug which uses the wrong resource type for the link
                 */
                $result_move = ssh2_sftp_rename($wp_filesystem->sftp_link, $extracted_files_location, $dest_path);
            } else {
                $result_move = $wp_filesystem->move($extracted_files_location, $dest_path, false); //broken for ssh2 due to a bug.
            }


            $this->debug()->logVar('$result_move = ', $result_move);









            if (!$result_move) {
                $install_result['message'] = __('An Addon of the same name already has been installed. If you wish to continue, you must first remove the existing Addon directory \'' . $addon_dir_name . '\' before adding a new one of the same name. \'', $this->plugin()->getTextDomain());
                $install_result['success'] = false;
                $this->debug()->logVar('Addon Install Failed! $install_result = ', $install_result);

                $this->deleteTempAddonsDirectory($temp_directory);
                return $install_result;
            }



            $install_result['message'] = '';
            $install_result['success'] = true;
            $this->debug()->logVar('Success! Addon file installed, $install_result = ', $install_result);


            $this->deleteTempAddonsDirectory($temp_directory);
            return $install_result;
        }


        return $install_result;
    }

    /**
     *
     * Post Edit form tag
     *
     * Adds enctype to the form .
     * Ref: http://codex.wordpress.org/Plugin_API/Filter_Reference/post_edit_form_tag
     * Ref: http://stackoverflow.com/questions/9947094/how-to-change-the-form-enctype-for-custom-post-type-in-wordpress
     *
     *
     * @param none
     * @return void
     */
    public function post_edit_form_tag() {

        echo ' enctype="multipart/form-data"';
    }

    /**
     * Delete Temp Addons Directory
     *
     * Safely deletes the Temp Addons Directory
     *
     * @param string $temp_directory The path to the directory where the zip is to be extracted.
     * @return array $install_result
     */
    public function deleteTempAddonsDirectory($temp_directory) {


        global $wp_filesystem;

        $addons_directory_path = $this->getNormalizedFilesystemPath($this->plugin()->getAddonsDirectory(), $wp_filesystem->method);
        $plugin_directory_path = $this->getNormalizedFilesystemPath($this->plugin()->getDirectory(), $wp_filesystem->method);
        ;
        if (!$wp_filesystem->is_dir($temp_directory)) {
            $result_delete['success'] = true;
            $result_delete['message'] = 'Temp directory does not exist, so deletion was skipped.';
            $this->debug()->logVar('$result_delete = ', $result_delete);
            return $result_delete;
        }

        if ($temp_directory === '' || $temp_directory === '/'
        ) { //verify we arent deleting the root directory which could happen if one of our variables was inadvertently not set or is empty.
            $result_delete['success'] = false;
            $result_delete['message'] = __('Temp Directory ' . $temp_directory . ' is invalid', $this->plugin()->getTextDomain());
            return $result_delete;
        }


        $this->debug()->logVar('$addons_directory_path = ', $addons_directory_path);
        $this->debug()->logVar('$temp_directory = ', $temp_directory);
        $this->debug()->logVar('$this->plugin()->tools()->getRelativePath($addons_directory_path, $temp_directory) = ', $this->plugin()->tools()->getRelativePath($addons_directory_path, $temp_directory));




        /*
         * do some basic checks before deleting
         * Want to make sure we are deleting a subdirectory of the plugin directory,
         * and hopefully, the temp directory in the addons directory
         */
        if (
//                strpos($temp_directory, $plugin_directory_path) !== false  //verify the temp directory contains the plugin path
//                && strpos($temp_directory, $addons_directory_path) !== false //verify the temp directory contains the Addons Directory path
//                && strpos($temp_directory, $addons_directory_path . '/temp') !== false //verify the temp directory contains its own path ( in event the variable $temp_directory wasnt set properly)
//
                true) {

            $this->debug()->logVar('Will be deleting $temp_directory = ', $temp_directory);

            $result_delete['success'] = $wp_filesystem->delete($temp_directory, true); //source says not to use rmdir, true for
            $this->debug()->logVar('$result_delete = ', $result_delete);

            if ($result_delete['success'] === true) {
                $result_delete['message'] = '';
                return $result_delete;
            } else {
                $result_delete['message'] = 'WP_Filesystem could not delete temporary directory';
                return $result_delete;
            }
        } else {
            //this is a safeguard against mistakenly deleting the entire Addons directory if the temp_unzip_dir_path is not set properly
            $result_delete['message'] = __('Temp Directory ' . $temp_directory . ' is invalid', $this->plugin()->getTextDomain());
            $this->debug()->logError('Check the $temp_directory, it didnt pass the deletion safety checks, ensure the directory being deleted is the correct one.');
            $this->debug()->logVar('$temp_directory = ', $temp_directory);

            $result_delete['success'] = false;
            return $result_delete;
        }
    }

    /**
     * Hook - File System Method
     *
     * This hook serves to override the get_filesystem_method() which provides the
     * method the WP_Filesystem uses to access the server's files.
     * Return the suffix of the File System Class you want to use.
     *
     * The docs (ref: http://core.trac.wordpress.org/browser/tags/3.6.1/wp-admin/includes/file.php#L857 )
     * say that valid text to return are : 'direct','ssh','ftpext' or 'ftpsockets'
     * BUT, i've found that these fail, and you need to use:
     * Direct, FTPext , SSH2 , and 'ftpsockets'  . The case is important since
     * whatever is returned is used as the suffix of the class name
     * to instantiate the filesystem class.
     *
     * You can easily verify this by looking at the source link:
     * http://core.trac.wordpress.org/browser/tags/3.6.1/wp-admin/includes/file.php#L816
     *  and then doing a search for 'extends WP_Filesystem_Base' in
     * your own installed source.
     *
     * To set the method, you can rely on the default or :
     * 1) define('FS_METHOD',<method>);	 in your wp-config.php
     * 2) use this filter by add_filter(''filesystem_method',array($this,'fileSystemMethod'));
     *
     * Option 2 ( the filter ) will override the FS_METHOD constant.
     *
     * @param none
     * @return void
     */
    public function fileSystemMethod($method) {






        //    return ('FTPext');/ ftpsockets FTPext
        //   $method = 'Direct';
//        $method = 'FTPext';
        //$method = 'ftpsockets'; //works, regular ftp
        $method = 'SSH2'; //works only if i give it a connection
//        $this->debug()->logVar('Forcing use of $method  = ', $method);
        return $method;
    }

    /**
     * Get Add Ons Directory Path FS
     *
     * Get the Addons Directory Path normalized for File System.
     * Each of the WP_Filesystem methods start from their root, which may be
     * different between file systems. This method ensures that we return the
     * correct path
     *
     * @param none
     * @return void
     */
    public function getNormalizedFilesystemPath($path, $method) {

        $method = strtolower($method);
        $this->debug()->logVar('$path = ', $path);
        $this->debug()->logVar('ABSPATH = ', ABSPATH);
        $this->debug()->logVar('$method = ', $method);

        switch ($method) {

            case 'ftpext':
            case 'ssh2':
            case 'ftpsockets':
                if (defined('FTP_BASE')) {
                    $base = FTP_BASE;
                } else {
                    $base = ABSPATH;
                }

                $relative_path = $this->plugin()->tools()->getRelativePath($base, $path);
                $this->debug()->logVar('$relative_path = ', $relative_path);

                return ($relative_path);
                break;


            case 'direct':
                return $path;

            default:
                return $path;
        }
    }

}
?>