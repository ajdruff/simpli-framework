<?php
//echo '<pre>';
//echo '<pre>';
//$this->plugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);
//  $f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');
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
//        'hint' => 'Use this setting to temporarily disable ' . $this->plugin()->getName() . ' for troubleshooting or maintenance. \'No\' will disable all plugin functionality except for this Administrative area, allowing you continued access to these settings. To completely remove ' . $this->plugin()->getName() . ', de-activate it from the plugins menu.', $this->plugin()->getSlug(),
//            )
//    );
//
//
//
//
//
//    $f->formEnd(array('template'=>'formEndMaintenance'));
//

$f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');



$f->getTheme()->setTheme('Admin');

$f->setFilter(array('Admin', 'Settings'));
//$f->el(array(
//    'el' => 'text',
//    'name' => 'hello_global_default_text',
//    'label' => 'Text you want to insert',
//    'hint' => 'The text that will be inserted into the post',
//    'heading' => '',
//        )
//);
//

$f->el(array(
    'el' => 'checkbox',
    'options' => array('enabled' => 'Enabled', 'disabled' => 'Click for Disabled'),
    // 'selected'=>array('disabled'=>'yes','enabled'=>'no'),
    'selected' => 'disabled',
    'name' => 'hello_global_default_enabled',
    'label' => 'Radio produced by method call',
    'hint' => 'Global ENable',
    'heading' => '',
        )
);
$f->el(array(
    'el' => 'radio',
    'options' => array('orange' => 'Orange', 'red' => 'Red', 'yellow' => 'Yellow', 'blue' => 'Blue'),
    //  'selected'=>array('orange'=>'yes','red'=>'no'),
    'selected' => 'yellow',
    'name' => 'dropdown_setting',
    'label' => 'Method Call Radio/Checkbox/Dropdown',
    'hint' => 'Example colors',
    'heading' => '',
        )
);

/*
 * to indicate selected,  you may use either an array or singleton
 * either for checkboxes, radio, or dropdown.
 * for radio, you can either do this :
 * selected='enabled' or this : selected=array('disabled'=>'yes');
 * the second form is what the checkbox expects, the first form is what the radio expects, but both should work
 */
?>

[simpli_hello_form_options  radio  selected="disable," name='hello_global_default_placement'  label="shortcode test for radio"]
enable | Enabled
disable | Click for Disabled


[/simpli_hello_form_options]

[simpli_hello_form_options  dropdown  selected="red,orange" name='dropdown_setting'  label="shortcode test for checkbox"]
orange | Orange
red | Red
yellow | Yellow



[/simpli_hello_form_options]

<?php $this->debug()->stop(false); ?>
</div>


</script>