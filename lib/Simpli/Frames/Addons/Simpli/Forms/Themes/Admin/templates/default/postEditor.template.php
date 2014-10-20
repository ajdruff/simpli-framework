<?php
/* Post Editor Template
 * This is the same html as what is found in wp-admin/edit-form-advanced.php , between the id=postdivrich divs.
 * The filters add the necessary php code.
 */
?>
<div id="{ID}" style="visibility:hidden" class="postarea">
    {WP_EDITOR}
    <table id="post-status-info" cellspacing="0"><tbody><tr>
                <td id="wp-word-count">
                    {WORD_COUNT}
                </td>
                <td class="autosave-info">
                    <span class="autosave-message">&nbsp;</span>
                    {LAST_EDIT}

                </td>
            </tr></tbody></table>

</div>
<?php
/*
 * Need a bit of javascript to show editor only after it has finished rendering.
 * Because we are using wp_editor in a unconventional way ( we are capturing its output, then sending it to a template),
 * the editor will first display a non-tinymce textarea, then render the tinymce area on top of it. This process is shown to the user
 * while its happening, so it makes the page rendering
 * look very sloppy. By hiding the editor by default, then showing it on page load, we give tinymce time to render and you dont
 * get the sloppy behavior. You could go a step futher and place a ajax spinner while this is happening if it makes you feel better :)
 */
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#{ID}').css('visibility', 'visible');
    });
</script>
