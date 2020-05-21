<?php

/****************************************
Labels and countries
****************************************/

// Covid 19 Labels
$covtags_labels         = array (
  'total_deaths'              =>  __( 'Total Deaths', COVTAGS_TEXTDOMAIN ),
  'total_cases'               =>  __( 'Total Cases', COVTAGS_TEXTDOMAIN ),
  'mild_condition'            =>  __( 'Mild Condition', COVTAGS_TEXTDOMAIN ),
  'mild'                      =>  __( 'Mild', COVTAGS_TEXTDOMAIN ),
  'critical'                  =>  __( 'Critical', COVTAGS_TEXTDOMAIN ),
  'deaths'                    =>  __( 'Deaths', COVTAGS_TEXTDOMAIN ),
  'recovered'                 =>  __( 'Recovered', COVTAGS_TEXTDOMAIN ),
  'active'                    =>  esc_html__( 'Active', COVTAGS_TEXTDOMAIN ),
  'todayDeaths'               =>  esc_html__( 'Today Deaths', COVTAGS_TEXTDOMAIN ),
  'todayCases'                =>  esc_html__( 'Today Cases', COVTAGS_TEXTDOMAIN ),
  'closed'                    =>  esc_html__( 'Closed', COVTAGS_TEXTDOMAIN ),
  'cases_chart'               =>  esc_html__( 'Total Coronavirus Cases', COVTAGS_TEXTDOMAIN ),
  'deaths_chart'              =>  esc_html__( 'Total Coronavirus Deaths', COVTAGS_TEXTDOMAIN ),
  'recovered_chart'           =>  esc_html__( 'Total Recovered', COVTAGS_TEXTDOMAIN ),
  'covid_pandemic'            =>  esc_html__( 'COVID-19 Coronavirus Pandemic' , COVTAGS_TEXTDOMAIN ),
  'world_word'                =>  esc_html__( 'World', COVTAGS_TEXTDOMAIN ),
  'country_name'              =>  esc_html__( 'Country Name', COVTAGS_TEXTDOMAIN ),
  'cofirmed'                  =>  esc_html__( 'Confirmed', COVTAGS_TEXTDOMAIN ),
  'timeline'                  =>  esc_html__( 'Timeline', COVTAGS_TEXTDOMAIN ),
  'cases'                     =>  esc_html__( 'Cases', COVTAGS_TEXTDOMAIN ),
  'of_world_cases'            =>  esc_html__( 'of the world deaths', COVTAGS_TEXTDOMAIN ),
  'entries'                   =>  esc_html__( 'entries', COVTAGS_TEXTDOMAIN ),
  'showing'                   =>  esc_html__( 'Showing', COVTAGS_TEXTDOMAIN ),
  'to_'                       =>  esc_html__( 'to', COVTAGS_TEXTDOMAIN ),
  'of_'                       =>  esc_html__( 'of', COVTAGS_TEXTDOMAIN ),
  'tests'                     =>  esc_html__( 'Tests', COVTAGS_TEXTDOMAIN ),
  'next'                      =>  esc_html__( 'Next', COVTAGS_TEXTDOMAIN ),
  'prev'                      =>  esc_html__( 'Prev', COVTAGS_TEXTDOMAIN ),
  'casesPerOneMillion'        =>  esc_html__( 'Cases Per One Million', COVTAGS_TEXTDOMAIN ),
  'deathsPerOneMillion'       =>  esc_html__( 'Deaths Per One Million', COVTAGS_TEXTDOMAIN ),
  'testsPerOneMillion'        =>  esc_html__( 'Tests Per One Million', COVTAGS_TEXTDOMAIN ),
  'world_wide'                => esc_html__( 'World Wide', COVTAGS_TEXTDOMAIN ),
  'load_more'                 => esc_html__( 'Load More' , COVTAGS_TEXTDOMAIN ),
  'cases_outcome'             => esc_html__( 'Cases which had an outcome' , COVTAGS_TEXTDOMAIN ),
  'map_title'                 => esc_html__( 'Total Confirmed Cases', COVTAGS_TEXTDOMAIN ),
  'select_country'            => esc_html__( 'Select Country', COVTAGS_TEXTDOMAIN ),
);

// Months Of The Year
$covtags_year_months    = array (
  'jan'             =>  esc_html__( 'Jan', COVTAGS_TEXTDOMAIN ),
  'feb'             =>  esc_html__( 'Feb', COVTAGS_TEXTDOMAIN ),
  'mar'             =>  esc_html__( 'Mar', COVTAGS_TEXTDOMAIN ),
  'apr'             =>  esc_html__( 'Apr', COVTAGS_TEXTDOMAIN ),
  'may'             =>  esc_html__( 'May', COVTAGS_TEXTDOMAIN ),
  'jun'             =>  esc_html__( 'Jun', COVTAGS_TEXTDOMAIN ),
  'jul'             =>  esc_html__( 'Jul', COVTAGS_TEXTDOMAIN ),
  'aug'             =>  esc_html__( 'Aug', COVTAGS_TEXTDOMAIN ),
  'sep'             =>  esc_html__( 'Sep', COVTAGS_TEXTDOMAIN ),
  'oct'             =>  esc_html__( 'Oct', COVTAGS_TEXTDOMAIN ),
  'nov'             =>  esc_html__( 'Nov', COVTAGS_TEXTDOMAIN ),
  'dec'             =>  esc_html__( 'Dec', COVTAGS_TEXTDOMAIN )
);


/****************************************
Actions With Hooks
****************************************/

// Ajax for standard Card ( Live Updates For Covid 19 )
if( ! function_exists( 'covtags_ajax_standard_card' ) ) {

  function covtags_ajax_standard_card() {

    // Verify Nonce
    if( ! wp_verify_nonce( $_REQUEST['covtags_coronavirus_sec'], 'covtags_live_update_action' ) ) {

      wp_send_json( ( object ) array(
        'status_code'   => 0,
        'response_code' => 404,
        'message'       => __( 'error', COVTAGS_TEXTDOMAIN ),
        'data'          => array(),
      ) );
      exit;

    }

    // Load Needed Classes
    $http_request = new covtags_http_requests();

    // For Collect data together
    $results = array ();

    // Send Request For first two element in array
    foreach (  $http_request->data_slug as $key => $slug_name) {

      // Case target elements are not exists
      if( 'all' !== $slug_name && 'countries' !== $slug_name ) {
        break;
      }

      // Build Url then call request method
      $api_url  = sprintf( $http_request->api_url . '%1$s', $slug_name );
      $response = $http_request->covtags_api_request( $api_url );

      // Store new data inside array
      $results[ $slug_name ] = $response;

    }

    wp_send_json( $results );
    exit;

  }

  add_action( 'wp_ajax_covtags_live_update_data',  'covtags_ajax_standard_card' );
  add_action( 'wp_ajax_nopriv_covtags_live_update_data',  'covtags_ajax_standard_card' );

}

// Load External Files
if( ! function_exists( 'covtags_load_external_files' ) ) {

  function covtags_load_external_files() {

    // Globals
    global $covtags_year_months, $covtags_labels;

    /* Load Google Font Links
    ---------------------------------*/
    wp_enqueue_style( 'covtags-montserrat-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800', false, COVTAGS_VER, 'all' );
    wp_enqueue_style( 'covtags-comfortaa-google-fonts', 'https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;500;600', false, COVTAGS_VER, 'all' );

    /* Load Stylesheet Files
    ---------------------------------*/
    wp_enqueue_style( 'covtags-awesome-fonts', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css', false, COVTAGS_VER, 'all' );
    wp_enqueue_style( 'covtags-select2-sheet', COVTAGS_SRC . 'assets/css/select2.min.css', false , COVTAGS_VER, 'all' );
    wp_enqueue_style( 'covtags-card-styles', COVTAGS_SRC . 'assets/css/card-styles.css', false , COVTAGS_VER, 'all' );

    /* Load Javascript Files
    ---------------------------------*/
    wp_enqueue_script( 'covtags-chartjs-script', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js', array( 'jquery' ), COVTAGS_VER, true );
    wp_enqueue_script( 'covtags-select2-script',  COVTAGS_SRC . 'assets/js/select2.min.js', array( 'jquery' ), COVTAGS_VER, true );
    wp_enqueue_script( 'covtags-card-scripts',  COVTAGS_SRC . 'assets/js/card-scripts.js', array( 'jquery' ), COVTAGS_VER, true );

    /* Localize Scripts
    ---------------------------------*/
    $http_request = new covtags_http_requests();
    $data_script  = array(
      'months_labels'  => $covtags_year_months,
      'basic_labels'   => $covtags_labels,
      'countries'      => $http_request->covtags_countries( NULL, false ),
      'all'            => $http_request->covtags_world_report( false ),
      'historical'     => $http_request->covtags_historical( false ),
      'covid19_icon'   => COVTAGS_SRC . 'assets/img/bg-icon.png',
      'secure'         => wp_create_nonce( 'covtags_live_update_action' ),
      'url'            => esc_url( admin_url( 'admin-ajax.php' ) ),
    );
    wp_localize_script( 'covtags-card-scripts', 'covtags_obj', $data_script );

  }
  add_action( 'wp_enqueue_scripts', 'covtags_load_external_files' );

}

/****************************************
General Methods
****************************************/

// For Checkboxes and radio boxes
if( ! function_exists( 'covtags_is_checked' ) ) {

  function covtags_is_checked( $saved_value, $input_value, $default = 'checked' ) {

    echo ( $saved_value ===  $input_value )? esc_attr( $default ): '';

  }

}

// For Checkboxes Array
if( ! function_exists( 'covtags_is_checked_in_array' ) ) {

  function covtags_is_checked_in_array( $array_fields , $input_value ) {

    if ( ! is_array( $array_fields ) ) {
      $array_fields = array();
    }

    $is_chicked = ( array_search( $input_value, $array_fields, true ) === false ) ? '' : esc_attr( 'checked' );
    echo $is_chicked;

  }

}


/****************************************
Filters
****************************************/

// International System (of Units)
if( ! function_exists( 'covtags_filter_number_with_units' ) ) {

  function covtags_filter_number_with_units( $number, $format ) {

    // Case it Zero
    $number = ( int ) $number;
    if( 0 === $number ) {
      return $number;
    }

    // Needed Givens
    $number_length = strlen( $number );
    $ratio         = 3;
    $indexes       = array();
    $units         = array(
      __( 'k', COVTAGS_TEXTDOMAIN ),
      __( 'M', COVTAGS_TEXTDOMAIN ),
      __( 'G', COVTAGS_TEXTDOMAIN ),
      __( 'T', COVTAGS_TEXTDOMAIN ),
      __( 'P', COVTAGS_TEXTDOMAIN ),
      __( 'E', COVTAGS_TEXTDOMAIN ),
      __( 'Z', COVTAGS_TEXTDOMAIN ),
      __( 'Y', COVTAGS_TEXTDOMAIN )
    );

    // Build Delimiters
    for ( $i=$ratio; $i < $number_length; $i++ ) {

      if( 0 === ( $i % $ratio) ) {
        $indexes[ count( $indexes ) ] = ( $number_length - ( count( $indexes ) * $ratio ) ) - $ratio ;
      }

    }

    // Set Target Delimiter
    switch ( $format ) {

      // Format Number With Comma
      case 'comma_format':

        if( 0 !== count( $indexes ) ) {
          foreach ( $indexes as $key => $index) {
            $number  = substr_replace( $number , ',' , $index , 0 );
          }
        }

        break;

      // International System (of Units)
      case 'unit_format':

        if( ! isset( $units[ count( $indexes ) - 1 ] ) ) {
          return $number;
        }

        if( 0 !== count( $indexes ) ){
          $number = substr( $number, 0, min( $indexes ) ) ;
        }

        $number   .= $units[ count( $indexes ) - 1 ] ;

        break;

      // Empty space
      default:

        if( 0 !== count( $indexes ) ){
          foreach ( $indexes as $key => $index) {
            $number  = substr_replace( $number, ' ', $index, 0 );
          }
        }

        break;

    }

    return $number;

  }
  add_filter( 'covtags_number_unit', 'covtags_filter_number_with_units', 10, 2 );

}

// Filter Data with target array
if( ! function_exists( 'covtags_filter_fields_callback' ) ) {

  function covtags_filter_fields_callback( $string, $default_list, $splitter = ',' ) {

    // Join string as an array
    $list_args = explode( ',', $string );

    // map and check fields is it already exists or list
    $list_args = array_map( function( $field ) use ( $default_list ) {

      $build_field = $field;
      $strip_space = explode( ' ', $field );

      // Strip Spaces
      if( 0 === count( $strip_space ) ) {
        $build_field = $field;
      }

      // Field key has spaces issue
      if( 0 !== count( $strip_space ) ) {

        $strip_this_spaces = '';
        for ( $i=0; $i < count( $strip_space ); $i++) {

           if( $i !== 0 ){
             $strip_this_spaces .= trim( ucfirst( $strip_space[ $i ] ) );
           }else {
             $strip_this_spaces .= trim( $strip_space[ $i ] );
           }

        }
        $build_field = $strip_this_spaces;

      }

      // Field key is already exists with our array
      if( in_array( $build_field, $default_list ) ){
        return $build_field;
      }

    }, $list_args );

    // to remove empty fields
    $list_args = array_filter( $list_args );

    // to reindex array
    $list_args = array_values( $list_args );

    return $list_args;

  }
  add_filter( 'covtags_filter_fields', 'covtags_filter_fields_callback', 10, 3 );

}
