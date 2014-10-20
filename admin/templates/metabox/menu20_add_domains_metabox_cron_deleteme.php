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

    


    $f->formEnd(
            array(
                'template' => 'formEndAddDomain'
            )
    );
    ?>
    
    <div style="margin-bottom:150px; height:50px;" class="button-controls pull-right">
   <div   class="pull-left simpli_forms_response">&nbsp;</div><input type="submit" id="{form_name}_{form_counter}_add_domain" class="button-primary" value="Add Domain(s)" name="{form_name}_{form_counter}_add_domain">

    <img alt="<?php _e('Waiting...', $this->plugin()->getTextDomain()); ?>" src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />
</div>
<p style="height:50px;"><!-- padding --></p>

<?php  $f->formEnd(

    );
    ?>
    
    
</div>