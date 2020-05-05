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
      'basic_labels'  => $covtags_labels
    );

    # Google Fonts
    wp_enqueue_style( 'covtags-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&display=swap', false , COVTAGS_VER , 'all' );

    # Css
    wp_enqueue_style( 'covtags-awesome-fonts', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css', false , COVTAGS_VER , 'all' );
    wp_enqueue_style( 'covtags-stylesheets'  , COVTAGS_SRC . 'assets/css/covtags.styles.min.css', false , COVTAGS_VER , 'all' );

    # Scripts
    wp_enqueue_script( 'covtags-chartjs-script', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js', array( 'jquery' ) , COVTAGS_VER , false );
    wp_enqueue_script( 'covtags-scripts' ,  COVTAGS_SRC . 'assets/js/covtags.min.js', array( 'jquery' ) , COVTAGS_VER , false );
    wp_localize_script( 'covtags-scripts', 'covtags_obj', $enq_data );

  }
}
add_action( 'wp_enqueue_scripts', 'covtags_load_external_files' );
