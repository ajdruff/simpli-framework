/*
 * Simpli Framework namespace v1.0.0
 * ref: http://www.zachleat.com/web/namespacing-outside-of-the-yahoo-namespace/
 */


if(typeof jQuery.namespace !== 'function'){

    jQuery.namespace = function() {
    var a=arguments, o=null, i, j, d;
    for (i=0; i<a.length; i=i+1) {
        d=a[i].split(".");
        o=window;
        for (j=0; j<d.length; j=j+1) {
            o[d[j]]=o[d[j]] || {};
            o=o[d[j]];
        }
    }
    return o;
};


}




//this to be replaced by the plugin company and plugin name
jQuery.namespace('simpli.hello');

/*
 *  usage :
simpli.hello.message = function(message)
{
    alert( message );
};

simpli.hello.message('hello world!');


 */

simpli.hello.log = function(message)
{
    jQuery(document).ready(function() {
      if (simpli_hello.plugin.debug===true){
    //alert(message);
    console.log( message );

    }
    });
};

simpli.hello.test=function(){

/*
 * tests jquery selectors  - just outputs the title of the page
 */
simpli.hello.log (jQuery("title").html());
simpli.hello.log('Plugin Name is ' + simpli_hello.plugin.name);

}

jQuery(document).ready(function() {



    simpli.hello.test();


});