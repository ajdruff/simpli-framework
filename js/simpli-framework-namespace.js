/**
 * Simpli Framework namespace
 *
 * Creates a Javascript namespace for the current plugin
 * Always load this in the header and do not make dependent on jQuery or anything else.
 * Ref: http://www.zachleat.com/web/namespacing-outside-of-the-yahoo-namespace/
 * @package SimpliFramework
 * @subpackage SimpliHello
 */



//
//if(typeof jQuery.namespace !== 'function'){
//
//    jQuery.namespace = function() {
//    var a=arguments, o=null, i, j, d;
//    for (i=0; i<a.length; i=i+1) {
//        d=a[i].split(".");
//        o=window;
//        for (j=0; j<d.length; j=j+1) {
//            o[d[j]]=o[d[j]] || {};
//            o=o[d[j]];
//        }
//    }
//    return o;
//};
//
//
//}

if (typeof simpli_namespace !== 'function') {

    simpli_namespace = function() {
        var a = arguments, o = null, i, j, d;
        for (i = 0; i < a.length; i = i + 1) {
            d = a[i].split(".");
            o = window;
            for (j = 0; j < d.length; j = j + 1) {
                o[d[j]] = o[d[j]] || {};
                o = o[d[j]];
            }
        }
        return o;
    };


}


//this to be replaced by the plugin company and plugin name
simpli_namespace('simpli.hello');

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

    if (typeof simpli_hello != 'undefined') { //check if simpli_hello namespace is available . if it is, variables are also available
        if (simpli_hello.plugin.debug === true) { //if variables are available, we can check preferences

    console.log( message );
        } else {
            console.log(message); //output to log anyway if there are no footer variables. this is in event of a fatal php error where localvars will not be printed.
        }
    }

};


