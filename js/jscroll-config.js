/**
 * jscroll Config
 *
 * Configuration for the jscroll jquery plugin
 * which allows for the infinite scrolling of domain names
 */




var loadingHtml = '<div id="jscroll-loading-html" class="text-center"><div  style="padding:5px;margin-left:10px;color:#3A87AD;background-color:#D9EDF7;border-color:#BCE8F1"><img style="padding:10px" src="' + simpli.frames.vars.plugin.url + '/images/spinner.gif"><span  id="pause-loading-message">Please wait while we load more domain names...&nbsp;&nbsp;</span></div><div><a href="/about/">About Us</a>&nbsp;|&nbsp<a href="/contact/">Contact Us</a>&nbsp;|&nbsp<a href="/privacy/">Privacy</a>&nbsp;|&nbsp<a href="/tos/">TOS</a><div></div>';




jQuery(document).ready(function() {

/*
 * first check to see if we defined the ticker. we might
 * have commented it out for troubleshooting
 */
    if (typeof (simpli.frames.vars.plugin.ticker) !== 'undefined') {


        jQuery('.infinite-scroll').jscroll({
            autoTrigger: true,
            autoTriggerUntil: simpli.frames.vars.plugin.ticker.ticker_total_pages - 1, //set in core.php/getTickerListings . We subtract one since the first page is requested on page load and doesnt require the use of infinite scroll
            loadingHtml: loadingHtml,
            padding: 1500,
            nextSelector: '#next-page',
        });
    }
});









