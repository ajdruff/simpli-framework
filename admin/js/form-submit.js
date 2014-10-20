/**
 * Ajax Submit Form
 *
 * Handles the Plugin's submission of a form either with or without ajax. This includes the  insertion of the nonce and the management of the checkbox inputs
 * called by event bindings in the form-menu-events.js and form-post-events.js scripts.
 * Loaded by the Metabox class
 *
 * form-submit.js
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliFrames
 */


/*
 * create the script's namespace
 */

if (typeof nomstock.com.submit === 'undefined') {
    nomstock.com.submit = {};
}


/**
 * Prep Form
 *
 * Prepare the submitted form by adding
 * the nonce and hidden inputs that track the unchecked checkboxes
 *
 * @param object form The form object
 * @returns void
 */

jQuery(document).ready(function() {
    nomstock.com.log('form-submit.js loaded inline');
});

nomstock.com.submit.prepare_form = function(form)
{



    /*
     * add the nonce for our post options
     */
//    $nonce_element = '<input type="hidden" name="' + nomstock.com.vars.plugin.slug + '_nonce" value="' + nomstock.com.vars.save_post_option_nonce + '">';
//
//    jQuery(form).append($nonce_element);


    /*
     * Get all the unchecked checkboxes
     *
     * Explanation of selector:
     * input[id^='" + nomstock.com.vars.plugin.slug + "']  //find all inputs that begin with nomstock.com.vars or whatever your plugin slug is
     *
     * :checkbox:not(:checked)   //of those, get all checkboxes that are not checked
     */

    /*
     * Framework fix: found an error in the code that added unchecked boxes - removed the plugin.slug from the search criteria.
     * 
     * $unchecked_checkboxes = jQuery(form).find("input[id^='" + nomstock.com.vars.plugin.slug + "']:checkbox:not(:checked)");
     */
    $unchecked_checkboxes = jQuery(form).find("input:checkbox:not(:checked)");
    //jQuery('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
    /*
     * Insert a hidden input field containing the unchecked checkbox's value
     */
    $unchecked_checkboxes.each(function() {

        jQuery(this).after('<input class="hidden-temp" type="hidden" name="' + jQuery(this).attr('name') + '">');
    })
}


/*
 * Reset Checkboxes
 *
 * Removes the hidden input fields that were
 * added on the previous submission
 *
 * @param {type} form
 * @returns {undefined}
 */
nomstock.com.submit.reset_checkboxes = function(form) {
    /*
     * remove any temporary hidden fields that were previously added.
     */
    jQuery(form).find('.hidden-temp').remove();
};
/*
 * Ajax Submit
 *
 * Submits form using ajax and available nonces.
 *
 * @param {type} form
 * @returns {undefined}
 */



/**
 * Show Ajax Response
 *
 * Shows ajax response message
 * Intended to be called only by the nomstock.com.submit.ajaxSubmit method
 * 
 * @param object form The form currently being processed
 * @param object responseObject The response object as created by nomstock.com.submit.ajaxSubmit
 * 
 * @return string The parsed output of the form body tag 
 */

nomstock.com.submit.show_ajax_response = function(form, responseObject) {

    /*
     * display the response differently, depending
     * on whether we are debugging
     *
     */

    nomstock.com.log('debug is ' + 'on');
    nomstock.com.log('nomstock.com.vars.plugin.debug =  ' + nomstock.com.vars.plugin.debug);



    /*
     * If an element exists within the form with class ='.simpli_forms_response',
     * then use it. otherwise, use the popup if available.
     */
    var output_element;
    if (typeof nomstock.com.vars.forms['target'] !== 'undefined') {
        output_element = jQuery('#' + nomstock.com.vars.forms['target']);
    }
    else if (jQuery(form).find('.simpli_forms_response').length > 0) {
        output_element = jQuery(form).find('.simpli_forms_response');
    } else {

        if (jQuery('#message-body').length > 0) {
            output_element = jQuery('#message-body');
            if (nomstock.com.vars.plugin.debug) {
                /*
                 * don't use message-body if debugging since it floats and is difficult to read. instead add a debugging container.
                 */
                if (jQuery('#debug-messages').length === 0) {//if a debug message container doesnt exist

                    /*
                     * then add it
                     */
                    jQuery(form).after('<div id="debug-messages"></div>');
                }

                output_element = jQuery('#debug-messages');
                output_element = jQuery('#message-body');
            }
        } else {// if there is no other target found, replace the form itself with the message

            output_element = jQuery(form);

        }


    }


    /*
     * Reset Validation Class Main Message Output
     * Remove previous validation message styling so we can start fresh
     *  
     */
    output_element.removeClass('sf-error').removeClass('sf-success');

    /*
     * Handle Successful Result Messages
     */
    if (responseObject.successResult === true) {


        /*
         * if debugging or nomstock.com.vars.response_fadeout=false, don't clear response message.
         */

        if (nomstock.com.vars.plugin.debug || (nomstock.com.vars.forms.response_fadeout===false)) {

            output_element.html(responseObject.successMessage).addClass('sf-success').fadeOut(0).fadeIn().delay(5000);
        }

        else {

            output_element.html(responseObject.successMessage).addClass('sf-success').fadeOut(0).fadeIn().delay(5000).fadeOut();


        }
/*
 * Finally, hide the form if the result is a success
 */
if (typeof nomstock.com.vars.forms['hide_form'] === true) {
if (jQuery(form).length > 0) {
    jQuery(form).hide();
}

}



    } else if (responseObject.successResult === false) {
        
        
                /*
         * Handle Error Result Messages
         */

        
        /*
         * If debugging, then don't clear the response message
         * once its displayed. This is because the response may be
         * quite long and the user will need time to analyze it
         */
        
      //  jQuery(output_element).closest("form").prepend('<div id="simpli_forms_target_error">error</div>').center();
     //    jQuery(output_element).append('<div id="simpli_forms_target_error">error</div>');
         
      //   jQuery('#simpli_forms_target_error').center();
        
        /*
         * 
         * Float a Div with the error message above the form
         * Outputting to an element that is not the target element is necessary since outputting to the target element, depending on where it is located,
         * may replace the form, preventing the user from being able to fix his errors.
         * 
         * 
         */

    jQuery('<div></div>').css({
        'position': 'absolute',
        'top': '50%',
        'left': '50%',
            'width': '48%',
    
        'transform': 'translate(-50%, -50%)',
        'z-index': '100'
    }).prependTo(form).html(responseObject.errorMessage).addClass('sf-error').fadeIn().delay(2500).fadeOut(500, function(){
  if (nomstock.com.vars.plugin.debug){
        jQuery(this).fadeIn();
  
  }else{
       jQuery(this).remove();
  }
          });


        
//        if (nomstock.com.vars.plugin.debug|| (nomstock.com.vars.forms.response_fadeout===false)) {
//
//            output_element.html(responseObject.errorMessage).addClass('sf-error').fadeOut(0).fadeIn().delay(5000);
//        }
//
//        else {
//
//            output_element.html(responseObject.errorMessage).addClass('sf-error').fadeOut(0).fadeIn().delay(5000).fadeOut();
//
//
//        }

        /*
         * Populate Validation Error Messages
         */

/*
 * Reset the classes for validation messages
 */
jQuery(form).find( "[data-sf-valid]" ).addClass('sf-valid').removeClass('sf-valid-error').removeClass('sf-valid-success');


        jQuery.each(responseObject.validationMessages, function(name, message) {




            jQuery.each(message, function(type, validation_html) {

                if (type === 'error') {
                    jQuery(form).find('[data-sf-valid="' + name + '"]').removeClass('sf-valid').removeClass('sf-valid-success').removeClass('sf-valid-error').addClass('sf-valid-error').html(validation_html);
                } else if (type === 'success') {
                    jQuery(form).find('[data-sf-valid="' + name + '"]').removeClass('sf-valid').removeClass('sf-valid-success').removeClass('sf-valid-error').addClass('sf-valid-success').html(validation_html);
                }

                nomstock.com.log('type = ' + type + '; name=' + '[data-sf-valid="' + name + '"]' + 'html = ' + validation_html);
            })




        })





    }









}

nomstock.com.submit.ajaxSubmit = function(form, event, ajax_action_slug) {
    nomstock.com.log('ajax_action_slug=' + ajax_action_slug);
    var triggeredBy;
    /*
     * How to tell if clicked by script
     * http://stackoverflow.com/a/6982085/3306354 
     * ref: http://jsfiddle.net/fN8h7/2/
     * 
     */
    if (event.which) {
        triggeredBy='mouse';
        
        nomstock.com.log('clicked by a mouse');
    }else{
       triggeredBy='script'; 
         nomstock.com.log('clicked by a script');
    }
    
    event.preventDefault();
    var ajax_action = nomstock.com.vars.plugin.slug + '_' + ajax_action_slug; //set the ajax action for WordPress
    nomstock.com.submit.prepare_form(); //prepared the form for submission - adds checkboxes

    var output_element = {};

    /*
     * WordPress normally makes ajaxurl available within Admin.
     * If it hasn't been defined, we are likely trying to make
     * an ajax request outside of admin. In that case,
     * we can use the localized version
     */
    if (typeof ajaxurl === 'undefined') {
        ajaxurl = nomstock.com.vars.forms['ajaxurl'];
        nomstock.com.log('ajaxurl = ' + ajaxurl);

    }


    /*
     * if unique nonces are not configured, then use the default
     */
    if (!nomstock.com.vars.forms.unique_action_nonces) {

        ajax_action_slug = 'default';
    }
    nonce_value = nomstock.com.vars.forms.nonce_values[ajax_action_slug];  
    nomstock.com.log('ajax form submitted, used nonce value for \'' + ajax_action + '\' = ' + nonce_value);
    /*
     * Show the ajax spinner image
     * This appears when waiting for an ajax response
     */
    jQuery(form).find('.submit-waiting').show();



/*
 * Add Spam Controls
 * If spam controls on, then replace timestamp with one 2 days in the future
 * but only if this event was fired by the mouse and not by a script
 * http://jsfiddle.net/fN8h7/2/
 * http://stackoverflow.com/a/5971324/3306354
 */

    if (nomstock.com.vars.forms.spam_controls===true && triggeredBy==='mouse') {
     var timestamp = Math.round(+new Date()/1000);
timestamp += 3600 * 24 *2 ; //3600 secs * hours * days
//var datetime = new Date(timestamp*1000); //convert back to date object
jQuery('#sf-as-time').attr('value',timestamp);
   
    }

    
    /*
     * Submit the Ajax Request
     */



    data =
            /*
             * Form Fields
             *
             * Serialize is used to turn all the form's fields
             * into a query string that is ready for posting
             */
            jQuery(form).serialize()
            /*
             * Ajax Action
             *
             * This will tell WordPress ajax script which hook to call
             */
            + '&action=' + ajax_action
            /*
             * HTTP Referer URL
             *
             * Pass the current page relative url as the referring url
             * This uses escape() so the string wont be interpreted as fields
             * jQuery(location).attr('search') is the query string
             */
            + '&_nomstock_forms_referer_url=' + escape(jQuery(location).attr('pathname') + jQuery(location).attr('search'))

            /*
             * Form Nonce
             *
             * Pass the nonce that was created when we Enqueued the script
             * We pass it here so the user doesnt need to remember to
             * add it in the templates
             * you can find the localized statements where this script is enqueued.
             */
            + '&' + nomstock.com.vars.forms.nonce_field_name + '=' + nonce_value;


    success = function(response) {
      
        var responseObject = {};
        try {
            responseObject = jQuery.parseJSON(response);
        } catch (err) {
            /*
             * if an error occurs while parsing,
             * this likely means the returned response is 
             * just a normal string, so set it as the successMessage
             * assume the message is successful since we dont have
             * any error information
             */
            nomstock.com.log(err.message);

            responseObject.successResult = true;
            responseObject.successMessage = response;
            responseObject.errorMessage = response;
            responseObject.validationMessages = [];
        }



        /*
         * if result is false, then cycle through validation messages and populate them
         */

        /*
         * First, Clear any validation messages that occurred in the previous submission
         */
        jQuery(form).find("[data-sf-valid]").addClass('sf-valid').removeClass('sf-valid-success').removeClass('sf-valid-error').html('');

        /*
         * Next, add validation messages
         * Iterate over the messages returned, and populate their corresponding validation tags
         */
///jQuery(form).find( "[data-sf-valid]" ).addClass('sf-valid-error').removeClass('sf-valid').html('error');


        if (jQuery.isArray(responseObject.validationMessages)) {
            
     
        jQuery.each(responseObject.validationMessages, function(name, message) {

            jQuery(form).find("[data-sf-valid='" + name + "']").addClass('sf-valid-error').removeClass('sf-valid').html(message);

            nomstock.com.log("Name: " + name + ", Message: " + message);
        })
   }


        nomstock.com.submit.reset_checkboxes(form);
        jQuery(form).find('.submit-waiting').hide();


        /*
         * Show the response message
         */
        nomstock.com.submit.show_ajax_response(form, responseObject);



        return false;
    }



    /*
     * Set Method based on Form's method attribute
     */
    if (jQuery(form).attr('method').toUpperCase() === 'GET') {
        nomstock.com.log('submitting via ajax and GET method');
        method = 'GET';
    } else {
        nomstock.com.log('submitting via ajax and POST method');
        method = 'POST';
    }

    jQuery.ajax({
        type: method,
        url: ajaxurl,
        data: data,
        success: success,
        dataType: 'html' //xml,html,json,jsonp,text http://api.jquery.com/jQuery.ajax/
    });





}; //end of the definition of ajaxSubmit
/*
 * Non-Ajax Submit
 *
 * Submits form using ajax and available nonces.
 *
 * @param  object form
 * @param  object event
 * @param string form_action_slug
 * @returns {undefined}
 */

nomstock.com.submit.nonAjaxSubmit = function(form, event, form_action_slug) {

    var nonce_index = form_action_slug;
    nomstock.com.log('form_action_slug=' + form_action_slug);
    form_action = nomstock.com.vars.plugin.slug + '_' + form_action_slug; //set the form action
    nomstock.com.log('submitting the form using a non-ajax request');
    nomstock.com.submit.prepare_form(); //prepare the form by adding checkboxes for false values
    /*
     * if unique nonces are not configured, then use the default
     */
    if (!nomstock.com.vars.forms.unique_action_nonces) {

        nonce_index = 'default';
    }
    nonce_value = nomstock.com.vars.forms.nonce_values[nonce_index];
    nomstock.com.log('Non-Ajax form submitted, used nonce value for \'' + nonce_index + '\' = ' + nonce_value);
    /*
     * Show the spinner image
     * It will spin until the form submits and refreshes the page,
     * so no need to clear it ( remember, this is a non-ajax request)
     */
    jQuery(form).find('.submit-waiting').show();
    /*
     * update the action attribute
     */
    form_action_attribute = jQuery(form).attr('action');
    /*
     * if using a GET method, we must make sure the action attribute is added to
     * the form submission so it gets added back to the url. otherwise, it will
     * be replaced by the form's field values.
     */
//preserveActionParms();
    if (jQuery(form).attr('method').toUpperCase() === 'GET') {
        actionParms = jQuery.url(form_action_attribute).param();

//        jQuery.each(actionParms, function(key, value) {
//            var_name = key;
//            var_value = value;
//            /*
//             * append the GET action value to the form as a hidden value
//             */
//            jQuery(form).append('<input type="hidden" name="' + var_name + '" value="' + var_value + '">');
//
//
//        }
//
//
//
//        ) //end for each

        /*
         * add action
         */
        nomstock.com.vars.query_var_action = 'nomstock_com_action';
        jQuery(form).append('<input type="hidden" name="' + nomstock.com.vars.query_var_action + '" value="' + form_action_slug + '">');


        var _nomstock_forms_referer_url = escape(jQuery(location).attr('pathname') + jQuery(location).attr('search'));
        nomstock.com.log('_nomstock_forms_referer_url = ' + _nomstock_forms_referer_url);
        jQuery(form).append('<input name="_nomstock_forms_referer_url" value="' + _nomstock_forms_referer_url + '" type="hidden" >');
    }

//    nomstock.com.logWarn('bailed out of form submission');
//    event.preventDefault();
//    return(false);
    /*
     * append the nonce field to the form
     */
    jQuery(form).append('<input type="hidden" name="' + nomstock.com.vars.forms.nonce_field_name + '" value="' + nonce_value + '">');
    /*
     * append the id of the form
     */
    jQuery(form).append('<input type="hidden" name="nomstock_forms_id" value="' + jQuery(form).attr('id') + '">');






    /*
     * submit the form
     */
    form.submit();


    /*
     * Add the nonce value to the form , if not already added.
     */
//+ '&action=' + ajax_action
// + '&' + nomstock.com.vars.forms.nonce_field_name + '=' + nonce_value

};
