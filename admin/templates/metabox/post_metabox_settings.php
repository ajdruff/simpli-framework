<div id="simpli-hello">
<?php
//echo '<pre>';
//echo '<pre>';
//$this->getPlugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);
/*
 * Must add a namespace div
 */



/*
 * nonce field is required since the hookPostSave method verifies it
 *
 */
 wp_nonce_field('save_post',$this->getPlugin()->getSlug().'_nonce');
$f = $this->getPlugin()->getAddon('Simpli_Forms')->getModule('Form');



$f->getTheme()->setTheme('Admin');
$f->setFilter(array('Options'));

$prefix = $this->getPlugin()->getSlug();
$f->el(array(
    'el' => 'radio',
    'options' => array('enabled' => 'Yes', 'disabled' => 'No'),
    'name' => 'enabled',
    'label' => 'Enabled for this post:',
    'template' => 'radio_post',
    'template_option' => 'radio_post_option',
    'hint' => '',
    'heading' => '',
        )
);
$f->el(array(
    'el' => 'radio',
    'options' => array('before' => 'Before Content', 'after' => 'After Content', 'default' => 'Default'),
    'name' => 'placement',
    'label' => 'Placement:<br>',
    'template' => 'radio_post',
    'template_option' => 'radio_post_option',
    'hint' => '',
    'heading' => '',
        )
);
$f->el(array(
    'el' => 'radio',
    'options' => array('false' => 'Custom', 'true' => 'Default'),
    'name' => 'use_global_text',
    'label' => 'Text',
    'template' => 'radio_post',
    'template_option' => 'radio_post_option',
    'hint' => '',
    'heading' => '',
        )
);
$f->el(array(
    'el' => 'text',
    'name' => 'text',
    'label' => 'Custom Text:',
    'template' => 'text_post',
    'hint' => '',
    'heading' => '',
        )
);
$f->el(array(
    'el' => 'checkbox',
    'options' => array('red' => 'Red','orange'=>'Orange'),
    'selected' => array('red' => 'yes'),
    'name' => 'my_checkbox',
    'label' => 'My Checkboxes',
     'template' => 'checkbox_post',
    'template_option' => 'checkbox_post_option',
    'hint' => '',
    'heading' => '',
        )
);
?>



<script type="text/javascript">
    jQuery(document).ready(function($) {
        var form = $('#post');
        $(form).find('.hidden-temp').remove();
        $(form).find('.hidden-checkbox').removeClass('hidden-checkbox');
        $('#publish,#save-post').click(function() {
           // alert( $('#simpli-hello').find('input:checkbox:not(:checked)').attr('name'));

            $('#simpli-hello').find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
            $('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + $('.hidden-checkbox').attr('name') + '">');
            //$('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
        });


    });
</script>

</div>