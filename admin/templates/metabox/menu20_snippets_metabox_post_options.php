<div id="simpli-hello">
    <?php
//echo '<pre>';
//echo '<pre>';
//$this->plugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);
    /*
     * Must add a namespace div
     */



    /*
     * nonce field is required since the hookPostSave method verifies it
     *
     */
    //  wp_nonce_field('save_post', $this->plugin()->getSlug() . '_nonce');
    $f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');



    $f->getTheme()->setTheme('Admin');
    $f->setFilter(array('Options'));

    $prefix = $this->plugin()->getSlug();
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

    if (!in_array('Menu15CustomPostType', $this->plugin()->DISABLED_MODULES)) {
        $options = array('false' => 'Custom', 'true' => 'Default', 'snippet' => 'Snippet');
    } else {
        $options = array('false' => 'Custom', 'true' => 'Default', 'snippet' => 'Snippet');
    }

    $f->el(array(
        'el' => 'radio',
        'options' => $options,
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

    /*
     *
     * Provide a dropdown with the Simpli Hello Snippets
     * if they are available.
     *
     */
    if (!in_array('Menu15CustomPostType', $this->plugin()->DISABLED_MODULES)) {


        $snippets = get_posts(
                array('post_type' => $this->plugin()->getSplug() . '_snippet')
        );
        $options = array();
        foreach ($snippets as $snippet) {
            $options[$snippet->ID] = $snippet->post_name;
        }

        $f->el(array(
            'el' => 'dropdown',
            'options' => $options,
            'name' => 'snippet',
            'label' => 'Simpli Hello Snippets:',
            'hint' => '<a href="#' . admin_url() . '/wp-admin/edit.php?post_type=' . $this->plugin()->getSplug() . '_snippet' . '">View/Edit Snippets</a>',
            'heading' => '',
            'template' => 'dropdown_post1',
            'template_option' => 'dropdown_post_option',
                )
        );
    }
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