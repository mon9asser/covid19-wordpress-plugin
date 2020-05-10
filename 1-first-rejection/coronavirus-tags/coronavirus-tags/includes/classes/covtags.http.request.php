<?php

// Request Data From Api
if( !class_exists( 'CovTags_Request' ) ){

  class CovTags_Request {

      // Attributes
      public $errors;
      public $api_url;
      public $req_args;
      public $cache_period;
      public $transient_name;
      public $filter_columns;
      public $basic_url;
      public $covtags_options       = array(); // countries
      public $covtags_options_name  = 'covtags_eratags_key_name'; // key of options
      public $history_lists         = array();

      // Main Callback to init imortant Data
      public function __construct (){

        # Set Basic Transient Name
        $this->transient_name = 'covtags_data_v2';
        # For Handling Errors
        $this->errors         = new WP_Error();
        # Store And Clean Target Api Url
        $this->api_url        = esc_url( 'https://api.caw.sh/v2/' );
        # For Rest Data
        $this->basic_url      = esc_url( 'https://api.caw.sh/v2/' );
        # Request Args
        $this->req_args       = array(
           'timeout' => 60
        );

        # Store Default Cache Period
        if( !isset( $this->cache_period ) ){
          $this->cache_period = ( 5 * 60 ); // 5 Mins
        }
      }

      // Countries Data
      public function Countries ( $country_name = NULL, $filter = false, $sort_key = null ){

        $countries_data = ( object ) array(
          'status_code'     => 0,
          'response_code'   => 404,
          'message'         => '',
          'data'            => array()
        );

        # Storing Important Values
        $this->transient_name .= "_countries";
        $this->api_url .= 'countries';

        # Sort Parameter Issue
        if( $sort_key !== NULL && $country_name !== NULL){
          $this->errors->add( 'error', __( "You Could't able to use country_name parameter with sort_key paramter, Set one as null" ) );
        }

        # Sort By Parameter
        if( NULL !== $sort_key ){
          $this->api_url .= '?sort=' . $sort_key ;
        }

        # Case Request Specific Country
        if( NULL !== $country_name){
            $this->api_url .= '/' . $country_name;
            $this->transient_name .= "_country_" . $country_name;
        }

        # Filter With spesific filters
        if( false !== $filter ){
          $this->transient_name .= "_filter";
        }

        # Case Enable Two Both
        if( false !== $filter && $country_name !== NULL ){
          $this->transient_name .= "_country_by_filter";
        }

        # Rquest Callback
        $response = $this->Retrieve();

        # Handling Errors
        if( count( $this->errors->get_error_codes() ) !== 0 ){
          $countries_data->status_code    = 0;
          $countries_data->response_code  = 404;
          $countries_data->message        = $this->errors->get_error_codes();
          $countries_data->data           = $this->errors->get_error_messages();
          return $countries_data;
        }

        # Case Success Request
        $countries_data->status_code    = 1;
        $countries_data->response_code  = 200;
        $countries_data->message        = 'OK';
        $countries_data->data           = $response;

        # Filter Current Request With a Specific keys
        if( false !== $filter ){


          if( !isset( $this->filter_columns ) ){
            $this->filter_columns = array(
               'countryInfo._id',
               'country',
               'countryInfo.iso3',
               'countryInfo.flag',
               'countryInfo.long',
               'countryInfo.lat'
            );
          }

          if ( $country_name !== NULL ){
            $response = array( $response );
          }

          $countries_data->data                       = $this->Filtered_Data( $response );
          $this->covtags_options['list_of_countries'] = $this->Filtered_Data( $response );
        }



        # Return Response Data
        return $countries_data ;
      }

      // World Cases Report
      public function World_Report ( $filter = false ){
           $world_results = ( object ) array(
             'status_code'    => 0,
             'response_code'  => 404,
             'message'        => '',
             'data'           => array()
           );
           # Storing Important Values
           $this->transient_name .= "_world_report";
           $this->api_url .= 'all';

           # Filter With spesific filters
           if( false !== $filter ){
             $this->transient_name .= "_filter";
           }

           # Rquest Callback
           $response = $this->Retrieve();

          # Handling Errors
          if( count( $this->errors->get_error_codes() ) !== 0 ){
            $world_results->status_code   = 0;
            $world_results->response_code = 404;
            $world_results->message       = $this->errors->get_error_codes();
            $world_results->data          = $this->errors->get_error_messages();
            return $world_results;
          }

          # Case Success Request
          $world_results->status_code     = 1;
          $world_results->response_code   = 200;
          $world_results->message         = 'OK';
          $world_results->data            = $response;

          # Case Filter
          if( false !== $filter ){
            if( !isset( $this->filter_columns ) ){

               $this->filter_columns = array (
                  'recovered'
               );
             }

             $response = array( $response );
             $world_results->data = $this->Filtered_Data( $response );
           }

          # Return Response Data
          return $world_results ;
      }

      // Historical
      public function Historical ( $country_name = null , $filter = false ){

          $history_data = ( object ) array(
            'status_code'   => 0,
            'response_code' => 404,
            'message'       => '',
            'data'          => array()
          );
          # Storing Important Values
          $this->transient_name .= "_historical";
          $this->api_url        .= 'historical';

          # Filter With spesific filters
          if( false !== $filter ){
            $this->transient_name .= "_filter";
          }

          if( NULL !== $country_name){
              $this->api_url .= '/' . $country_name;
              $this->transient_name .= "_country_" . $country_name;
          }

          # Rquest Callback
          $response = $this->Retrieve();

          # Handling Errors
          if( count( $this->errors->get_error_codes() ) !== 0 ){
            $history_data->status_code    = 0;
            $history_data->response_code  = 404;
            $history_data->message        = $this->errors->get_error_codes();
            $history_data->data           = $this->errors->get_error_messages();
            return $history_data;
          }

          # Case Success Request
          $history_data->status_code    = 1;
          $history_data->response_code  = 200;
          $history_data->message        = 'OK';
          $history_data->data           = $response;

          if( false !== $filter ){

            if( !isset( $this->filter_columns ) ){
              $this->filter_columns = array(
                 'country',
                 'timeline.cases',
                 'timeline.deaths',
                 'timeline.recovered'
              );
            }

            if ( $country_name !== NULL ){
              $response = array( $response );
            }

            $history_data->data = $this->Filtered_Data( $response );
          }

          return $history_data ;
      }

      // Filter An Object Or Array and return keys with values
      private function Filtered_Data ( $data_array  ){


        $requested = array ();

        # Casting
        if( !is_array ( $this->filter_columns ) ){
          $this->filter_columns = ( array ) $this->filter_columns;
        }

        if( !is_array ( $data_array ) ){
          $data_array = ( array ) $data_array;
        }

        # Use map callback to filter columns with it children
        $args = array_map( function( $object ){
          $return_data = array();
          if( 0 ===  count( $this->filter_columns ) ) {
            return $object ;
          }
          foreach ( $this->filter_columns as $key => $key_name ) {

            # Explode String to show the children
            $children = explode( '.', $key_name );

            # Build Keys With Target Values for specific cases
            if( count( $children ) === 1 ){
              $return_data[$children[0]] = $object->{$children[0]};
            }
            if( count( $children ) === 2 ){
              $return_data[$children[1]] = $object->{$children[0]}->{$children[1]};
            }
            if( count( $children ) === 3 ){
              $return_data[$children[2]] = $object->{$children[0]}->{$children[1]}->{$children[2]};
            }
            if( count( $children ) === 4 ){
              $return_data[$children[3]] = $object->{$children[0]}->{$children[1]}->{$children[2]}->{$children[3]};
            }
            if( count( $children ) === 5 ){
              $return_data[$children[4]] = $object->{$children[0]}->{$children[1]}->{$children[2]}->{$children[3]}->{$children[4]};
            }
            if( count( $children ) === 6 ){
                $return_data[$children[5]] = $object->{$children[0]}->{$children[1]}->{$children[2]}->{$children[3]}->{$children[4]}->{$children[5]};
            }
          }
          return $return_data ;
        } , $data_array );


        return (object) $args ;
      }

      // Timeline Cases and deaths
      public function Historical_Sheet ( $field = 'deaths', $country = null, $week_days = 7, $is_last_row = true ){

          # Reset main Value
          $this->history_lists = array();

          # Send Request
          $data_provider       = $this->Historical( $country );

          # Handling errors
          if( 0 === $data_provider->status_code || 200 !==$data_provider->response_code ){
            return;
          }

          # Success Request
          $data_provider = $data_provider->data;

          # Case Request for country
          if( NULL !== $country && isset( $data_provider->timeline->{$field} ) ){

            # Timeline Fields
            $fields = ( array ) $data_provider->timeline->{$field};

            # Change key name
            array_map( function( $item_value, $item_key ){

              # Store with date format case
              $item_key                       = strtotime( $item_key );
              $item_key                       = strtolower( date( 'd_M_Y', $item_key ) );
              $this->history_lists[$item_key] = $item_value;

            }, $fields , array_keys( $fields ));

          }

          # Case Request For All World
          if( NULL === $country ) {

              array_map( function( $item ) use ( $field ) {

                 # Get Target Column With Data
                 $target_field = ( array ) $item->timeline->{$field};

                 # Build And calculate Columns
                 array_map ( function ( $arr_val, $arr_key ) {

                   # Store with date format case
                   $arr_key = strtotime( $arr_key );
                   $arr_key = strtolower( date( 'd_M_Y', $arr_key ) );

                   # Store new unique keys
                   if( !isset( $this->history_lists[$arr_key] ) ){
                     $this->history_lists[$arr_key] = 0 ;
                   }

                   # Calculate all fields in the same key
                   $this->history_lists[$arr_key] = ( $this->history_lists[$arr_key] + $arr_val );

                 }, $target_field, array_keys( $target_field) );

               }, $data_provider );

          }

          # Split current array to many parts according to counts of day per week
          $reults = ( 0 !== $week_days ) ? array_chunk( $this->history_lists, $week_days , true ) : $this->history_lists ;

          # Return Last row for each chuncked array
          if( false !== $is_last_row && 0 !== $week_days ){

              # Rest Big array
              $this->history_lists = array();

              # Filter Last row from each array
              array_map( function( $args ){

                # Store important keys
                $keys                           = array_keys( $args );
                $last_key                       = $keys[count( $keys ) - 1];
                $this->history_lists[$last_key] = $args[$last_key];

              }, $reults );

          }else {
            $this->history_lists = $reults;
          }

          return $this->history_lists;
      }

      // Retrieve Corona Data
      private function Retrieve (){

          # Main Request
          $requestBody = get_transient( $this->transient_name );


          if( false === $requestBody && null !== $requestBody ){

            $request =  wp_remote_get( $this->api_url, $this->req_args);
            $requestBody = wp_remote_retrieve_body( $request );

            #  Try To Getting an errors
            if( !is_array( $request ) || is_wp_error( $request )){
              $this->errors->add( 'error', __( 'Something went wrong, please try later' ) );
            }
            if( 200 !== wp_remote_retrieve_response_code( $request ) && 403 !== wp_remote_retrieve_response_code( $request ) ){
              $this->errors->add( 'request_error', __( 'Bad Request' ) );
            }

            # Set Cache Period
            if( count( $this->errors->get_error_codes() ) === 0 && ( 200 === wp_remote_retrieve_response_code( $request ) )){
              set_transient( $this->transient_name, $requestBody , $this->cache_period );
            }
          }

          # Reset Data
          $this->api_url = $this->basic_url;

          # Store body data
          //$response_body = wp_remote_retrieve_body( $request );
          $decoded_array = json_decode( $requestBody );
          return $decoded_array;

      }


      // Build an options
      private function CovTags_Options (){
          $this->covtags_options['options'] = array(
              // 5 mins ( Default Option)
              'cache_period'  => ( 60 * 30 )
          );

        // Wordpress Built-in Sanitize key
        $this->covtags_options_name = apply_filters ( 'sanitize_key', $this->covtags_options_name ) ;
      }

      // Save Options of our api
      public function Save(){
        if( count( $this->covtags_options ) !== 0 ){

          // Build General Options
          $this->CovTags_Options();

          // Save Options
          update_option( $this->covtags_options_name ,  $this->covtags_options );
        }
      }
  }

}
