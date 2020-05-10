<?php

if( !class_exists( 'CovTags_Compatibility' ) ){

  class CovTags_Compatibility {

    // Invocation all class callbacks
    public function _Load(){
      # Wordpress And PHP Version compatibility Cases
      $this->php_compatibility();
      $this->software_compatibility();
    }

    // Prevent Installing on old php versions
    public function php_compatibility(){
      if( version_compare( COVTAGS_PHPVER , '5.6' , '<' ) ){
              // Message
              $covtags_message = sprintf( esc_html__( 'This Plugin requires at least PHP version 5.3. You are running version %s. Please upgrade and try again', COVTAGS_TEXTDOMAIN ), COVTAGS_PHPVER );
              // Die With Message
              wp_die( $covtags_message );
          }
    }

    // Prevent Installing on old wordpress versions
    public function software_compatibility(){
      // Wordpress Version
      global $wp_version;
      if( version_compare( $wp_version , '4.5' , '<' ) ){
              // Message
              $covtags_message = sprintf( esc_html__( 'This Plugin requires at least wordpress version 4.5 You are running version %s. Please upgrade and try again', COVTAGS_TEXTDOMAIN ), $wp_version );
              // Die With Message
              wp_die( $covtags_message );
          }
    }

  }

}
