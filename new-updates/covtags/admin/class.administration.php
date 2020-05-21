<?php

// Check if current page not admin
if ( ! is_admin() ) {
  return;
}

// Class Of Covid19 Panel
if ( ! class_exists( 'covtags_administration_panel' ) ) {

  class covtags_administration_panel {

    // Attributes
    public $covtags_options;

    // Autoload some settings before call methods
    public function __construct() {

      // Prevent real user from seeing this page
      if( ! current_user_can( 'manage_options' ) ) {
        return;
      }

      // Load Settings from db
      $this->covtags_options = get_option( 'covtags_coronavirus_options' );

      // Enqueue external files of css and js
      add_action( 'admin_enqueue_scripts',                  array( $this, 'include_external_files' ) );
      add_action( 'admin_menu',                             array( $this, 'create_admin_menu_page' ) );

      // Ajax Actions
      add_action( 'wp_ajax_CoronaVirusTags_action',         array( $this, "callback_ajax_data_covid" ) );
      add_action( 'wp_ajax_nopriv_CoronaVirusTags_action',  array( $this, "callback_ajax_data_covid" ) );

    }

    // Include external files ( Stylesheets and javascript for admin panel )
    public function include_external_files() {

      // Include Files
      wp_enqueue_script( 'covtags-admin-scripts', COVTAGS_SRC . 'assets/js/admin-scripts.js', array( 'jquery' ), COVTAGS_VER, true );
      wp_enqueue_style( 'covtags-admin-styles', COVTAGS_SRC . 'assets/css/admin-styles.css', false, COVTAGS_VER, 'all' );

      // Localize
      wp_localize_script( 'covtags-admin-scripts', 'eratags_obj', array(
        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'covid-settings' ),
        "saving"        => __( "Saving ...", COVTAGS_TEXTDOMAIN ),
        "save_changes"  => __( "Save Changes", COVTAGS_TEXTDOMAIN ),
      ) );

    }

    // Create Administation Menu Panel For Covid 19
    public function create_admin_menu_page() {

      // Built-in wp callback to create menu page
      add_menu_page(
        __( 'Coronavirus Live updates', COVTAGS_TEXTDOMAIN ),
        __( 'Covtags'    , COVTAGS_TEXTDOMAIN ),
        'manage_options',
        'covtags',
        array( $this, 'render_html_of_admin_page' ),
        COVTAGS_SRC . 'assets/img/logo-2-250x250.png',
        20
      );

    }

    // Render Html Code For Admin Page
    public function render_html_of_admin_page() {

      // Getting Data From Options
      $covtags_title_data       = ! isset( $this->covtags_options[ 'title' ] ) ? '' : $this->covtags_options[ 'title' ];
      $covtags_description_data = ! isset( $this->covtags_options[ 'description' ] ) ? '' : $this->covtags_options[ 'description' ];

      ob_start();
      ?>

      <!-- Wrap class of wp -->
      <div class="wrap">
        <!-- Structure Wrapper -->
        <div class="covtags-container-adm">

          <!-- Left Sidebar -->
          <div class="covtags-tab-contents">

            <!-- Logo Container -->
            <div class="eratags-logo">
              <img class="responsive-logo" src="<?php echo esc_url( COVTAGS_SRC . 'assets/img/eratags-logo.jpg' ); ?>" alt="<?php echo esc_attr( 'Eratags Logo' , COVTAGS_TEXTDOMAIN ); ?>">
            </div>

            <!-- Menu List -->
            <ul>
              <li>
                <a class="open-anchor-element tags-selected-anchor" href="#covtags-settings">
                  <span><?php esc_html_e( 'Basic Options', COVTAGS_TEXTDOMAIN ); ?></span>
                  <span class="dashicons dashicons-admin-settings"></span>
                </a>
              </li>
              <li>
                <a class="open-anchor-element" href="#covtags-shortcodes">
                  <span><?php esc_html_e( 'Shortcode Builder', COVTAGS_TEXTDOMAIN ); ?></span>
                  <span class="dashicons dashicons-schedule"></span>
                </a>
              </li>
            </ul>
          </div> <!-- End Left Sidebar -->

          <!-- List Our Website -->
          <div class="tags-website-info">
            <a href="<?php echo esc_url( 'https:eratags.com' ); ?>" target="_blank">
              <?php esc_html_e( 'eratags.com', COVTAGS_TEXTDOMAIN ); ?>
            </a>
          </div>

          <!-- Page Contents -->
          <div class="covtags-blocks">
            <div class="cotags-basic-contents">

              <!-- Settings and Options -->
              <div class="covtags-settings">

                <!-- Basic Settings -->
                <div class="block-contents">

                  <!-- Full Side -->
                  <div class="tags-panel-container">
                    <h1>
                      <?php esc_html_e( 'Covtags - Coronavirus Live Updates', COVTAGS_TEXTDOMAIN ); ?>
                    </h1>
                  </div>

                  <!-- Left Side -->
                  <div class="tags-side-left tags-content-block">

                    <!-- Settings -->
                    <div id="covtags-settings" class="covtags-block">

                      <!-- Title -->
                      <div class="block-title">
                        <h4>
                          <?php esc_html_e( 'Settings', COVTAGS_TEXTDOMAIN ); ?>
                        </h4>
                      </div>

                      <!-- Form Contents -->
                      <div class="tags-field-container">

                        <!-- Covid19 Title -->
                        <div class="tags-form-tbl-contents">
                          <div class="covtags-label">
                            <label for="<?php echo esc_attr( 'covtags_corona_title' ); ?>">
                              <?php esc_html_e( 'Covid19 Title', COVTAGS_TEXTDOMAIN ); ?>
                            </label>
                          </div>
                          <div class="covtags-field">
                            <input type="text" id="<?php echo esc_attr( 'covtags_corona_title' ); ?>" name="<?php echo esc_attr( 'covtitle' ); ?>" value="<?php echo esc_attr( $covtags_title_data ); ?>">
                          </div>
                        </div>

                        <!-- Covid19 Description -->
                        <div class="tags-form-tbl-contents">
                          <div class="covtags-label">
                            <label for="<?php echo esc_attr( 'covtags_corona_desc' ); ?>">
                              <?php esc_html_e( 'Covid19 Description', COVTAGS_TEXTDOMAIN ); ?>
                            </label>
                          </div>
                          <div class="covtags-field">
                            <textarea id="<?php echo esc_attr( 'covtags_corona_desc' ); ?>" rows="8" cols="80" name="<?php echo esc_attr( 'covdesc' ); ?>"><?php echo $covtags_description_data; ?></textarea>
                          </div>
                        </div>

                        <!-- Default Cache Period -->
                        <div class="tags-form-tbl-contents">
                          <div class="covtags-label remove-padding">
                            <?php esc_html_e( 'Cache Period', COVTAGS_TEXTDOMAIN ); ?>
                          </div>
                          <div class="covtags-field">
                            <span class="tags-note">
                              <?php
                                 echo sprintf( esc_html__( 'By Default, Every %1$s Minutes .', COVTAGS_TEXTDOMAIN ) , 10 );
                              ?>
                            </span>
                            <!-- <p>
                              <span class="dashicons dashicons-info"></span>
                              <?php esc_html_e( 'We recommend you to use standard card in your wordpress site to allow your visitors see the covid19 updates without refresh the browser', COVTAGS_TEXTDOMAIN ); ?>
                            </p> -->
                          </div>
                        </div>

                        <!-- Saving Button -->
                        <div class="tags-form-tbl-contents tags-text-left tags-save-container">
                          <a id="save-coronavirus-tags" href="#">
                            <?php esc_html_e( 'Save Changes', COVTAGS_TEXTDOMAIN ); ?>
                          </a>
                        </div>

                      </div>

                    </div>

                    <!-- Shortcode Builder -->
                    <div id="covtags-shortcodes" class="covtags-block hide-element">

                      <!-- Title -->
                      <div class="block-title">
                        <h4>
                          <?php esc_html_e( 'Shortcode Builder For Covid 19', COVTAGS_TEXTDOMAIN ); ?>
                        </h4>
                      </div>

                      <!-- Form Contents -->
                      <form class="" id="shorcodeform" action="#" method="post">

                        <div class="tags-field-container">

                          <!-- Shortcode Type -->
                          <div class="tags-form-tbl-contents set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'covtags-data-provider' ); ?>">
                                <?php esc_html_e( 'Shortcode Type :', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <select id="<?php echo esc_attr( 'covtags-data-provider' );?>" class="" name="">
                                 <option value="<?php echo esc_attr( 'standard_card' ); ?>"><?php esc_html_e( 'Standard Card', COVTAGS_TEXTDOMAIN ); ?></option>
                                 <option value="<?php echo esc_attr( 'ticker' ); ?>"><?php esc_html_e( 'Ticker', COVTAGS_TEXTDOMAIN ); ?></option>
                                 <option value="<?php echo esc_attr( 'datatable' ); ?>"><?php esc_html_e( 'Datatable', COVTAGS_TEXTDOMAIN ); ?></option>
                                 <option value="<?php echo esc_attr( 'stats_card' ); ?>"><?php esc_html_e( 'Stats Card', COVTAGS_TEXTDOMAIN ); ?></option>
                                 <option value="<?php echo esc_attr( 'map_card' ); ?>"><?php esc_html_e( 'Map Card', COVTAGS_TEXTDOMAIN ); ?></option>
                                 <option value="<?php echo esc_attr( 'status_card' ); ?>"><?php esc_html_e( 'Status Card', COVTAGS_TEXTDOMAIN ); ?></option>
                              </select>
                            </div>
                          </div>

                          <!-- Ticker Data : Globally - Countries - all -->
                          <div data-name="<?php echo esc_attr( 'ticker-data' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'ticker-data' ); ?>">
                                  <?php esc_html_e( 'Data Type', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field tags-3-cols">
                              <ul>
                                 <li>
                                    <label class="ticker-data-type" for="<?php echo esc_attr( 'ticker-data-globally' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-data-globally' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker-data' );?>" value="<?php echo esc_attr( 'globally' ); ?>">
                                      <?php esc_html_e( 'Globally', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="ticker-data-type" for="<?php echo esc_attr( 'ticker-data-country' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-data-country' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker-data' );?>" value="<?php echo esc_attr( 'country' ); ?>">
                                      <?php esc_html_e( 'Specific Country', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="ticker-data-type" for="<?php echo esc_attr( 'ticker-data-all' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-data-all' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker-data' );?>" value="<?php echo esc_attr( 'all' ); ?>">
                                      <?php esc_html_e( 'All Countries', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Select Country -->
                          <div data-name="<?php echo esc_attr( 'country' ); ?>" class="tags-form-tbl-contents tags-data-opt-field tags-two-fields set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'ticker-data-countries' ); ?>">
                                 <?php esc_html_e( 'Country Name', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <select  id="<?php echo esc_attr( 'ticker-data-countries' ); ?>" class="covtags-select-handler shortcode_select_texts" name="<?php echo esc_attr( 'country' ); ?>">
                                 <option value="<?php echo esc_attr( 'none' ); ?>" disabled selected><?php echo esc_html__( 'Select Country' ); ?></option>
                                 <?php foreach ( $this->covtags_options[ 'list_of_countries' ] as $key => $country_ob ): ?>
                                 <option value="<?php echo esc_attr( $country_ob[ '_id' ]); ?>"><?php echo $country_ob[ 'country' ]; ?></option>
                                 <?php endforeach; ?>
                              </select>
                              <input class="shortcode_input_texts" type="text" name="country-text" value="" placeholder="<?php echo esc_attr( 'Country Text' ); ?>">
                            </div>
                          </div>

                          <!-- Shortcode Title -->
                          <div data-name="<?php echo esc_attr( 'card-text' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom shortcode-open-option-data">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'covtags-data-title' ); ?>">
                                <?php esc_html_e( 'Card Text :', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <input class="covtag-shortcode-input shortcode_input_texts" type="text" name="<?php echo esc_attr( 'card-text' ); ?>" value="">
                            </div>
                          </div>

                          <!-- Ticker Position -->
                          <div data-name="<?php echo esc_attr( 'ticker-position' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'ticker-speed' ); ?>">
                                  <?php esc_html_e( 'Ticker Position', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field tags-3-cols">
                              <ul>
                                 <li>
                                    <label class="ticker-data-position" for="<?php echo esc_attr( 'ticker-position-normal' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-position-normal' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker-position' );?>" value="<?php echo esc_attr( 'normal' ); ?>">
                                      <?php esc_html_e( 'Normal Position', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="ticker-data-position" for="<?php echo esc_attr( 'ticker-position-top' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-position-top' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker-position' );?>" value="<?php echo esc_attr( 'top' ); ?>">
                                      <?php esc_html_e( 'Sticky In Top', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="ticker-data-position" for="<?php echo esc_attr( 'ticker-position-bottom' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-position-bottom' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker-position' );?>" value="<?php echo esc_attr( 'bottom' ); ?>">
                                      <?php esc_html_e( 'Sticky In Bottom', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>


                          <!-- Shortcode Live Text -->
                          <div data-name="<?php echo esc_attr( 'live-text' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom shortcode-open-option-data">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'covtags-live-text' ); ?>">
                                <?php esc_html_e( 'Live Text :', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <input id="<?php echo esc_attr( 'covtags-live-text' ); ?>" class="covtag-shortcode-input shortcode_input_texts" type="text" name="<?php echo esc_attr( 'live-text' ); ?>" value="">
                            </div>
                          </div>

                          <!-- Rows Per Page -->
                          <div data-name="<?php echo esc_attr( 'rows-per-page' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'rows-per-page' ); ?>">
                                <?php esc_html_e( 'Datatable Rows Per Page :', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <select id="<?php echo esc_attr( 'rows-per-page' ); ?>" name="<?php echo esc_attr( 'rows-per-page' ); ?>" class="covtags-select-handler shortcode_select_texts">
                                <option value="<?php echo esc_attr( '5' ); ?>"> <?php esc_html_e( '5 rows', COVTAGS_TEXTDOMAIN ); ?> </option>
                                <option value="<?php echo esc_attr( '10' ); ?>"> <?php esc_html_e( '10 rows', COVTAGS_TEXTDOMAIN ); ?> </option>
                                <option value="<?php echo esc_attr( '15' ); ?>"> <?php esc_html_e( '15 rows', COVTAGS_TEXTDOMAIN ); ?> </option>
                                <option value="<?php echo esc_attr( '20' ); ?>"> <?php esc_html_e( '20 rows', COVTAGS_TEXTDOMAIN ); ?> </option>
                              </select>
                            </div>
                          </div>

                          <!-- Dark Mode Option -->
                          <div data-name="<?php echo esc_attr( 'dark-mode' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom shortcode-open-option-data">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'dark-mode-opt' ); ?>">
                                <?php esc_html_e( 'Enable Dark Mode :', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                <li>
                                   <label class="" for="<?php echo esc_attr( 'radio-dark_mode' ); ?>">
                                     <input class="shortcode_radio_box" id="<?php echo esc_attr( 'radio-dark_mode' ); ?>" type="radio" name="<?php echo esc_attr( 'dark-mode' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                     <?php esc_html_e( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
                                   </label>
                                </li>
                                <li>
                                   <label class="" for="<?php echo esc_attr( 'radio-dark_mode-x' ); ?>">
                                     <input class="shortcode_radio_box" id="<?php echo esc_attr( 'radio-dark_mode-x' ); ?>" type="radio" name="<?php echo esc_attr( 'dark-mode' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                     <?php esc_html_e( 'No', COVTAGS_TEXTDOMAIN ); ?>
                                   </label>
                                </li>
                             </ul>
                            </div>
                          </div>

                          <!-- Layouts -->
                          <div data-name="<?php echo esc_attr( 'layout' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'layout-opt' ); ?>">
                                <?php esc_html_e( 'Layout :', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'radio-layout' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'radio-layout' ); ?>" type="radio" name="<?php echo esc_attr( 'layout' ); ?>" value="<?php echo esc_attr( 'flat' ); ?>">
                                      <?php esc_html_e( 'Flat', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'radio-layout-x' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'radio-layout-x' ); ?>" type="radio" name="<?php echo esc_attr( 'layout' ); ?>" value="<?php echo esc_attr( 'table' ); ?>">
                                      <?php esc_html_e( 'Table', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Graph Type -->
                          <div data-name="<?php echo esc_attr( 'graph-type' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'graph-type-opt' ); ?>">
                                <?php esc_html_e( 'Table Graph Style', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-style-line' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-graph-style-line' ); ?>" type="radio" name="<?php echo esc_attr( 'graph_type' ); ?>" value="<?php echo esc_attr( 'line' ); ?>">
                                      <?php esc_html_e( 'Line Graph', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-style-bar' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-graph-style-bar' ); ?>" type="radio" name="<?php echo esc_attr( 'graph_type' ); ?>" value="<?php echo esc_attr( 'bar' ); ?>">
                                      <?php esc_html_e( 'Bar Graph', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Status Type -->
                          <div data-name="<?php echo esc_attr( 'status-type' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'status-type-opt' ); ?>">
                                <?php esc_html_e( 'Status Card Type', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'cases-type-covtags-active' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'cases-type-covtags-active' ); ?>" type="radio" name="<?php echo esc_attr( 'status_type' ); ?>" value="<?php echo esc_attr( 'active' ); ?>">
                                      <?php esc_html_e( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'cases-type-covtags-closed' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'cases-type-covtags-closed' ); ?>" type="radio" name="<?php echo esc_attr( 'status_type' ); ?>" value="<?php echo esc_attr( 'closed' ); ?>">
                                      <?php esc_html_e( 'Closed', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Hide Title -->
                          <div data-name="<?php echo esc_attr( 'hide-title' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'hide-title-opt' ); ?>">
                                 <?php esc_html_e( 'Hide Title', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-hide-yes' ); ?>">
                                      <input id="<?php echo esc_attr( 'tbl-graph-hide-yes' ); ?>" type="radio" name="<?php echo esc_attr( 'hide_title' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                      <?php esc_html_e( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-hide-no' ); ?>">
                                      <input id="<?php echo esc_attr( 'tbl-graph-hide-no' ); ?>" type="radio" name="<?php echo esc_attr( 'hide_title' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                      <?php esc_html_e( 'No', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Show Percentage -->
                          <div data-name="<?php echo esc_attr( 'show-percentage' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'show-percentage-data' ); ?>">
                                 <?php esc_html_e( 'Show Percentage Value', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-perc-hide-yes' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-perc-hide-yes' ); ?>" type="radio" name="<?php echo esc_attr( 'show_percentage' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                      <?php esc_html_e( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-perc-hide-no' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-perc-hide-no' ); ?>" type="radio" name="<?php echo esc_attr( 'show_percentage' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                      <?php esc_html_e( 'No', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- use_graph_with -->
                          <div data-name="<?php echo esc_attr( 'use-graph-with' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'use-graph-with' ); ?>">
                                 <?php esc_html_e( 'Show Graph Style Or Disable it ?', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-style-disable-x' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-graph-style-disable-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                      <?php esc_html_e( 'Disable', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-style-line-x' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-graph-style-line-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'line' ); ?>">
                                      <?php esc_html_e( 'Line Graph', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-style-bar-x' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-graph-style-bar-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'bar' ); ?>">
                                      <?php esc_html_e( 'Bar Graph', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-style-pie-x' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-graph-style-pie-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'pie' ); ?>">
                                      <?php esc_html_e( 'Pie Graph', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-style-doughnut-x' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-graph-style-doughnut-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'doughnut' ); ?>">
                                      <?php esc_html_e( 'Doughnut Graph', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'tbl-graph-style-polarArea-x' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'tbl-graph-style-polarArea-x' ); ?>" type="radio" name="<?php echo esc_attr( 'use_graph_with' ); ?>" value="<?php echo esc_attr( 'polarArea' ); ?>">
                                      <?php esc_html_e( 'Polar Area Graph', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Select Fields -->
                          <div data-name="<?php echo esc_attr( 'fields' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'display-fields' ); ?>">
                                 <?php esc_html_e( 'Data Fields', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul data-name="fields">
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box"  for="<?php echo esc_attr( 'field-tags-today-cases' ); ?>">
                                      <input id="<?php echo esc_attr( 'field-tags-today-cases' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                      <?php esc_html_e( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box"  for="<?php echo esc_attr( 'field-tags-cases' ); ?>">
                                      <input id="<?php echo esc_attr( 'field-tags-cases' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'cases' ); ?>">
                                      <?php esc_html_e( 'Confirmed', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li >
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'field-tags-today-deaths' ); ?>">
                                      <input id="<?php echo esc_attr( 'field-tags-today-deaths' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                                      <?php esc_html_e( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'field-tags-deaths' ); ?>">
                                      <input id="<?php echo esc_attr( 'field-tags-deaths' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'deaths' ); ?>">
                                      <?php esc_html_e( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'field-tags-recovered' ); ?>">
                                      <input id="<?php echo esc_attr( 'field-tags-recovered' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'recovered' ); ?>">
                                      <?php esc_html_e( 'Recovered', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'field-tags-active' ); ?>">
                                      <input id="<?php echo esc_attr( 'field-tags-active' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'active' ); ?>">
                                      <?php esc_html_e( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'field-tags-critical' ); ?>">
                                      <input id="<?php echo esc_attr( 'field-tags-critical' ); ?>" type="checkbox" name="<?php echo esc_attr( 'fields' ); ?>" value="<?php echo esc_attr( 'critical' ); ?>">
                                      <?php esc_html_e( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Ticker Tooltip Fields -->
                          <div data-name="<?php echo esc_attr( 'tooltip-fields' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'display-tooltip-fields' ); ?>">
                                 <?php esc_html_e( 'Tooltip Fields', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul data-name="tooltip-fields">
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box"  for="<?php echo esc_attr( 'tooltip-tags-today-cases' ); ?>">
                                      <input id="<?php echo esc_attr( 'tooltip-tags-today-cases' ); ?>" type="checkbox" name="<?php echo esc_attr( 'tooltip-fields' ); ?>" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                      <?php esc_html_e( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box"  for="<?php echo esc_attr( 'tooltip-tags-cases' ); ?>">
                                      <input id="<?php echo esc_attr( 'tooltip-tags-cases' ); ?>" type="checkbox" name="<?php echo esc_attr( 'tooltip-fields' ); ?>" value="<?php echo esc_attr( 'cases' ); ?>">
                                      <?php esc_html_e( 'Confirmed', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li >
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'tooltip-tags-today-deaths' ); ?>">
                                      <input id="<?php echo esc_attr( 'tooltip-tags-today-deaths' ); ?>" type="checkbox" name="<?php echo esc_attr( 'tooltip-fields' ); ?>" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                                      <?php esc_html_e( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'tooltip-tags-deaths' ); ?>">
                                      <input id="<?php echo esc_attr( 'tooltip-tags-deaths' ); ?>" type="checkbox" name="<?php echo esc_attr( 'tooltip-fields' ); ?>" value="<?php echo esc_attr( 'deaths' ); ?>">
                                      <?php esc_html_e( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'tooltip-tags-recovered' ); ?>">
                                      <input id="<?php echo esc_attr( 'tooltip-tags-recovered' ); ?>" type="checkbox" name="<?php echo esc_attr( 'tooltip-fields' ); ?>" value="<?php echo esc_attr( 'recovered' ); ?>">
                                      <?php esc_html_e( 'Recovered', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'tooltip-tags-active' ); ?>">
                                      <input id="<?php echo esc_attr( 'tooltip-tags-active' ); ?>" type="checkbox" name="<?php echo esc_attr( 'tooltip-fields' ); ?>" value="<?php echo esc_attr( 'active' ); ?>">
                                      <?php esc_html_e( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'tooltip-tags-critical' ); ?>">
                                      <input id="<?php echo esc_attr( 'tooltip-tags-critical' ); ?>" type="checkbox" name="<?php echo esc_attr( 'tooltip-fields' ); ?>" value="<?php echo esc_attr( 'critical' ); ?>">
                                      <?php esc_html_e( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="covtags-checkbox-handler shortcode_check_box" for="<?php echo esc_attr( 'tooltip-tags-active' ); ?>">
                                      <input id="<?php echo esc_attr( 'tooltip-tags-active' ); ?>" type="checkbox" name="<?php echo esc_attr( 'tooltip-fields' ); ?>" value="<?php echo esc_attr( 'active' ); ?>">
                                      <?php esc_html_e( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>


                          <!-- Show Icon Or Flag -->
                          <div data-name="<?php echo esc_attr( 'icon-flag' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'display-flag' ); ?>">
                                 <?php esc_html_e( 'Display Icon Or Flag', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'yes-display-covtags' ); ?>">
                                    <input class="shortcode_radio_box" id="<?php echo esc_attr( 'yes-display-covtags' ); ?>" type="radio" name="<?php echo esc_attr( 'icon_flag' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                    <?php esc_html_e( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'no-display-covtags' ); ?>">
                                    <input class="shortcode_radio_box" id="<?php echo esc_attr( 'no-display-covtags' ); ?>" type="radio" name="<?php echo esc_attr( 'icon_flag' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                    <?php esc_html_e( 'No', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Enable RTL Direction -->
                          <div data-name="<?php echo esc_attr( 'enable-rtl' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom shortcode-open-option-data">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'enable-rtl' ); ?>">
                                 <?php esc_html_e( 'Enable RTL Direction', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'yes-rtl-direction' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'yes-rtl-direction' ); ?>" type="radio" name="<?php echo esc_attr( 'enable-rtl' ); ?>" value="<?php echo esc_attr( 'yes' ); ?>">
                                      <?php esc_html_e( 'Yes', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'no-rtl-direction' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'no-rtl-direction' ); ?>" type="radio" name="<?php echo esc_attr( 'enable-rtl' ); ?>" value="<?php echo esc_attr( 'no' ); ?>">
                                      <?php esc_html_e( 'No', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Languages And Custom Texts -->
                          <div data-name="<?php echo esc_attr( 'collected-fields' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'covtasgs-custom-textes' ); ?>">
                                 <?php esc_html_e( 'Country Data Fields', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field tags-many-inputs">
                              <ul>
                                <li class="cases-label">

                                   <label class="shortcode_check_box_collecter" for="<?php echo esc_attr( 'cases-input-tagsx' ); ?>">
                                     <input id="<?php echo esc_attr( 'cases-input-tagsx' ); ?>" type="checkbox" name="fields" value="<?php echo esc_attr( 'cases' ); ?>">
                                     <?php esc_html_e( 'Cases :', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Cases' ); ?>" type="text" name="<?php echo esc_attr( 'cases-text' ); ?>">
                                   </label>

                                </li>
                                <li class="death-label">

                                   <label class="shortcode_check_box_collecter" for="<?php echo esc_attr( 'deaths-input-tagsx' ); ?>">
                                     <input id="<?php echo esc_attr( 'deaths-input-tagsx' ); ?>" type="checkbox" name="fields" value="<?php echo esc_attr( 'deaths' ); ?>">
                                     <?php esc_html_e( 'Deaths :', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Deaths' ); ?>" type="text" name="<?php echo esc_attr( 'deaths-text' ); ?>">
                                   </label>

                                </li>
                                <li class="tcases-label">

                                   <label class="shortcode_check_box_collecter" for="<?php echo esc_attr( 'today-cases-input-tagsx' ); ?>">
                                     <input id="<?php echo esc_attr( 'today-cases-input-tagsx' ); ?>" type="checkbox" name="fields" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                     <?php esc_html_e( 'Today Cases :', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Today Cases' ); ?>" type="text" name="<?php echo esc_attr( 'today-cases-text' ); ?>">
                                   </label>

                                </li>
                                <li class="tdeath-label">

                                   <label class="shortcode_check_box_collecter" for="<?php echo esc_attr( 'today-deaths-input-tagsx' ); ?>">
                                     <input id="<?php echo esc_attr( 'today-deaths-input-tagsx' ); ?>" type="checkbox" name="fields" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                                     <?php esc_html_e( 'Today Deaths :', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Today Deaths' ); ?>" type="text" name="<?php echo esc_attr( 'today-deaths-text' ); ?>">
                                   </label>

                                </li>
                                <li class="recovered-label">

                                   <label class="shortcode_check_box_collecter" for="<?php echo esc_attr( 'recovered-input-tagsx' ); ?>">
                                     <input id="<?php echo esc_attr( 'recovered-input-tagsx' ); ?>" type="checkbox" name="fields" value="<?php echo esc_attr( 'recovered' ); ?>">
                                     <?php esc_html_e( 'Recovered :', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Recovered' ); ?>"  type="text" name="<?php echo esc_attr( 'recovered-text' ); ?>">
                                   </label>

                                </li>
                                <li class="critical-label">

                                   <label class="shortcode_check_box_collecter" for="<?php echo esc_attr( 'critical-input-tagsx' ); ?>">
                                     <input id="<?php echo esc_attr( 'critical-input-tagsx' ); ?>" type="checkbox" name="fields" value="<?php echo esc_attr( 'critical' ); ?>">
                                     <?php esc_html_e( 'Critical :', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Critical' ); ?>"  type="text" name="<?php echo esc_attr( 'critical-text' ); ?>">
                                   </label>

                                </li>
                                <li class="active-label">

                                   <label class="shortcode_check_box_collecter" for="<?php echo esc_attr( 'active-input-tagsx' ); ?>">
                                     <input id="<?php echo esc_attr( 'active-input-tagsx' ); ?>" type="checkbox" name="fields" value="<?php echo esc_attr( 'active' ); ?>">
                                     <?php esc_html_e( 'Active :', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Active' ); ?>"  type="text" name="<?php echo esc_attr( 'active-text' ); ?>">
                                   </label>

                                </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Languages And Custom Texts -->
                          <div data-name="<?php echo esc_attr( 'language-texts' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom shortcode-open-option-data">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'covtasgs-custom-textes' ); ?>">
                                 <?php esc_html_e( 'Customize Texts', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field tags-many-inputs">
                              <ul>
                                <li class="world-label">
                                   <label class="" for="<?php echo esc_attr( 'cases-input-tags' ); ?>">
                                     <?php esc_html_e( 'World Wide : ', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : World Wide' ); ?>" id="<?php echo esc_attr( 'cases-input-tags' ); ?>" type="text" name="<?php echo esc_attr( 'world-text' ); ?>">
                                   </label>
                                </li>
                                <li class="cases-label">
                                   <label class="" for="<?php echo esc_attr( 'cases-input-tags' ); ?>">
                                     <?php esc_html_e( 'Cases : ', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Cases' ); ?>" id="<?php echo esc_attr( 'cases-input-tags' ); ?>" type="text" name="<?php echo esc_attr( 'cases-text' ); ?>">
                                   </label>
                                </li>
                                <li class="death-label">
                                   <label class="" for="<?php echo esc_attr( 'deaths-input-tags' ); ?>">
                                     <?php esc_html_e( 'Deaths : ', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Deaths' ); ?>" id="<?php echo esc_attr( 'deaths-input-tags' ); ?>" type="text" name="<?php echo esc_attr( 'deaths-text' ); ?>">
                                   </label>
                                </li>
                                <li class="tcases-label">
                                   <label class="" for="<?php echo esc_attr( 'today-cases-input-tags' ); ?>">
                                     <?php esc_html_e( 'Today Cases : ', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Today Cases' ); ?>" id="<?php echo esc_attr( 'today-cases-input-tags' ); ?>" type="text" name="<?php echo esc_attr( 'today-cases-text' ); ?>">
                                   </label>
                                </li>
                                <li class="tdeath-label">
                                   <label class="" for="<?php echo esc_attr( 'today-deaths-input-tags' ); ?>">
                                     <?php esc_html_e( 'Today Deaths : ', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Today Deaths' ); ?>" id="<?php echo esc_attr( 'today-deaths-input-tags' ); ?>" type="text" name="<?php echo esc_attr( 'today-deaths-text' ); ?>">
                                   </label>
                                </li>
                                <li class="recovered-label">
                                   <label class="" for="<?php echo esc_attr( 'recovered-input-tags' ); ?>">
                                     <?php esc_html_e( 'Recovered : ', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Recovered' ); ?>" id="<?php echo esc_attr( 'recovered-input-tags' ); ?>" type="text" name="<?php echo esc_attr( 'recovered-text' ); ?>">
                                   </label>
                                </li>
                                <li class="critical-label">
                                   <label class="" for="<?php echo esc_attr( 'critical-input-tags' ); ?>">
                                     <?php esc_html_e( 'Critical : ', COVTAGS_TEXTDOMAIN ); ?>
                                     <input class="shortcode_input_texts" placeholder="<?php echo esc_attr( 'Default Text : Critical' ); ?>" id="<?php echo esc_attr( 'critical-input-tags' ); ?>" type="text" name="<?php echo esc_attr( 'critical-text' ); ?>">
                                   </label>
                                </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Pagination Type -->
                          <div data-name="<?php echo esc_attr( 'paging-type' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'display-flag' ); ?>">
                                  <?php esc_html_e( 'Pagination Type', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'pagination-type-serials' ); ?>">
                                    <input class="shortcode_radio_box" id="<?php echo esc_attr( 'pagination-type-serials' ); ?>" type="radio" name="<?php echo esc_attr( 'paging_type' ); ?>" value="<?php echo esc_attr( 'serials' ); ?>">
                                    <?php esc_html_e( 'Serials and next | prev', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'pagination-type-loadmore' ); ?>">
                                    <input class="shortcode_radio_box" id="<?php echo esc_attr( 'pagination-type-loadmore' ); ?>" type="radio" name="<?php echo esc_attr( 'paging_type' ); ?>" value="<?php echo esc_attr( 'loadmore' ); ?>">
                                    <?php esc_html_e( 'Load More', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Descending By -->
                          <div data-name="<?php echo esc_attr( 'desc-by' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'desc-by' ); ?>">
                                  <?php esc_html_e( 'Descending Countries By', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-tags-today-casesradio' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-tags-today-casesradio' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                      <?php esc_html_e( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-tags-casesradio' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-tags-casesradio' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'cases' ); ?>">
                                      <?php esc_html_e( 'Confirmed', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-tags-today-deathsradio' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-tags-today-deathsradio' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                                      <?php esc_html_e( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-tags-deathsradio' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-tags-deathsradio' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'deaths' ); ?>">
                                      <?php esc_html_e( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-tags-recoveredradio' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-tags-recoveredradio' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'recovered' ); ?>">
                                      <?php esc_html_e( 'Recovered', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-tags-activeradio' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-tags-activeradio' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'active' ); ?>">
                                      <?php esc_html_e( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-tags-criticalradio' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-tags-criticalradio' ); ?>" type="radio" name="<?php echo esc_attr( 'desc_by' );?>" value="<?php echo esc_attr( 'critical' ); ?>">
                                      <?php esc_html_e( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- One Field -->
                          <div data-name="<?php echo esc_attr( 'field' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'ticker-field' ); ?>">
                                  <?php esc_html_e( 'Field', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-ticker-todayCases' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-ticker-todayCases' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'todayCases' ); ?>">
                                      <?php esc_html_e( 'Today Cases', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-ticker-cases' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-ticker-cases' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'cases' ); ?>">
                                      <?php esc_html_e( 'Confirmed', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-ticker-todayDeaths' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-ticker-todayDeaths' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'todayDeaths' ); ?>">
                                      <?php esc_html_e( 'Today Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-ticker-deaths' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-ticker-deaths' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'deaths' ); ?>">
                                      <?php esc_html_e( 'Deaths', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-ticker-recovered' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-ticker-recovered' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'recovered' ); ?>">
                                      <?php esc_html_e( 'Recovered', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-ticker-active' ); ?>">
                                    <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-ticker-active' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'active' ); ?>">
                                    <?php esc_html_e( 'Active', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'field-ticker-critical' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'field-ticker-critical' ); ?>" type="radio" name="<?php echo esc_attr( 'field' );?>" value="<?php echo esc_attr( 'critical' ); ?>">
                                      <?php esc_html_e( 'Critical', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                          <!-- Ticker Speed -->
                          <div data-name="<?php echo esc_attr( 'ticker-speed' ); ?>" class="tags-form-tbl-contents tags-data-opt-field set-border-bottom">
                            <div class="covtags-label">
                              <label for="<?php echo esc_attr( 'ticker-speed' ); ?>">
                                  <?php esc_html_e( 'Ticker Speed', COVTAGS_TEXTDOMAIN ); ?>
                              </label>
                            </div>
                            <div class="covtags-field">
                              <ul>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'ticker-speed-normal' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-speed-normal' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker_speed' );?>" value="<?php echo esc_attr( 'normal' ); ?>">
                                      <?php esc_html_e( 'Normal', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'ticker-speed-slow' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-speed-slow' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker_speed' );?>" value="<?php echo esc_attr( 'slow' ); ?>">
                                      <?php esc_html_e( 'Slow', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'ticker-speed-medium' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-speed-medium' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker_speed' );?>" value="<?php echo esc_attr( 'medium' ); ?>">
                                      <?php esc_html_e( 'Medium', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                                 <li>
                                    <label class="" for="<?php echo esc_attr( 'ticker-speed-fast' ); ?>">
                                      <input class="shortcode_radio_box" id="<?php echo esc_attr( 'ticker-speed-fast' ); ?>" type="radio" name="<?php echo esc_attr( 'ticker_speed' );?>" value="<?php echo esc_attr( 'fast' ); ?>">
                                      <?php esc_html_e( 'Fast', COVTAGS_TEXTDOMAIN ); ?>
                                    </label>
                                 </li>
                              </ul>
                            </div>
                          </div>

                        </div>

                      </form>

                    </div>

                  </div>

                  <!-- Right Side -->
                  <div class="tags-side-right tags-content-block set-as-sticky-position">

                      <!-- Shortcode Builder -->
                      <div class="covtags-about-us-card tags-shortcode-block-contents">
                        <div class="block-title covtags-help-block">
                          <h4>
                            <?php esc_html_e( 'Shortcode Contents For Covid 19', COVTAGS_TEXTDOMAIN ); ?>
                          </h4>
                          <div id="concatenated-shortcode-id" class="tags-shorcode-contents">
                            <?php esc_html_e( '[covtags-standard dark-mode="no"]', COVTAGS_TEXTDOMAIN ); ?>
                          </div>
                        </div>
                      </div>

                      <!-- Online Documentation -->
                      <div class="covtags-about-us-card tags-online-documentation">
                         <div class="block-title covtags-help-block">
                           <span class="dashicons dashicons-media-document"></span>
                           <h4>
                             <?php esc_html_e( 'Check Our Online Documentation From', COVTAGS_TEXTDOMAIN ); ?>
                           </h4>
                           <a href="<?php echo esc_url( 'https:eratags.com' ); ?>" target="_blank">
                             <?php esc_html_e( 'Here', COVTAGS_TEXTDOMAIN ); ?>
                           </a>
                         </div>
                       </div>

                     <!-- Help Center -->
                     <div class="covtags-about-us-card">
                        <div class="block-title covtags-help-block">
                          <span class="dashicons dashicons-sos"></span>
                          <h4>
                            <?php esc_html_e( 'Do you need Help ?', COVTAGS_TEXTDOMAIN ); ?>
                          </h4>
                          <a href="<?php echo esc_url( 'https:eratags.com' ); ?>" target="_blank">
                            <?php esc_html_e( 'Contact Us', COVTAGS_TEXTDOMAIN ); ?>
                          </a>
                        </div>
                      </div>

                  </div>

                </div> <!-- End Basic Settings -->

              </div>
              <!-- End Settings and Options -->

            </div>
          </div> <!-- End Page Contents -->

        </div> <!-- End Structure Wrapper -->
      </div> <!-- End Wrap class of wp -->

     <?php
      return ob_get_contents();

    }

    // Ajax Callback to save options
    public function callback_ajax_data_covid() {

      // Verify wp nonce
      if ( ! wp_verify_nonce( $_REQUEST[ 'secure' ], "covid-settings") ) {
         return;
      }

      // Target Data
      $description = isset( $_REQUEST[ "description" ] ) ? $_REQUEST[ "description" ] : '' ;
      $title       = isset( $_REQUEST[ "title" ] )       ? $_REQUEST[ "title" ]       : '';

      // Store fields inside it array
      $this->covtags_options[ 'description' ] = sanitize_text_field( strip_tags( $description ) );
      $this->covtags_options[ 'title' ] = sanitize_text_field( strip_tags( $title ) );

      // Saving via option callback
      update_option( 'covtags_coronavirus_options' ,  $this->covtags_options );

      exit;

    }

  }

  // Load Class
  new covtags_administration_panel();

}
