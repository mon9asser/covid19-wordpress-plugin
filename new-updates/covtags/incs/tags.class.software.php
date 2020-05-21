<?php

if( ! class_exists( 'covtags_software_compatibility' ) ) {

  class covtags_software_compatibility {

    public function __construct() {

      // Check about the current version for php
      $this->php_compatibility();

      // Prevent old wordpress versions
      $this->wordpress_compatibility();

    }

    /* Check about the current version for php */
    public function php_compatibility() {

      if( version_compare( COVTAGS_PHPVER , '5.6' , '<' ) ) {

        // Display this message when php version is lower than target version
        $build_message = sprintf( esc_html__( 'This Plugin requires at least PHP version 5.6 You are running version %1$s. Please upgrade and try again', COVTAGS_TEXTDOMAIN ), COVTAGS_PHPVER );
        wp_die( $build_message );

      }

    }

    /* Prevent old wordpress versions */
    public function wordpress_compatibility() {

      global $wp_version;
      if( version_compare( $wp_version , '4.6' , '<' ) ) {

        // Display this message when wordpress version is lower than target version
        $build_message = sprintf( esc_html__( 'This Plugin requires at least wordpress version 4.6 You are running version %1$s. Please upgrade and try again', COVTAGS_TEXTDOMAIN ), $wp_version );
        wp_die( $build_message );

      }

    }

  }

}
