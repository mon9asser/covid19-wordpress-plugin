<?php


// Si System ( Number Units )
if( !function_exists ( 'covtags_si_system_number_units_callback') ){
  function covtags_si_system_number_units_callback ( $number, $format ){
    # Basic Variables
    $indexes        = array ();
    $number_length  = strlen( $number );
    $build_number   = $number;
    $ratio          = 3 ;

    # International System (of Units)
    $units        = array (
      __( 'k', COVTAGS_TEXTDOMAIN ),
      __( 'M', COVTAGS_TEXTDOMAIN ),
      __( 'G', COVTAGS_TEXTDOMAIN ),
      __( 'T', COVTAGS_TEXTDOMAIN ),
      __( 'P', COVTAGS_TEXTDOMAIN ),
      __( 'E', COVTAGS_TEXTDOMAIN ),
      __( 'Z', COVTAGS_TEXTDOMAIN ),
      __( 'Y', COVTAGS_TEXTDOMAIN )
    );

    # NA with zero value
    if( $build_number === 0 ){
      $build_number = "-";
    }

    # Build Delimiters
    for ( $i=3; $i < $number_length; $i++ ) {
        if( 0 === ( $i % $ratio) ){
          $indexes[count( $indexes )] = ( $number_length - ( count( $indexes ) * $ratio ) ) - $ratio ;
        }
    }

    # Case Number Format is -> ,
    if( 'format_comma' === $format ){
        if( 0 !== count( $indexes ) ){
          foreach ( $indexes as $key => $index) {
            $build_number  = substr_replace( $build_number , ',' , $index , 0 );
          }
        }
    }

    # Case Number Format is -> space
    if( 'format_space' === $format ){
        if( 0 !== count( $indexes ) ){
          foreach ( $indexes as $key => $index) {
            $build_number  = substr_replace( $build_number , ' ' , $index , 0 );
          }
        }
    }

    # Case Number Format is -> n_format_name
    if( 'format_unit' === $format ){

      if( !isset( $units[ count( $indexes ) - 1 ] ) )
        return $build_number ;

      if( 0 !== count( $indexes ) ){
          $build_number = substr( $build_number, 0, min( $indexes ) ) ;
      }

      $build_number   .= $units[ count( $indexes ) - 1 ] ;

    }

    return $build_number;
  }
}
add_filter( 'covtags_si_system_number_units', 'covtags_si_system_number_units_callback', 10, 2 );


// to be compatible with old versions on wp
if( !function_exists( 'covtags_doshortcode_for_widget_texts') ){
    function covtags_doshortcode_for_widget_texts ( $text ){
      return do_shortcode( $text );
    }
}
add_filter( 'widget_text', 'covtags_doshortcode_for_widget_texts');
