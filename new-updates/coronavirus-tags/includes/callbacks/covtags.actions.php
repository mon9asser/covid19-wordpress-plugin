<?php

#-------------------------------------------
# Shortcode and Widget External Files
#-------------------------------------------
if( !function_exists( 'covtags_load_external_files' ) ){
  function covtags_load_external_files (){
    global $months_names, $covtags_labels;

    # Load historical Data to send it with localize script
    $httpRequest = new CovTags_Request();
    $enq_data   = array(
      'labels'        => $months_names,
      'basic_labels'  => $covtags_labels,
      'secure'        => wp_create_nonce( 'nonce_send_update' ),
      'url'           => esc_url( admin_url( 'admin-ajax.php' ) )
    );

    # Google Fonts
    wp_enqueue_style( 'covtags-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800', false , COVTAGS_VER , 'all' );
    wp_enqueue_style( 'covtags-google-fonts-2', 'https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;500;600', false , COVTAGS_VER , 'all' );

    # Css
    wp_enqueue_style( 'covtags-awesome-fonts', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css', false , COVTAGS_VER , 'all' );
    wp_enqueue_style( 'covtags-select2-sheet'  , COVTAGS_SRC . 'assets/css/select2.min.css', false , COVTAGS_VER , 'all' );
    wp_enqueue_style( 'covtags-stylesheets'  , COVTAGS_SRC . 'assets/css/src/main.styles.css', false , COVTAGS_VER , 'all' );


    # Scripts
    wp_enqueue_script( 'covtags-chartjs-script', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js', array( 'jquery' ) , COVTAGS_VER , false );
    wp_enqueue_script( 'covtags-select2-script' ,  COVTAGS_SRC . 'assets/js/select2.min.js', array( 'jquery' ) , COVTAGS_VER , false ); 
    wp_enqueue_script( 'covtags-scripts' ,  COVTAGS_SRC . 'assets/js/src/main.scripts.js', array( 'jquery' ) , COVTAGS_VER , false );
    wp_localize_script( 'covtags-scripts', 'covtags_obj', $enq_data );

  }
}
add_action( 'wp_enqueue_scripts', 'covtags_load_external_files' );



if( !function_exists( 'is_checked' ) ){

  function is_checked( $saved_value, $input_value, $default = 'checked' ){
    echo ( $saved_value ===  $input_value )? esc_attr( $default ): '';
  }

}

if( !function_exists( 'is_checked_in_array' ) ){

  function is_checked_in_array ( $array_fields , $input_value ){

    if ( !is_array( $array_fields ) ){
      $array_fields = array();
    }

    $is_chicked = ( array_search( $input_value, $array_fields, true ) === false ) ? '': esc_attr( 'checked' );
    echo $is_chicked;
  }

}
