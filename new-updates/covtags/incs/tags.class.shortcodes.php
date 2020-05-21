<?php

if( ! class_exists( 'covtags_shortcodes' ) ) {

  class covtags_shortcodes extends covtags_ui {

    public function __construct() {

      add_shortcode( 'covtags-standard', array( $this, 'covtags_standard_card_shortcode' ) );
      add_shortcode( 'covtags-ticker', array( $this, 'covtags_ticker_card_shortcode' ) );

    }

    /* Standard Card ( Live Changes ) */
    public function covtags_standard_card_shortcode( $attributes ) {

      $attributes   = shortcode_atts( array(
        'card-text'         => null,
        'live-text'         => null,
        'dark-mode'         => 'no',
        'enable-rtl'        => 'no',
        'cases-text'        => null,
        'deaths-text'       => null,
        'today-cases-text'  => null,
        'today-deaths-text' => null,
        'recovered-text'    => null,
        'critical-text'     => null,
        'world-text'        => null,
      ), $attributes, 'covtags-standard' );

      // Render UI
      ob_start();
      echo $this->covtags_standard_card_ui( $attributes );
      return ob_get_clean();

    }

    /* Ticker Card ( Live Changes ) */
    public function covtags_ticker_card_shortcode( $attributes ) {

      $attributes = shortcode_atts( array(
        'ticker-data'       => 'all',
        'ticker-position'   => 'normal',
        'dark-mode'         => 'no',
        'icon-flag'         => 'yes',
        'enable-rtl'        => 'no',
        'collected-fields'  => '',
        'tooltip-fields'    => '',
        'cases-text'        => null,
        'deaths-text'       => null,
        'today-cases-text'  => null,
        'today-deaths-text' => null,
        'recovered-text'    => null,
        'critical-text'     => null,
        'active-text'       => null,
        'ticker-speed'      => 'normal',
        'country'           => null,
        'country-text'      => null,
        'field'             => 'cases',
        'card-text'         => null
      ), $attributes, 'covtags-standard' );

      // Render UI
      ob_start();
      echo $this->covtags_ticker_card_ui( $attributes );
      return ob_get_clean();
      
    }

  }

  // Init Shortcodes
  new covtags_shortcodes();

}
