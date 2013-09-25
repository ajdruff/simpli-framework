<?php

/*
 * Ajax Message
 *
 * Handles the output of ajax messages, formatted for WordPress Admin
 *
 *
 */


/*
 * Check for errors

  $errors[]='That was definitly the wrong answer';
 * $errors[]='Nope, try again';
  $errors[]='Can\'t you get anything right?';

 */

if (sizeof($errors) > 0) { //if there are error messages, display them

    /*
     * Build an Error Template and Process it
     */
    $error_html = '';
    foreach ($errors as $error) {
        $error_html.= '<li><p>' . $error . '</p></li>';
    }
    $error_html.= '</ul></div>';
    $tags['ERROR_HTML'] = $error_html;

    $template = '
    <div class="error below-h2 fade" id="message">

    <ul>
    {ERROR_HTML}
    </ul>
    </div>';
} else { // but if there are no errors...
    /*
     * Build a Message Template and Process it
     */
    $tags['MESSAGE'] = $message;
    $tags['RELOAD_SCRIPT'] = ( $logout || $reload ) ? '<script type="text/javascript">var d = new Date();window.location = window.location.href+\'&\' + d.getTime();</script>' : '';
    // $template = '<div class="updated below-h2 fade" id="message"><p>{MESSAGE}</p></div>{RELOAD_SCRIPT}';
    //  $template = '<div style="" id="message"><p>{MESSAGE}</p></div>{RELOAD_SCRIPT}';
    $template = '<span style="background:#FFFBCC" id="message">{MESSAGE}</span>{RELOAD_SCRIPT}';
    $template = '<div class="updated below-h2 fade" id="message"><p>{MESSAGE}</p></div>{RELOAD_SCRIPT}';
}

if (!$this->plugin()->DEBUG) //if debug is not on, then check if its an ajax request.We dont want to check ajax when debugging since we want to be able to call it directly
    if (!$this->plugin()->tools()->isAjax()) {
        return '';
    }

/*
 * Clean Buffers
 * Get rid of all the output buffers and end output buffering
 * ao as to ensure nothing is output except what follows after the cleaning
 * Do not clean if we are using compression since compression makes use of the buffers,
 * so we would lose our content otherwise.
 */
if (!$this->plugin()->COMPRESS) {
    while (@ob_end_clean());
}

/*
 * Finally output the template
 */
echo $this->plugin()->tools()->crunchTpl($tags, $template);
?>