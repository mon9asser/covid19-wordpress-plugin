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



// Filter Widget Data
if( !function_exists( 'coronavirus_tags_widget_cards' ) ){

  function coronavirus_tags_widget_cards( $options ){

    $attributes = array();
    $ui = new CovTags_UI();

    # Extract Options : Standard Card
    if( 'standars-card' === $options['card-type'] ){
      $attributes                       = array();
      $attributes['title']              = $options['covid_text'];
      $attributes['live_word']          = $options['live_text'];
      $attributes['dark_mode']          = $options['dark_mode'];
      $attributes['is-widget']          = $options['is-widget'];
      return $ui->Standard_Card_Countries( $attributes );
    }

    # Extract Options : Map Card
    if( 'map-card' === $options['card-type'] ){
      $attributes = array();
      $attributes['dark_mode']          = $options['dark_mode'];
      $attributes['storke_color']       = 'tan';
      $attributes['fill_color']         = '#dad3d1';
      $attributes['storke_hover_color'] = '#b99b73';
      $attributes['fill_hover_color']   = '#fff1dd';
      return $ui->covtags_render_map( $attributes );
    }

    # Extract Options : Ticker Card
    if( 'ticker-card' === $options['card-type'] ){
      $attributes = array();
      $attributes['dark_mode']          = $options['dark_mode'];
      $attributes['ticker_speed']       = $options['ticker_speed'] ;
      $attributes['ticker_text']        = $options['ticker_text'];
      $attributes['field']              = $options['data_provider'];
      $attributes['position']           = 'normal'; # We've No ticker position option for widget
      return $ui->Card_All_countries_tricker( $attributes );
    }


    # Extract Options : Statistics Card
    if( 'stats-card' === $options['card-type'] ){

      $field_data = $options['fields'];
      $field_string = "";

      for ($i=0; $i < count( $field_data ); $i++) {
        $field_string .= $field_data[$i];
        if( $i < count( $field_data ) ){
            $field_string .= ',';
        }
      }

      $options['fields'] = $field_string;
      $options['cols'] = 4;
      $options['inner-spacing'] = 0;
      $attributes = $options;

      return $ui->Card_live_statistics( $attributes );
    }

    # Extract Options : Status Card
    if( 'status-card' === $options['card-type'] ){
      return $ui->Show_Cases_Status_with_graph( $options );
    }

    return;
  }
  add_filter( 'coronavirus_tags_cards', 'coronavirus_tags_widget_cards', 10, 1 );

}
