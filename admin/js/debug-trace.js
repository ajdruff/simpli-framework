/**
 * Debug Trace
 *
 * Hides or Displays a 'more' debug block on click
 * Always load this in the header
 * @package SimpliFramework
 * @subpackage SimpliHello
 */


    simpli.hello.bind_debug_events = function()
{
 
    var previous_trace_link_text = ''; //remembers the previous link text so we can revert to it when we collapse the trace
    var less_link_text = '<em>Less</em>';
    jQuery('.simpli_debug_more').click(function(e) {

        e.preventDefault();


        el = jQuery(this).parent().parent().find('.simpli_debug_toggle');

        if (el.css('visibility') === 'visible') {

            el.css('visibility', 'hidden').css('display', 'none');
            jQuery(this).html(previous_trace_link_text);
        }
        else {
            previous_trace_link_text = jQuery(this).html();

            el.css('visibility', 'visible').css('display', 'block')

            jQuery(this).html(less_link_text);
        }
        });

}


/*
 * Must come after function definition and not surrounded by ready
 */
simpli.hello.bind_debug_events();