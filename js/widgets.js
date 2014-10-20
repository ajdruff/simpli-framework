/* /*
 * 
 * Javascript to support widget operations
 * 
 * widgets.js
 * 
 */

nomstock.com.log('loaded widgets.js');



/*
 * create the script's namespace
 */
if (typeof nomstock.com.widgets === 'undefined') {
    nomstock.com.widgets = {};
}


/*
 * Uses Local Storage
 * http://stackoverflow.com/questions/4170319/jquery-plugin-for-html-5-local-storage
 * 
 */
jQuery(document).ready(function() {
    
    /*
     * Use Local Storage to Check for A Previously 
     * Used WidgeCode and populate your form with that.
     */
    
    nomstock.com.widgets.AutoFillWidgetCode();
    
    /*
     * 
     * Configure Markitup TextArea
     */
  //  nomstock.com.widgets.configureMarkitup();
    
});


/*
 * Declare Variables for Widgets Namespace
 */
nomstock.com.widgets.widgetFormElement = jQuery('#widget_code_1');

nomstock.com.widgets.key = 'savedWidgetCode';

/*
 * AutoFillWidgetCode
 * Manages the saving and retrieving of the Widget Code from local storage
 */
nomstock.com.widgets.AutoFillWidgetCode = function() {



    // use jStorage to retrieve a stored key
    // on first load this is going to return undefined
    widgetCode = localStorage.getItem(nomstock.com.widgets.key);

    // if a widgetCode was saved from previous session
    // set the value of the widgetCode field to that
    if (widgetCode) {
        nomstock.com.widgets.widgetFormElement.val(widgetCode);

    }

    // if widgetCode wasn't saved then
    // set widgetCode field value to blank and focus on it
    // and make sure the checkbox is unchecked
    else {
        nomstock.com.widgets.widgetFormElement.val('').focus();
    }


}

/*
 * Namespace hook to form submit
 * This is fired using the form-menu-hooks.js plugin
 */
nomstock.com.widgets.saveWidgetCode = function(trigger_event, args) {

    localStorage.setItem(nomstock.com.widgets.key, nomstock.com.widgets.widgetFormElement.val());

    nomstock.com.log('saved to local storage');

}

nomstock.com.widgets.addHooks = function()

{
    /*
     *
     * Add action hooks here
     * Usage:
     * nomstock.com.add_action('name_of_action_tag', function_name_without_quotes_around_it);
     *
     */



    nomstock.com.add_action(nomstock.com.vars.plugin.slug + '_submit_prompt_' + 'submit_domain', nomstock.com.widgets.saveWidgetCode);
    
}

/*
 * Configure TextArea to use Markitup, a Markdown text editor
 */
nomstock.com.widgets.configureMarkitup = function()

{
 /*
  * I couldn't get any other selector to work except textarea
  */
 
      jQuery("textarea").markItUp(mySettings);


}