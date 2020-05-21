<?php

if( ! class_exists( 'covtags_ui' ) ) {

  class covtags_ui extends covtags_http_requests {

    /* Standard Card ( Using this method in shortcode and widget api ) */
    function covtags_standard_card_ui( $card_options ) {

      // Getting api data
      $world_report       = $this->covtags_world_report();
      $world_countries    = $this->covtags_countries();

      // Descending data according to cases
      usort( $world_countries, function( $a, $b ) {
        return ( $a->cases < $b->cases ) ? 1 : -1;
      } );

      // Needed Variables sent from ( Widget Api or Shortcode ) - we set default values to help us in widget api
      $standard_text      = ( isset( $card_options[ 'card-text' ] ) && null !== $card_options[ 'card-text' ] && '' !== $card_options[ 'card-text' ] ) ? $card_options[ 'card-text' ]  : __( "Coronavirus", COVTAGS_TEXTDOMAIN );
      $live_text          = ( isset( $card_options[ 'live-text' ] ) && null !== $card_options[ 'live-text' ] && '' !== $card_options[ 'live-text' ] ) ? $card_options[ 'live-text' ]  : __( "Live", COVTAGS_TEXTDOMAIN );

      // Dark mode and rtl
      $dark_mode          = isset( $card_options[ 'dark-mode' ] )  ? $card_options[ 'dark-mode' ]  : 'no';
      $enable_rtl         = isset( $card_options[ 'enable-rtl' ] ) ? $card_options[ 'enable-rtl' ] : 'no';

      /*Load User Texts Or Default Texts ? We Will make defaul "world report" as a default text */
      // Cases
      $cases_text         = __( 'Cases', COVTAGS_TEXTDOMAIN );
      if ( isset( $card_options[ 'cases-text' ] ) && $card_options[ 'cases-text' ] !== null && $card_options[ 'cases-text' ] !== '' ) {
        $cases_text = $card_options[ 'cases-text' ];
      }

      // Deaths
      $deaths_text        = __( 'Deaths', COVTAGS_TEXTDOMAIN );
      if ( isset( $card_options[ 'deaths-text' ] ) && $card_options[ 'deaths-text' ] !== null && $card_options[ 'deaths-text' ] !== '' ) {
        $deaths_text = $card_options[ 'deaths-text' ];
      }

      // Today Cases
      $today_cases_text   = __( 'Today', COVTAGS_TEXTDOMAIN );
      if ( isset( $card_options[ 'today-cases-text' ] ) && $card_options[ 'today-cases-text' ] !== null && $card_options[ 'today-cases-text' ] !== '' ) {
        $today_cases_text = $card_options[ 'today-cases-text' ];
      }

      // Today Deaths
      $today_deaths_text = __( 'Today', COVTAGS_TEXTDOMAIN );
      if ( isset( $card_options[ 'today-deaths-text' ] ) && $card_options[ 'today-deaths-text' ] !== null && $card_options[ 'today-deaths-text' ] !== '' ) {
        $today_deaths_text = $card_options[ 'today-deaths-text' ];
      }

      // Recovered
      $recovered_text    = __( 'Recovered', COVTAGS_TEXTDOMAIN );
      if ( isset( $card_options[ 'recovered-text' ] ) && $card_options[ 'recovered-text' ] !== null && $card_options[ 'recovered-text' ] !== ''  ) {
        $recovered_text = $card_options[ 'recovered-text' ];
      }

      // Critical
      $critical_text     = __( 'Critical', COVTAGS_TEXTDOMAIN );
      if ( isset( $card_options[ 'critical-text' ] ) && $card_options[ 'critical-text' ] !== null && $card_options[ 'critical-text' ] !== ''  ) {
        $critical_text = $card_options[ 'critical-text' ];
      }

      // World Wide
      $world_text        = __( 'World Wide', COVTAGS_TEXTDOMAIN );
      if ( isset( $card_options[ 'world-text' ] ) && $card_options[ 'world-text' ] !== null && $card_options[ 'world-text' ] !== ''  ) {
        $world_text = $card_options[ 'world-text' ];
      }

      // Prepare Standard Card Classes
      $standard_card_classes = 'covtags-standard-card-wrapper covtags-standard-cards';

      // Dark Mode
      if( 'yes' === strtolower( $dark_mode ) ) {
        $standard_card_classes .= ' standard-dark-mode';
      }

      // RTL Direction
      if( 'yes' === strtolower( $enable_rtl ) ) {
        $standard_card_classes .= ' standard-rtl-direction';
      }

      // Prepare unique ids
      $standard_card_id   = 'covtags-standard-' . rand( 1, 1000 ) . '-' . time() ;
      $select2_id         = 'covtags-select2-'  . rand( 1, 1000 ) . '-' . time() ;

      // Render UI ( HTML ELEMENTS )
      ob_start();

      ?>
      <!-- Standard Card Container -->
      <div id="<?php echo esc_attr( $standard_card_id ); ?>" class="<?php echo esc_attr( $standard_card_classes ); ?>">

        <!-- Standard Card Title -->
        <h3>
          <!-- Live Text -->
          <span class='covtags-live-contents'><?php echo $live_text; ?></span>
          <!-- Card Text -->
          <span><?php echo $standard_text; ?></span>
        </h3>

        <!-- Standard Card Select Country or globally -->
        <div class='covtags-list-countries-data'>
          <!-- Select COuntries or Globally data -->
          <select id='<?php echo esc_attr( $select2_id ); ?>'>

            <!-- globally Option -->
            <option value='<?php echo esc_html__( '0', COVTAGS_TEXTDOMAIN ); ?>' selected><?php echo $world_text; ?></option>

            <!-- List of All Countries -->
            <?php foreach ( $world_countries as $country_key => $country ): ?>
              <option data-flag='<?php echo esc_attr( $country->countryInfo->flag ); ?>' value='<?php echo esc_attr( $country->countryInfo->_id ); ?>'><?php echo $country->country; ?></option>
            <?php endforeach; ?>

          </select>
        </div>

        <!-- List All Data Of Card -->
        <ul class='covid-country-data'>

          <!-- Cases And Today Cases -->
          <li class="confirmed-data">
            <div class="covtags-col-container">
              <span><?php echo $cases_text; ?></span>
              <span><?php echo apply_filters( 'covtags_number_unit', $world_report->cases, 'comma_format' ); ?></span>
              <span>
                <?php echo $today_cases_text; ?>
                <i><?php echo apply_filters( 'covtags_number_unit', $world_report->todayCases, 'comma_format' );?></i>
              </span>
            </div>
          </li>

          <!-- Deaths And Today Deaths -->
          <li class="deaths-data">
            <div class="covtags-col-container">
              <span><?php echo $deaths_text; ?></span>
              <span><?php echo apply_filters( 'covtags_number_unit', $world_report->deaths, 'comma_format' ); ?></span>
              <span>
                <?php echo $today_deaths_text; ?>
                <i><?php echo apply_filters( 'covtags_number_unit', $world_report->todayDeaths, 'comma_format' );?></i>
              </span>
            </div>
          </li>

          <!-- Recovered -->
          <li class='recovered-data'>
            <div class='covtags-col-container'>
              <span><?php echo $recovered_text; ?></span>
              <span><?php echo apply_filters( 'covtags_number_unit', $world_report->recovered, 'comma_format' ); ?></span>
            </div>
          </li>

          <!-- Critical -->
          <li class='critical-data'>
            <div class='covtags-col-container'>
              <span><?php echo $critical_text; ?></span>
              <span><?php echo apply_filters( 'covtags_number_unit', $world_report->critical, 'comma_format' ); ?></span>
            </div>
          </li>

        </ul>

      </div>
      <?php
      return ob_get_clean();
    }

    /* Ticker Options */
    function covtags_ticker_card_ui ( $ticker_options ) {

      // Globalas
      global $covtags_labels;

      // Deafult  Settings
      $ticker_data       = isset( $ticker_options[ 'ticker-data' ] )       ? $ticker_options[ 'ticker-data' ]       : 'all';
      $ticker_position   = isset( $ticker_options[ 'ticker-position' ] )   ? $ticker_options[ 'ticker-position' ]   : 'normal';
      $dark_mode         = isset( $ticker_options[ 'dark-mode' ] )         ? $ticker_options[ 'dark-mode' ]         : 'no';
      $icon_flag         = isset( $ticker_options[ 'icon-flag' ] )         ? $ticker_options[ 'icon-flag' ]         : 'yes';
      $rtl_direction     = isset( $ticker_options[ 'enable-rtl' ] )        ? $ticker_options[ 'enable-rtl' ]        : 'no';
      $fields            = isset( $ticker_options[ 'collected-fields' ] )  ? $ticker_options[ 'collected-fields' ]  : 'todayCase,cases,todayDeaths,deaths,recovered,critical,active';
      $tooltip_fields    = isset( $ticker_options[ 'tooltip-fields' ] )    ? $ticker_options[ 'tooltip-fields' ]    : 'todayCase,cases,todayDeaths,deaths,recovered,critical,active';
      $cases_text        = isset( $ticker_options[ 'cases-text' ] )        ? $ticker_options[ 'cases-text' ]        : esc_html__( 'Cases', COVTAGS_TEXTDOMAIN );
      $deaths_text       = isset( $ticker_options[ 'deaths-text' ] )       ? $ticker_options[ 'deaths-text' ]       : esc_html__( 'Deaths', COVTAGS_TEXTDOMAIN );
      $today_cases_text  = isset( $ticker_options[ 'today-cases-text' ] )  ? $ticker_options[ 'today-cases-text' ]  : esc_html__( 'Today Cases', COVTAGS_TEXTDOMAIN );
      $today_deaths_text = isset( $ticker_options[ 'today-deaths-text' ] ) ? $ticker_options[ 'today-deaths-text' ] : esc_html__( 'Today Deaths', COVTAGS_TEXTDOMAIN );
      $recovered_text    = isset( $ticker_options[ 'recovered-text' ] )    ? $ticker_options[ 'recovered-text' ]    : esc_html__( 'Recovered', COVTAGS_TEXTDOMAIN );
      $critical_text     = isset( $ticker_options[ 'critical-text' ] )     ? $ticker_options[ 'critical-text' ]     : esc_html__( 'Critical', COVTAGS_TEXTDOMAIN );
      $active_text       = isset( $ticker_options[ 'active-text' ] )       ? $ticker_options[ 'active-text' ]       : esc_html__( 'Active', COVTAGS_TEXTDOMAIN );
      $ticker_speed      = isset( $ticker_options[ 'ticker-speed' ] )      ? $ticker_options[ 'ticker-speed' ]      : 'normal';
      $country           = isset( $ticker_options[ 'country' ] )           ? $ticker_options[ 'country' ]           : null;
      $country_text      = isset( $ticker_options[ 'country-text' ] )      ? $ticker_options[ 'country-text' ]      : null;
      $field             = isset( $ticker_options[ 'field' ] )             ? $ticker_options[ 'field' ]             : 'cases';
      $card_text         = isset( $ticker_options[ 'card-text' ] )         ? $ticker_options[ 'card-text' ]         : null;

      // Getting Ticker Data
      $default_fields   = array(
        'cases',
        'deaths',
        'todayCases',
        'todayDeaths',
        'active',
        'critical',
        'recovered'
      );

      // Filtering data
      if( ! in_array( $ticker_data , array( 'globally', 'country', 'all' ) ) ) {
        $ticker_data = 'all';
      }

      if( ! in_array ( $ticker_speed, array( 'normal', 'medium', 'slow', 'fast' ) ) ) {
        $ticker_speed = 'normal';
      }

      if( ! in_array( $ticker_position , array( 'normal', 'bottom', 'top' ) ) ) {
        $ticker_position = 'normal';
      }

      if( ! in_array( $dark_mode , array( 'yes', 'no' ) ) ) {
        $dark_mode = 'no';
      }

      if( ! in_array( $rtl_direction , array( 'yes', 'no' ) ) ) {
        $rtl_direction = 'no';
      }

      if( $country === 'none' ) {
        $country = null;
      }

      // Prepare Required data
      $fields         = apply_filters( 'covtags_filter_fields', $fields, $default_fields );
      $field          = apply_filters( 'covtags_filter_fields', $field, $default_fields )[ 0 ];
      $tooltip_fields = apply_filters( 'covtags_filter_fields', $tooltip_fields, $default_fields );
      $world_report   = $this->covtags_world_report();
      $countries      = $this->covtags_countries( $country );

      // Default Data
      if( 0 === count( $fields ) ){
          $fields = $default_fields;
      }

      // Descending Data By Choosed Field
      if ( 'all' === $ticker_data && $country === null ){

        usort( $countries, function( $args_a, $args_b ) use( $field ) {
          $args_a = ( array ) $args_a;
          $args_b = ( array ) $args_b;
          return ( $args_a[ $field ] > $args_b[ $field ] ) ? -1 : 1;
        } );

      }

      /* Building Data of html */

      // Classes
      $ticker_classes = sprintf( 'covtags-ticker covtags-ticker-position-%1$s covtags-ticker-speed-%2$s covtags-ticker-data-%3$s', $ticker_position, $ticker_speed, $ticker_data );

      // Ticker in dark mode
      if( 'yes' === $dark_mode ) {
        $ticker_classes .= ' covtags-ticker-dark';
      }

      // Ticker in rtl direction
      if( 'yes' === $rtl_direction && 'normal' === $ticker_position ) {
        $ticker_classes .= ' covtags-ticker-rtl';
      }

      // Casting
      $world_report  = ( array ) $world_report;
      $countries     = ( array ) $countries;


      // Getting Default Texts
      if( $country_text === NULL ) {
        $country_text = ( $country !== NULL ) ? $country : esc_html__( 'Globally', COVTAGS_TEXTDOMAIN );
      }

      if( $card_text === null ){
        $card_text = esc_html__( 'Globally', COVTAGS_TEXTDOMAIN );
      }

      // Ticker Text or Title $country !== NULL
      $ticker_object = ( 'globally' === $ticker_data || $country === NULL ) ? $world_report : $countries;
      $ticker_text   = ( 'country' === $ticker_data ) ? $country_text : $card_text ;
      $ticker_icon   = COVTAGS_SRC . ( ( 'no' === $dark_mode ) ? 'assets/img/logo-2-250x250.png' : 'assets/img/logo-2-250x250-red.png' );
      if( 'country' === $ticker_data && $country !== NULL ){
        $ticker_icon = $countries[ 'countryInfo' ]->flag;
      }

      ob_start();
      ?>

      <!-- Ticker Container -->
      <div class="<?php echo esc_attr( $ticker_classes ); ?>">

        <!-- Ticker Text -->
        <div class="covtags-ticker-text">

          <!-- Ticker Flag Or ICon -->
          <?php if ( 'yes' === $icon_flag ): ?>
            <span class="covtags-ticker-icon" style="background-image:url(<?php echo esc_url( $ticker_icon ); ?>);"></span>
          <?php endif; ?>

          <!-- Ticker Text -->
          <span><?php echo $ticker_text; ?></span>

        </div>

        <!-- Ticker Body -->
        <div class="covtags-ticker-contents">

          <!-- Countries Ticker -->
          <?php if ( 'all' === $ticker_data ): ?>
            <?php foreach( $countries as $ticker_key => $tick_obj ): ?>
              <!-- Items -->
              <div class="covtags-ticker-item covtags-ticker-allcountries">

                  <!-- Item Flag -->
                  <span class="covtags-ticker-flag" style="background-image:url('<?php echo esc_url( $tick_obj->countryInfo->flag ); ?>');"></span>

                  <!-- Key Name -->
                  <span class="covtags-ticker-item-data"><?php echo $tick_obj->country?></span>

                  <!-- Key Value -->
                  <span class="covtags-ticker-item-value"><?php echo apply_filters( 'covtags_number_unit', $tick_obj->{ $field }, 'comma_format' ); ?></span>

                  <!-- Tooltip -->
                  <?php if ( 'normal' !== $ticker_position && 0 !== count( $tooltip_fields ) ): ?>
                    <div class="covtags-ticker-tooltip">
                      <ul>
                        <!-- Tooltip Fields -->
                        <?php foreach( $tooltip_fields as $tool_key => $tooltip_data ): ?>
                          <li>
                            <span><?php echo $covtags_labels[ $tooltip_data ]; ?></span>
                            <span><?php echo apply_filters( 'covtags_number_unit', $country_obj[ $tooltip_data ], 'comma_format' ); ?></span>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <!-- Globally Or Specific Country -->
          <?php if ( ( 'country' === $ticker_data || 'globally' === $ticker_data ) ): ?>

            <?php foreach( $fields as $ticker_key => $tick_obj ): ?>
              <!-- Items -->
              <div class="covtags-ticker-item covtags-ticker-glocountry">

                <!-- Key Name -->
                <span class="covtags-ticker-item-data">
                  <?php
                    switch ( $tick_obj ) {

                      case 'todayCases':
                        echo $today_cases_text;
                        break;

                      case 'todayDeaths':
                        echo $today_deaths_text;
                        break;

                      case 'recovered':
                        echo $recovered_text;
                        break;

                      case 'deaths':
                        echo $deaths_text;
                        break;

                      case 'critical':
                        echo $critical_text;
                        break;

                      case 'active':
                        echo $active_text;
                        break;

                      default:
                        echo $cases_text;
                        break;
                    }
                  ?>
                </span>

                <!-- Key Value -->
                <span class="covtags-ticker-item-value"><?php echo apply_filters( 'covtags_number_unit', $ticker_object[ $tick_obj ], 'comma_format' ); ?></span>

              </div>
            <?php endforeach;?>

          <?php endif;?>

        </div>

      </div>

      <?php

      return ob_get_clean();

    }

  }

}
