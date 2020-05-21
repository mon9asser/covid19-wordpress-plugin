( function(){
  "use strict"



  /**************************************************
  Split Arrays to pieces
  *************************************************/
  Object.defineProperty( Array.prototype, 'chunk', {
    value: function( chunkSize ) {

      var that = this;
      return Array( Math.ceil( that.length / chunkSize) ).fill( ).map( function( _, i ){
          return that.slice( i * chunkSize , ( i * chunkSize ) + chunkSize );
      } );

    }
  } );

  /**************************************************
  System International Units
  *************************************************/
  var numberFormatter   = function ( number, is_percentage ){

    var format = Math.abs( Number ( number ) );

    // Case use : !== %
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

  /**************************************************
  Split number and set it with comma
  *************************************************/
  var numberWithCommas  = function ( nStr ) {

     if( undefined === nStr ){
       return '-';
     }

     // concatenated string
     nStr += '';

     // Split with a rejax
     var x   = nStr.split('.');
     var x1  = x[ 0 ];
     var x2  = x.length > 1 ? '.' + x[ 1 ] : '';
     var rgx = /(\d+)(\d{3})/;

     // Loop and set
     while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
     }

     // Return value
     return x1 + x2;

  };



  /**************************************************
  Select2 js Liberary
  *************************************************/
  var covtags_standard_cards = jQuery( '.covtags-standard-cards' );
  covtags_standard_cards.each( function( i, item ){

    // Get unique id of each element
    var standardIdSlug     = item.getAttribute( 'id' ),
        standardId         = jQuery( '#' + standardIdSlug ),
        selectIdSlug       = standardId.find( 'select' ).attr( 'id' ),
        selectId           = jQuery( '#' + selectIdSlug ),
        casesContainer     = standardId.find( '.confirmed-data' ).find( 'span' ),
        deathsContainer    = standardId.find( '.deaths-data' ).find( 'span' ),
        recoveredContainer = standardId.find( '.recovered-data' ).find( 'span' ),
        criticalContainer  = standardId.find( '.critical-data' ).find( 'span' ),
        lists              = standardId.find( 'li' ),
        countries          = JSON.parse( covtags_obj.countries ),
        worldReport        = JSON.parse( covtags_obj.all ),
        liveUpdates,
        setLiveChangesInTimer,
        formatState,
        setupCommingData;

    // Setup Comming Data inside elements
    setupCommingData = function ( elementData, countries = null,  worldReport = null){

      // Get Id of select box
      var selectedId    = parseInt ( elementData.val() ),
          countries     = countries,
          worldReport   = worldReport,
          selectedIndex,
          dataObject;

      // Get Current Index of Country or world wide report
      selectedIndex = Object.keys( countries ).findIndex( x => parseInt( countries[ x ].countryInfo._id ) === selectedId );

      // Gett An Object
      dataObject    = ( -1 === selectedIndex ) ? worldReport : countries[ selectedIndex ];

      // Cases
      casesContainer.eq( 1 ).html( numberWithCommas( dataObject.cases ) );

      // Today Cases
      casesContainer.eq( 2 ).children( 'i' ).html( numberWithCommas( dataObject.todayCases ) );

      // Deaths
      deathsContainer.eq( 1 ).html( numberWithCommas( dataObject.deaths ) );

      // Today Deaths
      deathsContainer.eq( 2 ).children( 'i' ).html( numberWithCommas( dataObject.todayDeaths ) );

      // Recovered
      recoveredContainer.eq( 1 ).html( numberWithCommas( dataObject.recovered ) );

      // Critical
      criticalContainer.eq( 1 ).html( numberWithCommas( dataObject.critical ) );

    };

    // Starting Live Updates
    setLiveChangesInTimer = function (){

      setTimeout( function(){

        // DataString
        var dataString = {
          'action': 'covtags_live_update_data',
          'covtags_coronavirus_sec': covtags_obj.secure
        }

        // Send Ajax
        jQuery.ajax({
          url: covtags_obj.url,
          method: "get",
          data: dataString,
          success: function( data ){

            // Case World Report
            if( undefined !== data.all && ( undefined !== data.all.response_code && 200 === data.all.response_code ) ) {
              worldReport = JSON.parse( data.all.data );
            }

            // Case Countries
            if( undefined !== data.countries && ( undefined !== data.countries.response_code && 200 === data.countries.response_code ) ) {
              countries = JSON.parse( data.countries.data );
            }

            // Fill Elements with new data
            setupCommingData( selectId, countries, worldReport );

          }
        });

        // Repeat
        setLiveChangesInTimer();

      },  ( ( 1000 * 60 )  * 10 ) ); // Every 10 Minutes ( ( 1000 * 60 )  * 10 )

    };
    setLiveChangesInTimer();

    // Build Texts and flag
    formatState = function( state ) {

      // Case country id does not exists
      if ( !state.id ) {
        return '<span>' + state.text + '</span>';
      }

      // Getting Flag url Data
      var flagUrl =  ( 0 === parseInt( state.id ) ) ? covtags_obj.covid19_icon : state.element.attributes[ 0 ].nodeValue;

      // Image Flag
      var state = jQuery(
        '<span>' + '<img class="img-flag" src="' + flagUrl + '" />' + state.text + '</span>'
      );

      return state;

    };

    // Excute Select 2 Lib
    selectId.select2({
      allowClear: true,
      placeholder: covtags_obj.basic_labels.select_country,
      templateResult: formatState
    });

    // Get Information Of Specific Country
    selectId.on( 'change', function (){

      lists.hide();
      setupCommingData( jQuery( this ), countries, worldReport );
      lists.fadeIn();

    } );

  } );


} )( jQuery );
