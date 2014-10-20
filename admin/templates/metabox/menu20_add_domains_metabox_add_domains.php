<div class="col-md-8" style="float:none;margin:0 auto;">


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
    ?>



    <table class="table-bordered" style="text-centered">
        <thead>
            <tr>
                <th>Domain Name</th>
                <th>Source</th>
                <th>Source Listing Properties</th>
                <th>Nomstock Listing Options</th>


            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Subdomain -->                

                <td style="white-space:nowrap;">    <?php
                    $f->el( array(
                        'el' => 'text',
                        'style' => 'display:inline;',
                        'name' => 'subdomain',
                        'placeholder' => 'Example',
                        'label' => '',
                        'hint' => '',
                        'heading' => '',
                        'layout'=> 'bare'
                        , 'template' => 'text'
                            )
                    );
                    ?>

                    <?php
                    $f->el( array(
                        'el' => 'dropdown',
                        'style' => 'display:inline;',
                        'options' => array(
                            'com' => '&nbsp;.com ',
                            'net' => '&nbsp;.net',
                            'org' => '&nbsp;.org',
                        ),
                        'selected' => 'com',
                        'name' => 'tld',
                        'heading' => '',
                        'label' => '',
                        'hint' => '',
                         'layout'=> 'bare'
                        , 'template' => 'dropdown'
                            )
                    );
                    ?></td>
                <!-- Source -->   

                <td><?php
                    $f->el( array(
                        'el' => 'dropdown',
                        'options' => array(
                            'reg_search' => 'Registration Search'
                            , 'member_inventory' => 'My Inventory'
                            , 'afternic' => 'Afternic'
                            , 'godaddy' => 'GoDaddy Auctions'
                            , 'sedo' => 'Sedo'
                            , 'droplist' => 'Drop List'
                        ),
                        'selected' => 'member_inventory',
                        'name' => 'source',
                        'heading' => 'Source',
                        'label' => 'Where did you find the domain?',
                        'hint' => '',
                        'layout'=> 'bare'
                        , 'template' => 'dropdown'
                            )
                    );
                    ?></td>






                <!-- Buy It Now -->   

                <td style="white-space:nowrap;">   


                    <p class="form-group">


                    <p class="checkbox">

                        <input type="checkbox" checked="checked" value="yes" id="purchase_options_bin" name="purchase_options[bin]">
                        <label for="purchase_options_bin" style="padding-top:10px;margin-left:2px">  BIN</label>

                    </p>

                    <p class="checkbox">

                        <input type="checkbox" checked="checked" value="yes" id="purchase_options_bid" name="purchase_options[bid]">
                        <label for="purchase_options_bid" style="padding-top:10px;margin-left:2px">  Make Offer</label>

                    </p>



<?php
$f->el( array(
    'el' => 'dropdown',
    'options' => array(
        'usd' => '&nbsp;&nbsp;&#36; USD ',
        'eur' => '&nbsp;&#128; EUR',
    ),
    'selected' => 'usd',
    'name' => 'currency',
    'heading' => '',
    'label' => 'Currency',
    'hint' => '',
                        'layout'=> 'bare'
                        , 'template' => 'dropdown'
        )
);
?>                       
                    <?php
                    $f->el( array(
                        'el' => 'text',
                        'name' => 'price',
                        'label' => 'Price: ',
                        'hint' => 'Whole Number, without any punctuation',
                        'heading' => 'Pricing',
                        'placeholder' => 'Price, e.g: 1200'
                        , 'layout'=> 'bare'
                        , 'template' => 'text'
                            )
                    );
                    ?>

                    </p>   


                </td>

                <!-- Featured -->   
                <td><p>
                        <span>Featured:</span>   
<?php
$f->el( array(
    'el' => 'dropdown',
    'options' => array( 'yes' => 'Yes', 'no' => 'No' ),
    'selected' => 'no',
    'name' => 'featured',
    'heading' => 'Listing Options',
    'label' => 'Featured: ',
    'hint' => 'A Featured Domain is Displayed prominantly at the top of the page.',
                        'layout'=> 'bare'
                        , 'template' => 'dropdown'
        )
);
?>
                    </p>

                    <!-- Price Banner -->   
                    <p>Price Banner:</p>   
                        <?php
                        $f->el( array(
                            'el' => 'text',
                            'name' => 'price_note',
                            'placeholder' => 'New!',
                            'label' => 'Price Banner: ',
                            'hint' => 'A short note that will appear near the domain\'s price',
                            'heading' => ''
                                        ,'layout'=> 'bare'
                        , 'template' => 'text'
                                )
                        );
                        ?>
                </td>

            </tr>
        </tbody>
    </table >

    <!-- Hidden Fields -->
    <input type="hidden" name="added_by" value="">

<div class="simpli_forms_response"></div>


<?php

$f->formEnd(
        array(
            'template' => 'formEndAddDomain'
        )
);
?>
</div>



