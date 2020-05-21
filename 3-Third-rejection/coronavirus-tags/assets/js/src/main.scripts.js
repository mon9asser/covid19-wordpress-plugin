(function( $ ){
  'use strict'

  /* Split Arrays to pieces */
  Object.defineProperty( Array.prototype, 'chunk', {
      value: function( chunkSize ) {
          var that = this;
          return Array( Math.ceil( that.length / chunkSize) ).fill( ).map( function( _, i ){
              return that.slice( i * chunkSize , i * chunkSize + chunkSize );
          });
      }
  });

  var ertagsCoronaVirus = {
      countries : null,
      all       : null
  };

  /* Number Format Callback SI System */
  var numberFormatter   = function ( number, is_percentage ){
      // Default Caser
      var format = Math.abs( Number ( number ) );

      if( false === is_percentage ){
          // Billions Case
          if( Math.abs( Number( number ) ) >= 1.0e+9 ){
              format = ( Math.abs( Number( number ) ) / 1.0e+9 ).toFixed( 1 ) + "B";
          }

          // Case Million
          if( Math.abs( Number( number ) ) >= 1.0e+6 && Math.abs( Number( number ) ) < 1.0e+9 ){
              format = ( Math.abs( Number( number ) ) / 1.0e+6 ).toFixed( 1 )  + "M";
          }

          // Case Million
          if( Math.abs( Number( number ) ) >= 1.0e+3 && Math.abs( Number( number ) )  < 1.0e+6 ){
              format = Math.sign( number ) * ( ( Math.abs( number ) / 1000 ).toFixed( 1 ) ) + 'k';
          }
      }

      return format;
  };
  var numberWithCommas  = function ( nStr ) {

      if( undefined === nStr ){
        return '-';
      }

       nStr += '';
       var x = nStr.split('.');
       var x1 = x[0];
       var x2 = x.length > 1 ? '.' + x[1] : '';
       var rgx = /(\d+)(\d{3})/;
       while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
       }
       return x1 + x2;

  }

  /* Request */
  var tagsRequests = function ( dataname, apiContainer ){

      return jQuery.ajax({ // send it to php
          url: covtags_obj.url ,
          method: "get",
          data: { 'action':'covtags_live_update_data', '_wpnonce':covtags_obj.secure, 'fields': dataname },
          success: function ( data ){
            if( parseInt( data.response_code ) === 200 && data.status_code === 1 ){

              ertagsCoronaVirus[dataname] = JSON.parse( data.data ) ;
              fillDataWithTargetFields( apiContainer ) ;
            }
          },
          fail: function ( jqXHR, textStatus ){
            return textStatus;
          }
      });
  };

  /* Fill Data into target elements */
  var fillDataWithTargetFields = function( apiContainer ){
    var  currentData = apiContainer.find( 'select' ).val(),
        targetData;

    if( parseInt( currentData ) === 0 ){
      targetData = ertagsCoronaVirus.all ;
    }

    if( parseInt( currentData ) !== 0 ){

      targetData = ertagsCoronaVirus.countries.filter(function( obj ){
        if( parseInt ( obj.countryInfo._id )  === parseInt( currentData ) ){
          return obj;
        }
      });

      targetData = targetData[0];
    }
    fillStandardCardWithData( apiContainer, targetData, false );
  };

  /* Chart Js Callback */
  var covtagsBuildChartjsScript = function( canvasId, settings, is_percentage = false ){

      // Build Variables
      var canvas, chartJs, ticksIndex;

      // Add Format with International System of Units
      if( settings.options.scales &&  undefined !== settings.options.scales.yAxes ){
          // Build Scale
          ticksIndex  = settings.options.scales.yAxes.findIndex( x=> x.ticks );
          if( ticksIndex !== -1 ){
          // Add Format For Chart Numbers
          settings.options.scales.yAxes[ticksIndex].ticks.callback = function( value, index, values ){
              var numberVal = numberFormatter( value, is_percentage );
              return ( is_percentage ) ? '%' + numberVal : numberVal;
          }
        }
      }

      // Add Some Customizations For tooltips -> Si System ( world units )
      settings.options.tooltips = {
        intersect: false,
        callbacks: {
          label : function ( tooltipItem, data ){
            var currentItemIndex  = tooltipItem.index,
                label             = data.labels[currentItemIndex],
                numbers           = data.datasets[0].data[currentItemIndex],
                graphDataNumber   = ( is_percentage ) ? '%' + numberFormatter ( Math.round( numbers * 100) / 100, is_percentage ) : numberFormatter ( Math.round( numbers * 100) / 100, is_percentage );

             document.getElementById( canvasId ).textContent = tooltipItem.yLabel;

             return ' ' + label + ' : ' +graphDataNumber ;
          }
        }
      };

      // Create Canvas with target chart
      canvas      = document.getElementById( canvasId ).getContext('2d');
      chartJs     = new Chart( canvas , settings );

      // Return Chart
      return chartJs;
  };

  /* Get Allo countries history */
  var covtagsBuildHistoryData = function ( countryName, dtOptions ){

    // Trying to get an error from coming data
     if( undefined === covtags_obj || 0 === dtOptions.historyData.status_code ){
       return;
     }

     // Success Data
    var historyData = dtOptions.historyData.data;

    // Get Country Object
    var countryIndex = historyData.findIndex( x => x.country === countryName );

    if( -1 === countryIndex ){
      return;
    }

    // Build and collect
    var collected       = new Array();
    var requiredLabels  = new Array();
    var requiredData    = new Array();
    var chunk           = new Object({
      labels: new Array(),
      data  : new Array()
    });

    // Get multi countries under the same name
    var countriesData = historyData.filter( x => x.country === countryName );
    countriesData.map( function( item ){

       Object.keys( item.timeline.cases ).map( function( key, index ){
         var thisIndex = collected.findIndex( x => x.keyName ===  jQuery.trim( key ) );
         if( thisIndex === -1 ){

            // store target
            var targetKey = jQuery.trim( key ) ;

            // Push to Array
            collected.push({
              keyName : targetKey,
              data    : item.timeline.cases[key]
            });

            // Push and sum Data
            requiredData.push( item.timeline.cases[key] );

            // Split and clean string of labels
            var exploded        = targetKey.split( '/' );
            var labelsKeyArray  = Object.keys( covtags_obj.labels ) ;
            var monthKey        = exploded[0];
            var monthLabel      = covtags_obj.labels[ labelsKeyArray[ monthKey - 1  ] ];
            var numberOfMonth   =  parseInt( exploded[1] );
            var targetDay       = numberOfMonth + ' ' + monthLabel;

            requiredLabels.push( targetDay );

         }else {
           // collect and sum case the data already exists !
           collected[thisIndex].data  = (collected[thisIndex].data + item.timeline.cases[key]);
           requiredData[thisIndex]    = collected[thisIndex].data;
         }

       });

    });

    // Return last record of array
    requiredLabels.chunk(4).map( function( item ){
      chunk.labels.push(item[item.length - 1 ]);
    });
    requiredData.chunk(4).map( function( item ){
      chunk.data.push(item[item.length - 1 ]);
    });
    return chunk;
  };

  /* Build ChartJs Options of DataTable */
  var covidtagsCanvasCountryGraph = function ( CanvasId, countryName, tableData, dtOptions ){

    // Organize History Data
    var countryHistory   =  covtagsBuildHistoryData( countryName, dtOptions );

    if( undefined === countryHistory ){
      // Delete Graph
      jQuery( '#'+CanvasId.toString() ).remove();
      jQuery( '.'+CanvasId.toString() ).remove();
      return undefined;
      // Stop Script
    }



    var countryHistoryLabels  = countryHistory.labels;
    var countryHistoryData    = countryHistory.data;
    var cbg = (  jQuery.trim( tableData.attr( 'data-graph-type') ).toString() === 'line' ) ? 'transparent': 'tomato' ;

    var options               = {
      type:  tableData.attr( 'data-graph-type') ,
      options: {
        legend:{
          display : false
        },
        responsive: true,
        scales: {
  					xAxes : [{
              barPercentage: 0.3,
  						gridLines: {
                color: ( dtOptions.isDarkMode === true ) ? '#0f282f' : '#eee',
  							drawBorder: false
  						},
  						ticks : {
  							maxTicksLimit : 8,
  							padding: 20,
                fontColor: ( dtOptions.isDarkMode === true ) ? '#ffffff59' : '#b1b0b0'
  						}
  					}],
  					yAxes : [{
  						gridLines: {
  							display: false,
  							drawBorder: false
  						},
  						ticks : {
  							maxTicksLimit : 4,
  							padding: 5,
                fontColor: ( dtOptions.isDarkMode === true ) ? '#ffffff59' : '#b1b0b0'
  						}
  					}]
				}
      },
      data: {
        labels   : countryHistoryLabels,
				datasets : [{
					data: countryHistoryData,
					borderColor: '#eb2f06',
					backgroundColor: cbg ,
					pointBackgroundColor: '#fff',
					pointHoverBackgroundColor: '#fff' ,
					borderCapStyle:'round'
				}]
      }
    };

    return covtagsBuildChartjsScript( CanvasId , options );
  }

  /* Create Instance from ChartJs to datatable before load page */
  var covtagsSetupChartJsInsideContainer = function ( dataTable, dataTableObj ){

    var tableRows = dataTable.find( '.cotags-row-container' );

    tableRows.each(( i, item ) => {
      var countryName = item.getAttribute( 'data-country-title' );
      var canvasId    = item.getAttribute( 'data-canvas-id' );
      var isChart     = covidtagsCanvasCountryGraph( canvasId, countryName, dataTable, dataTableObj  );

      if( undefined === isChart ){
          jQuery( item ).find( '.covtags-up-down' ).remove();;
      }
    });

  };

  /* Sorting DataTable By Desc or Asc */
  var covtagsSortingDataTableRows = function ( dataTable, rowsPerPage ){

      var sortingHandler = dataTable.find( '.desc-asc-sorted-data' );
      sortingHandler.on( 'click', function(){
        var thisElem          = jQuery( this ),
            sortBy            = thisElem.attr( 'data-sort-by' ),
            dataTableBody     = dataTable.find( '.covtags-table-boody' ),
            dataTableRows     = dataTable.find( '.cotags-row-container' ),
            classElements     = {
                   normal_element: 'fa-arrows-alt-v',
                   up_element    : 'fa-sort-amount-up-alt',
                   down_element  : 'fa-sort-amount-down-alt'
            },
            currState         =  ( thisElem.children( 'span.fa' ).hasClass( classElements.down_element ) || thisElem.children( 'span.fa' ).hasClass( classElements.normal_element ) ) ? true  : false ;

            // Change all elements to sort element
            sortingHandler.each(function( i ){
               var thisIcon = jQuery( this ).children( 'span.fa' );
               thisIcon.removeClass().addClass( 'fa ' + classElements.normal_element );
            });

            // Change Icon to another one according to Desc Asc
            var newState = ( currState ) ? classElements.up_element : classElements.down_element;
            thisElem.children( 'span.fa' ).removeClass().addClass( 'fa ' +  newState );

            // Sorting According to reuired Data
            var sortCallback = function(a, b){
              var sortedItems ,
                  sortBy = thisElem.attr( 'data-sort-by' );

                  if( ( !currState ) === false ){
                    sortedItems = ( ( parseInt( jQuery( a ).attr( sortBy ) ) ) > parseInt( jQuery( b ).attr( sortBy ) ) ) ? 1 : -1;
                  }else {
                    sortedItems = ( ( parseInt( jQuery( a ).attr( sortBy ) ) ) < parseInt( jQuery( b ).attr( sortBy ) ) ) ? 1 : -1;
                  }

                  return sortedItems;
            };

            return dataTableRows.css({ display: 'block' })
                                .sort( sortCallback )
                                .appendTo( dataTableBody )
                                .css( { display: 'none' } )
                                .parent( dataTableBody )
                                .children( '.cotags-row-container:nth-child(-n+'+rowsPerPage+')')
                                .css( { display: 'block' } );
      });
  };

  /* Load More -> Option of Pagination Type */
  var covtagsSetupPaginationWithLoadMoreType = function ( dataTable, rowsPerPage ){

    var loadMore = dataTable.find( '.covtags-load-more' );
    loadMore.on( 'click', function(){
       // Get current visible data
       var visibleRows = dataTable.find( '.cotags-row-container:visible' ).length;
       for ( var i = visibleRows ; i < ( rowsPerPage + visibleRows ); i++) {
          dataTable.find( '.cotags-row-container' ).eq( i ).css({
            display : 'block'
          });
       }
    });

  };

  /* Serial Paging -> Options of pagination Type */
  var covtagsSetupPaginationWithSerialType = function ( dataTable, rowsPerPage ){

      // Setup Pages and rows
      var rowCounts       = dataTable.find( '.cotags-row-container' ).length,
          pageCounts      = Math.ceil( rowCounts / rowsPerPage ),
          pageStartAt     = 0,
          pageEndAt       = 9,
          currentPage     = 0,
          serialContainer = dataTable.find( '.covtags-pagination-data' ),
          startAt         = 0,
          endAt           = 6,
          initSerialNumbers,
          slideToTargetRows;

      // Go To Target Rows
      slideToTargetRows = function (){

        var targetRows = dataTable.find( '.cotags-row-container' );
        targetRows.css({
          display: 'none'
        });

        for ( var i = pageStartAt; i < pageEndAt; i++ ) {
          targetRows.eq( i ).css({
             display : 'block'
           });
        }

        initSerialNumbers();

      };

      //  Start Page Serials
      initSerialNumbers = function(){

        // Reset Paging Container
        serialContainer.html( '' );
        for (var i = 0; i < pageCounts; i++) {

          var serial = ( i + 1 ) ;

          // Add Events to main dom
          var serials = jQuery( `<li data-main-index=\'${i}\'><span>${serial}</span></li>` ).on( 'click', function(){

              // Givens
              var index   = parseInt( jQuery( this ).index() );
              currentPage = index ;
              pageStartAt = Math.round( index * rowsPerPage );
              pageEndAt   = Math.round( ( index + 1 ) * ( rowsPerPage ) );

              // Build Next Or Prev Serials
              serialContainer.children('li:visible').each(function( i ,item ){

                // Givens
                var equality          = parseInt ( item.getAttribute( 'data-main-index') ) ===  index ;
                var lastVisibleSerial = parseInt ( serialContainer.children('li:visible').length - 1 );

                // Check if current element is last index in main list
                if( lastVisibleSerial === i &&  ( false !== equality )  && ( pageCounts >= endAt  ) ){
                  startAt  += 6;
                  endAt  += 6;
                }

                // Check if current element is first index in main list
                if( equality !== false &&  i === 0  && ( parseInt ( item.getAttribute( 'data-main-index') ) !== 0 ) ){
                  startAt  -= 6;
                  endAt  -= 6;
                }

              });

              // Callback to  Target Rows
              slideToTargetRows( );
          });

          // Display Target Serials
          serials.css({ display: 'none' });
          if( startAt <= i && endAt >= i ){
            serials.css({ display: 'inline' });
          }


          // Append Target Serials
          serials.appendTo( serialContainer );

        }

      }

      // Next Page and prev Page
      var nexPrev = dataTable.find( '.covtags-prev-next' );
      nexPrev.on( 'click', function (){
        var goType = jQuery( this ).attr( 'data-go-type' );


        if( goType === 'next' && currentPage  < ( pageCounts - 1 )  ){
          currentPage++ ;
          pageStartAt = Math.round( currentPage * rowsPerPage );
          pageEndAt   = Math.round( ( currentPage + 1 ) * ( rowsPerPage ) );
        }

        if( goType === 'prev' && currentPage > 0 ){
          currentPage-- ;
          pageStartAt = Math.round( currentPage * rowsPerPage );
          pageEndAt   = Math.round( ( currentPage + 1 ) * ( rowsPerPage ) );
        }

        slideToTargetRows();
      });


      initSerialNumbers();
  }

  /* Working with DataTable Options  */
  window.CovTagsDataTable = function ( tableData ){

    if( undefined === tableData.id ){
      return;
    }

    // Givens
    var dataTable    = jQuery( '#' + tableData.id );
    var slideHandler = dataTable.find( '.covtags-up-down' );


    // Init ChartJs Or Graph inside Containers
    covtagsSetupChartJsInsideContainer ( dataTable, tableData );

    // Init Slide Toggles
    slideHandler.on( 'click', function(){
      var currSlideHandler = jQuery( this );
      var targetElement = jQuery( this ).parent( '.covtags-table-row' ).next( '.covtags-graph-container' );
      targetElement.slideToggle(function(){
        currSlideHandler.children( '.fa' ).toggleClass( 'ico-down-element' );
      });
    });

    // Descending Ascending
    covtagsSortingDataTableRows( dataTable, tableData.rowsPerPage );

    // Pagination according types
    if( undefined === tableData.paginationType || undefined === tableData.rowsPerPage ){
      return;
    }

    if( 'serials' === tableData.paginationType ){
      covtagsSetupPaginationWithSerialType( dataTable,  parseInt( tableData.rowsPerPage ) );
    }

    if( 'loadmore' === tableData.paginationType ){
      covtagsSetupPaginationWithLoadMoreType( dataTable,  parseInt( tableData.rowsPerPage ) );
    }

  };

  /* Working with chart js according to types */
  window.CovTagsChartJs   = function ( chartJs ){

    var optionObject = {};

    if( undefined === chartJs.id ){
      return false;
    }

    if( undefined === chartJs.options ){
      return false;
    }

    // Setup Chart Type Value
    if( undefined !== chartJs.options.chartType ){
      optionObject.type = chartJs.options.chartType ;
    }

    // Setup Data Object---------------------
    optionObject.data = new Object();
    if( undefined !== chartJs.options.labels ){
      optionObject.data.labels = chartJs.options.labels;
    }
    if( undefined !== chartJs.options.dataProviders ){
      optionObject.data.datasets = chartJs.options.dataProviders ;
    }

    // Setup Options Object-------------------
    optionObject.options = new Object();
    optionObject.options.legend = {
      display : false
    };
    optionObject.options.responsive = true ;

      optionObject.options.scales = {
        xAxes : new Array(),
        yAxes : new Array()
      };

      var xObject = new Object();
      xObject.gridLines = {};
      xObject.ticks = {};

      if( undefined !== chartJs.options.xGridlineBorder ){
        xObject.gridLines.drawBorder = chartJs.options.xGridlineBorder;
      }
      if( undefined !== chartJs.options.xGridlineDisplay ){
        xObject.gridLines.display = chartJs.options.xGridlineDisplay;
      }
      if( undefined !== chartJs.options.xMaxTicksLimit ){
        xObject.ticks.maxTicksLimit = chartJs.options.xMaxTicksLimit;
      }
      if( undefined !== chartJs.options.xTicksPadding ){
        xObject.ticks.padding = chartJs.options.xTicksPadding;
      }
      optionObject.options.scales.xAxes.push( xObject );

      var yObject = new Object();
      yObject.gridLines = {};
      yObject.ticks = {};

      if( undefined !== chartJs.options.yGridlineBorder ){
        yObject.gridLines.drawBorder = chartJs.options.yGridlineBorder;
      }
      if( undefined !== chartJs.options.yGridlineDisplay ){
        yObject.gridLines.display = chartJs.options.yGridlineDisplay;
      }
      if( undefined !== chartJs.options.yMaxTicksLimit ){
        yObject.ticks.maxTicksLimit = chartJs.options.yMaxTicksLimit;
      }
      if( undefined !== chartJs.options.yTicksPadding ){
        yObject.ticks.padding = chartJs.options.yTicksPadding;
      }

      optionObject.options.scales.yAxes.push( yObject );

      if( chartJs.options.useGrids === false ){
         delete optionObject.options.scales ;
      }

      return covtagsBuildChartjsScript ( chartJs.id, optionObject ,  chartJs.isPercentage );

  };

  /* Graph Options for Cases Or Closed Status */
  window.load_graph_for_active_closed_cases = function ( graphData ){


    var labelColors = [ graphData.options.labelsColors.negative_data.color, graphData.options.labelsColors.positive_data.color, graphData.options.labelsColors.basic_data.color];
    var options               = {
      type:  graphData.options.graphType , // graphData.options.graphType
      options: {
        legend:{
          display : false
        },
        responsive: true,
        scales: {
            xAxes : [{
              barThickness: 15,
              // barPercentage: 0.9,
              gridLines: {
                display: false,
                drawBorder: false
              },
              ticks : {
                maxTicksLimit : 8,
                padding: 0,
                display: false,
              }
            }],
            yAxes : [{
              gridLines: {
                display: false,
                drawBorder: false
              },
              ticks : {
                maxTicksLimit : 4,
                padding: 0,
                display: false,
              }
            }]
        }
      },
      data: {
        labels   : graphData.options.dataLabels,
        datasets : [{
          data: graphData.options.dataGeneral, //graphData.options.dataGeneral
          borderColor: ( graphData.options.graphType === 'line')? '#eb2f06': 'transparent',
          backgroundColor:  labelColors,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'transparent' ,
          pointBorderColor: 'transparent' ,
          borderCapStyle:'round'
        }]
      }
    };

    return covtagsBuildChartjsScript( graphData._id, options , false  );
  }

  /* Render Map */
  window.covtagsRenderMap = function ( svgMap ){
    var svgElement   = jQuery( '#'+ svgMap._id ).children( 'svg' ),
        svgContainer = jQuery( '#'+ svgMap._id ),
        paths        = svgElement.find( 'path' );

        // Fill with default colors
        paths.css({
          fill    : svgMap.options.storke_color,
          stroke  : svgMap.options.fill_color
        });

        // Addd Event with hover
        paths.on( 'mouseover', function( event ){


          // Trying to get an error
          if( svgMap.all_countries.response_code === 0 ){
            return;
          }

          var allCountries = svgMap.all_countries.data;
          var currentEl   = jQuery( this );
          var countryName = currentEl.attr( 'title' );
          var svgCountryTitle = currentEl.attr( 'title' );

          var indexIsExists = allCountries.findIndex( x => x.country === svgCountryTitle );
          if( -1 === indexIsExists ){
            return;
          }
          var countryObj = allCountries.find( x => x.country === svgCountryTitle );
          var tooltipMap = currentEl.parent( 'svg' ).next( '.covtags-map-tooltip' );

          // Formating with units ( Si System )
          var countryCases      = numberFormatter ( countryObj.cases, false ) ,
              countryDeaths     = numberFormatter ( countryObj.deaths, false ) ,
              countryRecovered  = numberFormatter ( countryObj.recovered, false ),
              todayCases        = numberFormatter ( countryObj.todayCases, false ) ;

          // Fill tooltip with target data
          var confirmed = tooltipMap.find( '.covtags-cases-data' );
          var deaths = tooltipMap.find( '.covtags-deaths-data' );
          var recovered = tooltipMap.find( '.covtags-recovered-data' );
          var todaycases = tooltipMap.find( '.covtags-todaycases-data' );
          var imgCountry = tooltipMap.find( '.countryInfo' ).children( 'img' );
          var countryNameInfo = tooltipMap.find( '.countryInfo' ).children( 'span' );

          confirmed.children( 'span:first-child' ).html( covtags_obj.basic_labels.cofirmed );
          confirmed.children( 'span:last-child' ).html( countryCases );

          todaycases.children( 'span:first-child' ).html( covtags_obj.basic_labels.todayCases );
          todaycases.children( 'span:last-child' ).html( todayCases );

          deaths.children( 'span:first-child' ).html( covtags_obj.basic_labels.deaths );
          deaths.children( 'span:last-child' ).html( countryDeaths );

          recovered.children( 'span:first-child' ).html( covtags_obj.basic_labels.recovered );
          recovered.children( 'span:last-child' ).html( countryRecovered );

          imgCountry.attr( 'src', countryObj.countryInfo.flag );
          countryNameInfo.html( countryName );

          currentEl.css({
            fill    : svgMap.options.storke_hover_color,
            stroke  : svgMap.options.fill_hover_color
          });

          var newX = ( event.pageX - svgContainer.offset().left );
          var newY = ( event.pageY - svgContainer.offset().top ) ;

          var otherWidth  = Math.round( svgContainer.width() - newX );
          var otherHeight = Math.round( svgContainer.height() - newY );


          var spectRatio = 30 ;

          var newLeftPosition = ( newX >= otherWidth ) ? ( newX -  ( tooltipMap.width() + spectRatio) ) : (newX + spectRatio);
          var newTopPosition =  ( newY >= otherHeight ) ? ( newY -  ( tooltipMap.height() + spectRatio) ) : (newY + spectRatio);

          var cssObjPoistion = {
            left: newLeftPosition ,
            top: newTopPosition ,
            display: 'block'
          }


          tooltipMap.css(cssObjPoistion);

        });

        paths.on( 'mouseleave', function(){
          jQuery( '#'+ svgMap._id ).find( '.covtags-map-tooltip' ).css({
            display : 'none'
          });
          paths.css({
            fill    : svgMap.options.storke_color,
            stroke  : svgMap.options.fill_color
          });
        });

       if( svgElement.width() < 650 ){
         return;
       }

       var statisContainer = svgContainer.find( '.convtags-map-statistic-container' );

       svgElement.css({
         width: (svgElement.width() - statisContainer.width()) + 'px',
         float: 'right',
         padding: '40px'
       });
       svgContainer.css({
         background: '#f1f1f1'
       });

  };

  /* Conrona Live Data */
  window.liveUpdateForCovid19 = function ( standardCard ){
    /*
      New Cases
      New Deaths
      -----------
      Cases
      deaths
      Recovered
      Critical
    */
  }



  var apiRequestData = function ( requestData , apiContainer){
    // tagsRequests

    setTimeout(function(){
      for ( var i = 0; i < requestData.length; i++ ) {
        tagsRequests( requestData[i], apiContainer );
      }

      apiRequestData([
        'all',
        'countries'
      ], apiContainer);

      // 10 Mins to live update
    },  ( (1000 * 60)  * 10 ) );

  };

  var fillStandardCardWithData = function ( apiContainer, dataProvider = null, refresh = false ){

    var cases,
        todayCases,
        recovered,
        todayDeaths,
        deaths,
        critical;

      // Fill Data into Standard Card
      cases         = apiContainer.find( 'li.confirmedData' );
      todayCases    = cases.find( 'span:last-child' ).find('i');
      deaths        = apiContainer.find( 'li.deathData' );
      todayDeaths   = deaths.find( 'span:last-child' ).find('i');
      critical      = apiContainer.find( 'li.criticalData' );
      recovered     = apiContainer.find( 'li.recoveredData' );

      if( dataProvider === null ){
        return;
      }

      if( refresh !== false ){
        apiContainer.find('li').hide();
      }

      cases.find('span').eq(1).html( numberWithCommas ( dataProvider.cases ) );
      todayCases.html( ((dataProvider.todayCases !== 0 ) ? '+': '') + numberWithCommas ( dataProvider.todayCases ) );

      deaths.find('span').eq(1).html( numberWithCommas ( dataProvider.deaths ) );
      todayDeaths.html( ((dataProvider.todayDeaths !== 0 ) ? '+': '') + numberWithCommas ( dataProvider.todayDeaths ) );

      critical.find('span').eq(1).html( numberWithCommas ( dataProvider.critical ) );
      recovered.find('span').eq(1).html( numberWithCommas ( dataProvider.recovered ) );

      if( refresh !== false ){
        apiContainer.find('li').fadeIn();
      }
  };

  // Need Functiona Now ++++
  window.applySelect2Plugin = function ( standardCards ){

    // INIT VARS
    var comboBox    = jQuery( '#' + standardCards._id ),
        apiContainer= comboBox.parent( '.covtags-list-countries-data' ).parent( '.covtags-standard-card-wrapper' ),
        targetData;

    ertagsCoronaVirus.countries   = standardCards.countries;
    ertagsCoronaVirus.all         = standardCards.reportApi;

    // Live Data [ Send A request ]
    apiRequestData([
      'all',
      'countries'
    ], apiContainer );

    // Create Combo Box with flag
    var formatState = function ( state ){

      if ( !state.id ) {
        return state.text;
      }
      var flagUrl =  state.element.attributes[0].nodeValue ;
      var state = jQuery(
        '<span><img src="' + flagUrl + '" class="img-flag" /> ' + state.text + '</span>'
      );
      return state;
    };

    comboBox.select2({
       placeholder: "Select Country",
       allowClear: true,
       templateResult: formatState
    });

    comboBox.on( 'change', function (){

      var countryId     = jQuery( this ).val();
      if( countryId !== 0 ){
        var eratags_covid19 = ( ertagsCoronaVirus.countries.data !== undefined ) ? ertagsCoronaVirus.countries.data : ertagsCoronaVirus.countries;
        targetData = eratags_covid19.filter(function( vl ){
            if( parseInt (vl.countryInfo._id)  === parseInt( countryId ) ){
              return vl;
            }
         });
        targetData = targetData[0];
      }
      if( parseInt( countryId ) === 0 ){
        targetData = ertagsCoronaVirus.all;
      }

      // Fill With Newest data
      fillStandardCardWithData( apiContainer, targetData, true );

    });


  }


  // Ticker
  window.covtagsAssignTooltipForThisTicker = function ( tickerData ){
    var tickerSelector = jQuery( '#' + tickerData._id ),
        items          = tickerSelector.find( '.tags-ticker-item' ),
        tickerData     = tickerSelector.find( '.tags-ticker' );

       

  };

})(jQuery);
