/* /*
 * 
 * Javascript to support widget operations
 * 
 * widgets.js
 * 
 */

simpli.frames.log('loaded widgets.js');



/*
 * create the script's namespace
 */
if (typeof simpli.frames.widgets === 'undefined') {
    simpli.frames.widgets = {};
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
    
    simpli.frames.widgets.AutoFillWidgetCode();
    
    /*
     * 
     * Configure Markitup TextArea
     */
  //  simpli.frames.widgets.configureMarkitup();
    
});


/*
 * Declare Variables for Widgets Namespace
 */
simpli.frames.widgets.widgetFormElement = jQuery('#widget_code_1');

simpli.frames.widgets.key = 'savedWidgetCode';

/*
 * AutoFillWidgetCode
 * Manages the saving and retrieving of the Widget Code from local storage
 */
simpli.frames.widgets.AutoFillWidgetCode = function() {



    // use jStorage to retrieve a stored key
    // on first load this is going to return undefined
    widgetCode = localStorage.getItem(simpli.frames.widgets.key);

    // if a widgetCode was saved from previous session
    // set the value of the widgetCode field to that
    if (widgetCode) {
        simpli.frames.widgets.widgetFormElement.val(widgetCode);

    }

    // if widgetCode wasn't saved then
    // set widgetCode field value to blank and focus on it
    // and make sure the checkbox is unchecked
    else {
        simpli.frames.widgets.widgetFormElement.val('').focus();
    }


}

/*
 * Namespace hook to form submit
 * This is fired using the form-menu-hooks.js plugin
 */
simpli.frames.widgets.saveWidgetCode = function(trigger_event, args) {

    localStorage.setItem(simpli.frames.widgets.key, simpli.frames.widgets.widgetFormElement.val());

    simpli.frames.log('saved to local storage');

}

simpli.frames.widgets.addHooks = function()

{
    /*
     *
     * Add action hooks here
     * Usage:
     * simpli.frames.add_action('name_of_action_tag', function_name_without_quotes_around_it);
     *
     */



    simpli.frames.add_action(simpli.frames.vars.plugin.slug + '_submit_prompt_' + 'submit_domain', simpli.frames.widgets.saveWidgetCode);
    
}

/*
 * Configure TextArea to use Markitup, a Markdown text editor
 */
simpli.frames.widgets.configureMarkitup = function()

{
 /*
  * I couldn't get any other selector to work except textarea
  */
 
      jQuery("textarea").markItUp(mySettings);


}