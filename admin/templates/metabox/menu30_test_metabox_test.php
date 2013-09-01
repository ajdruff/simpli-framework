<?php
//echo '<pre>';
//echo '<pre>';
//$this->getPlugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);

$f = $this->getPlugin()->getAddon('Simpli_Forms')->getModule('Form');


/*
 * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
 * and output of any of the fields by modifying the attributes before they are processed in the template
 */
//$f->startForm(
//        array(
//            'id' => $this->getSlug(),
//            'filter' => 'Settings',
//            'theme' => 'Admin'
//        )
//);
//     $this->text('text', 'Text you want to insert', 'The text that will be inserted into the post', '') ;
//wp_nonce_field($this->getPlugin()->getSlug(), $this->getPlugin()->getSlug());
?>

<div class="misc-pub-section">


    <?php
    /*
     * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
     * and output of any of the fields by modifying the attributes before they are processed in the template
     * @todo: create a wrapper for this so its more obvious where the form starts.
     */
//$f->formStart(array(
//    'theme' => 'Admin',
//  'filter' => array('Settings')
//
//        )
//);
//
//
//$f->el(array(
//    'el_id' => 'radio',
//    'options' => array('before' => 'Before the content', 'after' => 'After the content'),
// //       'selected' => 'after',
//    'name' => 'hello_global_default_placement',
//    'heading' => 'Text Placement',
//    'label' => 'Where do you want to place the text?',
//    'hint' => 'Selecting \'Before\' will add your text before the content in the post. Selecting \'After\' will add your text after the content in the post. ',
//        )
//);
//
//$f->el(array(
//    'el_id' => 'radio',
//    'options' => array('enabled' => 'Enabled', 'disabled' => 'Disabled'),
//  //  'selected' => 'enabled',
//    'name' => 'hello_global_default_enabled',
//    'heading' => 'Global Enable',
//    'label' => 'Enable or Disable Text Insertion for All Posts',
//    'hint' => 'Selecting \'Enabled\' will turn on text insertion for all posts that have it enabled. Selecting \'Disabled\' will prevent any text from being inserted even if your post settings allow it. ',
//        )
//);
//
//
//$f->el(array(
//    'el_id' => 'text',
//    'name' => 'hello_global_default_text',
//    'label' => 'Text to Insert into Post Content: ',
//    'hint' => 'Add the text that you want to insert into your post content. ',
//    'heading' => 'Hello World Text',
//    'value' => ''
//        )
//);
//
//$f->formEnd();
    $f->formStart(array(
        'theme' => 'Admin',
        'filter' => array('Admin', 'Settings')
            )
    );


    $f->el(array(
        'el_id' => 'radio',
        'options' => array('yes' => 'Yes', 'no' => 'No'),
        //       'selected' => 'after',
        'name' => 'radio_setting',
        'heading' => 'Radio Button Setting Example',
        'label' => 'Do you want this option?',
        'hint' => 'Provide helpful text here describing this setting or provide a help <a href="#">link</a>',
            )
    );


    $f->el(array(
        'el_id' => 'text',
        'name' => 'text_setting',
        'label' => 'Your Name:',
        'hint' => 'Provide helpful text here describing this setting or provide a help <a href="#">link</a>',
        'heading' => 'Text Setting Example'
            )
    );

    $f->el(array(
        'el_id' => 'select',
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

    $f->el(array(
        'el_id' => 'checkbox',
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

//    $f->endForm(
//            array(
//                'filter' => 'Settings',
//                'theme' => 'Admin'
//            )
//    );
//$f->el(array(
//    'el_id' => 'select',
//    'name' => 'my_select_options'  //the name of the form field.
//    , 'value' => 'pa' //value of options that is selected
//    , 'label' => 'States'
//    , 'hint' => null
//    , 'help' => null
//    , 'options' => array(
//        'PA' => 'Pennsylvania',
//        'WA' => 'Washington',
//        'CA' => 'California',
//    )
//    , 'default_option' => 'CA' //string indiciating the value that should be selected on default
//        )
//);



    $this->debug()->logVar('$f->getForm() = ', $f->getForm());
    ?>



</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var form = $('#post');
        $(form).find('.hidden-temp').remove();
        $(form).find('.hidden-checkbox').removeClass('hidden-checkbox');
        $('#publish,#save-post').click(function() {
            //alert( $('#simpli-hello').find('input:checkbox:not(:checked)').attr('name'));

            $('#simpli-hello').find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
            $('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + $('.hidden-checkbox').attr('name') + '">');
            //$('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
        });


    });
</script>