/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* must be false since we are not showing place holders for 1 pixel images */


jQuery(document).ready(function() {
    
    jQuery("img.lazy").show().lazyload();
    
jQuery("img.lazy").lazyload({
    skip_invisible : false
});


});
