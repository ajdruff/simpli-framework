<div class="misc-pub-section">


    <?php
    /*
     * start the form , and optionally give it a theme and a filter. The filter allows you to modify the input
     * and output of any of the fields by modifying the attributes before they are processed in the template
     */
    $f = $this->plugin()->getAddon( 'Simpli_Forms' )->getModule( 'Form' );
    ?><strong style="font-size:16px;line-height:5px">Filters:</strong><?php
    $available_form_names = $this->plugin()->tools()->getDbColumn(
            'simpli_forms', //$table,
            'form_name', //$field,
            null, //$query=null,
            true, //$assoc=true,
            true //$wordify=true 
    );

    $available_form_names[ 'any' ] = 'Any';



    $f->formStart( array(
        'theme' => 'Bootstrap',
        'ajax' => true,
        'filter' => 'Bootstrap',
        'response_fadeout'=>false,
        //     'style' => 'border: 1px dashed grey;display:block',
        'class' => '',
        'label_size' => '1',
        'size' => '1',
        'name' => 'simpli_forms_get_forms',
        'target' => 'forms_table_response_target', //a DOM id that tells the form where to place the response
        'template_type' => 'inline' //'inline','horiz'.
            )
    );
    
  /*
   * test fields, you can disable all of these by
   * turning render to false
   */
    
        $f->el( array(
        'el' => 'file',
                'render'=>false,
        'name' => 'test_file',
        'accept' => 'image/png', //audio/*,video/*,image/*,
//        'class' => 'btn btn-default btn-xs',
//        'style' => 'display:block;',
        'heading' => '',
        'label' => 'Image Upload',
        'hint' => ''
            )
    );
        
        
    $f->el( array(
        'el' => 'textarea',
        'name' => 'test_textarea1',
        'render'=>false,
        //  'size' => '8',
        'rows' => '1',
        'cols' => '1',
        'label' => 'Test Area',
        'size'=>'2',
        'hint' => '',
            )
    );



    $f->el( array(
        'el' => 'text',
                'render'=>false,
        'name' => 'test_text',
        //  'size' => '8',
        'label' => 'Test',
        'hint' => '',
            )
    );
    
   $f->el( array(
        'el' => 'password',
               'render'=>false,
        'name' => 'test_password',
        //  'size' => '8',
        'label' => 'Password:',
        'hint' => '',
            )
    );
   
   /*
    * 
    * Available Form Names
    */

    $f->el( array(
        'el' => 'dropdown',
                'render'=>true,
        'options' => $available_form_names,
        'name' => 'selected_form',
        'selected' => 'any',
        'heading' => '',
        'label' => 'Form',
        'hint' => '',
        'size' => '2',
 
            )
    );


/*
 * Status Filter
 */


    $f->el( array(
        'el' => 'dropdown',
                'render'=>true,
        'options' => array( 'any' => 'Any', 'new' => 'New', 'saved' => 'Saved', 'deleted' => 'Deleted' ),
        'name' => 'status',
        'selected' => 'any',
        'heading' => '',
        'label' => 'Status',
        'hint' => '',
                'size' => '2',

            )
    );
 


        /*
         * Show Hidden Fields 
         */


        $f->el( array(
            'el' => 'radio',
                    'render'=>true,
            'options' => array( true => 'Hide', false => 'Show' ),
            'name' => 'hide_hidden_fields',
            'selected' => 'hide',
            'heading' => '',
                    'size'=>'2',
//            'class'=>'radio',
//            'style'=>'display:block;',
            'label' => 'Hidden Fields',
//            'template'=>'radio_inline',
//            'template_option'=>'radio_inline_option',
            'hint' => '',
                )
        );

    
//              $f->el( array(
//        'el' => 'button',
//        'name' => 'text',
////        'size' => '1',
////        'label_size' => '1',
//        'value' => 'Apply',
//        'action' => 'get_forms',
//       'class' => 'btn-primary btn-block'
//            )
//    );
    ?>


    <?php
    $f->el( array(
        'el' => 'button',
        'name' => 'text',
      
//        'size' => '1',
//        'label_size' => '1',
        'value' => 'Apply',
        'action' => 'apply_filters',
        'style'=>'position:relative;bottom:4px;',
        'class' => 'btn-primary btn-sm'
            )
    );
    ?>
 
        <?php
        $f->formEnd();


        $f->formStart( array(
            'theme' => 'Bootstrap',
            'ajax' => false,
            'filter' => 'Bootstrap',
            'style' => '',
            'response_fadeout'=>false,
            'class' => 'form form-horizontal',
            'label_size' => '5',
            'size' => 'medium',
            'name' => 'simpli_forms_update_forms'
                )
        );



        $f->el( array(
            'el' => 'button',
            'value' => 'Update',
            'action' => 'update_form_status',
            'class' => 'btn-warning btn-block'
                )
        );
        ?><div id="forms_table_response_target"><?php
        
        /*
         * set filters which is an associate array that provides
         * where clauses.
         * e.g.: 
         * array('form_name'=>'my_form_1')
         */
    $filters = array();
    $this->debug()->logVar( '$filters = ', $filters );
    
    /*
     * render the table from the query
     */
    

    echo $this->module()->getFormsHtmlTable(
            $filters, //$filters
            array( 'action', 'form_name' ),//$hidden_fields
            true //$hide_hidden
            
            );
    ?></div><?php
    $f->formEnd();
    ?>
</div>