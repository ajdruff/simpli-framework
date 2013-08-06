
jQuery(document).ready(function() {


    jQuery('.simpli_debug_more').click(function(e) {
        //id=debug-trace.js
        e.preventDefault();


        el = jQuery(this).parent().parent().find('.simpli_debug_toggle');

        if (el.css('visibility') === 'visible') {
    
            el.css('visibility', 'hidden').css('display', 'none');
            jQuery(this).html('<em>More</em>');
        }
        else {


            el.css('visibility', 'visible').css('display', 'block')

            jQuery(this).html('<em>Less</em>');
        }
    });

});

