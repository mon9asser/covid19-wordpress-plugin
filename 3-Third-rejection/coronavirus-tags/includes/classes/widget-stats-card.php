<?php
if( ! class_exists( 'CovTags_Widget_Stats_Card' ) ){

  class CovTags_Widget_Stats_Card extends WP_Widget{

        /* Get All Saved Countries From api */
        public $countries;

        /**
    	 * Widget API: Coronavirus Live Card
    	 */
        public function __construct(){

          parent::__construct(
                'covtags-widget-stats',
                esc_html__( 'Coronavirus Tags - World Stats' )
          );

          # Get Plugin Option
          $covtags_plug_options = get_option( 'covtags_eratags_key_name' );
          $this->countries      = ( array ) $covtags_plug_options['list_of_countries'];
    		}


        /**
  		  * Outputs the content for the widget instance.
  		  */
        public function widget( $args, $instance ){

       			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */

       			echo ( $args['before_widget'] );

       			if ( ! empty($instance['title']) ){
       				echo ( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
       			}

            /* Options */ //!empty( $instance['stats_text'] )     ?  $instance['stats_text']      :
            $is_dark        = ( 'on' === $instance['dark_mode'] )   ? 'yes'                         : 'no';
            $stats_text     = ( strlen( $instance['country'] ) > 1 )?  $instance['country']         : $instance['stats_text'];
            $layout         = !empty( $instance['layout'] )         ?  $instance['layout']          : 'flat';
            $stats_style    = !empty( $instance['stats_style'] )    ?  $instance['stats_style']     : 'style-3';
            $data_provider  = !empty( $instance['data_provider'] )  ?  $instance['data_provider']   : ['cases'];
            $country        = !empty( $instance['country'] )        ?  $instance['country']         : 0;
            $rounded        = !empty( $instance['rounded'] )        ?  $instance['rounded']         : 0;
            $align_text     = !empty( $instance['align_text'] )     ?  $instance['align_text']      : 'center';
            $icon_flag      = !empty( $instance['icon_flag'] )      ?  $instance['icon_flag']       : 'yes';

            $options = array(
              'card-type'    => 'stats-card',
              'dark_mode'    => $is_dark,
              'title'        => $stats_text,
              'layout'       => $layout,
              'style'        => $stats_style,
              'fields'       => $data_provider,
              'country'      => ( $country === 0 )? NULL : $country ,
              'rounded'      => $rounded,
              'align_text'   => $align_text ,
              'icon_flag'    => $icon_flag
            );

            echo apply_filters ( 'coronavirus_tags_cards', $options );

       			echo ( $args['after_widget'] );
     		}

        /**
  		 * Outputs the settings form for the widget.
  		 */
  		 public function form( $instance ){

            $title                   = isset( $instance['title'] )         ? $instance['title']        : ''; #
      			$stats_text              = isset( $instance['stats_text'] )    ? $instance['stats_text']   : ''; #
            $stats_layout            = isset( $instance['layout'] )        ? $instance['layout']       : ''; #
            $stats_style             = isset( $instance['stats_style'] )   ? $instance['stats_style']  : ''; #
            $stats_data_provider     = isset( $instance['data_provider'] ) ? $instance['data_provider']: ''; #
            $country                 = isset( $instance['country'] )       ? $instance['country']      : ''; #
            $rounded                 = isset( $instance['rounded'] )       ? $instance['rounded']      : ''; #
            $align_text              = isset( $instance['align_text'] )    ? $instance['align_text']   : '';
            $icon_flag               = isset( $instance['icon_flag'] )     ? $instance['icon_flag']    : '';
            $is_dark                 = isset( $instance['dark_mode'] )     ? $instance['dark_mode']    : ''; #


            ?>
            <div class="covtags-widget-container">
              <div class="widget-content">
                <p>
                  <label for="<?php echo esc_attr( $this->get_field_id ( 'title' ) ); ?>">
                    <?php echo esc_html__( 'Title:', COVTAGS_TEXTDOMAIN ); ?>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                  </label>
                </p>

                <p>
                  <label for="<?php echo esc_attr( $this->get_field_id ( 'stats_text' ) ); ?>">
                    <?php echo esc_html__( 'Globe Text:', COVTAGS_TEXTDOMAIN ); ?>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'stats_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'stats_text' ) ); ?>" type="text" value="<?php echo esc_attr( $stats_text ); ?>">
                  </label>
                </p>

                <p class="contains-combo-box">
                  <label for="<?php echo esc_attr( $this->get_field_id ( 'stats_layout' ) ); ?>">
                    <?php echo esc_html__( 'Stats Layout:', COVTAGS_TEXTDOMAIN ); ?>
                    <select id="<?php echo esc_attr( $this->get_field_id ( 'stats_layout' ) ); ?>" class="covtags-select-combo-float-right" name="<?php echo esc_attr( $this->get_field_name ( 'layout' ) ); ?>">
                      <option <?php is_checked( $stats_layout, 'flat', 'selected' ); ?> value="<?php echo esc_attr( 'flat' ); ?>"><?php echo esc_html__( 'Flat', COVTAGS_TEXTDOMAIN );?></option>
                      <option <?php is_checked( $stats_layout, 'table', 'selected' ); ?> value="<?php echo esc_attr( 'table' ); ?>"><?php echo esc_html__( 'Table', COVTAGS_TEXTDOMAIN );?></option>
                    </select>
                  </label>
                </p>

                <p class="contains-combo-box">
                  <label for="<?php echo esc_attr( $this->get_field_id ( 'stats_style' ) ); ?>">
                    <?php echo esc_html__( 'Stats Styles:', COVTAGS_TEXTDOMAIN ); ?>
                    <select id="<?php echo esc_attr( $this->get_field_id ( 'stats_style' ) ); ?>" class="covtags-select-combo-float-right" name="<?php echo esc_attr( $this->get_field_name ( 'stats_style' ) ); ?>">
                      <option <?php is_checked( $stats_style, 'style-1', 'selected' ); ?> value="<?php echo esc_attr( 'style-1' ); ?>"><?php echo esc_html__( 'Style 1', COVTAGS_TEXTDOMAIN );?></option>
                      <option <?php is_checked( $stats_style, 'style-2', 'selected' ); ?> value="<?php echo esc_attr( 'style-2' ); ?>"><?php echo esc_html__( 'Style 2', COVTAGS_TEXTDOMAIN );?></option>
                      <option <?php is_checked( $stats_style, 'style-3', 'selected' ); ?> value="<?php echo esc_attr( 'style-3' ); ?>"><?php echo esc_html__( 'Style 3', COVTAGS_TEXTDOMAIN );?></option>
                    </select>
                  </label>
                </p>

                <p>
                  <label for="<?php echo esc_attr( $this->get_field_id ( 'country' ) ); ?>">
                    <?php echo esc_html__( 'Country Or Globe:', COVTAGS_TEXTDOMAIN ); ?>
                    <select id="<?php echo esc_attr( $this->get_field_id ( 'country' ) ); ?>" class="covtags-select-combo" name="<?php echo esc_attr( $this->get_field_name ( 'country' ) ); ?>">
                      <option <?php is_checked( '0', '0', 'selected' ); ?> value="<?php echo esc_attr( '0' ); ?>"><?php echo esc_html__( 'World Wide', COVTAGS_TEXTDOMAIN );?></option>
                      <?php foreach ( $this->countries as $country__key => $country_ ): ?>
                        <option <?php is_checked( $country ,  $country_['country'] , 'selected' ); ?> value="<?php echo esc_attr( $country_['country'] ); ?>"><?php echo $country_['country'];?> </option>
                      <?php endforeach; ?>
                    </select>
                  </label>
                </p>

                <div class="covtags-widget-radios">

                  <label class="title-fit">
                    <?php echo esc_html__( 'Stats Data Provider:', COVTAGS_TEXTDOMAIN ); ?>
                  </label>

                  <ul>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_cases' ) ); ?>">
                        <input <?php is_checked_in_array( $stats_data_provider, 'cases' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_cases' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider[]' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'cases' ); ?>">
                        <?php echo esc_html__( 'Cases', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_deaths' ) ); ?>">
                        <input <?php is_checked_in_array( $stats_data_provider, 'deaths' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_deaths' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider[]' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'deaths' ); ?>">
                        <?php echo esc_html__( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_today_cases' ) ); ?>">
                        <input <?php is_checked_in_array( $stats_data_provider, 'todayCases' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_today_cases' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider[]' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'todayCases' ); ?>">
                        <?php echo esc_html__( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_today_deaths' ) ); ?>">
                        <input <?php is_checked_in_array( $stats_data_provider, 'todayDeaths' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_today_deaths' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider[]' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                        <?php echo esc_html__( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>

                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_active' ) ); ?>">
                        <input <?php is_checked_in_array( $stats_data_provider, 'active' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_active' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider[]' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'active' ); ?>">
                        <?php echo esc_html__( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>

                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_recovered' ) ); ?>">
                        <input <?php is_checked_in_array( $stats_data_provider, 'recovered' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_recovered' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider[]' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'recovered' ); ?>">
                        <?php echo esc_html__( 'Recovered', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>

                    <li>
                      <label for="<?php echo esc_attr( $this->get_field_id ( 'data_provider_critical' ) ); ?>">
                        <input <?php is_checked_in_array( $stats_data_provider, 'critical' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'data_provider_critical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'data_provider[]' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'critical' ); ?>">
                        <?php echo esc_html__( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>

                  </ul>

                </div>

                <p>
                  <label for="<?php echo esc_attr( $this->get_field_id ( 'rounded' ) ); ?>">
                    <?php echo esc_html__( 'Box Radius ( Rounded ):', COVTAGS_TEXTDOMAIN ); ?>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'rounded' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'rounded' ) ); ?>" max="25" min="2" type="number" placeholder="0"
                    value="<?php echo esc_attr( $rounded ); ?>">
                  </label>
                </p>

                <div class="covtags-widget-radios">

                  <label class="title-fit">
                    <?php echo esc_html__( 'Text Position:', COVTAGS_TEXTDOMAIN ); ?>
                  </label>

                  <ul>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'align_text_left' ) ); ?>">
                        <input <?php is_checked( $align_text, 'left' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'align_text_left' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'align_text' ) ); ?>" type="radio" value="<?php echo esc_attr( 'left' ); ?>">
                        <?php echo esc_html__( 'Left', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'align_text_center' ) ); ?>">
                        <input <?php is_checked( $align_text, 'center' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'align_text_center' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'align_text' ) ); ?>" type="radio" value="<?php echo esc_attr( 'center' ); ?>">
                        <?php echo esc_html__( 'Center', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                  </ul>

                </div>



                <div class="covtags-widget-radios">

                  <label class="title-fit">
                    <?php echo esc_html__( 'Use Icon Or Flag:', COVTAGS_TEXTDOMAIN ); ?>
                  </label>

                  <ul>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'icon_flag_yes' ) ); ?>">
                        <input <?php is_checked( $icon_flag, 'yes' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'icon_flag_yes' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'icon_flag' ) ); ?>" type="radio" value="<?php echo esc_attr( 'yes' ); ?>">
                        <?php echo esc_html__( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'icon_flag_no' ) ); ?>">
                        <input <?php is_checked( $icon_flag, 'no' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'icon_flag_no' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'icon_flag' ) ); ?>" type="radio" value="<?php echo esc_attr( 'no' ); ?>">
                        <?php echo esc_html__( 'No', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                  </ul>

                </div>

                <p>
                  <input <?php echo ( 'on' === $is_dark )? esc_attr( 'checked'): ''; ?> class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id ( 'dark_mode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'dark_mode' ) ); ?>">
		              <label for="<?php echo esc_attr( $this->get_field_id ( 'dark_mode' ) ); ?>">
                    <?php echo esc_html__( 'Enable Dark Mode ?', COVTAGS_TEXTDOMAIN ); ?>
                  </label>
                </p>

			         </div>
            </div>
            <?php
       }
        /**
    	 * Handles updating settings for widget instance.
    	 */
    		public function update( $new_instance, $old_instance ){
          $instance                    = $old_instance;
    			$instance['title']           = sanitize_text_field(strip_tags( $new_instance['title'] ));
          $instance['dark_mode']       = !empty( $new_instance['dark_mode'] )? $new_instance['dark_mode']: 'no';
          $instance['stats_text']      = sanitize_text_field(strip_tags( $new_instance['stats_text'] ));
          $instance['layout']          = !empty( $new_instance['layout'] )? $new_instance['layout']: 'flat';
          $instance['stats_style']     = !empty( $new_instance['stats_style'] )? $new_instance['stats_style']: 'style-1';
          $instance['data_provider']   = $new_instance['data_provider'];
          $instance['country']         = $new_instance['country'];
          $instance['rounded']         = sanitize_text_field( strip_tags($new_instance['rounded']) );
          $instance['align_text']      = !empty( $new_instance['align_text'] )? $new_instance['align_text']: 'left';
          $instance['icon_flag']       = !empty( $new_instance['icon_flag'] )? $new_instance['icon_flag'] : 'yes';

          return $instance;
    		}
  }

  /**
   * Register the widget.
   */
  add_action( 'widgets_init', 'ertags_register_widget_stats_card' );
  function ertags_register_widget_stats_card(){
    register_widget( 'CovTags_Widget_Stats_Card' );
  }

}
