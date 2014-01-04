
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
    , 'ajax' => true
    , 'method' => 'get'
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






$f->formEnd(array(
    'template' => 'formEndSayHello'
    , 'response' => true
        )
);
?>
