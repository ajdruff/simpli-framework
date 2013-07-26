<?php
//echo '<pre>';
//print_r($post_options);
//echo '<pre>';
//$this->getPlugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);

$f = $this->getPlugin()->getModule('Form');
$f->setFormFilter('options');
$f->setFormFilter('settings');

//     $this->text('text', 'Text you want to insert', 'The text that will be inserted into the post', '') ;

wp_nonce_field($this->getPlugin()->getSlug(), $this->getPlugin()->getSlug());
?>

<div class="misc-pub-section">


    <?php
    $f->text(array(
        'option_name' => 'text',
     //   , 'label' => 'Text you want to insert'
         'hint' => 'The text that will be inserted into the post'
        , 'value' => '22'

            )
    );
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