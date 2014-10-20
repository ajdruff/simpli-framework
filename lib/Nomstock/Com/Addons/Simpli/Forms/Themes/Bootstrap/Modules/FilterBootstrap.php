<?php

/**
 * Form Filter Module - Bootstrap
 *
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Nomstock_Com_Addons_Simpli_Forms_Themes_Bootstrap_Modules_FilterBootstrap extends Nomstock_Com_Addons_Simpli_Forms_Modules_Filter {

    /**
     * Common Filter
     *
     * All Fields are subject to this filter
     * * @param none
     * @return void
     */
    protected function _commonFilter( $properties ) {

        $properties = parent::_commonFilter( $properties );

        $this->debug()->t();
        $this->debug()->log( 'applying the common filters of the base class' );

        extract( $properties );

        /*
         * Several Size filter expressions follow. Their purpose is to 1) Check if the attribute exists, if it does and assigned a value, use it. If it doesnt, take its value from the properties of the entire form. Once a value is assigned, then convert it to a value that the twitter bootstrap framework can use.
         */
        /*
         * Device Size
         */

        if ( array_key_exists( 'device_size', $atts ) ){ $tags[ 'ds' ] = $this->changeDeviceSizeToBoostrapSize( $this->maybeTakeFormDefault( 'device_size', $atts ) ); }


        /*
         * Element Size
         */

        if ( array_key_exists( 'label_size', $atts ) ){ $atts[ 'label_size' ] = $this->changeSizeToBoostrapSize( $this->maybeTakeFormDefault( 'label_size', $atts ) ); }

        /*
         * Label Size
         */

        if ( array_key_exists( 'size', $atts ) ){ $atts[ 'size' ] = $this->changeSizeToBoostrapSize( $this->maybeTakeFormDefault( 'size', $atts ) ); }


//                                                                    
//                                                                    
//        if ( array_key_exists( 'device_size', $atts ) && is_null( $atts[ 'device_size' ] ) ) {
//
//            $atts [ 'device_size' ] = $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'device_size' ];
//        }
//        $device_sizes = array(
//            'small' => 'sm',
//            'medium' => 'md',
//            'large' => 'lg',
//            'extra-small' => 'xs',
//        );
//        $tags[ 'ds' ] = $device_sizes[ $atts [ 'device_size' ] ];
//
//        $form_size = $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'size' ];

        /*
         * Device Sizing
         * if the element has an attribute named 'device_size' , and it is not assigned
         * a value, use the form's device_size and transform it to a column size that
         * can be used for boostrap sizing in the col-md-# format 
         *      
         */
//        $form_size = $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'size' ];
//        if ( array_key_exists( 'size', $atts ) && is_null( $atts[ 'size' ] ) ) {
//
//
//
//            $atts[ 'size' ] = $this->changeToBoostrapSize( $form_size );
//
//    } elseif ( array_key_exists( 'size', $atts ) ) {
//            /* If the element has an attribute 'size', and its assigned a value, transform it to a column size that
//             * can be used for boostrap sizing in the col-md-# format 
//             */
//            $this->debug()->logVar( 'Element set size attribute to: ', $atts[ 'size' ], true );
//            $atts[ 'size' ] = $this->changeToBoostrapSize( $atts[ 'size' ] );
//
//}
//
//        $this->debug()->logVar( '$form_size = ', $form_size );



        /*
         * Element Sizing
         * if the element has an attribute named 'size' , and it is not assigned
         * a value, use the form's size and transform it to a column size that
         * can be used for boostrap sizing in the col-md-# format 
         *      
         */

//        
//                    if ( array_key_exists( 'size', $atts )){$atts['size']=$this->changeToBoostrapSize($this->maybeTakeFormDefault('size',$atts));}
//        $this->debug()->logVar( 'form properties = ', $form_size = $this->addon()->getModule( 'Form' )->form );
//        $this->debug()->logVar( '$atts = ', $atts );
//        $form_size = $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'size' ];
//        if ( array_key_exists( 'size', $atts ) && is_null( $atts[ 'size' ] ) ) {
//
//
//
//            $atts[ 'size' ] = $this->changeToBoostrapSize( $form_size );
//
//    } elseif ( array_key_exists( 'size', $atts ) ) {
//            /* If the element has an attribute 'size', and its assigned a value, transform it to a column size that
//             * can be used for boostrap sizing in the col-md-# format 
//             */
//            $this->debug()->logVar( 'Element set size attribute to: ', $atts[ 'size' ], true );
//            $atts[ 'size' ] = $this->changeToBoostrapSize( $atts[ 'size' ] );
//
//}
//
//        $this->debug()->logVar( '$form_size = ', $form_size );
//
//        $this->debug()->logVar( '$form_size = ', $form_size );
//        $this->debug()->logVar( '$atts[ size ] = ', $atts[ 'size' ] );

        /*
         * Label Sizing
         * if the element has an attribute named 'label_size' , and it is not assigned
         * a value, use the form's label_size and transform it to a column size that
         * can be used for boostrap sizing in the col-md-# format 
         *      
         */
//
//        $form_label_size = $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'label_size' ];
//        if ( array_key_exists( 'label_size', $atts ) && is_null( $atts[ 'label_size' ] ) ) {
//
//
//
//            $atts[ 'label_size' ] = $this->changeToBoostrapSize( $form_label_size );
//
//    } elseif ( array_key_exists( 'label_size', $atts ) ) {
//            /* If the element has an attribute 'label_size, transform it to a column size that
//             * can be used for boostrap sizing in the col-md-# format 
//             */
//            $atts[ 'label_size' ] = $this->changeToBoostrapSize( $atts[ 'label_size' ] );
//
//}
//
//        $this->debug()->logVar( '$form_label_size = ', $form_label_size );

        /*
         * Add a unique prefix to the name so we dont conflict with other plugins that might be on the same form
         */

        if ( array_key_exists( 'name', $atts ) && is_null( $atts[ 'name' ] ) ) {
            $atts[ 'name' ] = $this->getFieldPrefix() . $atts[ 'name' ];
        }



        /*
         * Add a default label if one wasnt provided
         */


        if ( array_key_exists( 'label', $atts ) && is_null( $atts[ 'label' ] ) ) {

            $atts[ 'label' ] = $this->getDefaultFieldLabel( $atts[ 'name' ] );
        }

        $this->debug()->logVar( '$atts = ', $atts );

        $tags[ 'form_counter' ] = $this->addon()->getModule( 'Form' )->form_counter;
        if ( isset( $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'name' ] ) ) {
            $tags[ 'form_name' ] = $this->addon()->getModule( 'Form' )->form[ 'form' ][ 'name' ];
        }



        return (compact( 'scid', 'atts', 'tags' ));
       }

    /**
     * Change to Bootstrap Size
     *
     * Changes a preset text size to a numeric string corresponding to the 
     * Bootstrap grid column for use in label and element sizing for classes
     *  in the form col-md-12 where 12 is the grid column corresponding to 'large'
     * 
     *
     * @param string $size A text or numeric string
     * @return void
     */
    public function changeSizeToBoostrapSize( $size ) {

        $label_sizes = array(
            'small' => '3',
            'medium' => '6',
            'large' => '12',
            'extra-small' => '2',
        );

        if ( in_array( $size, array_keys( $label_sizes ) ) ){
            $normalizedSize = $label_sizes[ $size ];
    } else{
            $normalizedSize = $size;

    }
        return $normalizedSize;
    }

    /**
     * Change  Device Size to Bootstrap Size
     *
     * Changes a preset text size to one of the device sizes bootstrap uses. The preset text sizes should be consistent with the size attribute
     * 
     *
     * @param string $size A text or numeric string
     * @return void
     */
    public function changeDeviceSizeToBoostrapSize( $size ) {

        $device_sizes = array(
            'small' => 'sm',
            'medium' => 'md',
            'large' => 'lg',
            'extra-small' => 'xs',
        );

        if ( in_array( $size, array_keys( $device_sizes ) ) ){
            $normalizedSize = $device_sizes[ $size ];
    } else{
            $normalizedSize = $size;

    }
        return $normalizedSize;
}

    /**
     * Maybe Take Form Default
     *
     * Returns the value of the form default if the attribute is null
     *
     * @param string $att_name The name of the attribute
     * @param string $atts The array of attributes
     * @return void
     */
    public function maybeTakeFormDefault( $att_name, $atts ) {



        $form_att = $this->addon()->getModule( 'Form' )->form[ 'form' ][ $att_name ];
        if ( is_null( $atts[ $att_name ] ) ) {



            $atts[ $att_name ] = $form_att;

    }

        return($atts[ $att_name ]);


    }

    /**
     * Filter Radio
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterRadio( $properties ) {
        $this->debug()->t();

        extract( $properties );

        if ( is_null( $atts[ 'class' ] ) ) {
            $atts[ 'class' ] = 'radio';
}

        $properties = (compact( 'scid', 'atts', 'tags' ));

        /*
         * use the shared code for radio,dropdown, and checkbox elements
         */
        return($this->_filterOptions( $properties ));
}

    /**
     * Form Start
     *
     * Filters the Text Tag Attribute
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterFormStart( $properties ) {
        $this->debug()->setMethodFilter( __FUNCTION__ ,false);
        $this->debug()->t();

        extract( $properties );
        /*
         * Add a container
         * 
         * add container start tag if container is set to true and class is provided. if class is not provided, set to 'container'
         * the filterFormEnd() provides an ending tag for {container_end} if the formStart has contained set to true.
         * 
         * 
         */

        /*
         * if the container attribute was not set, then set it to true since many of 
         * the Bootstrap layouts depend on a container
         */
        if ( array_key_exists( 'container', $atts ) && is_null( $atts[ 'container' ] ) ) {
            $atts[ 'container' ] = true;
           }
// if no container class was configured then set a default value
        if ( array_key_exists( 'container_class', $atts ) && is_null( $atts[ 'container_class' ] ) ) {


            $atts[ 'container_class' ] = 'container-fluid';
     }

        if ( $atts[ 'container' ] === true ) {


            $tags[ 'container_start' ] = '<div class="' . $atts[ 'container_class' ] . '"><!-- start of container, added by formStart() -->';

} else  if ( $atts[ 'container' ] === false ){
            $tags[ 'container_start' ] = '';

}

        $this->debug()->logVar( '$atts = ', $atts );

        $properties = compact( 'scid', 'atts', 'tags' );

        /*
         * Now run it through the parent filter
         */
        $properties = parent::filterFormStart( $properties );

        return ($properties);
}

}
