<?php

// Check if current page not admin
if( !is_admin() ){
  return;
}

// Require pluggable File to handle user roles
require_once ABSPATH     . 'wp-includes/pluggable.php';

if( !class_exists( 'covtags_administration_panel' ) ) {

  class covtags_administration_panel {

    public $covtags_options;
    public $covtags_options_name = 'covtags_eratags_key_name';
    public function __construct (){

      if( !current_user_can( 'manage_options' ) ){
        return;
      }

      // Load Attriubtes
      $this->covtags_options = get_option( $this->covtags_options_name );

      // Load Methods
      add_action( 'admin_enqueue_scripts', array( $this, 'include_external_files' ) );
      add_action( 'admin_menu', array( $this, 'create_menu' ) );

      // Ajax Actions
      add_action("wp_ajax_CoronaVirusTags_action", array( $this, "callback_ajax_data_covid" ) );
      add_action("wp_ajax_nopriv_CoronaVirusTags_action", array( $this, "callback_ajax_data_covid" ) );
    }
    // Css And Js Files
    public function include_external_files (){
      wp_enqueue_script( 'covtags-admin-scripts', COVTAGS_SRC . 'assets/js/src/admin.script.js', array( 'jquery' ), COVTAGS_VER, true );
      wp_enqueue_style( 'covtags-admin-styles', COVTAGS_SRC . 'assets/css/src/admin.styles.css', false, COVTAGS_VER, 'all' );
      wp_localize_script( 'covtags-admin-scripts', 'eratags_obj', array(
        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'covid-settings' ),
        "saving"        => __( "Saving ...", COVTAGS_TEXTDOMAIN ),
        "save_changes"  => __( "Save Changes", COVTAGS_TEXTDOMAIN )
      ));
    }
    // Main Admin Page
    public function covtags_admin_page_callback (){

      // Banner Of Plugin
      $this->CovTags_Banner();

    }
    // Wordpress Ajax
    public function callback_ajax_data_covid (){

      if ( !wp_verify_nonce( $_REQUEST['secure'], "covid-settings")) {
         return;
      }

      $description = $_REQUEST["description"];
      $title = $_REQUEST["title"];
      // $cache_period = $_REQUEST["cache_period"];

      if( !empty( $description ) ){
        $this->covtags_options['options']['description'] = $description;
      }

      if( !empty( $title ) ){
        $this->covtags_options['options']['title'] = $title;
      }

      // if( !empty( $cache_period ) ){
      //   $this->covtags_options['options']['cache_period'] = round( $cache_period * 60 );
      // }

      $re = update_option( $this->covtags_options_name ,  $this->covtags_options );
      return $re ;
      exit;
    }
    // Banner Of Covid 19 Plugin
    public function CovTags_Banner (){
        ?>
        <div class="wrap">
          <div class="covtags-container-adm">

            <!-- Tab Lists -->
            <div class="covtags-tab-contents">
              <div class="eratags-logo">
                <img class="responsive-logo" src="<?php echo esc_url( COVTAGS_SRC . 'assets/img/eratags-logo.jpg' ); ?>" alt="<?php echo esc_attr( 'Eratags Logo' , COVTAGS_TEXTDOMAIN ); ?>">
              </div>
              <ul>
                <li>
                  <a class="open-anchor-element" href="#covtags-settings">
                    <span class="dashicons dashicons-admin-settings"></span>
                    <span>
                      <?php esc_html_e( 'Basic Settings', COVTAGS_TEXTDOMAIN ); ?>
                    </span>
                  </a>
                </li>
                <li>
                  <a class="open-anchor-element" href="#covtags-shortcodes">
                    <span class="dashicons dashicons-schedule"></span>
                    <span>
                      <?php esc_html_e( 'Shortcode Builder', COVTAGS_TEXTDOMAIN ); ?>
                    </span>
                  </a>
                </li>
              </ul>
            </div>

            <div class="covtags-blocks">
              <!-- Contents -->
              <div class="cotags-basic-contents">


                <!-- Settings -->
                <div class="covtags-settings">


                    <div id="covtags-settings" class="block-contents">
                      <h1><?php esc_html_e( 'Settings', COVTAGS_TEXTDOMAIN ); ?></h1>
                      <div class="covtags-contents-body covtags-contents-body-override">

                        <div class="eratags-fields">
                          <h4><?php esc_html_e( 'Corona Virus Title', COVTAGS_TEXTDOMAIN ); ?></h4>
                          <input id="covtags_corona_title" type="text" name="<?php echo esc_attr( 'covtitle' ); ?>" value="<?php echo esc_attr( $this->covtags_options['options']['title']) ; ?>">
                        </div>

                        <div class="eratags-fields">
                          <h4><?php esc_html_e( 'Corona Virus Description', COVTAGS_TEXTDOMAIN ); ?></h4>
                          <textarea id="covtags_corona_desc" name="<?php echo esc_attr( 'name' ); ?>" rows="8" cols="80" name="<?php echo esc_attr( 'covdesc' ); ?>"><?php echo $this->covtags_options['options']['description']; ?></textarea>
                        </div>

                        <div class="eratags-fields">
                          <h4><?php esc_html_e( 'Cache Period', COVTAGS_TEXTDOMAIN ); ?></h4>
                          <div class="highlightes-mins">
                            <?php
                              $mins = ( int ) $this->covtags_options['options']['cache_period']  / 60 ;
                              echo sprintf( esc_html__( 'The updates every %1$s minutes .', COVTAGS_TEXTDOMAIN ) , $mins);
                            ?>
                          </div>
                        </div>


                      </div>

                      <div class="eratags-fields setbottom">
                        <a id="save-coronavirus-tags" href="#" class="save-changes-button">
                          <?php esc_html_e( 'Save Changes', COVTAGS_TEXTDOMAIN ); ?>
                        </a>
                      </div>
                    </div>

                    <div id="covtags-shortcodes" class="block-contents">
                      <h1><?php esc_html_e( 'Shortcode Builder', COVTAGS_TEXTDOMAIN ); ?></h1>
                      <div class="covtags-contents-body">
                        <div class="tags-col-1">
                          <div class="eratags-fields covstags-field-contents">
                            <h4 class="mm-data">
                              <span><?php esc_html_e( 'Data Provider and Fields', COVTAGS_TEXTDOMAIN ); ?></span>
                            </h4>
                            <form class="" id="shorcodeform" action="#" method="post">

                                <!-- Shortcode Type + -->
                                <div class="eratags-fields default-section covtags-shortcode-field">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Shortcode Data Provider', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <select id="<?php echo esc_attr( 'covtags-data-provider' );?>" class="" name="">
                                      <option value="<?php echo esc_attr( 'covtags-all-countries' ); ?>"><?php esc_html_e( 'Datatable', COVTAGS_TEXTDOMAIN ); ?></option>
                                      <option value="<?php echo esc_attr( 'covtags-standard-card' ); ?>"><?php esc_html_e( 'Standard Card', COVTAGS_TEXTDOMAIN ); ?></option>
                                      <option value="<?php echo esc_attr( 'covtags-statistics' ); ?>"><?php esc_html_e( 'Stats Card', COVTAGS_TEXTDOMAIN ); ?></option>
                                      <option value="<?php echo esc_attr( 'covtags-tricker-world-card' ); ?>"><?php esc_html_e( 'Tricker', COVTAGS_TEXTDOMAIN ); ?></option>
                                      <option value="<?php echo esc_attr( 'covtags-status' ); ?>"><?php esc_html_e( 'Status Card', COVTAGS_TEXTDOMAIN ); ?></option>
                                      <option value="<?php echo esc_attr( 'covtags-map' ); ?>"><?php esc_html_e( 'Map Card', COVTAGS_TEXTDOMAIN ); ?></option>
                                    </select>
                                  </div>
                                </div>

                                <!-- Rows Per Page in table + -->
                                <div data-name="<?php echo esc_attr( 'rows_per_page' ); ?>" class="eratags-fields tble-fields covtags-shortcode-field open-section">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Rows Per page', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                     <input class="covtag-shortcode-input" type="number" min="3" max="50" name="<?php echo esc_attr( 'rows_per_page' ); ?>" value="">
                                  </div>
                                </div>

                                <!-- Is Dark mode -->
                                <div data-name="<?php echo esc_attr( 'dark_mode' ); ?>" class="eratags-fields tble-fields covtags-shortcode-field open-section">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Enable Dark Mode', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'radio-dark_mode' ); ?>">
                                          <input id="<?php echo esc_attr( 'radio-dark_mode' ); ?>" type="radio" name="<?php echo esc_attr( 'dark_mode' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                          <?php esc_html_e( 'Yes' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'radio-dark_mode-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'radio-dark_mode-x' ); ?>" type="radio" name="<?php echo esc_attr( 'dark_mode' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                          <?php esc_html_e( 'No' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>


                                <!-- Inner Spacing + -->
                                <div data-name="<?php echo esc_attr( 'inner-spacing' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Inner Spacing', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                     <input class="covtag-shortcode-input" type="text" min="3" max="50" name="<?php echo esc_attr( 'inner-spacing' ); ?>" value="" placeholder="10 10 20 5">
                                  </div>
                                </div>

                                <!-- Title + -->
                                <div data-name="<?php echo esc_attr( 'title' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Title', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <input class="covtag-shortcode-input" type="text" name="<?php echo esc_attr( 'title' ); ?>" value="">
                                  </div>
                                </div>

                                <!-- Layout -->
                                <div data-name="<?php echo esc_attr( 'layout' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Layout', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'radio-layout' ); ?>">
                                          <input id="<?php echo esc_attr( 'radio-layout' ); ?>" type="radio" name="<?php echo esc_attr( 'layout' ); ?>" value="<?php echo esc_attr( 'flat' ); ?>">
                                          <?php esc_html_e( 'Flat' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'radio-layout-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'radio-layout-x' ); ?>" type="radio" name="<?php echo esc_attr( 'layout' ); ?>" value="<?php echo esc_attr( 'table' ); ?>">
                                          <?php esc_html_e( 'Table' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>




                                <!-- Graph Type -->
                                <div data-name="<?php echo esc_attr( 'graph_type' ); ?>" class="eratags-fields tble-fields open-section">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Table Graph Style', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-line' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-line' ); ?>" type="radio" name="<?php echo esc_attr( 'graph_type' ); ?>" value="<?php echo esc_attr( 'line' ); ?>">
                                          <?php esc_html_e( 'Line Graph' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-bar' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-bar' ); ?>" type="radio" name="<?php echo esc_attr( 'graph_type' ); ?>" value="<?php echo esc_attr( 'bar' ); ?>">
                                          <?php esc_html_e( 'Bar Graph' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <!-- status_type -->
                                <div data-name="<?php echo esc_attr( 'status_type' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Status Card Type', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'cases-type-covtags-active' ); ?>">
                                          <input id="<?php echo esc_attr( 'cases-type-covtags-active' ); ?>" type="radio" name="<?php echo esc_attr( 'status_type' ); ?>" value="<?php echo esc_attr( 'active' ); ?>">
                                          <?php esc_html_e( 'Active' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'cases-type-covtags-closed' ); ?>">
                                          <input id="<?php echo esc_attr( 'cases-type-covtags-closed' ); ?>" type="radio" name="<?php echo esc_attr( 'status_type' ); ?>" value="<?php echo esc_attr( 'closed' ); ?>">
                                          <?php esc_html_e( 'Closed' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <!-- hide_title -->
                                <div data-name="<?php echo esc_attr( 'hide_title' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Hide Title', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-hide-yes' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-hide-yes' ); ?>" type="radio" name="<?php echo esc_attr( 'hide_title' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                          <?php esc_html_e( 'Yes' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-hide-no' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-hide-no' ); ?>" type="radio" name="<?php echo esc_attr( 'hide_title' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                          <?php esc_html_e( 'No' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <!-- show_percentage -->
                                <div data-name="<?php echo esc_attr( 'show_percentage' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Show Percentage Value', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-perc-hide-yes' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-perc-hide-yes' ); ?>" type="radio" name="<?php echo esc_attr( 'show_percentage' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                          <?php esc_html_e( 'Yes' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-perc-hide-no' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-perc-hide-no' ); ?>" type="radio" name="<?php echo esc_attr( 'show_percentage' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                          <?php esc_html_e( 'No' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <!-- use_graph_with -->
                                <div data-name="<?php echo esc_attr( 'use_graph_with' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Show Graph Style Or Disable it ?', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-disable-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-disable-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                          <?php esc_html_e( 'Disable' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-line-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-line-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'line' ); ?>">
                                          <?php esc_html_e( 'Line Graph' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-bar-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-bar-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'bar' ); ?>">
                                          <?php esc_html_e( 'Bar Graph' ); ?>
                                        </label>
                                      </li>

                                      <!-- <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-radar-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-radar-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'radar' ); ?>">
                                          <?php esc_html_e( 'Radar Graph' ); ?>
                                        </label>
                                      </li> -->

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-pie-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-pie-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'pie' ); ?>">
                                          <?php esc_html_e( 'Pie Graph' ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-doughnut-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-doughnut-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'doughnut' ); ?>">
                                          <?php esc_html_e( 'Doughnut Graph' ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'tbl-graph-style-polarArea-x' ); ?>">
                                          <input id="<?php echo esc_attr( 'tbl-graph-style-polarArea-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'polarArea' ); ?>">
                                          <?php esc_html_e( 'Polar Area Graph' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <!-- align_text -->
                                <div data-name="<?php echo esc_attr( 'align_text' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Align Texts', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'text-align-l-covtags' ); ?>">
                                          <input id="<?php echo esc_attr( 'text-align-l-covtags' ); ?>" type="radio" name="<?php echo esc_attr( 'align_text' ); ?>" value="<?php echo esc_attr( 'left' ); ?>">
                                          <?php esc_html_e( 'Left' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'text-align-c-covtags' ); ?>">
                                          <input id="<?php echo esc_attr( 'text-align-c-covtags' ); ?>" type="radio" name="<?php echo esc_attr( 'align_text' ); ?>" value="<?php echo esc_attr( 'center' ); ?>">
                                          <?php esc_html_e( 'Center' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <!-- fields -->
                                <div data-name="<?php echo esc_attr( 'fields' ); ?>" class="eratags-fields tble-fields open-section">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Display Fields', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul data-name="fields">
                                      <li>
                                        <label class="covtags-checkbox-handler"  for="<?php echo esc_attr( 'field-tags-today-cases' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-today-cases' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                          <?php esc_html_e( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-checkbox-handler"  for="<?php echo esc_attr( 'field-tags-cases' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-cases' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'cases' ); ?>">
                                          <?php esc_html_e( 'Confirmed', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li >
                                        <label class="covtags-checkbox-handler" for="<?php echo esc_attr( 'field-tags-today-deaths' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-today-deaths' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                                          <?php esc_html_e( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-checkbox-handler" for="<?php echo esc_attr( 'field-tags-deaths' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-deaths' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'deaths' ); ?>">
                                          <?php esc_html_e( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-checkbox-handler" for="<?php echo esc_attr( 'field-tags-recovered' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-recovered' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'recovered' ); ?>">
                                          <?php esc_html_e( 'Recovered', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-checkbox-handler" for="<?php echo esc_attr( 'field-tags-active' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-active' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'active' ); ?>">
                                          <?php esc_html_e( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-checkbox-handler" for="<?php echo esc_attr( 'field-tags-critical' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-critical' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'critical' ); ?>">
                                          <?php esc_html_e( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <!-- <li>
                                        <label class="covtags-checkbox-handler" for="<?php echo esc_attr( 'field-tags-casesperonemillion' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-casesperonemillion' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'casesPerOneMillion' ); ?>">
                                          <?php esc_html_e( 'Cases Per One Million', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li> -->

                                      <!-- <li>
                                        <label class="covtags-checkbox-handler" for="<?php echo esc_attr( 'field-tags-deathsperonemillion' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-deathsperonemillion' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'deathsPerOneMillion' ); ?>">
                                          <?php esc_html_e( 'Deaths Per One Million', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li> -->

                                    </ul>
                                  </div>
                                </div>

                                <!-- icon_flag -->
                                <div data-name="<?php echo esc_attr( 'icon_flag' ); ?>" class="eratags-fields tble-fields open-section">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Display Icon Or Flag', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'yes-display-covtags' ); ?>">
                                          <input id="<?php echo esc_attr( 'yes-display-covtags' ); ?>" type="radio" name="<?php echo esc_attr( 'icon_flag' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                          <?php esc_html_e( 'Yes' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'no-display-covtags' ); ?>">
                                          <input id="<?php echo esc_attr( 'no-display-covtags' ); ?>" type="radio" name="<?php echo esc_attr( 'icon_flag' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                          <?php esc_html_e( 'No' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <!-- paging_type -->
                                <div data-name="<?php echo esc_attr( 'paging_type' ); ?>" class="eratags-fields tble-fields open-section">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Pagination Type', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'pagination-type-serials' ); ?>">
                                          <input id="<?php echo esc_attr( 'pagination-type-serials' ); ?>" type="radio" name="<?php echo esc_attr( 'paging_type' ); ?>" value="<?php echo esc_attr( 'serials' ); ?>">
                                          <?php esc_html_e( 'Serials and next | prev' ); ?>
                                        </label>
                                      </li>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'pagination-type-loadmore' ); ?>">
                                          <input id="<?php echo esc_attr( 'pagination-type-loadmore' ); ?>" type="radio" name="<?php echo esc_attr( 'paging_type' ); ?>" value="<?php echo esc_attr( 'loadmore' ); ?>">
                                          <?php esc_html_e( 'Load More' ); ?>
                                        </label>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <!-- field -->
                                <div data-name="<?php echo esc_attr( 'field' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Display Field', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-today-casesradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-today-casesradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                          <?php esc_html_e( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-casesradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-casesradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'cases' ); ?>">
                                          <?php esc_html_e( 'Confirmed', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-today-deathsradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-today-deathsradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                                          <?php esc_html_e( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-deathsradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-deathsradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'deaths' ); ?>">
                                          <?php esc_html_e( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-recoveredradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-recoveredradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'recovered' ); ?>">
                                          <?php esc_html_e( 'Recovered', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-activeradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-activeradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'active' ); ?>">
                                          <?php esc_html_e( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-criticalradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-criticalradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'critical' ); ?>">
                                          <?php esc_html_e( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <!-- <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-casesperonemillionradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-casesperonemillionradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'casesPerOneMillion' ); ?>">
                                          <?php esc_html_e( 'Cases Per One Million', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-deathsperonemillionradio' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-deathsperonemillionradio' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'deathsPerOneMillion' ); ?>">
                                          <?php esc_html_e( 'Deaths Per One Million', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li> -->

                                    </ul>
                                  </div>
                                </div>

                                <!-- tricker_speed -->
                                <div data-name="<?php echo esc_attr( 'tricker_speed' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Tricker Speed', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <select class="covtags-select-handler" name="<?php echo esc_attr( 'tricker_speed' ); ?>">
                                      <option value="<?php echo esc_attr( 15 ); ?>"><?php esc_html_e( 15 ); ?></option>
                                      <option value="<?php echo esc_attr( 20 ); ?>"><?php esc_html_e( 20 ); ?></option>
                                      <option value="<?php echo esc_attr( 25 ); ?>"><?php esc_html_e( 25 ); ?></option>
                                      <option value="<?php echo esc_attr( 30 ); ?>"><?php esc_html_e( 30 ); ?></option>
                                      <option value="<?php echo esc_attr( 35 ); ?>"><?php esc_html_e( 35 ); ?></option>
                                      <option value="<?php echo esc_attr( 40 ); ?>"><?php esc_html_e( 40 ); ?></option>
                                      <option value="<?php echo esc_attr( 45 ); ?>"><?php esc_html_e( 45 ); ?></option>
                                      <option value="<?php echo esc_attr( 50 ); ?>"><?php esc_html_e( 50 ); ?></option>
                                    </select>
                                  </div>
                                </div>

                                <!-- country -->
                                <div data-name="<?php echo esc_attr( 'country' ); ?>" class="eratags-fields tble-fields">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Country Name', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <select class="covtags-select-handler" name="<?php echo esc_attr( 'country' ); ?>">
                                       <option value=""><?php echo esc_html__( 'None' ); ?></option>
                                      <?php foreach ( $this->covtags_options['list_of_countries'] as $key => $country_ob ): ?>
                                        <option value="<?php echo esc_attr( $country_ob['country']); ?>"><?php echo $country_ob['country']; ?></option>
                                      <?php endforeach; ?>
                                    </select>
                                  </div>
                                </div>

                                <!-- desc_by -->
                                <div data-name="<?php echo esc_attr( 'desc_by' ); ?>" class="eratags-fields tble-fields open-section">
                                  <div class="field-x1">
                                    <?php esc_html_e( 'Descending By Field', COVTAGS_TEXTDOMAIN ); ?>
                                  </div>
                                  <div class="field-x2">
                                    <ul>
                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-today-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-today-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                          <?php esc_html_e( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-casesradio-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-casesradio-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'cases' ); ?>">
                                          <?php esc_html_e( 'Confirmed', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-today-deathsradio-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-today-deathsradio-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                          <?php esc_html_e( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-deathsradio-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-deathsradio-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'deaths' ); ?>">
                                          <?php esc_html_e( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-recoveredradio-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-recoveredradio-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'recovered' ); ?>">
                                          <?php esc_html_e( 'Recovered', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-activeradio-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-activeradio-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'active' ); ?>">
                                          <?php esc_html_e( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-criticalradio-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-criticalradio-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'critical' ); ?>">
                                          <?php esc_html_e( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <!-- <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-casesperonemillionradio-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-casesperonemillionradio-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'casesPerOneMillion' ); ?>">
                                          <?php esc_html_e( 'Cases Per One Million', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li>

                                      <li>
                                        <label class="covtags-radio-handler" for="<?php echo esc_attr( 'field-tags-deathsperonemillionradio-desc' ); ?>">
                                          <input id="<?php echo esc_attr( 'field-tags-deathsperonemillionradio-desc' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'deathsPerOneMillion' ); ?>">
                                          <?php esc_html_e( 'Deaths Per One Million', COVTAGS_TEXTDOMAIN ); ?>
                                        </label>
                                      </li> -->

                                    </ul>
                                  </div>
                                </div>


                            </form>

                          </div>

                        </div>
                        <div class="tags-col-2">
                          <div class="eratags-fields">
                            <h4 class="mm-data rdtl"><?php esc_html_e( 'Shortcode Content', COVTAGS_TEXTDOMAIN ); ?></h4>
                          </div>
                          <div class="eratags-fields covtags_shortcode_container" contenteditable="false">
                            <?php echo esc_html__( '[covtags-all-countries]', COVTAGS_TEXTDOMAIN ); ?>
                          </div>
                        </div>
                      </div>
                    </div>

                </div>

              </div>
            </div>

          </div>
        </div>
        <?php
    }
    // Selected Combobox data
    public function selected ( $select_box ){
      $cache_period = ( int ) $this->covtags_options['options']['cache_period'] ;
      $cal_mins     = round( $select_box * 60 );
      if( $cache_period === (int) $cal_mins ){
        echo esc_attr( 'selected');
      }
    }
    // Create admin menu page
    public function create_menu (){
        // Main Menu
        add_menu_page( __( 'Coronavirus Tags', COVTAGS_TEXTDOMAIN ), __( 'Covid19-Tags', COVTAGS_TEXTDOMAIN ), 'manage_options', 'covtags', array( $this, 'covtags_admin_page_callback' ),   COVTAGS_SRC . 'assets/img/covid-icon.png', 20 );

    }

  }

}


new covtags_administration_panel();
