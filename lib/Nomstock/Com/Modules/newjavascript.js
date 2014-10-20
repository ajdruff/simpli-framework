$("#success-alert").hide();
$("#error-alert").hide();


jQuery(document).ready(function() {
    

    
$('button').click(function(btn,e) {
    $('input').each(function() {
        if (!$(this).val()) {
            $("#error-alert").show();
            $("#success-alert").hide();
            return false;
        }
        else
        {
          
                var form = $('#contact_us'); // contact form
                var submit = $('button');  // submit button
                var status = $('#form-status'); // alert div for show alert message


                    $.ajax({
                        url: '/contact-us', // form action url
                        type: 'POST', // form submit method get/post
                        dataType: 'html', // request type html/json/xml
                        data: form.serialize(), // serialize form data 
                        beforeSend: function() {
                            submit.html('Sending....'); // change submit button text
                        },
                        success: function(data) {
                            form.trigger('reset'); // reset form
                            $("#success-alert").show();
                            $("#error-alert").hide();
                            submit.html('Send'); // reset submit button text

                        },
                        error: function(e) {
                            console.log(e)
                        }

                    });
     
        }
        
    });
});

// form submit event
form.on('submit', function(e) {
    // form submit event
                    e.preventDefault(); // prevent default form submit

    
    });


});


