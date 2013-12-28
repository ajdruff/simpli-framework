<?php echo 'web server running as ' . (exec("whoami")); ?>

<div style="padding:20px;" class="misc-pub-section">
    <?php
    $dest_parent_dir = $this->plugin()->getAddonsDirectory();

    $url = $this->plugin()->tools()->getCurrentWPURL(); //where you want the credential values posted back to . we'll post the values back to this page so we can add the values to the form that we'll submit.
    //works:  $url = 'http://wpdev.com/wp-admin/edit.php?post_type=sf_snippet&page=simpli_test_menu20_dev_manage';
    $this->debug()->logVar('$url = ', $url);


    /*
     * request_filesystem_credentials() will return false and display a form when it cant write to the test directory.
     */
    $creds = request_filesystem_credentials(
            $url, //$form_post url The url where you want the credential values posted to.
            '', //The type of method to use for the WP_Filesystem. This is normally determined automatically, and so should remain a blank string. //Options must match the available suffix extensions of the subclasses here: http://www.tig12.net/downloads/apidocs/wp/1.packages/WordPress_Filesystem.html . Try 'Direct' , 'FTPext' , 'ftpsockets', and 'SSH2' .
            false, //$error false if no error was detected, true if there's an error in initializing the WP_Filesystem class.
            $dest_parent_dir, //$context The directory path to test for filesystem methods in. or false for the default case. WordPress will try to write to this directory with the different methods. This for example, might be the addons directory.
            null // $extra_fields An array of form field names to "pass-through" the credentials form. Information in the $_POST array will be used along with this array to build hidden inputs into the POST form, so that the data returns with credentials for later processing. Null if no extra fields are needed.
    );






    if (false === $creds) {// if $creds is false, it means it couldnt write directly, so requesting credentials
        $this->debug()->logVar('$creds = ', $creds);
        $this->debug()->log('Direct file acccess  <strong>failed</strong>, so displaying form to request creds from user');
        ?></div><?php
    return; //when authentication fails, exit this include now so we dont show the rest of the form.
} elseif (true === $creds) {

    $this->debug()->logVar('$creds = ', $creds);
//The request_filesystem_credentials call will test to see if it is capable of writing to the local filesystem directly without credentials first. If this is the case, then it will return true and not do anything. Your code can then proceed to use the WP_Filesystem class.
    $this->debug()->log('Direct Access <strong>Worked</strong> , so not displaying the creds form, proceeding to show the upload form.');
}

if (is_array($creds)) {
    $this->debug()->logVar('Now that we have the user supplied credentials, we will attempt to use them for file access using WP_Filesystem', $creds);
}
/*
 * if direct access successed, $creds will be true, so will proceed to show your form and not display the connection information form
 * if direct access failed,$creds will be false, so will request credential information from user by displaying a form
 * if the user completed the form, $creds will contain the fields completed by the user, and you can then attempt to use them to start WP_Filesystem.
 */
$result_wp_credential_check = WP_Filesystem($creds);
if ($result_wp_credential_check === false) {//try to start WP_Filesystem with the credentials provided.If they dont work, display the authentication form again.
    $this->debug()->log('WP Filesystem check of credentials failed');

    $creds = request_filesystem_credentials(
            $url, //$form_post url The url where you want the credential values posted to.
            '', //The type of method to use for the WP_Filesystem. This is normally determined automatically, and so should remain a blank string. //Options are  either 'direct', 'ssh', 'ftpext' or 'ftpsockets'
            true, //$error false if no error was detected, true if there's an error in initializing the WP_Filesystem class. By setting this to true, an admin error will automatically be generated and shown above your form, informing the user 'ERROR: There was an error connecting to the server, Please verify the settings are correct.'
            $dest_parent_dir, //$context The directory path to test for filesystem methods in. or false for the default case. WordPress will try to write to this directory with the different methods. This for example, might be the addons directory.
            null // $extra_fields An array of form field names to "pass-through" the credentials form. Information in the $_POST array will be used along with this array to build hidden inputs into the POST form, so that the data returns with credentials for later processing. Null if no extra fields are needed.
    );

    return;
} elseIf (true === $result_wp_credential_check) {
    global $wp_filesystem;
    $filesystem_class = get_class($wp_filesystem);
    $this->debug()->logVar('Class of WP_Filesystem being used  = ', $filesystem_class);
    $this->debug()->log('Accessing the WP_Filesystem using class ' . $filesystem_class . ' and the supplied credentials worked. We will now add the credential information to the form so they can be used by the form handler which will use them to write the uploaded file to the destination directory');
} else {

    $this->debug()->logVar('$result_wp_credential_check = ', $result_wp_credential_check);
}
?>






<?php
/*
 * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
 * and output of any of the fields by modifying the attributes before they are processed in the template
 */
$f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');


$f->formStart(array(
    'theme' => 'Admin'
    , 'filter' => array(
        'Settings'
    )
    , 'enctype' => "multipart/form-data"
    , 'name' => 'simpli_forms'
    , 'method' => 'post'
//      , 'action' => '/?simpli_frames_action = upload_addon' //normally, the framework will supply this based on the value of the button action (either for ajax or non-ajax). you should only use it if you want to override that behavior.
    , 'ajax' => false
        )
);

/*
 * If $creds is an array, pass all the values as an array.
 * Otherwise, if $creds is true, then just pass its value on.
 */
if (is_array($creds)) {


    foreach ($creds as $key => $value) {
        echo '<input type="hidden" name="simpli_frames[creds][' . $key . ']" value="' . $value . '">';
    }
} else {

    echo '<input type="hidden" name="simpli_frames[creds]" value="' . $creds . '">';
}

$f->el(array(
    'el' => 'file',
    'name' => 'addon_zip_file',
    'label' => 'Addon File:',
    'hint' => 'Upload the Addon zip file',
    'heading' => 'Addon Upload'
        )
);





$f->formEnd(
        array('template' => 'formEndUploadAddon'));
?>
</div>