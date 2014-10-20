<?php
	/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


?>


<div><h1>Bootstrap Theme Example - Bare Layout
    </h1></div>

<?php
global $nomstock_com;
$f = $nomstock_com->getAddon( 'Simpli_Forms' )->getModule( 'Form' );

$f->formStart( array(
    'theme' => 'Bootstrap',
    'ajax' => true,
    'filter' => 'Bootstrap',
    'style' => '',
//                                'container'=>false,
//                               'container_class'=>'container',
    //    'class' => "col-md-10 col-md-offset-1",
    //    'label_size' => '4',
        'size' => '3',
        'label_size' => 3,
    'name' => 'bare_example',
    'layout' => 'bare'
        )
);
?>

<table class="table table-bordered table-striped">
    <tr>
        
        <th>First Name</th>
        
        
        <td><?php
$f->el( array(
    'el' => 'text',
    'name' => 'first_name',
    'style' => "",
    'placeholder' => 'Enter your first name',
    'value' => '',
    'label' => 'First Name',
    'hint' => 'Your First Name',
    'heading' => ''
        )
);
?></td>
    
    </tr>
    
    <!-- ***********New Row*********** -->
        <tr>
        
        <th>Description</th>
        
        
        <td>
            
          <?php
$f->el( array(
    'el' => 'textArea',
    'name' => 'description',
    'style' => "",
    'placeholder' => 'Enter something here',
    'value' => '',
    'label' => 'Description',
    'hint' => 'Add more detail about your product',
    'heading' => ''
        )
);
?>  
        </td>
    
    </tr>
    
    <!-- ***********New Row*********** -->
        <tr>
        
        <th>Radio</th>
        
        
        <td>
            
           <?php
$f->el( array(
    'el' => 'radio',
    'name' => 'radio_bike_color',
    'class' => '',
    'style' => '',
      'options' => array( 'red' => 'Red', 'blue' => 'Blue', 'orange' => 'Orange' ),
    'value' => 'select',
        'select' => 'red',
    'label' => 'Color:',
    'hint' => 'The color of your new bike',
    'heading' => '',

        )
); ?> 
        </td>
    
    </tr>
        <!-- ***********New Row*********** -->
        <tr>
        
        <th></th>
        
        
        <td>
            <?php
$f->el( array(
    'el' => 'dropdown',
    'name' => 'dropdown_bike_color',
    'class' => '',
    'style' => '',
    'options' => array( 'select' => 'Select a color', 'red' => 'Red', 'blue' => 'Blue', 'orange' => 'Orange' ),
    'value' => 'select',
    'label' => 'Color:',
    'hint' => 'The color of your new bike',
    'heading' => '',
        )
);
?>
            
        </td>
    
    </tr>
        <!-- ***********New Row*********** -->
        <tr>
        
        <th>Upload a File</th>
        
        
        <td>
            <?php
$f->el( array(
    'el' => 'file',
    'name' => 'file_upload',
    'style' => "",
    'value' => '',
    'label' => 'Upload an image:',
    'hint' => 'Click to upload an image',
    'heading' => ''
        )
);
?>
            
        </td>
    
    </tr>
        <!-- ***********New Row*********** -->
        <tr>
        
        <th>Checkbox Example</th>
        
        
        <td>
            <?php

$f->el( array(
    'el' => 'checkbox',
    'name' => 'checkbox_bike_color',
    'class' => '',
    'style' => '',
        'options' => array( 'red' => 'Red', 'blue' => 'Blue', 'orange' => 'Orange' ),
    'select' => 'red',
    'label' => 'Color:',
    'hint' => 'The color of your new bike',
    'heading' => '',
 

        )
);
?>
            
        </td>
    
    </tr>
</table>














<?php $f->formEnd(); ?>