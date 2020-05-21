<?php
if( ! class_exists( 'CovTags_Widget_Status_Card' ) ){

  class CovTags_Widget_Status_Card extends WP_Widget{

        /* Get All Saved Countries From api */
        public $countries;

        /**
    	 * Widget API: Coronavirus Live Card
    	 */
        public function __construct(){

          parent::__construct(
                'covtags-widget-Status',
                esc_html__( 'Coronavirus Tags - Status Card' )
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


            $status_text      = ( strlen( $instance['country'] ) > 1 )       ? $instance['country']        : $instance['status_text'];
            $hide_title       = !empty(  $instance['hide_title'] )           ? $instance['hide_title']     : 'yes';
            $graph_type       = !empty( $instance['use_graph_with'] )        ? $instance['use_graph_with'] : 'bar';
            $status_type      = !empty( $instance['status_type'] )           ? $instance['status_type']    : 'active';
            $country          = !empty( $instance['country'] )               ? $instance['country']        : null;
            $icon_flag        = !empty( $instance['icon_flag'] )             ? $instance['icon_flag']      : 'yes';
            $show_percentage  = !empty( $instance['show_percentage'] )       ? $instance['show_percentage']: 'yes';
            $rounded          = !empty( $instance['rounded'] )               ? $instance['rounded']        : 0;
            $is_dark          = ( 'on' === $instance['dark_mode'] )          ? 'yes'                       : 'no';
            $style            = 'style-1';  # Default for now
            $colors           = "#ff6348,#5352ed,#ffa502"; # Default for now

            $options = array(
              'card-type'           => 'status-card',
              'title'               => $status_text,
              'hide_title'          => $hide_title,
              'use_graph_with'      => $graph_type,
              'status_type'         => $status_type,
              'style'               => $style,
              'country'             => $country,
              'icon_flag'           => $icon_flag,
              'show_percentage'     => $show_percentage,
              'rounded'             => $rounded,
              'dark_mode'           => $is_dark,
              'colors'              => $colors
            );

            echo apply_filters ( 'coronavirus_tags_cards', $options );

       			echo ( $args['after_widget'] );
     		}

        /**
  		 * Outputs the settings form for the widget.
  		 */
  		 public function form( $instance ){

            $title                   = isset( $instance['title'] )          ? $instance['title']            : ''; #x
      			$status_text             = isset( $instance['status_text'] )    ? $instance['status_text']      : ''; #x
            $hide_title              = isset( $instance['hide_title'] )     ? $instance['hide_title']       : ''; #x
            $use_graph_with          = isset( $instance['use_graph_with'] ) ? $instance['use_graph_with']   : ''; #x
            $country                 = isset( $instance['country'] )        ? $instance['country']          : ''; #x
            $status_type             = isset( $instance['status_type'] )    ? $instance['status_type']      : ''; #x
            $show_percentage         = isset( $instance['show_percentage'] )? $instance['show_percentage']  : ''; #x
            $rounded                 = isset( $instance['rounded'] )        ? $instance['rounded']          : ''; #x
            $is_dark                 = isset( $instance['dark_mode'] )      ? $instance['dark_mode']        : ''; #
            $icon_flag               = isset( $instance['icon_flag'] )      ? $instance['icon_flag']        : ''; #


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
                  <label for="<?php echo esc_attr( $this->get_field_id ( 'status_text' ) ); ?>">
                    <?php echo esc_html__( 'Globe Text:', COVTAGS_TEXTDOMAIN ); ?>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'status_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'status_text' ) ); ?>" type="text" value="<?php echo esc_attr( $status_text ); ?>">
                  </label>
                </p>

                <div class="covtags-widget-radios">

                  <label class="title-fit">
                    <?php echo esc_html__( 'Hide Title:', COVTAGS_TEXTDOMAIN ); ?>
                  </label>

                  <ul>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'hide_title_yes' ) ); ?>">
                        <input <?php is_checked( $hide_title, 'yes' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'hide_title_yes' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'hide_title' ) ); ?>" type="radio" value="<?php echo esc_attr( 'yes' ); ?>">
                        <?php echo esc_html__( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'hide_title_no' ) ); ?>">
                        <input <?php is_checked( $hide_title, 'no' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'hide_title_no' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'hide_title' ) ); ?>" type="radio" value="<?php echo esc_attr( 'no' ); ?>">
                        <?php echo esc_html__( 'No', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                  </ul>

                </div>


                <div class="covtags-widget-radios">

                  <label class="title-fit">
                    <?php echo esc_html__( 'Use Graph ? Choose Type :', COVTAGS_TEXTDOMAIN ); ?>
                  </label>

                  <ul>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'use_graph_no' ) ); ?>">
                        <input <?php is_checked( $use_graph_with, 'no' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'use_graph_no' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'use_graph_with' ) ); ?>" type="radio" value="<?php echo esc_attr( 'no' ); ?>">
                        <?php echo esc_html__( 'None', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'use_graph_bar' ) ); ?>">
                        <input <?php is_checked( $use_graph_with, 'bar' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'use_graph_bar' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'use_graph_with' ) ); ?>" type="radio" value="<?php echo esc_attr( 'bar' ); ?>">
                        <?php echo esc_html__( 'Bar Graph', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'use_graph_line' ) ); ?>">
                        <input <?php is_checked( $use_graph_with, 'line' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'use_graph_line' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'use_graph_with' ) ); ?>" type="radio" value="<?php echo esc_attr( 'line' ); ?>">
                        <?php echo esc_html__( 'Line Graph', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'use_graph_pie' ) ); ?>">
                        <input <?php is_checked( $use_graph_with, 'pie' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'use_graph_pie' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'use_graph_with' ) ); ?>" type="radio" value="<?php echo esc_attr( 'pie' ); ?>">
                        <?php echo esc_html__( 'Pie Graph', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>

                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'use_graph_doughnut' ) ); ?>">
                        <input <?php is_checked( $use_graph_with, 'doughnut' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'use_graph_doughnut' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'use_graph_with' ) ); ?>" type="radio" value="<?php echo esc_attr( 'doughnut' ); ?>">
                        <?php echo esc_html__( 'Doughnut Graph', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>

                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'use_graph_polararea' ) ); ?>">
                        <input <?php is_checked( $use_graph_with, 'polarArea' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'use_graph_polararea' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'use_graph_with' ) ); ?>" type="radio" value="<?php echo esc_attr( 'polarArea' ); ?>">
                        <?php echo esc_html__( 'Polar Area Graph', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>

                  </ul>

                </div>

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
                    <?php echo esc_html__( 'Type Of Status Card:', COVTAGS_TEXTDOMAIN ); ?>
                  </label>

                  <ul>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'status_active' ) ); ?>">
                        <input <?php is_checked( $status_type, 'active' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'status_active' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'status_type' ) ); ?>" type="radio" value="<?php echo esc_attr( 'active' ); ?>">
                        <?php echo esc_html__( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'status_closed' ) ); ?>">
                        <input <?php is_checked( $status_type, 'closed' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'status_closed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'status_type' ) ); ?>" type="radio" value="<?php echo esc_attr( 'closed' ); ?>">
                        <?php echo esc_html__( 'Closed', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                  </ul>

                </div>

                <div class="covtags-widget-radios">

                  <label class="title-fit">
                    <?php echo esc_html__( 'Show Percentage:', COVTAGS_TEXTDOMAIN ); ?>
                  </label>

                  <ul>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'show_percentage_yes' ) ); ?>">
                        <input <?php is_checked( $show_percentage, 'yes' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'show_percentage_yes' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'show_percentage' ) ); ?>" type="radio" value="<?php echo esc_attr( 'yes' ); ?>">
                        <?php echo esc_html__( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
                      </label>
                    </li>
                    <li>
                      <label  for="<?php echo esc_attr( $this->get_field_id ( 'show_percentage_no' ) ); ?>">
                        <input <?php is_checked( $show_percentage, 'no' ); ?> class="widefat" id="<?php echo esc_attr( $this->get_field_id ( 'show_percentage_no' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'show_percentage' ) ); ?>" type="radio" value="<?php echo esc_attr( 'no' ); ?>">
                        <?php echo esc_html__( 'No', COVTAGS_TEXTDOMAIN ); ?>
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
          $instance                     = $old_instance;
    			$instance['title']            = sanitize_text_field(strip_tags( $new_instance['title'] ));
          $instance['dark_mode']        = !empty( $new_instance['dark_mode'] )? $new_instance['dark_mode']: 'no';
          $instance['status_text']      = sanitize_text_field(strip_tags( $new_instance['status_text'] ));
          $instance['hide_title']       = $new_instance['hide_title'];
          $instance['use_graph_with']   = !empty( $new_instance['use_graph_with'] )? $new_instance['use_graph_with']: 'no';
          $instance['country']          = $new_instance['country'];
          $instance['status_type']      = !empty( $new_instance['status_type'] )? $new_instance['status_type']: 'active';
          $instance['rounded']          = sanitize_text_field((strip_tags($new_instance['rounded'])));
          $instance['show_percentage']  = !empty( $new_instance['show_percentage'] )?$new_instance['show_percentage']:'yes';
          $instance['icon_flag']        = !empty( $new_instance['icon_flag'] )?$new_instance['icon_flag']:'yes';

          return $instance;
    		}
  }

  /**
   * Register the widget. | Disable this widget
   */
  // add_action( 'widgets_init', 'ertags_register_widget_status_card' );
  // function ertags_register_widget_status_card(){
  //   register_widget( 'CovTags_Widget_Status_Card' );
  // }

}
