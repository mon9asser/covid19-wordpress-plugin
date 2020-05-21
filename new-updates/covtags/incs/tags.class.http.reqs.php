<?php

if( ! class_exists( 'covtags_http_requests' ) ) {

  class covtags_http_requests {

    // Attributes
    public $api_url   = 'https://api.caw.sh/v2/';
    public $data_slug = array(
      'all',
      'countries',
      'historical'
    );
    public $req_args  = array(
      'timeout' => 60
    );

    /* Setup a Cron Job */
    public function covtags_setup_cron_job() {

      // Add new Schedule
      add_filter( 'cron_schedules', array ( $this, 'setup_cron_schedules_every_ten_mins' ), 10, 1 );

      // Set a Schedule
      if( ! wp_next_scheduled( 'covtags_coronavirus_cron_job' ) ){
        wp_schedule_event( time(), 'every_10_minutes', 'covtags_coronavirus_cron_job' );
      }

      // Apply Hook for cron job
      add_action( 'covtags_coronavirus_cron_job', array( $this, 'covtags_save_coronavirus_options' ) );

    }

    /* Setup a Schedule every 10 minutes */
    public function setup_cron_schedules_every_ten_mins() {

      // Schedule every 10 minutes
      $schedules[ 'every_10_minutes' ] = array(
        'display'   => __( 'Every 10 Minutes' ),
        'interval'  => ( 60 * 10 )
      );
      return $schedules;

    }

    /* Start Cron Job : Prepare Options and settings for http request */
    public function covtags_save_coronavirus_options() {

      // Check if we have a stored data before
      $stored_options = get_option( 'covtags_coronavirus_options' );

      // Case no array type or fields
      if( ! is_array( $this->data_slug ) || count( $this->data_slug ) === 0 ) {
        return;
      }

      // Case There are no options stored before
      if( ! is_array( $stored_options ) || empty( $stored_options ) ) {

        // just an empty array
        $stored_options = array();
        if( ! isset( $stored_options ) ){
          $stored_options = array();
        }

      }

      // Loop into 3 fields and get data according to it
      foreach ( $this->data_slug as $slug_key => $slug_name ) {

        // Send Request for 3 times ( countries - historical and all )
        $api_url = sprintf( $this->api_url . '%1$s', $slug_name );
        $response = $this->covtags_api_request( $api_url );

        if( 200 === ( int ) $response->response_code ) {
          $stored_options[ $slug_name ] = $response;
        }

      }

      // Update Other Options -> Country Names with iso3
      $countries_iso3 = $this->covtags_get_countries_info( $stored_options[ 'countries' ] );

      // Save Countries with iso3
      $stored_options[ 'list_of_countries' ] = $countries_iso3;

      // Update Corona virus Options
      update_option( 'covtags_coronavirus_options' ,  $stored_options );

    }

    /* Filter Country Objects and reset only target information */
    public function covtags_get_countries_info( $countries ) {

      // Case error in http request
      if( 200 !== $countries->response_code ) {
        return array();
      }

      // Success Calling
      $stored_countries = json_decode( $countries->data );

      // Filter Data and Git only Specific fields for country names
      $mapped_countries = array_map( function( $country_obj ){

        // Default List
        $country_data = array();

        // Required Fields
        $country_data[ 'country' ] = $country_obj->country;
        $country_data[ '_id' ]     = $country_obj->countryInfo->_id;
        $country_data[ 'iso2' ]    = $country_obj->countryInfo->iso2;
        $country_data[ 'iso3' ]    = $country_obj->countryInfo->iso3;
        $country_data[ 'lat' ]     = $country_obj->countryInfo->lat;
        $country_data[ 'long' ]    = $country_obj->countryInfo->long;
        $country_data[ 'flag' ]    = $country_obj->countryInfo->flag;

        return $country_data;

      }, $stored_countries );

      return $mapped_countries;

    }

    /* Send Request To Covid 19 api */
    public function covtags_api_request( $url ) {

      // Request Data
      $results       = array();
      $api_url       = esc_url( $url );
      $request       = wp_remote_get( $api_url, $this->req_args);
      $response_code = wp_remote_retrieve_response_code( $request );

      // Case Success Data Store and define needed objects
      if( 200 === $response_code ) {
        $response_data              = wp_remote_retrieve_body( $request );
        $results[ 'data' ]          = $response_data;
        $results[ 'status_code' ]   = 1;
        $results[ 'response_code' ] = $response_code;
        $results[ 'message' ]       = __( 'success', COVTAGS_TEXTDOMAIN );
      }else {
        $results[ 'data' ]          = '';
        $results[ 'status_code' ]   = 0;
        $results[ 'response_code' ] = 404;
        $results[ 'message' ]       = __( 'Something went wrong, please try later', COVTAGS_TEXTDOMAIN );
      }

      // Return final result
      return ( object ) $results;
    }

    /* Retrieve Data */
    private function covtags_retrieve( $data_name = null ) {

      // return an empty array if data name is null
      if( $data_name === null ){
        return array();
      }

      // Getting Stored Data from options
      $api_data = get_option( 'covtags_coronavirus_options' );

      // Get target data according to request in parameter
      if( isset ( $api_data[ $data_name ] ) && $api_data[ $data_name ]->response_code === 200 ){
        return $api_data[ $data_name ]->data;
      }

      // By Default
      return array();

    }

    /* Countries Data Report */
    public function covtags_countries( $country_name = NULL, $is_decoded = true ) {

      // Retrieve Data
      $results = $this->covtags_retrieve( 'countries' );

      // Case We have no data
      if(  0 === count( ( array ) json_decode( $results ) ) ) {
        return false;
      }

      // Case Request a Specific Country
      if( NULL !== $country_name ) {

        $countries_data = json_decode( $results ) ;

        $results = array_filter( $countries_data, function( $obj ) use ( $country_name ) {
          return ( int ) $obj->countryInfo->_id === ( int ) $country_name;
        } );
        $results = array_values( $results )[ 0 ];

        if( false === $is_decoded ){
          $results = json_encode( $results );
        }

      }else {

        // Decode Data case it php method
        $results =  ( $is_decoded === true ) ? json_decode( $results ) : $results ;

      }

      return $results ;

    }

    /* Countries Data Report */
    public function covtags_world_report( $is_decoded = true  ) {

      // Retrieve Data
      $results = $this->covtags_retrieve( 'all' );

      // Case We have no data
      if(  0 === count( ( array ) json_decode( $results ) ) ) {
        return false;
      }

      // decode or encode
      $results = ( $is_decoded === true ) ? json_decode( $results ) : $results;

      return $results;

    }

    /* Historical Data For All Countries */
    public function covtags_historical( $is_decoded = true ) {

      // Retrieve Data
      $results = $this->covtags_retrieve( 'historical' );

      // Case We have no data
      if(  0 === count( ( array ) json_decode( $results ) ) ) {
        return false;
      }

      // decode or encode
      $results = ( $is_decoded === true ) ? json_decode( $results ) : $results;

      return $results;

    }

  }

  // Activate Cron Job
  $http_request = new covtags_http_requests();
  $http_request->covtags_setup_cron_job();

}
