<?php
if( ! class_exists( 'CovTags_Widget_Map_Card' ) ){

  class CovTags_Widget_Map_Card extends WP_Widget{

        /**
    	 * Widget API: Coronavirus Live Card
    	 */
        public function __construct(){

          parent::__construct(
                'covtags-widget-map',
                esc_html__( 'Coronavirus Tags - World Map' )
          );
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


            /* Is Dark Mode Option */
            $is_dark = ( 'on' === $instance['dark_mode'] ) ? 'yes' : 'no' ;

            $options = array(
              'card-type'  => 'map-card',
              'dark_mode'  => $is_dark
            );

            echo apply_filters ( 'coronavirus_tags_cards', $options );

       			echo ( $args['after_widget'] );
     		}

        /**
  		 * Outputs the settings form for the widget.
  		 */
  		 public function form( $instance ){

      			$title          = isset( $instance['title'] )       ? $instance['title']      : '';
            $dark_mode        = isset( $instance['dark_mode'] ) ?  $instance['dark_mode'] : 'no';

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
                  <input <?php echo ( 'on' === $dark_mode )? esc_attr( 'checked'): ''; ?> class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id ( 'dark_mode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'dark_mode' ) ); ?>">
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
    			$instance['title']           = sanitize_text_field( strip_tags( $new_instance['title'] ) );
          $instance['dark_mode']       = !empty( $new_instance['dark_mode'] )? $new_instance['dark_mode']: 'no';

          return $instance;
    		}
  }

  /**
   * Register the widget.
   */
  add_action( 'widgets_init', 'ertags_register_widget_map' );
  function ertags_register_widget_map(){
    register_widget( 'CovTags_Widget_Map_Card' );
  }

}
