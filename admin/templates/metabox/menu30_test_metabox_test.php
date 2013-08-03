<?php
//echo '<pre>';
//print_r($post_options);
//echo '<pre>';
//$this->getPlugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);

$f = $this->getPlugin()->getAddon('Simpli_Forms')->getModule('Form');


/*
 * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
 * and output of any of the fields by modifying the attributes before they are processed in the template
 */
$f->createForm(
        array(
            'filter' => ''
            , 'theme' => 'Seattle'
        )
);

//     $this->text('text', 'Text you want to insert', 'The text that will be inserted into the post', '') ;

wp_nonce_field($this->getPlugin()->getSlug(), $this->getPlugin()->getSlug());
?>

<div class="misc-pub-section">


<?php
$f->el(array(
    'el_id' => 'text',
    'name' => 'text',
    //   , 'label' => 'Text you want to insert'
   // 'hint' => 'The text that will be inserted into the post'
 'value' => '22'
        )
);

$f->el(array(
    'el_id' => 'text',
    'name' => 'my_name',
    'template_id' => 'text2',
    //   , 'label' => 'Text you want to insert'
    'hint' => 'The text that will be inserted into the post'
    , 'value' => '22'
        )
);

$f->el(array(
    'el_id' => 'select',
    'name' => 'my_select_options'  //the name of the form field.
    , 'value' => 'pa' //value of options that is selected
    , 'label' => 'States'
    , 'hint' => null
    , 'help' => null
    , 'options' => array(
        'PA' => 'Pennsylvania',
        'WA' => 'Washington',
        'CA' => 'California',
    )
    , 'default_option' => 'CA' //string indiciating the value that should be selected on default
        )
);






echo '<pre>', print_r($f->getForm(), true), '</pre>';
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