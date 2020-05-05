<?php
if( !class_exists( 'CovTags_UI' ) ){

  class CovTags_UI extends CovTags_Request {

    public $stored_options;

    // Load Main Options
    public function __construct( ){

      // Stored Options
      $this->stored_options     = get_option( $this->covtags_options_name );

      // Transient Options
      $this->cache_period = $this->stored_options['options']['cache_period'];

      // Parent Class ( HTTP REQUEST WITH : wp-remote )
      parent::__construct();

    }

    // Table Of Countries
    public function Card_All_Countries ( $options = array() , $fields = array(), $style_class = null, $drak_mode = false ){

      # Basic Givens
      global $covtags_labels, $iso_countries;
      $data_table         = '';
      $hide_row           = ''; # ?
      $tableId            = trim( "covid-table-" . time() . rand( 10, 10000 ) );
      $data_table_classes = apply_filters( 'covtags_datatables_class_styles', '' );

      if( $style_class !== null ){
        $data_table_classes = apply_filters( 'covtags_datatables_class_styles', $style_class );
      }

      $data_table_options = apply_filters( 'covtags_datatables_options', array(
        'show_country_flag' => true ,
        'pagination_type'   => 'serials', /* auto  OR serials OR loadmore */
        'rows_per_page'     => 10
      ));


      if( count( $options ) !== 0 ){
          $data_table_options = apply_filters( 'covtags_datatables_options', $options );
      }

      $data_graph = 'line';
      if( isset( $data_table_options['graph_type'] ) ){
        $data_graph= $data_table_options['graph_type'];
      }


      # Keys should be in lowercase
      $data_fields        = apply_filters( 'covtags_datatables_fields', array(
        'cases'     => ( object ) array( 'color' => 'black', 'is_desc' => true ),
        'deaths'    => ( object ) array( 'color' => 'red' ,  'is_desc' => false ),
        'recovered' => ( object ) array( 'color' => 'green' ,'is_desc' => false ),
        'active'    => ( Object ) array( 'color' => 'teal'  ,'is_desc' => false  )
      ));
      if( count( $fields ) !== 0 ){
          $data_fields = apply_filters( 'covtags_datatables_fields', $fields );
      }
      $cell_size     = ( count( $data_fields ) !== 1 ) ? round( 67 / count( $data_fields ) ): 50;
      $case_one_cell = ( count( $data_fields ) === 1 ) ? 'width:50%;': '';
      $table_height  = ( 'auto' === $data_table_options['pagination_type'] ) ? 'covtags-auto-height': '';

      # Send Request to apis and check if we will get an error or not
      $countries          = $this->Countries();
      $world_wide_report  = $this->World_Report();
      if( 0 === $countries->status_code || 0 === $world_wide_report->status_code ){
        return;
      }

      // Store Successed Message
      $countries         = $countries->data;
      $world_wide_report = $world_wide_report->data;

      ob_start();
      ?>


      <!-- Table Container -->
      <div class="covtags-table-container <?php echo ( true === $drak_mode )? esc_attr( 'datatable-dark-mode' ) :'';?>">

        <!-- Table Body -->
        <div id="<?php echo esc_attr( $tableId ); ?>" data-graph-type='<?php echo esc_attr( $data_graph ); ?>' class="covtags-table covtags-list-countries-<?php echo esc_attr( $data_table_classes );?>">

          <!-- Table Header -->
          <div class="covtags-table-header">

            <!-- Country Title -->
            <div class="covtags-table-cell" style="<?php echo esc_attr( $case_one_cell );?>">
    					<span>
                <?php echo $covtags_labels['world_word']; ?>
              </span>
    					<span class=""></span>
    				</div>

            <!-- Data Fields -->
            <?php foreach ( $data_fields as $field_key => $attributes ): ?>

            <div data-sort-by="data-<?php echo esc_attr( $field_key ); ?>"  class="covtags-table-cell desc-asc-sorted-data covtags-cell-header-data" style="width:<?php echo esc_attr( $cell_size ) . '%'; ?>;">

              <span><?php echo $covtags_labels[$field_key]; ?></span>

              <?php $get_icon = ( $attributes->is_desc ) ? esc_attr( 'fa fa-sort-amount-down-alt' ):esc_attr( 'fa fa-arrows-alt-v' ); ?>

              <?php echo sprintf(
                '<span class=\'%1$s\'></span>',
                $get_icon
              ); ?>

              <?php
                # Descending elements accordign to keys
                if( false !== $attributes->is_desc ){

                  uasort( $countries, function( $a, $b ) use ( $field_key ) {

                    if( !isset( $a->{$field_key} ) || !isset( $b->{$field_key} ) ){
                      return ( $a->country < $b->country );
                    }

                    return ( $a->{$field_key} < $b->{$field_key} );
                  });

                }
              ?>
    				</div>

            <?php endforeach; ?>

          </div>

          <!-- Table Body -->
          <div class="covtags-table-boody <?php echo esc_attr( $table_height )?>">

              <!-- Table Row Container -->
              <?php $row_index = 0; ?>

              <?php foreach ( $countries as $country_key => $country ): ?>

                <?php

                  $counrty_slug = strtolower( str_replace( " ", "", $country->countryInfo->iso3 . time() . '-' . mt_rand( 1, 200 ) ) );
                  $country_name       = $country->country;
                  $row_attributes = "";

                  foreach ( $data_fields as $field_key => $country_fields ) {
                     $row_attributes .= sprintf(
                       'data-%1$s=%2$s ',
                       $field_key,
                       $country->{$field_key}
                     );
                  }
                  if( $row_index >= $data_table_options['rows_per_page'] && ( $data_table_options['pagination_type'] !== 'auto' ) ){
                    $hide_row = 'display:none;';
                  }
                ?>



                <div data-country-title="<?php echo esc_attr( $country_name ); ?>" data-canvas-id="<?php echo esc_attr( $counrty_slug . '-canvas' ); ?>" <?php echo esc_attr( $row_attributes ); ?>  style="<?php echo esc_attr( $hide_row ); ?>" class="cotags-row-container" >

                  <!-- Country Details -->
                  <div class="covtags-table-row">

                    <!-- Country Name -->
                    <div class='covtags-table-cell covtags-country-cell' style="<?php echo esc_attr( $case_one_cell );?>">
                      <?php if ( false !== $data_table_options['show_country_flag'] ): ?>
                        <img class='covtags-img-beside-text' src="<?php echo esc_url( $country->countryInfo->flag ); ?>" width="25">
                      <?php endif; ?>
                      <span class="covtags-set-country-title">
                        <?php echo $country->country; ?>
                      </span>
                    </div>

                    <!-- Api Fields -->
                    <?php foreach ( $data_fields as $field_key => $field_atts ): ?>
                      <div class="covtags-table-cell covtags-numbers" style="width:<?php echo esc_attr( $cell_size ) . '%'; ?>; color:<?php echo esc_attr( $field_atts->color ); ?>;">
                        <?php echo apply_filters( 'covtags_si_system_number_units', $country->{$field_key}, 'format_comma' ); ?>
                      </div>
                    <?php endforeach; ?>

                    <!-- SlideToggle Handler -->
                    <div class="covtags-up-down">
        							<span class="fa fa-chevron-up"></span>
        						</div>

                  </div>

                  <!-- Country Graph Or Chart -->
                  <div class="covtags-graph-container">
                    <div class="graph-main-content">

                      <div class="covtags-historicals-options">
                          <!-- <?php
                            echo sprintf(
                              '<img src=\'%1$s\' width="25">',
                              esc_url( $country->countryInfo->flag )
                            );
                          ?> -->
                          <span class="covtags-timeline-title">
                            <span class='fa fa-chart-line chart-icon-stati'></span>
                            <?php echo $covtags_labels['cases'] . ' ' . $covtags_labels['timeline']; ?>
                          </span>

                          <span class="covtags-info-notice">
                            <?php echo round( ( $country->deaths * 100 ) / $world_wide_report->deaths, 2 ) . '% ' . $covtags_labels['of_world_cases'] ; ?>
                          </span>
                      </div>

                      <div class='covtags-historicals-graph'>
                        <canvas id="<?php echo esc_attr( $counrty_slug ); ?>-canvas" height="90px"></canvas>
                      </div>

                    </div>
                  </div>

                </div>


                <?php
                  $row_index++;
                ?>
              <?php endforeach; ?>
            </div>


          <!-- Table Footer -->
          <div class="covtags-table-footer">
            <!-- Totals -->
            <?php if ( $data_table_options['pagination_type'] === 'auto' ): ?>
              <div class="covtags-get-totals">

                <div class="title covtags-text-left">
                  <?php echo $covtags_labels['world_wide']; ?>
                </div>
                <!-- Api Fields -->
                <?php foreach ( $data_fields as $field_key => $field_atts ): ?>
                  <div class="cells covtags-text-center" style="width:<?php echo esc_attr( $cell_size ) . '%'; ?>; color:<?php echo esc_attr( $field_atts->color ); ?>;">
                    <?php echo apply_filters( 'covtags_si_system_number_units', $world_wide_report->{$field_key}, 'format_comma' ); ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <?php if ( $data_table_options['pagination_type'] === 'serials' ): ?>
              <span class="covtags-entries">
                <?php
                  echo sprintf(
                    esc_html__( 'Showing %1$s Countries', COVTAGS_TEXTDOMAIN  ),
                    count( $countries )
                  );
                ?>
              </span>

              <span data-go-type="prev" class='covtags-prev-next'>
                <?php echo $covtags_labels['prev']; ?>
              </span>
              <ul class="covtags-pagination-data"></ul>
              <span data-go-type="next" class='covtags-prev-next'>
                <?php echo $covtags_labels['next']; ?>
              </span>
            <?php endif; ?>


            <?php if ( $data_table_options['pagination_type'] === 'loadmore' ): ?>
              <span class="covtags-load-more">
                <?php echo $covtags_labels['load_more']; ?>
              </span>
            <?php endif; ?>

          </div>

        </div>
      </div>


      <script type="text/javascript">

        window.CovTagsDataTable({
          id              : <?php echo wp_json_encode( $tableId ) ; ?>,
          rowsPerPage     : <?php echo wp_json_encode( $data_table_options['rows_per_page'] ) ; ?>,
          paginationType  : <?php echo wp_json_encode( $data_table_options['pagination_type'] ) ; ?>,
          isDarkMode      : <?php echo wp_json_encode( $drak_mode ); ?>,
          historyData     : <?php echo wp_json_encode( $this->Historical() ); ?>
        });

      </script>

      <?php
      return ob_get_clean();
    }

    // Get World Details According to Field
    public function Card_Request_Chart_For ( $field_key = 'cases', $country = null,  $graph_options = array (), $with_chart = true , $days_per_chunk = 7 , $is_last_row = true ){

      global $months_names, $covtags_labels;
      $data_array           = array();
      $label_array          = array();
      $options              = array();
      $is_percentage        = false;
      $options['useGrids']  = true;

      # Request By Field Key
      if( 'cases' === $field_key || 'deaths' === $field_key || 'recovered' === $field_key ){

        # Data of history
        $history = $this->Historical_Sheet( $field_key, $country, $days_per_chunk, $is_last_row );

        # Case Last row has false value
        if( false === $is_last_row ){
          $merge_history = array() ;
          foreach ($history as $his_key => $array_target) {
            // we couldn't able to use wp_parse_args here its inaccurate
            $merge_history = array_merge_recursive ( $merge_history , $array_target );
          }
          $history = $merge_history;
        }

        # 7 Loops only not much case last row in option
        foreach ( $history as $month_date => $counts ) {

          # Exploade Current String to an array
          $exploded     = explode( '_', $month_date );

          # Date for this labels
          $target_label  = $months_names[ trim( $exploded[1] ) ] . ' ' . trim( $exploded[0] );

          # Store Labels
          array_push( $label_array, $target_label );
          array_push( $data_array, $counts );
        }

      }

      # Request By Percentage
      if( $field_key === 'percentage-report' ){

        # Request Data then format it
        $report_data = ( NULL === $country ) ? $this->World_Report() : $this->Countries( $country );

        # Trying to get an error
        if( 0 === $report_data->status_code || 200 !== $report_data->response_code ){
          return;
        }

        # Response Data
        $report_data = $report_data->data;

        # Some Givens
        $cases      = $report_data->cases;
        $active     = $report_data->active;
        $critical   = $report_data->critical;
        $deaths     = $report_data->deaths;
        $recovered  = $report_data->recovered;
        $mild       =  ( $active - $critical );

        # Needed Calculations
        $recovered_percentage = round( ( $recovered * 100 ) / $cases, 2 );
        $deaths_percentage    = round( ( $deaths * 100 ) / $cases, 2 );
        $mild_percentage      = round( ( $mild * 100 ) / $cases, 2 );
        $critical_percentage  = round( ( $critical * 100 ) / $cases, 2 );

        # Percentage is required
        $is_percentage = true ;

        # Build Label array
        $label_array = array(
          $covtags_labels['deaths'],
          $covtags_labels['mild_condition'],
          $covtags_labels['critical'],
          $covtags_labels['recovered']
        );

        # Build percentages for data
        $data_array = array(
          $deaths_percentage,
          $mild_percentage,
          $critical_percentage,
          $recovered_percentage,
          100
        );
      }

      # Build Graph Options by default
      if( $with_chart !== false ){

        $style_class   = ( isset( $graph_options['style_class'] ) )   ? $graph_options['style_class']   : '';
        $canvas_height = ( isset( $graph_options['canvas_height'] ) ) ? $graph_options['canvas_height'] : 450;

        # Default Options ( Data and labels )
        $options['is_percentage'] = $is_percentage;
        $options['labels']        = $label_array;
        $options['dataProviders'] = [array(
          'data' => $data_array
        )];


        # Store Chart Styles
        if( isset( $graph_options['graph_styles'] ) ){
          $aprsed_args =  wp_parse_args( $graph_options['graph_styles'] ,  $options['dataProviders'][0]  );
          $options['dataProviders'] = [$aprsed_args];
        }

        # Chart Type
        if( isset( $graph_options['graph_type'] ) ){
          $options['chartType'] = $graph_options['graph_type'];
        }

        # GridLine Options
        if( isset( $graph_options['gridline_options'] ) ){

          # Grid Vertical options => Y
          if( isset( $graph_options['gridline_options']['vertical'] ) ){

            if( isset( $graph_options['gridline_options']['vertical']['grid_borders'] ) ){
              $options['yGridlineBorder'] = $graph_options['gridline_options']['vertical']['grid_borders'];
            }

            if( isset( $graph_options['gridline_options']['vertical']['grid_display'] ) ){
              $options['yGridlineDisplay'] = $graph_options['gridline_options']['vertical']['grid_display'];
            }

            if( isset( $graph_options['gridline_options']['vertical']['ticks_limit'] ) ){
              $options['yMaxTicksLimit'] = $graph_options['gridline_options']['vertical']['ticks_limit'];
            }

            if( isset( $graph_options['gridline_options']['vertical']['ticks_padding'] ) ){
              $options['yTicksPadding'] = $graph_options['gridline_options']['vertical']['ticks_padding'];
            }

          }

          # Grid horizontal Options => X
          if( isset( $graph_options['gridline_options']['horizontal'] ) ){

            if( isset( $graph_options['gridline_options']['horizontal']['grid_borders'] ) ){
              $options['xGridlineBorder'] = $graph_options['gridline_options']['horizontal']['grid_borders'];
            }

            if( isset( $graph_options['gridline_options']['horizontal']['grid_display'] ) ){
              $options['xGridlineDisplay'] = $graph_options['gridline_options']['horizontal']['grid_display'];
            }

            if( isset( $graph_options['gridline_options']['horizontal']['ticks_limit'] ) ){
              $options['xMaxTicksLimit'] = $graph_options['gridline_options']['horizontal']['ticks_limit'];
            }

            if( isset( $graph_options['gridline_options']['horizontal']['ticks_padding'] ) ){
              $options['xTicksPadding'] = $graph_options['gridline_options']['horizontal']['ticks_padding'];
            }

          }

        }

        # Check in array
        $use_grid_status = array ( 'pie' );
        if( in_array( $graph_options['graph_type'], $use_grid_status ) ){
          $options['useGrids'] = false;
        }



        return $this->chart_js_canvas( $style_class , $canvas_height , $options );
      }

      # Return Array By Default
      return array(
        'labels'  => $label_array ,
        'data'    => $data_array
      );
    }

    // ChartJS Canvase
    public function chart_js_canvas( $canvas_container_class = null , $canvas_height = '450', $p_options = array() ){

      $canvas_id      = 'covtags-canvas-' . time() . mt_rand( 1, 1000 );
      $is_percentage  = isset ( $p_options['is_percentage'] )           ? $p_options['is_percentage'] : false ;
      $options        = apply_filters( 'covtags_main_chart_options', array(
        'labels'            =>  isset( $p_options['labels'] )           ? $p_options['labels']            : array(),
        'dataProviders'     =>  isset( $p_options['dataProviders'] )    ? $p_options['dataProviders']     : array(),
        'chartType'         =>  isset( $p_options['chartType'] )        ? $p_options['chartType']         : 'line',
        'xGridlineBorder'   =>  isset( $p_options['xGridlineBorder'] )  ? $p_options['xGridlineBorder']   : false,
        'xGridlineDisplay'  =>  isset( $p_options['xGridlineDisplay'] ) ? $p_options['xGridlineDisplay']  : false,
        'xMaxTicksLimit'    =>  isset( $p_options['xMaxTicksLimit'] )   ? $p_options['xMaxTicksLimit']    : 8,
        'xTicksPadding'     =>  isset( $p_options['xTicksPadding'] )    ? $p_options['xTicksPadding']     : 0,
        'yGridlineBorder'   =>  isset( $p_options['yGridlineBorder'] )  ? $p_options['yGridlineBorder']   : false,
        'yGridlineDisplay'  =>  isset( $p_options['yGridlineDisplay'] ) ? $p_options['yGridlineDisplay']  : false,
        'yMaxTicksLimit'    =>  isset( $p_options['yMaxTicksLimit'] )   ? $p_options['yMaxTicksLimit']    : 8,
        'yTicksPadding'     =>  isset( $p_options['yTicksPadding'] )    ? $p_options['yTicksPadding']     : 0,
        'useGrids'          =>  isset( $p_options['useGrids'] )         ? $p_options['useGrids']          : false,
      ));

      ob_start();
      ?>

      <div class="covid-chart-container <?php echo esc_attr( $canvas_container_class ); ?>">
        <canvas id="<?php echo esc_attr( $canvas_id ); ?>" class="covtags-canvas-element" height="<?php echo esc_attr( $canvas_height ); ?>"></canvas>
      </div>

      <script type="text/javascript">
        window.CovTagsChartJs({
          id           : <?php echo wp_json_encode( $canvas_id ); ?>,
          options      : <?php echo wp_json_encode( $options ); ?>,
          isPercentage : <?php echo wp_json_encode( $is_percentage );?>
        });
      </script>

      <?php
      return ob_get_clean();
    }

    // Card Cases Status ( Active - Closed ) Cases with Percentage Values
    public function Close_Active_Cases( $country = null ){

      # Target Card
      $card_cases = ( Null !== $country ) ?  $this->Countries( $country ) : $this->World_Report();

      # Trying to get an error
      if( 0 === $card_cases->status_code ){
        return;
      }

      # Case Success Data
      $card_cases     = $card_cases->data;
      $total_cases    = $card_cases->cases;

      # Active Cases
      $active_cases   = $card_cases->active;
      $critical_cases = $card_cases->critical;
      $mild_condition = round( $active_cases - $critical_cases );

      # Closed Cases
      $closed_cases   = round( $total_cases -  $active_cases );
      $deaths         = $card_cases->deaths;
      $recovered      = $card_cases->recovered;

      # Calculate Percentage ( Active )
      $critical_cases_percentage  = round( ( $critical_cases * 100 ) / $active_cases );
      $mild_condition_percentage  = round( ( $mild_condition * 100 ) / $active_cases );

      # Calculate Percentage ( Closed )
      $deaths_percentage          = round( ( $deaths * 100 ) / $closed_cases );
      $recovered_percentage       = round( ( $recovered * 100 ) / $closed_cases );



      return ( object ) array(
        'cases'        => $total_cases,
        'flag'         => ( $country !== null ) ? $card_cases->countryInfo->flag : '',
        'active_cases' => ( object ) array(
            'active'              => $active_cases,
            'critical'            => $critical_cases,
            'critical_percentage' => $critical_cases_percentage,
            'mild'                => $mild_condition,
            'mild_percentage'     => $mild_condition_percentage,


            'negative_data'       => 'critical',
            'positive_data'       => 'mild',
            'from_basic_data'     => 'active'
        ),
        'closed_cases' => ( object ) array(
            'closed'              => $closed_cases,
            'deaths'              => $deaths,
            'deaths_percentage'   => $deaths_percentage,
            'recovered'           => $recovered,
            'recovered_percentage'=> $recovered_percentage,

            'negative_data'       => 'deaths',
            'positive_data'       => 'recovered',
            'from_basic_data'     => 'closed'
        )
      );
    }

    // Card Statistics ( World and country )
    public function Card_live_statistics ( $attributes ){

      global $covtags_labels;

      # Cols : Sanitize Cols Data
      $columns = ( int) $attributes['cols'];
      if( $columns > 6 ){
        $columns = 6 ;
      }

      # Country : Sanitize for Country name and iso3 to be an _id
      $countries_list = ( array ) $this->stored_options['list_of_countries'];
      $country = null;
      if( $attributes['country'] !== null ){

        # Country is already exists in atts
        $country_title = strtolower( $attributes['country'] );

        # Check if current country is already exists in out database option
        $get_country = array_filter( $countries_list , function( $country ) use ( $country_title ){
          if( ( strtolower( $country['country'] ) ===  $country_title ) || ( strtolower( $country['iso3'] ) ===  $country_title ) ){
            return $country;
          }
        });

        # Country is already exists
        if( 0 !== count( $get_country ) ){
          $country = array_values( $get_country )[0]['_id'];
        }
      }

      # Dark mode is enabled
      $is_dark_mode = ( 'yes' === $attributes['dark_mode'] ) ? true : false;

      # Field : Sanitize Fields
      $default_fields  = array(
        'cases',
        'todayCases',
        'deaths',
        'todayDeaths',
        'recovered',
        'active',
        'critical'
      );
      if( $attributes['fields'] === '' ){
        $attributes['fields'] = 'cases,deaths,recovered';
      }
      $field_atts      = explode( ',', $attributes['fields'] );
      $required_fields = array_map( function( $field ) use ( $default_fields ) {

        $build_field = $field;
        $strip_space = explode( ' ', $field );

        # Fields has no issue
        if( 0 === count( $strip_space ) ){
          $build_field = $field;
        }

        # Field key has spaces issue
        if( 0 !== count( $strip_space ) ) {
          $strip_this_spaces = '';

          # Sanitize and Strip Spaces from field key
          for ( $i=0; $i < count( $strip_space ); $i++) {
             if( $i !== 0 ){
               $strip_this_spaces .= trim( ucfirst( $strip_space[$i] ) );
             }else {
               $strip_this_spaces .= trim( $strip_space[$i] );
             }
          }
          $build_field = $strip_this_spaces;
        }

        # Field key is already exists with our array
        if( in_array( $build_field, $default_fields ) ){
          return $build_field;
        }
      }, $field_atts ) ;
      $required_fields = array_filter( $required_fields );
      $required_fields = array_values( $required_fields );

      # Layout : Sanitize layout string
      $layout = 'flat';
      if( strtolower( $attributes['layout'] ) === 'flat' ){
        $layout = 'flat';
      }
      if( strtolower( $attributes['layout'] ) === 'table'  ){
        $layout = 'table';
      }

      # Styles : Sanitize Styles
      $style  = $attributes['style'];
      $styles = array( 'style-1', 'style-2', 'style-3' );
      if( !in_array( $style, $styles ) ){
        $style = 'style-1';
      }

      # Flag Or Icon : Sanitize Flag Or Icon Option
      $flag_use = ( strtolower ( $attributes['icon_flag'] ) === 'yes' )? true : false ;

      # Request An Api
      $report_data = ( $country !== NULL ) ? $this->Countries( $country ) : $this->World_Report();

      # Trying to get an error
      if( 0 === $report_data->status_code ) {
        return ;
      }

      # Align Center
      // if( $attributes['align_text'] === 'yes' ){
      //   $attributes['align_text'] = 'center';
      // }else {
      //   $attributes['align_text'] = 'left';
      // }

      $report_data = ( object ) $report_data->data;
      ob_start();
      ?>
      <!-- Card Container -->
      <div class="covtags-container <?php echo ( false !== $is_dark_mode ) ? esc_attr( 'statisitcs-card-dark-mode' ): ''?> statistic-<?php echo esc_attr( $style ); ?>" style="padding:<?php echo esc_attr( $attributes['inner-spacing'] );?>px; border-radius:<?php echo esc_attr( $attributes['rounded'] ); ?>px;">

        <div class="covtags-widget-title" style="text-align:<?php echo esc_attr( $attributes['align_text'] ); ?>;">
           <?php if( isset( $report_data->countryInfo ) && $flag_use === true ) : ?>
             <img src="<?php echo esc_url( $report_data->countryInfo->flag ); ?>" width="20" alt="">
           <?php endif; ?>

           <?php if( !isset( $report_data->countryInfo ) && $flag_use === true ) : ?>
             <span class="fas fa-globe"></span>
           <?php endif; ?>
          <span>
            <?php
              echo ucfirst( $attributes['title'] );
            ?>
          </span>
        </div>



        <ul class="covtags-list-cases covtags-<?php echo esc_attr( $layout ); ?> covtags-<?php echo esc_attr( $layout ); ?>-<?php echo esc_attr( $columns ); ?>" style='justify-content:<?php echo esc_attr( $attributes["align_text"] ); ?>'>

          <?php foreach ( $required_fields as $field_key => $field ): ?>
            <li>
              <div class="covtags-col-container">
                <div class="covtags-title">
                    <?php echo $covtags_labels[$field]; ?>
                </div>
                <div class="covtags-number">
                    <?php echo apply_filters( 'covtags_si_system_number_units', $report_data->{$field} , 'format_comma');?>
                </div>
              </div>
            </li>
          <?php endforeach; ?>

        </ul>
      </div>
      <?php
      return ob_get_clean();
    }

    // List All Countries in table with some options
    public function Table_List_all_countries ( $attributes ){

      # Field : Sanitize Fields
      $default_fields  = array(
        'cases',
        'todayCases',
        'deaths',
        'todayDeaths',
        'recovered',
        'active',
        'critical'
      );
      $field_atts      = explode( ',', $attributes['fields'] );
      # Dark mode is enabled
      $required_fields = array_map( function( $field ) use ( $default_fields ) {

        $build_field = $field;
        $strip_space = explode( ' ', $field );

        # Fields has no issue
        if( 0 === count( $strip_space ) ){
          $build_field = $field;
        }

        # Field key has spaces issue
        if( 0 !== count( $strip_space ) ) {
          $strip_this_spaces = '';

          # Sanitize and Strip Spaces from field key
          for ( $i=0; $i < count( $strip_space ); $i++) {
             if( $i !== 0 ){
               $strip_this_spaces .= trim( ucfirst( $strip_space[$i] ) );
             }else {
               $strip_this_spaces .= trim( $strip_space[$i] );
             }
          }
          $build_field = $strip_this_spaces;
        }



        # Field key is already exists with our array
        if( in_array( $build_field, $default_fields ) ){
          return $build_field;
        }
      }, $field_atts ) ;
      $required_fields = array_filter( $required_fields );
      $required_fields = array_values( $required_fields );

      # Is Dark Mode
      $is_dark_mode = ( 'yes' === $attributes['dark_mode'] ) ? true : false;

      # Rows : Rows Per Page
      $row_per_page = ( int ) $attributes['rows_per_page'];
      if( 0 === $row_per_page ){
        $row_per_page = 10;
      }

      # Graph Or Chart Type
      $graph_type = ( $attributes['graph_type'] !== 'line' && $attributes['graph_type'] !== 'bar' ) ? 'line': $attributes['graph_type'];

      # Pagination Type
      $paging = ( $attributes['paging_type'] !== 'serials' && $attributes['paging_type'] !== 'loadmore'  ) ? 'serials': $attributes['paging_type'];


      # Styles : Sanitize Styles
      $style  = $attributes['style'];
      $styles = array( 'style-1', 'style-2', 'style-3' );
      if( !in_array( $style, $styles ) ){
        $style = 'style-1';
      }

      # Flag Or Icon : Sanitize Flag Or Icon Option
      $flag_use = ( strtolower ( $attributes['icon_flag'] ) === 'yes' )? true : false ;

      # Descending : Option
      $is_desc = ( !in_array( $attributes['desc_by'], $default_fields ) ) ? 'cases': $attributes['desc_by'];

      # Font Colors
      $colors = explode( ',', $attributes['field_colors'] );
      if( count( $colors ) !== count( $required_fields ) ){
        $colors  = array ();

        foreach ($required_fields as $key => $value) {
          $colors[count($colors)] = ( $is_dark_mode === true )? '#fff': '#999';
        }
      }

      # Table : Options
      $tbl_options = array(
        'show_country_flag' => $flag_use,
        'pagination_type'   => $paging ,
        'rows_per_page'     => $row_per_page,
        'graph_type'        => $graph_type
      );
      $tble_listed_fields = array ();

      # 2nd Options : graph and rows
      for ($i=0; $i < count( $required_fields ); $i++) {
        $tble_listed_fields[$required_fields[$i]] = ( object ) array (
          'color' => $colors[$i], 'is_desc' => ( $is_desc === $required_fields[$i] )
        );
      }

      ob_start();
      ?>


      <?php
      echo $this->Card_All_Countries($tbl_options, $tble_listed_fields, $style, $is_dark_mode );
      return ob_get_clean();
    }

    // Render map
    public function covtags_render_map ( $attributes ){
      global $covtags_labels ;

      $world_reports = $this->World_Report();
      if( 0 === $world_reports->status_code ){
        return;
      }

      $world_reports = $world_reports->data;

      $id_map = "covtags-map-" . time() . mt_rand( 1, 1000 );
      $id_canvas = "covtags-map-" . time() . mt_rand( 1, 1000 );

      # Is Dark Mode
      $is_dark_mode = ( 'yes' === $attributes['dark_mode'] ) ? true : false;

      ob_start();
      ?>
      <div class="svg-map-container <?php echo ( $is_dark_mode === true )? 'map-in-dark-mode' :''; ?>" id="<?php echo esc_attr( $id_map ); ?>">
        <!-- World : Updated 22 min ago-->
        <div class="convtags-map-statistic-container">
          <div class="covtags-map-title">
            <?php echo $covtags_labels['map_title']; ?>
          </div>
          <div class="total-confimred-data">
            <h2>
              <?php
                echo apply_filters( 'covtags_si_system_number_units', $world_reports->cases, 'format_comma' );
              ?>
            </h2>
          </div>
          <div class="covtags-map-data-con">

            <div class="covtags-map-country covtags-cell-header-data">
                <i class="fas fa-globe"></i>
                <span><?php esc_html_e( 'World Wide', COVTAGS_TEXTDOMAIN ); ?></span>
            </div>

            <div class="covtags-map-data-block">
              <ul class="covtags-list-cases covtags-table covtags-table-4" style="justify-content:center">
                  <li>
                    <div class="covtags-col-container">
                      <div class="covtags-title">
                          <?php echo $covtags_labels['deaths']; ?>
                        </div>
                      <div class="covtags-number">
                          <?php
                            echo apply_filters( 'covtags_si_system_number_units', $world_reports->deaths, 'format_comma' );
                          ?>
                         </div>
                    </div>
                  </li>
                  <li>
                    <div class="covtags-col-container">
                      <div class="covtags-title">
                          <?php echo $covtags_labels['recovered']; ?>
                      </div>
                      <div class="covtags-number">
                        <?php
                          echo apply_filters( 'covtags_si_system_number_units', $world_reports->recovered, 'format_comma' );
                        ?>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="covtags-col-container">
                      <div class="covtags-title">
                          <?php echo $covtags_labels['critical']; ?>
                      </div>
                      <div class="covtags-number">
                        <?php
                          echo apply_filters( 'covtags_si_system_number_units', $world_reports->critical, 'format_comma' );
                        ?>
                      </div>
                    </div>
                  </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- Map In SVG -->
        <?php require_once COVTAGS_DIR . 'assets/img/render-map.php'; ?>

        <!-- tooltips -->
        <div class="covtags-map-tooltip">
          <div class="countryInfo">
            <img alt='Country Flag' src="https://raw.githubusercontent.com/NovelCOVID/API/master/assets/flags/us.png" width="20" />
            <span></span>
          </div>
          <div class="covtags-rows covtags-todaycases-data">
            <span></span> : <span></span>
          </div>
          <div class="covtags-rows covtags-cases-data">
            <span></span> : <span></span>
          </div>
          <div class="covtags-rows covtags-deaths-data">
            <span></span> : <span></span>
          </div>
          <div class="covtags-rows covtags-recovered-data">
            <span></span> : <span></span>
          </div>
        </div>
      </div>

      <script type="text/javascript">
          var obt_map = {
            _id             : <?php echo wp_json_encode( $id_map ); ?>,
            options         : <?php echo wp_json_encode( $attributes ); ?>,
            all_countries   : <?php echo wp_json_encode( $this->Countries() ); ?>
          }
          window.covtagsRenderMap( obt_map );
      </script>
      <?php
      return ob_get_clean();
    }

    // List Cases Status with graph
    public function Show_Cases_Status_with_graph ( $attributes ){

        #  Globals
        global $covtags_labels;

        # Country : Sanitize for Country name and iso3 to be an _id
        $countries_list = ( array ) $this->stored_options['list_of_countries'];
        $country = null;
        if( $attributes['country'] !== null ){

          # Country is already exists in atts
          $country_title = strtolower( $attributes['country'] );

          # Check if current country is already exists in out database option
          $get_country = array_filter( $countries_list , function( $country ) use ( $country_title ){
            if( ( strtolower( $country['country'] ) ===  $country_title ) || ( strtolower( $country['iso3'] ) ===  $country_title ) ){
              return $country;
            }
          });

          # Country is already exists
          if( 0 !== count( $get_country ) ){
            $country = array_values( $get_country )[0]['_id'];
          }
        }

        # Dark mode is enabled
        $is_dark_mode = ( 'yes' === $attributes['dark_mode'] ) ? true : false;

        # Request Api
        $status_data = $this->Close_Active_Cases( $country );

        # List Data of status type
        $status = ( 'closed' === $attributes['status_type'] )? $status_data->closed_cases : $status_data->active_cases;


        # Working with target data
        $negative   = $status->{$status->negative_data};
        $positive   = $status->{$status->positive_data};
        $basic_data = $status->{$status->from_basic_data};

        # Working with Percentage data
        $negative_per   = $status->{$status->negative_data . '_percentage'};
        $positive_per   = $status->{$status->positive_data . '_percentage'};
        $basic_data_per = 100;

        # Getting Labels
        $negative_label     = $covtags_labels[$status->negative_data];
        $positive_label     = $covtags_labels[$status->positive_data];
        $basic_data_label   = $covtags_labels[$status->from_basic_data];

        # Hide Title
        $hide_title = ( 'yes' === $attributes['hide_title'] ) ? true : false ;

        # Graph : no - line - bar - doughnut - pie - polarArea
        $graph = 'no';
        $array_graph_type = array( 'line', 'bar', 'doughnut', 'pie', 'polararea' );
        if( in_array( strtolower( $attributes['use_graph_with'] ), $array_graph_type ) ){
          $graph = ( 'polararea' === strtolower( $attributes['use_graph_with'] ) )? 'polarArea' : $attributes['use_graph_with'];
        }


        # Styles : Sanitize Styles
        $style  = $attributes['style'];
        $styles = array( 'style-1', 'style-2', 'style-3' );
        if( !in_array( $style, $styles ) ){
          $style = 'style-1';
        }

        # Graph : height
        $graph_height = 120 ;

        # Percentage : Disable or enable values
        $is_per_disable = ( 'no' === $attributes['show_percentage'] ) ? false : true;

        # Flag Or Icon : Sanitize Flag Or Icon Option
        $flag_use = ( strtolower ( $attributes['icon_flag'] ) === 'yes' )? true : false ;

        # Colors : Graph and point colors
        $colors = array( "#F79F1F", "#833471", "#1289A7" );
        if( $attributes['colors'] !== null ){
          $exploded_colors = explode( ',', $attributes['colors'] );

          if( count( $exploded_colors ) === 3  ){
            $colors = array();
            foreach ( $exploded_colors  as $key => $hex ) {
              $colors[] = $hex;
            }
          }

        }

        # Build JS Data
        $graph_options = array (
            'graphType'         => ( false !== $graph ) ? $graph: 'line',
            'dataGeneral'       => array (
              $negative ,
              $positive ,
              $basic_data
            ),
            'dataPercentage'    => array (
              $negative_per ,
              $positive_per ,
              $basic_data_per
            ),
            'dataLabels'        => array(
              $negative_label,
              $positive_label,
              $basic_data_label
            ),
            'labelsColors'      => array (
              'negative_data' => array (
                'label' => $negative_label,
                'color' => $colors[0]
              ),
              'positive_data'=> array (
                'label' => $positive_label,
                'color' => $colors[1]
              ),
              'basic_data'=> array (
                'label' => $basic_data_label,
                'color' => $colors[2]
              ))
        );

        $pid_canvas = 'closed-active-cases' . time() . mt_rand( 10, 1000 ) ;
        ob_start();
        ?>
        <div class="cases-status-<?php echo esc_attr( $style ); ?> <?php echo ( $is_dark_mode === true )? esc_attr( 'cases-status-dark-mode' ):''; ?>">
            <?php
              # Text
              if ( true !== $hide_title ){
                echo sprintf(
                  '<div class=\'covtags-widget-title\'><span>%1$s</span></div>',
                   $attributes['title']
                );
              }
            ?>
            <!-- Closed Or Active data -->
            <div class="covtags-container">
              <?php if ( 'no' !== $graph ) {
                echo sprintf(
                  '<div class="covtags-use-graph covtags-jsgraph-container"><canvas id=\'%1$s\' height=\'%2$s\'></canvas></div>',
                  esc_attr( $pid_canvas ),
                  esc_attr( $graph_height )
                );
              }
              ?>
              <div class="covtags-percentage-data">
                <ul>
                  <li>
                      <?php
                      # Numerical Values
                        echo sprintf(
                              '<span class=\'label-number\'><i style="background:%1$s;" class="label-color"></i>%2$s %3$s%%</span>',
                              esc_attr( $colors[0] ),
                              esc_attr( apply_filters( 'covtags_si_system_number_units' , $negative , 'format_comma' ) ),
                              ( false !== $is_per_disable ) ? esc_attr( '(' . $negative_per .')'):''
                        );
                      ?>
                      <span class="label-title"><?php echo $negative_label; ?></span>
                  </li>
                  <li>
                    <?php
                    # Numerical Values
                      echo sprintf(
                            '<span class=\'label-number\'><i style="background:%1$s;" class="label-color"></i>%2$s %3$s%%</span>',
                            esc_attr( $colors[1] ),
                            esc_attr( apply_filters( 'covtags_si_system_number_units' , $positive , 'format_comma' ) ),
                            ( false !== $is_per_disable ) ? esc_attr( '(' . $positive_per .')'):''
                      );
                    ?>
                    <span class="label-title"><?php echo $positive_label; ?></span>
                  </li>
                </ul>
                <?php
                  $targIcon = '' ;
                  $numericalValue = apply_filters( 'covtags_si_system_number_units' ,$basic_data, 'format_comma');
                  if ( $country !== null && $flag_use === true ){
                    $targIcon = sprintf(
                        '<img class="covtags-img-flg" src="%1$s" width="30" alt="">',
                        esc_url( $status_data->flag )
                    );
                  }
                  if( $country === null && $flag_use === true ) {
                    $targIcon = '<span class="fas fa-globe"></span>';
                  }
                  echo sprintf(
                    '<h2>%1$s<span>%2$s</span><span class=\'covtags-label-data\'>%3$s</span></h2>',
                    $targIcon,
                    $numericalValue,
                    $basic_data_label
                  );
                ?>
              </div>
            </div>
          </div>
          <?php
            $unique_id_graph = wp_json_encode( $pid_canvas );
            $graph_options   = wp_json_encode( $graph_options );
          ?>
          <?php if ( 'no' !== $graph ): ?>
            <script type="text/javascript">
              window.load_graph_for_active_closed_cases({
                _id     : <?php echo $unique_id_graph ;?>,
                options : <?php echo $graph_options ;?>
              });
            </script>
          <?php endif; ?>
          <?php
          return ob_get_clean();

      }

    //
    public function Card_All_countries_tricker ( $attributes ){
      global $covtags_labels;
      $attributes   = array_change_key_case( ( array ) $attributes , CASE_LOWER );
      $tricker_id   = 'covtags-tricker-data-' . time() . '-' . mt_rand(1, 1000);

      # Dark mode is enabled
      $is_dark_mode = ( 'yes' === $attributes['dark_mode'] ) ? true : false;

      $countries = $this->Countries();
      if( $countries->status_code === 0 ){
        return;
      }

      # Inner Spacing
      $inner_spacing = $attributes['inner-spacing'];
      $padding = '';
      $explodedSpacing = explode( ' ', $inner_spacing );
      for ( $i =0 ; $i < count( $explodedSpacing ); $i++ ) {

        $padding .= ( int ) $explodedSpacing[$i] ;

        if( $i !== (count( $explodedSpacing ) - 1) ) {
          $padding .= ' ';
        }
      }
      # Field : Sanitize Fields
      $default_fields  = array(
        'cases',
        'todayCases',
        'deaths',
        'todayDeaths',
        'recovered',
        'active',
        'critical'
      );
      $field_atts      = explode( ',', $attributes['field'] );
      $required_fields = array_map( function( $field ) use ( $default_fields ) {

        $build_field = $field;
        $strip_space = explode( ' ', $field );

        # Fields has no issue
        if( 0 === count( $strip_space ) ){
          $build_field = $field;
        }

        # Field key has spaces issue
        if( 0 !== count( $strip_space ) ) {
          $strip_this_spaces = '';

          # Sanitize and Strip Spaces from field key
          for ( $i=0; $i < count( $strip_space ); $i++) {
             if( $i !== 0 ){
               $strip_this_spaces .= trim( ucfirst( $strip_space[$i] ) );
             }else {
               $strip_this_spaces .= trim( $strip_space[$i] );
             }
          }
          $build_field = $strip_this_spaces;
        }

        # Field key is already exists with our array
        if( in_array( $build_field, $default_fields ) ){
          return $build_field;
        }
      }, $field_atts ) ;
      $required_fields = array_filter( $required_fields );
      $required_fields = array_values( $required_fields );
      $field_key = $attributes['desc_by'];
      $tricker_speed = $attributes['tricker_speed'];
      if( $tricker_speed < 0 ){
        $tricker_speed = 0;
      }

      $countries = $countries->data;

      uasort( $countries, function( $a, $b ) use ( $field_key ) {

        if( !isset( $a->{$field_key} ) || !isset( $b->{$field_key} ) ){
          return ( $a->country < $b->country );
        }

        return ( $a->{$field_key} < $b->{$field_key} );
      });

      ob_start();
      ?>
      <div class="covtags-card-statis <?php echo ( $is_dark_mode === true )? 'tricker-drak-mode': '';?>">
        <!-- <div class="covtags-main-img-icon">
          <span class="fas fa-virus"></span>
        </div> -->
        <div class="covtags-slideshow-card"  style="<?php echo 'padding:' . esc_attr( $padding ) . 'px;'; ?>">
          <?php if ( NULL !== $attributes['title'] && '' !== $attributes['title'] ): ?>
            <h4>
              <?php echo $attributes['title']; ?>
            </h4>
          <?php endif; ?>
            <div class="covidvirus-alert-message">
              <ul id="<?php echo esc_attr( $tricker_id ); ?>" class="tricker-cases">
                <?php foreach ( $countries as $country_key => $country ): ?>
                <li>
                  <img width="40px;" src="<?php echo esc_url( $country->countryInfo->flag ); ?>" alt="">
                  <div class="covtags-countryInfo">
                    <span class="cov-countryname"><?php echo $country->country;?></span>
                    <span class="cov-cases-number"> <b><?php echo apply_filters( 'covtags_si_system_number_units', $country->{$field_atts[0]}, 'format_comma' );?></b></span>
                  </div>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
        </div>
      </div>
      <script type="text/javascript">
        window.covtagsTrickerCoronaData({
          _id : <?php echo wp_json_encode( $tricker_id ); ?>,
          speed : <?php echo wp_json_encode( $tricker_speed ); ?>
        });
      </script>
      <?php
      return ob_get_clean();
    }

  }
}
