<div class="misc-pub-section">


    <?php
    /*
     * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
     * and output of any of the fields by modifying the attributes before they are processed in the template
     */
    $f = $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' );




    $f->formStart( array(
        'theme' => 'Admin'
        , 'filter' => array(
            'AddDomain'
        )
            )
    );




           

    
    $f->formEnd();
               
    ?>
</div>