<div class="misc-pub-section">


    <?php
    /*
     * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
     * and output of any of the fields by modifying the attributes before they are processed in the template
     */
    $f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');


    

        $f->formStart(array(
        'theme' => 'Admin'
        , 'filter' => array(
            'AddDomain'
        )
            )
    );


        $f->el(array(
        'el' => 'text',
        'name' => 'subdomain',
            'value'=> 'my default',
        'label' => 'Domain Name: ',
        'hint' => 'You domain name but without the Top Level Domain extension or the WWW',
        'heading' => 'Domain Name'
            )
    );
    
        $f->el(array(
        'el' => 'dropdown',
        'options' => array(
            'com' => '&nbsp;.com ',
            'net' => '&nbsp;.net',
            'org' => '&nbsp;.org',
      
        ),
        'selected' => 'com',
        'name' => 'tld',
        'heading' => '',
        'label' => 'Top Level Domain:',
        'hint' => '',
            )
    );
        
                 $f->el(array(
        'el' => 'radio',
        'options' => array('yes' => 'Yes', 'no' => 'No'),
              'selected' => 'no',
        'name' => 'approved',
        'heading' => 'Approval',
        'label' => 'Approved: ',
        'hint' => 'By default, domains added through the admin panel are approved. The listing period begins immediately upon approval.',
            )
    );
           

                     
                            $f->el(array(
        'el' => 'radio',
        'options' => array(
            'droplist' => 'Drop List'
            , 'sedo' => 'Sedo'
            , 'afternic' => 'Sedo'
            , 'godaddy' => 'GoDaddy Auctions'
            , 'reg_search' => 'Registration Search' 
                                
            ),
              'selected' => 'no',
        'name' => 'droplist',
        'heading' => 'Source',
        'label' => 'Where did you find the domain?',
        'hint' => '',
            )
    );           
                              $f->el(array(
        'el' => 'radio',
        'options' => array('yes' => 'Yes', 'no' => 'No'),
              'selected' => 'no',
        'name' => 'reg_available',
        'heading' => '',
        'label' => 'Is Domain Available for Registration: ',
        'hint' => 'Domains Listed for Registration will be referred to registration partners.',
            )
    );
                              
                              
          $f->el(array(
        'el' => 'checkbox',
        'options' => array(
            'bin' => 'Buy It Now (BIN)',
            'bid' => 'Make Offer (Bid)',
        ),
        'selected' => array
            (
            'bin' => 'yes',
            'bid' => 'yes',
        ),
        'name' => 'purchase_options',
        'heading' => 'Purchase Options',
        'label' => 'Buy it Now or Make Offer Available?',
        'hint' => '',
            )
    );  

                  $f->el(array(
        'el' => 'text',
        'name' => 'price',
        'label' => 'Price: ',
        'hint' => 'Whole Currency Amount, without any punctuation',
        'heading' => 'Pricing'
            )
    );
                  
       $f->el(array(
        'el' => 'dropdown',
        'options' => array(
            'usd' => '&nbsp;&nbsp;&#36; USD ',
            'eur' => '&nbsp;&#128; EUR',
      
        ),
        'selected' => 'usd',
        'name' => 'currency',
        'heading' => '',
        'label' => 'Currency',
        'hint' => '',
            )
    );
       
         $f->el(array(
        'el' => 'radio',
        'options' => array('yes' => 'Yes', 'no' => 'No'),
              'selected' => 'no',
        'name' => 'featured',
        'heading' => 'Listing Options',
        'label' => 'Featured: ',
        'hint' => 'A Featured Domain is Displayed prominantly at the top of the page.',
            )
    );
         
                           $f->el(array(
        'el' => 'text',
        'name' => 'price_note',
        'label' => 'Price Banner: ',
        'hint' => 'A short note that will appear near the domain\'s price',
        'heading' => ''
            )
    );
         
    $f->el(array(
        'el' => 'radio',
        'options' => array('yes' => 'Yes', 'no' => 'No'),
        //       'selected' => 'after',
        'name' => 'radio_setting',
        'heading' => 'Radio Button Setting Example',
        'label' => 'Do you want this option?',
        'hint' => 'Provide helpful text here describing this setting or provide a help <a href="#">link</a>',
            )
    );


    $f->el(array(
        'el' => 'text',
        'name' => 'text_setting',
        'label' => 'Your Name:',
        'hint' => 'Provide helpful text here describing this setting or provide a help <a href="#">link</a>',
        'heading' => 'Text Setting Example'
            )
    );

    $f->el(array(
        'el' => 'dropdown',
        'options' => array(
            'yellow' => '&nbsp;Yellow ',
            'orange' => '&nbsp;&nbsp;Orange&nbsp;&nbsp;',
            'red' => ' Red ',
            'blue' => ' Blue ',
        ),
        'selected' => 'blue',
        'name' => 'dropdown_setting',
        'heading' => 'Dropdown Setting Example',
        'label' => 'Color:',
        'hint' => 'Provide helpful text here describing this setting or provide a help <a href="#">link</a>',
            )
    );
//
    $f->el(array(
        'el' => 'checkbox',
        'options' => array(
            'yellow' => 'Yellow',
            'orange' => 'Orange',
            'red' => ' Red ',
            'blue' => ' Blue ',
        ),
        'selected' => array
            (
            'blue' => 'yes',
            'orange' => 'yes',
            'red' => 'no',
            'yellow' => 'yes'
        ),
        'name' => 'checkbox_setting',
        'heading' => 'Checkbox Setting Example',
        'label' => 'Place a checkmark next to each color you like:',
        'hint' => 'Provide helpful text here describing this setting or provide a help <a href="#">link</a>',
            )
    );



    $f->formEnd(
            array(
                'template'=>'formEndAddDomain'
            )
            
            );
    ?>
</div>