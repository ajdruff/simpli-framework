<?php
//echo '<pre>';
//echo '<pre>';
//$this->getPlugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);
//  $f = $this->getPlugin()->getAddon('Simpli_Forms')->getModule('Form');
//
//$f->formStart(array(
//    'theme' => 'Admin',
//  'filter' => array('Admin','Settings')
//
//        )
//);
//
//
//$f->el(array(
//    'el' => 'radio',
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
//    'el' => 'radio',
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
//    'el' => 'text',
//    'name' => 'hello_global_default_text',
//    'label' => 'Text to Insert into Post Content: ',
//    'hint' => 'Add the text that you want to insert into your post content. ',
//    'heading' => 'Hello World Text',
//    'value' => ''
//        )
//);
//
//$f->formEnd();
//
//        $f->formStart(array(
//        'theme' => 'Admin',
//        'filter' => array('Admin', 'Settings')
//            )
//    );
//
//
//    $f->el(array(
//        'el' => 'radio',
//        'options' => array('enabled' => 'Yes', 'disabled' => 'No'),
//        'name' => 'plugin_enabled',
//        'heading' => '',
//        'label' => 'Enable Plugin',
//        'hint' => 'Use this setting to temporarily disable ' . $this->getPlugin()->getName() . ' for troubleshooting or maintenance. \'No\' will disable all plugin functionality except for this Administrative area, allowing you continued access to these settings. To completely remove ' . $this->getPlugin()->getName() . ', de-activate it from the plugins menu.', $this->getPlugin()->getSlug(),
//            )
//    );
//
//
//
//
//
//    $f->formEnd(array('template_id'=>'formEndMaintenance'));
//

$f = $this->getPlugin()->getAddon('Simpli_Forms')->getModule('Form');



$f->getTheme()->setTheme('Admin');

//$f->setFilter(array('Admin','Options'));
//$f->el(array(
//    'el' => 'text',
//    'name' => 'hello_global_default_text',
//    'label' => 'Text you want to insert',
//    'hint' => 'The text that will be inserted into the post',
//    'heading' => '',
//        )
//);

$this->debug()->logVar('$f->getForm() = ', $f->getForm());
?>
[simpli_hello_form text  name='my_goodness' label='test1' options="enable=Enabled&disable=Click for Disabled"]

[simpli_hello_form_options  radio  name='test2' label='My Label' options="enable=Enabled&disable=Click for Disabled" ]

enable|Click for Enabled
disable|Click for Enabled


[/simpli_hello_form_options]



[simpli_hello_form_options  select  selected='disable' name='test3' label='My Label' options="enable=Click for Enabled&disable=Click for Disabled"/]


[simpli_hello_form_options  radio  name='test3' label='My Label']
enable | Enabled
disable | Click for Disabled


[/simpli_hello_form_options]


<?php $this->debug()->stop(); ?>
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