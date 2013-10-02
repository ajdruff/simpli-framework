


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
        'options' => array('enabled' => 'Yes', 'disabled' => 'No'),
        'name' => 'plugin_enabled',
        'heading' => '',
        'label' => 'Enable Plugin',
        'hint' => 'Use this setting to temporarily disable ' . $this->plugin()->getName() . ' for troubleshooting or maintenance. \'No\' will disable all plugin functionality except for this Administrative area, allowing you continued access to these settings. To completely remove ' . $this->plugin()->getName() . ', de-activate it from the plugins menu.', $this->plugin()->getSlug(),
            )
    );





    $f->formEnd(array('template'=>'formEndMaintenance'));

    ?>

