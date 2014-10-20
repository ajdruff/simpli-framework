

/*
 * Protect against console not being defined and an errant console.log
 * Console.log() is a common object in firefox and other browsers that is used
 * to output debug messages. Some browsers may not have it defined, and if not,
 * this will ensure that no errors will be thrown
 * All it does is  simply creates an empty object with some common used methods which
 * do nothing when called.
 */


if (typeof console !== "object") {
    console = {},
            console.log = function(a) {
    }
    console.debug = function(a) {
    }
    console.info = function(a) {
    }
    console.warn = function(a) {
    }
    console.error = function(a) {
    }
    console.assert = function(a) {
    }
    console.dir = function(a) {
    }
    console.dirxml = function(a) {
    }
    console.group = function(a) {
    }
    console.groupEnd = function(a) {
    }
    console.time = function(a) {
    }
    console.timeEnd = function(a) {
    }
    console.count = function(a) {
    }
    console.trace = function(a) {
    }
    console.profile = function(a) {
    }
    console.profileEnd = function(a) {
    }


}

/*
 * jquery extensions
 * 
 */

/*
 * jQuery Extension - outerHTML
 * http://stackoverflow.com/questions/2419749/get-selected-elements-outer-html
 */
(function($) {
  $.fn.outerHTML = function() {
  //  return $(this).clone().wrap('<div></div>').parent().html();
    
    return $(this).clone().wrap('<p>').parent().html();
  }
})(jQuery);

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
                                                $(window).scrollLeft()) + "px");
    return this;
}

/*
 * 


jQuery(document).ready(function() {
    


jQuery( "[data-sf-meta]").hover(
function() {
    jQuery(this).addClass('hover');
    jQuery( this ).html(  jQuery(this).attr("data-sf-meta") ) ;
}, function() {
jQuery( this ).html(  jQuery(this).html("meta") ) ;
   jQuery(this).addClass('unhover');
   
}

);

});

 */