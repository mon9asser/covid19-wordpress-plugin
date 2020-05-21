<?php
if( ! class_exists( 'covtags_wd_standard_card' ) ) {

  class covtags_wd_standard_card extends WP_Widget {

    /**
	 * Sets up the widgets name etc
	 */
    public function __construct() {

      $widget_ops = array(
        'description' => esc_html__( 'Live Changes For Covid 19' ),
      );
      parent::__construct( 'covtags-standard-card', esc_html__( 'Covtags - Standard Card' ), $widget_ops );

    }

    /**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
   public function widget( $args, $instance ) {

     echo ( $args['before_widget'] );

     /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
     if ( ! empty($instance['title']) ){
 		    echo ( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
 		 }

     // Options
     $covtags_options = array(
       'card-text'         => ( ! empty( $instance[ 'card-text' ] ) )         ? $instance[ 'card-text' ]         : null,
       'live-text'         => ( ! empty( $instance[ 'live-text' ] ) )         ? $instance[ 'live-text' ]         : null,
       'dark-mode'         => ( ! empty( $instance[ 'dark-mode' ] ) )         ? $instance[ 'dark-mode' ]         : 'no',
       'enable-rtl'        => ( ! empty( $instance[ 'enable-rtl' ] ) )        ? $instance[ 'enable-rtl' ]        : 'no',
       'cases-text'        => ( ! empty( $instance[ 'cases-text' ] ) )        ? $instance[ 'cases-text' ]        : null,
       'deaths-text'       => ( ! empty( $instance[ 'deaths-text' ] ) )       ? $instance[ 'deaths-text' ]       : null,
       'today-cases-text'  => ( ! empty( $instance[ 'today-cases-text' ] ) )  ? $instance[ 'today-cases-text' ]  : null,
       'today-deaths-text' => ( ! empty( $instance[ 'today-deaths-text' ] ) ) ? $instance[ 'today-deaths-text' ] : null,
       'recovered-text'    => ( ! empty( $instance[ 'recovered-text' ] ) )    ? $instance[ 'recovered-text' ]    : null,
       'critical-text'     => ( ! empty( $instance[ 'critical-text' ] ) )     ? $instance[ 'critical-text' ]     : null,
       'world-text'        => ( ! empty( $instance[ 'world-text' ] ) )        ? $instance[ 'world-text' ]        : null,
     );

     // Render UI
     $covtags_ui = new covtags_ui();
     echo $covtags_ui->covtags_standard_card_ui( $covtags_options );

     echo ( $args['after_widget'] );

   }

   /**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
   public function form( $instance ){

     // Prepare Data of widget options
     $title             = isset( $instance['title'] )               ? $instance['title']               : '';
     $card_text         = isset( $instance[ 'card-text' ] )         ? $instance[ 'card-text' ]         : '';
     $live_text         = isset( $instance[ 'live-text' ] )         ? $instance[ 'live-text' ]         : '';
     $dark_mode         = isset( $instance[ 'dark-mode' ] )         ? $instance[ 'dark-mode' ]         : '';
     $enable_rtl        = isset( $instance[ 'enable-rtl' ] )        ? $instance[ 'enable-rtl' ]        : '';

     $cases_text        = isset( $instance[ 'cases-text' ] )        ? $instance[ 'cases-text' ]        : '';
     $deaths_text       = isset( $instance[ 'deaths-text' ] )       ? $instance[ 'deaths-text' ]       : '';
     $today_cases_text  = isset( $instance[ 'today-cases-text' ] )  ? $instance[ 'today-cases-text' ]  : '';
     $today_deaths_text = isset( $instance[ 'today-deaths-text' ] ) ? $instance[ 'today-deaths-text' ] : '';
     $recovered_text    = isset( $instance[ 'recovered-text' ] )    ? $instance[ 'recovered-text' ]    : '';
     $critical_text     = isset( $instance[ 'critical-text' ] )     ? $instance[ 'critical-text' ]     : '';
     $world_text        = isset( $instance[ 'world-text' ] )        ? $instance[ 'world-text' ]        : '';

     ?>
     <!-- Widget Form Container -->
     <div class="covtags-widget-container">

       <!-- Widget Title -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'title' ) ); ?>">
             <?php echo esc_html__( 'Title:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'title' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $title ); ?>">
           </label>
         </p>
       </div>

       <!-- Card Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'card-text' ) ); ?>">
             <?php echo esc_html__( 'Card Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'card-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'card-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $card_text ); ?>">
           </label>
         </p>
       </div>

       <!-- Live Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'live-text' ) ); ?>">
             <?php echo esc_html__( 'Live Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'live-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'live-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $live_text ); ?>">
           </label>
         </p>
       </div>

       <!-- Dark Mode -->
       <div class="widget-content covtags-field-content covtags-group-checkradio">
         <p>
           <span class="covtags-widfform-title"><?php echo esc_html__( 'Enable Dark Mode:', COVTAGS_TEXTDOMAIN ); ?></span>

           <label for="<?php echo esc_attr( $this->get_field_id ( 'dark-no' ) ); ?>">
             <input <?php covtags_is_checked( $dark_mode, 'no');?> id="<?php echo esc_attr( $this->get_field_id ( 'dark-no' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'dark-mode' ) ); ?>"  class="widefat" type="radio" value="<?php echo esc_attr( 'no' ); ?>">
             <?php echo esc_html__( 'No', COVTAGS_TEXTDOMAIN ); ?>
           </label>

           <label for="<?php echo esc_attr( $this->get_field_id ( 'dark-yes' ) ); ?>">
             <input <?php covtags_is_checked( $dark_mode, 'yes');?> id="<?php echo esc_attr( $this->get_field_id ( 'dark-yes' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'dark-mode' ) ); ?>"  class="widefat" type="radio" value="<?php echo esc_attr( 'yes' ); ?>">
             <?php echo esc_html__( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
           </label>

         </p>
       </div>

       <!-- RTL Direction -->
       <div class="widget-content covtags-field-content covtags-group-checkradio">
         <p>
           <span class="covtags-widfform-title"><?php echo esc_html__( 'Enable RTL Direction:', COVTAGS_TEXTDOMAIN ); ?></span>

           <label for="<?php echo esc_attr( $this->get_field_id ( 'rtl-dir-no' ) ); ?>">
             <input  <?php covtags_is_checked( $enable_rtl, 'no');?> id="<?php echo esc_attr( $this->get_field_id ( 'rtl-dir-no' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'enable-rtl' ) ); ?>"  class="widefat" type="radio" value="<?php echo esc_attr( 'no' ); ?>">
             <?php echo esc_html__( 'No', COVTAGS_TEXTDOMAIN ); ?>
           </label>

           <label for="<?php echo esc_attr( $this->get_field_id ( 'rtl-dir-yes' ) ); ?>">
             <input <?php covtags_is_checked( $enable_rtl, 'yes');?> id="<?php echo esc_attr( $this->get_field_id ( 'rtl-dir-yes' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'enable-rtl' ) ); ?>"  class="widefat" type="radio" value="<?php echo esc_attr( 'yes' ); ?>">
             <?php echo esc_html__( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
           </label>

         </p>
       </div>

       <!-- Cases Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'cases-text' ) ); ?>">
             <?php echo esc_html__( 'Cases Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'cases-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'cases-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $cases_text ); ?>">
           </label>
         </p>
       </div>

       <!-- Deaths Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'deaths-text' ) ); ?>">
             <?php echo esc_html__( 'Deaths Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'deaths-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'deaths-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $deaths_text ); ?>">
           </label>
         </p>
       </div>

       <!-- Today Cases Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'today-cases-text' ) ); ?>">
             <?php echo esc_html__( 'Today Cases Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'today-cases-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'today-cases-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $today_cases_text ); ?>">
           </label>
         </p>
       </div>

       <!-- Today Deaths Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'today-deaths-text' ) ); ?>">
             <?php echo esc_html__( 'Today Deaths Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'today-deaths-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'today-deaths-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $today_deaths_text ); ?>">
           </label>
         </p>
       </div>

       <!-- Recovered Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'recovered-text' ) ); ?>">
             <?php echo esc_html__( 'Recovered Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'recovered-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'recovered-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $recovered_text ); ?>">
           </label>
         </p>
       </div>

       <!-- Critical Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'critical-text' ) ); ?>">
             <?php echo esc_html__( 'Critical Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'critical-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'critical-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $critical_text ); ?>">
           </label>
         </p>
       </div>

       <!-- World Wide Text -->
       <div class="widget-content covtags-field-content">
         <p>
           <label for="<?php echo esc_attr( $this->get_field_id ( 'world-text' ) ); ?>">
             <?php echo esc_html__( 'World Wide Text:', COVTAGS_TEXTDOMAIN ); ?>
             <input id="<?php echo esc_attr( $this->get_field_id ( 'world-text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name ( 'world-text' ) ); ?>"  class="widefat" type="text" value="<?php echo esc_attr( $world_text ); ?>">
           </label>
         </p>
       </div>

     </div>
     <?php

   }

   /**
   * Processing widget options on save
   *
   * @param array $new_instance The new options
   * @param array $old_instance The previous options
   *
   * @return array
   */
   public function update( $new_instance, $old_instance ) {

     $instance                     = $old_instance;
     $instance[ 'title' ]          = ( ! empty( $new_instance[ 'title' ] ) )      ? sanitize_text_field( strip_tags( $new_instance[ 'title' ] ) )              : '';
     $instance[ 'card-text' ]      = ( ! empty( $new_instance[ 'card-text' ] ) )  ? sanitize_text_field( strip_tags( $new_instance[ 'card-text' ] ) )          : '';
     $instance[ 'live-text' ]      = ( ! empty( $new_instance[ 'live-text' ] ) )  ? sanitize_text_field( strip_tags( $new_instance[ 'live-text' ] ) )          : '';
     $instance[ 'cases-text' ]     = ( ! empty( $new_instance[ 'cases-text' ] ) ) ? sanitize_text_field( strip_tags( $new_instance[ 'cases-text' ] ) )         : '';
     $instance[ 'world-text' ]     = ( ! empty( $new_instance[ 'world-text' ] ) ) ? sanitize_text_field( strip_tags( $new_instance[ 'world-text' ] ) )         : '';

     $instance[ 'deaths-text' ]       = ( ! empty( $new_instance[ 'deaths-text' ] ) )       ? sanitize_text_field( strip_tags( $new_instance[ 'deaths-text' ] ) )       : '';
     $instance[ 'recovered-text' ]    = ( ! empty( $new_instance[ 'recovered-text' ] ) )    ? sanitize_text_field( strip_tags( $new_instance[ 'recovered-text' ] ) )    : '';
     $instance[ 'critical-text' ]     = ( ! empty( $new_instance[ 'critical-text' ] ) )     ? sanitize_text_field( strip_tags( $new_instance[ 'critical-text' ] ) )     : '';
     $instance[ 'today-cases-text' ]  = ( ! empty( $new_instance[ 'today-cases-text' ] ) )  ? sanitize_text_field( strip_tags( $new_instance[ 'today-cases-text' ] ) )  : '';
     $instance[ 'today-deaths-text' ] = ( ! empty( $new_instance[ 'today-deaths-text' ] ) ) ? sanitize_text_field( strip_tags( $new_instance[ 'today-deaths-text' ] ) ) : '';

     $instance[ 'dark-mode' ]  = ( ! empty( $new_instance[ 'dark-mode' ] ) )  ? $new_instance[ 'dark-mode' ]  : 'no';
     $instance[ 'enable-rtl' ] = ( ! empty( $new_instance[ 'enable-rtl' ] ) ) ? $new_instance[ 'enable-rtl' ] : 'no';

     return $instance;
   }

  }

  /* Register the widget */
  if( ! function_exists( 'covtags_wd_standard_card_regsiter' ) ) {

    add_action( 'widgets_init', 'covtags_wd_standard_card_regsiter' );
    function covtags_wd_standard_card_regsiter() {
      register_widget( 'covtags_wd_standard_card' );
    }

  }

}
