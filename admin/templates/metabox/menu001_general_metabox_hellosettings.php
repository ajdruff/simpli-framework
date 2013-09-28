


    <?php
    /*
     * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
     * and output of any of the fields by modifying the attributes before they are processed in the template
     */
$f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');


$f->formStart(array(
    'theme' => 'Admin',
  'filter' => array('Admin','Settings')

        )
);


$f->el(array(
    'el' => 'radio',
    'options' => array('before' => 'Before the content', 'after' => 'After the content'),
    'name' => 'hello_global_default_placement',
    'heading' => 'Text Placement',
    'label' => 'Where do you want to place the text?',
    'hint' => 'Selecting \'Before\' will add your text before the content in the post. Selecting \'After\' will add your text after the content in the post. ',
        )
);

$f->el(array(
    'el' => 'radio',
    'options' => array('enabled' => 'Enabled', 'disabled' => 'Disabled'),
    'name' => 'hello_global_default_enabled',
    'heading' => 'Global Enable',
    'label' => 'Enable or Disable Text Insertion for All Posts',
    'hint' => 'Selecting \'Enabled\' will turn on text insertion for all posts that have it enabled. Selecting \'Disabled\' will prevent any text from being inserted even if your post settings allow it. ',
        )
);


$f->el(array(
    'el' => 'text',
    'name' => 'hello_global_default_text',
    'label' => 'Text to Insert into Post Content: ',
    'hint' => 'Add the text that you want to insert into your post content. ',
    'heading' => 'Hello World Text',
    'value' => ''
        )
);

$f->formEnd();

    ?>

