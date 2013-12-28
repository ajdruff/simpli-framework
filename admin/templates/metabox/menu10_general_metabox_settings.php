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
            'Settings'
        )
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



    $f->formEnd();
    ?>
</div>