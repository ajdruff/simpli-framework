
###Common Command Line Commands

##to output the cron script to html for viewing:##  
    cd /cygdrive/c/wamp/www/nomstock-dev.com/cron
    php nstock-domain-list-notify.php > /cygdrive/c/wamp/www/dev.com/cron_out.html


##To Start Jekyll Server
    cd /cygdrive/c/wamp/www/nomstock-dev.com/public_html/wp-content/content/published
    jekyll server --watch
 

###Changes to Framework

1. modified the getQueryVar method to use get_query_var
2. modified some of the patterns used in Query Var
3. Added a configuration variable for Query Var
4. Added a Templates Module
5. fixed bug in Tools base module to prevent crunchTPL from throwing error when $tags is not an array.
6. need to make sure that simpli_forms_response remains in tact after search and replace. this is the form's class that tells form-submit.js where to place the response message.
7. added the Forms addon which is required for the Simpli Forms module to handle front end forms
7a. added bootstrap theme to simpli forms (yeah!)
8. some minor changes to the forms-submit script to handle front end forms

9. fixed a bug in Plugin.php that echoed to the browser with missing script file.
--------------------- fixed code----------
                    $this->debug()->logError('couldnt load script: ' . $handle . ' due to missing script file ' . $script['path']);
                    echo '<script type="text/javascript">jQuery(document).ready(function() { console.error (\' WordPrsss Plugin ' . $this->getSlug() . ' attempted to enqueue ' . ' Missing Script File ' . str_replace('\\', '\\\\', $script['path']) . '\')});</script>';
                    
                    ------------------------

