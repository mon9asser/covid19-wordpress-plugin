<?php


// Cards
if( !class_exists( 'CovTags_Shortcode_Cards' ) ){
  class CovTags_Shortcode_Cards extends CovTags_UI {

    public function __construct (){

      # Go To Parent Contstructor
      parent::__construct();

      #statistics-card
      add_shortcode( 'covtags-statistics', array( $this, 'covtags_live_statistics_card' ) );  #XX
      add_shortcode( 'covtags-all-countries', array( $this, 'covtags_table_and_graph' ) );
      add_shortcode( 'covtags-status', array( $this, 'covtags_list_shortcode_with_status' ) );
      add_shortcode( 'covtags-map', array( $this, 'covtages_map_countreis_data' ) );  #XX
      add_shortcode( 'covtags-tricker-world-card', array( $this, 'covtags_tricker_world_card_data' ) ); #XX
      add_shortcode( 'covtags-standard-card', array( $this, 'covtags_standard_card_countries' ) ); #XX

      # Init Text Domain
      add_action( 'init', array( $this, 'Load_TextDomain' ) );
    }



    // [covtags-statistics title="World Wide Statistics" cols="4" fields="cases,deaths,recovered,active" layout="flat" style="default"]
    public function covtags_live_statistics_card ( $atts ){




      # Extract Attributes To sanitize it
      $attributes   = array_change_key_case( ( array ) $atts, CASE_LOWER );

      # Default Attributes
      $default_atts = array(
        'title'         => esc_html__( 'Default', COVTAGS_TEXTDOMAIN), #
        'cols'          => 4, # For next version
        'fields'        => 'cases,deaths,recovered,critical', #
        'layout'        => 'flat' , #
        'style'         => 'default', #
        'country'       => null, #
        'inner-spacing' => '20',
        'rounded'       => '0', #
        'align_text'    => 'yes', #
        'icon_flag'     => 'no', #
        'dark_mode'     => 'no' #
      );

      # Fill Values with defaults
      $attributes   = shortcode_atts( $default_atts , $attributes );

      # Cards for ( World Statistics and Country ) with options
      return $this->Card_live_statistics( $attributes );

    }

    // Table of all countries
    public function covtags_table_and_graph( $atts ){

      # Extract Attributes To sanitize it
      $attributes   = array_change_key_case( ( array ) $atts, CASE_LOWER );

      # Default Attributes
      $default_atts = array(
        'fields'        => 'cases,deaths,recovered',
        'field_colors'  => 'green,red,blue,teal', # For Next Version
        'desc_by'       => 'cases',
        'rows_per_page' => 10,
        'style'         => 'style-1', # For Next Version
        'graph_type'    => 'line',
        'paging_type'   => 'serials', // Serials or loademore
        'icon_flag'     => 'yes',
        'dark_mode'     => 'no'
      );

      # Fill Values with defaults
      $attributes   = shortcode_atts( $default_atts , $attributes );


      # List all countries with custom data fields
      return $this->Table_List_all_countries( $attributes );
    }

    // Closed or Active Cases
    public function covtags_list_shortcode_with_status ( $atts ){

      # Extract Attributes To sanitize it
      $attributes   = array_change_key_case( ( array ) $atts, CASE_LOWER );

      # Default Attributes
      $default_atts = array(
        'title'           => esc_html__( 'Cases', COVTAGS_TEXTDOMAIN),
        'hide_title'      => 'no' ,
        'use_graph_with'  => 'bar' , # no - [ line - bar - doughnut - pie - polarArea ]
        'status_type'     => 'active', # closed - active,
        'style'           => 'style-1', #
        'country'         => null,
        'icon_flag'       => 'yes' ,
        'show_percentage' => 'yes',
        'rounded'         => 0,
        'dark_mode'       => 'no',
        'colors'          => null # For Next Version
      );

      # Fill Values with defaults
      $attributes   = shortcode_atts( $default_atts , $attributes );


      # List Status of cases ( Active or closed )
      return $this->Show_Cases_Status_with_graph( $attributes );
    }


    public function covtages_map_countreis_data ( $atts ){

      # Extract Attributes To sanitize it
      $attributes   = array_change_key_case( ( array ) $atts , CASE_LOWER );

      $default_atts = array(
        'title'               => esc_html__( 'World Wide Map', COVTAGS_TEXTDOMAIN),
        'storke_color'        => 'tan',
        'fill_color'          => '#dad3d1',
        'storke_hover_color'  => '#b99b73',
        'fill_hover_color'    =>  '#fff1dd',
        'dark_mode'           => 'no'
      );

      # Fill Values with defaults
      $attributes   = shortcode_atts( $default_atts , $attributes );

      // Render Map
      return $this->covtags_render_map ( $attributes );
    }

    // Fit Card
    public function covtags_tricker_world_card_data ( $atts ){
      # Extract Attributes To sanitize it
      $attributes   = array_change_key_case( ( array ) $atts , CASE_LOWER );

      $default_atts = array(
        'title'               =>   NULL,
        'tricker_speed'       => 30,
        'desc_by'             => 'cases',
        'field'              => 'cases',
        'inner-spacing'       => '0',
        'dark_mode'           => 'no'
      );

      # Fill Values with defaults
      $attributes   = shortcode_atts( $default_atts , $attributes );

      # Country Tricker
      return $this->Card_All_countries_tricker( $attributes );

    }

    // Remain this shortcode
    public function covtags_ticker_bar ( $atts ){

      # Extract Attributes To sanitize it
      $attributes   = array_change_key_case( ( array ) $atts, CASE_LOWER );

      # Default Attributes
      $default_atts = array(
        'title'    => esc_html__( 'Default', COVTAGS_TEXTDOMAIN),
        'fields'   => 'todayCases',
        'position' => 'top' , # top - bottom - onthisposition # For Next Version
        'data'     => 'all_countries', # world_report - all_countries # currently use world wide and will add more options in next version
        'country'  => null
      );

      # Fill Values with defaults
      $attributes   = shortcode_atts( $default_atts , $attributes );

      # Cards for ( World Statistics and Country ) with options
      return $this->Ticker_Covid_News( $attributes );
    }

    // Standard Card
    public function covtags_standard_card_countries ( $atts ){
      # Extract Attributes To sanitize it
      $attributes   = array_change_key_case( ( array ) $atts, CASE_LOWER );

      # Default Attributes
      $default_atts = array(
        'title'     => null,
        'live_word' => null,
        'dark_mode' => 'no'
      );

      # Fill Values with defaults
      $attributes   = shortcode_atts( $default_atts , $attributes );

      # Cards for ( World Statistics and Country ) with options
      return $this->Standard_Card_Countries( $attributes );
    }

    // Load Text Domain
    public function Load_TextDomain (){
      load_plugin_textdomain( 'covid', false, COVTAGS_DIRNAME . '/languages' );
    }

  }
}

# Shortcode Cards
new CovTags_Shortcode_Cards();
