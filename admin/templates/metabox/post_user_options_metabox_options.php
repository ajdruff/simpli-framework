<div id="simpli-frames">
    <?php
//echo '<pre>';
//echo '<pre>';
//$this->plugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);
    /*
     * Must add a namespace div
     */





    $f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');

    $f->formStart(array(
        'name' => 'simpli_forms_post_options',
        'theme' => 'Admin',
        'ajax' => true,
        'action' => null, //ignored if using ajax or using the Options filter of the Admin theme, otherwise should be the url to the form submission.
        'method' => 'post',
        'template' => 'formStart',
        'filter' => 'Options' // Filter should be options, which will fill in the value with the value from the WordPress database
            )
    );



    $prefix = $this->plugin()->getSlug();
    $f->el(array(
        'el' => 'radio',
        'options' => array('enabled' => 'Yes', 'disabled' => 'No'),
        'name' => 'enabled',
        'label' => 'Enabled for this post:<br>',
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
        'label' => 'Placement:',
        'template' => 'radio_post',
        'template_option' => 'radio_post_option',
        'hint' => '',
        'heading' => '',
            )
    );

    $options = array('custom' => 'Custom', 'default' => 'Default', 'snippet' => 'Snippet');
    $f->el(array(
        'el' => 'radio',
        'options' => $options,
        'name' => 'use_global_text',
        'label' => 'Text:',
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





    /*
     *
     * Provide a dropdown with the Simpli Frames Snippets
     * if they are available.
     *
     */


    $snippets = get_posts(
            array('post_type' => $this->plugin()->getSlug() . '_snippet')
    );
    $options = array();
    foreach ($snippets as $snippet) {
        $options[$snippet->ID] = $snippet->post_name;
    }

    $f->el(array(
        'el' => 'dropdown',
        'options' => $options,
        'name' => 'snippet',
        'label' => 'Simpli Frames Snippets:',
        'hint' => '<a href=' . admin_url() . '/edit.php?post_type=' . $this->plugin()->getSlug() . '_snippet' . '>View/Edit Snippets</a>',
        'heading' => '',
        'template' => 'dropdown_post',
        'template_option' => 'dropdown_post_option',
            )
    );

    $f->formEnd(array('template' => 'formEndPostAjax'));

    /*
     * Example Checkbox
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
     *
     */
    ?>




</div>