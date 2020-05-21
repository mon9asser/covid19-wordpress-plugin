<?php

/**
 * Widget API : CovTags_Widget_Ticker_Card class
 *
 * @package Coronavirus Tags
 * @subpackage Ticker Widget
 * @since 1.0
 */

if( ! class_exists( 'CovTags_Widget_Ticker_Card' ) ){
  class CovTags_Widget_Ticker_Card extends WP_Widget {

    /*
    * Widget API: Coronavirus Live Card
    */
    public function __construct(){
      parent::__construct(
        'covtags-widget-ticker',
        esc_html__( 'Coronavirus Tags - World Ticker' )
      );
    }

    /*
    * Outputs the content for the widget instance.
    */
    public function widget( $args, $instance ){

      echo ( $args['before_widget'] );

      /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
      if ( ! empty($instance['title']) ){
        echo ( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
      }

      # Ticker Options
      $is_dark         =  isset( $instance['dark_mode'] )      ?  $instance['dark_mode']     : 'no';
      $ticker_speed    = !empty( $instance['ticker_speed'] )   ?  $instance['ticker_speed']  : 'normal' ;
      $ticker_text     = !empty( $instance['ticker_text'] )    ?  $instance['ticker_text']   : null ;
      $data_provider   = !empty( $instance['data_provider'] )  ?  $instance['data_provider'] : 'cases' ;

      # Build Options in array
      $options = array(
        'card-type'    => 'ticker-card',
        'dark_mode'    => ( 'on' === $is_dark )? 'yes': 'no',
        'ticker_speed' => $ticker_speed,
        'ticker_text'  => $ticker_text,
        'data_provider'=> $data_provider
      );

      # Filter Data Before Render
      echo apply_filters ( 'coronavirus_tags_cards', $options );

      echo ( $args['after_widget'] );

    }

    /*
     * Outputs the settings form for the widget.
     */
    public function form( $instance ){

      # Init Data with empty as a default value
      $title                   = isset( $instance['title'] )            ? $instance['title']           : '';
      $ticker_text             = isset( $instance['ticker_text'] )      ? $instance['ticker_text']     : '';
      $ticker_speed            = isset( $instance['ticker_speed'] )     ? $instance['ticker_speed']    : '';
      $ticker_data_provider    = isset( $instance['data_provider'] )    ? $instance['data_provider']   : '';
      $dark_mode               = isset( $instance['dark_mode'] )        ? $instance['dark_mode']       : '';

      ?>
       <div class="covtags-widget-container">
         <div class="widget-content">

          <!-- Widget Title -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id ( 'title' ) ); ?>">
              <?php echo esc_html__( 'Title:', COVTAGS_TEXTDOMAIN ); ?>
              <input class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </label>
          </p>

          <!-- Ticker Text -->
          <p>
            <label for="<?php echo esc_attr( $this->get_field_id ( 'ticker_text' ) ); ?>">
              <?php echo esc_html__( 'Ticker Text:', COVTAGS_TEXTDOMAIN ); ?>
              <input class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'ticker_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'ticker_text' ) ); ?>" type="text" value="<?php echo esc_attr( $ticker_text ); ?>">
            </label>
          </p>

          <!-- Ticker Speed -->
          <div class="covtags-widget-radios">
            <label class="title-fit">
              <?php echo esc_html__( 'Ticker Speed:', COVTAGS_TEXTDOMAIN ); ?>
            </label>
            <ul>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'ticker_speed_normal' ) ); ?>">
                  <input <?php is_checked( $ticker_speed, 'normal' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'ticker_speed_normal' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'ticker_speed' ) ); ?>" type="radio" value="<?php echo esc_attr( 'normal' ); ?>">
                  <?php echo esc_html__( 'Normal', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'ticker_speed_slow' ) ); ?>">
                  <input <?php is_checked( $ticker_speed, 'slow' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'ticker_speed_slow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'ticker_speed' ) ); ?>" type="radio" value="<?php echo esc_attr( 'slow' ); ?>">
                  <?php echo esc_html__( 'Slow', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'ticker_speed_medium' ) ); ?>">
                  <input <?php is_checked( $ticker_speed, 'medium' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'ticker_speed_medium' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'ticker_speed' ) ); ?>" type="radio" value="<?php echo esc_attr( 'medium' ); ?>">
                  <?php echo esc_html__( 'Medium', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'ticker_speed_fast' ) ); ?>">
                  <input <?php is_checked( $ticker_speed, 'fast' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'ticker_speed_fast' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'ticker_speed' ) ); ?>" type="radio" value="<?php echo esc_attr( 'fast' ); ?>">
                  <?php echo esc_html__( 'Fast', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
            </ul>
          </div>

          <!-- Data Provider  -->
          <div class="covtags-widget-radios">
            <label class="title-fit">
              <?php echo esc_html__( 'Tircker Data Provider:', COVTAGS_TEXTDOMAIN ); ?>
            </label>
            <ul>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_cases' ) ); ?>">
                  <input <?php is_checked( $ticker_data_provider, 'cases' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_cases' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider' ) ); ?>" type="radio" value="<?php echo esc_attr( 'cases' ); ?>">
                  <?php echo esc_html__( 'Cases', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_deaths' ) ); ?>">
                  <input <?php is_checked( $ticker_data_provider, 'deaths' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_deaths' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider' ) ); ?>" type="radio" value="<?php echo esc_attr( 'deaths' ); ?>">
                  <?php echo esc_html__( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_today_cases' ) ); ?>">
                  <input <?php is_checked( $ticker_data_provider, 'todayCases' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_today_cases' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider' ) ); ?>" type="radio" value="<?php echo esc_attr( 'todayCases' ); ?>">
                  <?php echo esc_html__( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_today_deaths' ) ); ?>">
                  <input <?php is_checked( $ticker_data_provider, 'todayDeaths' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_today_deaths' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider' ) ); ?>" type="radio" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                  <?php echo esc_html__( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
              <li>
                <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_active' ) ); ?>">
                  <input <?php is_checked( $ticker_data_provider, 'active' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_active' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider' ) ); ?>" type="radio" value="<?php echo esc_attr( 'active' ); ?>">
                  <?php echo esc_html__( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
              <li>
                <label for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_critical' ) ); ?>">
                  <input <?php is_checked( $ticker_data_provider, 'critical' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_critical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider' ) ); ?>" type="radio" value="<?php echo esc_attr( 'critical' ); ?>">
                  <?php echo esc_html__( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                </label>
              </li>
            </ul>
          </div>

          <!-- Dark Mode  -->
          <p>
            <input <?php echo ( 'on' === $dark_mode )? esc_attr( 'checked'): ''; ?> class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id ( 'dark_mode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'dark_mode' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id ( 'dark_mode' ) ); ?>">
              <?php echo esc_html__( 'Enable Dark Mode ?', COVTAGS_TEXTDOMAIN ); ?>
            </label>
          </p>

         </div>
       </div>
      <?php
    }

   /*
   * Handles updating settings for widget instance.
   */
   public function update( $new_instance, $old_instance ){
      $instance                    = $old_instance;
      $instance['title']           = sanitize_text_field( strip_tags( $new_instance['title'] ) );
      $instance['dark_mode']       = !empty( $new_instance['dark_mode'] )? $new_instance['dark_mode'] : '';
      $instance['ticker_speed']    = !empty( $new_instance['ticker_speed'] )? $new_instance['ticker_speed']: 'normal';
      $instance['ticker_text']     = sanitize_text_field(strip_tags( $new_instance['ticker_text'] ));
      $instance['data_provider']   = $new_instance['data_provider'];
      return $instance;
   }

  }

  /**
  * Register the widget.
  */
  add_action( 'widgets_init', 'ertags_register_widget_ticker' );
  function ertags_register_widget_ticker(){
    register_widget( 'CovTags_Widget_Ticker_Card' );
  }

}
