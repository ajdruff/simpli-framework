<?php
//echo '<pre>';

//echo '<pre>';
//$this->getPlugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);

$f = $this->getPlugin()->getAddon('Simpli_Forms')->getModule('Form');



$f->getTheme()->setTheme('Admin');
$f->setFilter(array('Admin','Options'));

$f->el(array(
    'el' => 'text',
    'name' => 'hello_global_default_text',
    'label' => 'Text you want to insert',
    'hint' => 'The text that will be inserted into the post',
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
            //alert( $('#simpli-hello').find('input:checkbox:not(:checked)').attr('name'));

            $('#simpli-hello').find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
            $('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + $('.hidden-checkbox').attr('name') + '">');
            //$('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
        });


    });
</script>