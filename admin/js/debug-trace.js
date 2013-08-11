/**
 * Debug Trace
 *
 * Functions supporting the Debug module trace method
 * Always load this in the header
 * @package SimpliFramework
 * @subpackage SimpliHello
 */



/**
 * Binds a collapsible item's click events
 *
 * This script will toggle collapse/expand divs between hidden and visible states
 * Usage: Use HTML in the following format (Example below). The content must be surrounded by a hidden div which is in turn surrounded by a div that contains an anchor element, and 2 spans which contain the anchor text that holds the expand/collapse text.
 * Note that the Expand Span element must always come before the Collapse element
 * For a working example, see the v() method in the Debug module.
 *         <div style="display:inline-block;">
 <a class="simpli_debug_citem" href="#"><span>More</span><span style="visibility:hidden;display:none">Less</span></a>
 <div style="visibility:hidden;display:none;">
 {CONTENT}
 </div>
 </div>
 * @package MintForms
 * @since 0.1.1
 * @uses
 * @param string $content The shortcode content
 * @return string The parsed output of the form body tag
 */

simpli.hello.debug_bind_collapse_expand_events =
function()
        {



    jQuery('.simpli_debug_citem').click(function(e) {

        e.preventDefault();


        el = jQuery(this).parent().find('div:first');//get the child div of the parent div of the <a> tag >
        anchor_text_expand_element = jQuery(this).find('span:first');//.html();

        anchor_text_collapse_element = anchor_text_expand_element.next('span');

        if (el.css('visibility') === 'visible') {
            /*
             * If already visible, hide it and update the anchor text
             */
            el.css('visibility', 'hidden').css('display', 'none');
            anchor_text_collapse_element.css('visibility', 'hidden').css('display', 'none');
            anchor_text_expand_element.css('visibility', 'visible').css('display', 'inline');
        }
        else {
            /*
             * If not visible, make it visible and update the anchor text
             */


            el.css('visibility', 'visible').css('display', 'block')

            anchor_text_collapse_element.css('visibility', 'visible').css('display', 'inline');
            anchor_text_expand_element.css('visibility', 'hidden').css('display', 'none');
        }
    });

}




/*
 * Bind the collapse/expand events for the $this->debug()->v function
 */

simpli.hello.debug_bind_collapse_expand_events();