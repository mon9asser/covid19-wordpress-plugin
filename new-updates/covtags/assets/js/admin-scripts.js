( function(){
  "use strict"

  /*********************************************
    Shortcode data
  *********************************************/
  var shortcodeObjData = {
    shortcode_body: {
      name: 'covtags-standard',
      data: new Array(),
      shortcode_list: new Array()
    },
    standard_card: {
      name: 'covtags-standard',
      data: new Array(
        'language-texts',
        'enable-rtl',
        'live-text',
        'title-text',
        'dark-mode'
      )
    },
    ticker: {
      name: 'covtags-ticker',
      data: new Array(
        'field',
        'card-text',
        'ticker-speed',
        'dark-mode',
        'icon-flag',
        'ticker-data',
        'tooltip-fields',
        'ticker-position'
      ),
      optional: {
        data_field_name: 'ticker-data',
        data_1: {
          data: new Array(
            'collected-fields',
            'enable-rtl',
            'card-text'
          ),
          hide: new Array(
            'field',
            'country',
            'tooltip-fields',
            'ticker-position'
          )
        },
        data_2: {
          data: new Array(
            'collected-fields',
            'enable-rtl',
            'country'
          ),
          hide: new Array(
            'field',
            'card-text',
            'tooltip-fields',
            'ticker-position'
          )
        },
        data_3: {
          data: new Array(
            'field',
            'card-text',
            'tooltip-fields',
            'ticker-position'
          ),
          hide: new Array(
            'collected-fields',
            'enable-rtl',
            'country'
          )
        },
      }
    },
    datatable: {
      name: 'covtags-datatable',
      data: new Array(
        'fields',
        'rows-per-page',
        'desc-by',
        'graph-type',
        'paging-type',
        'icon-flag',
        'dark-mode'
      )
    },
    stats_card: {
      name: 'covtags-stats',
      data: new Array(
        'title-text',
        'layout',
        'country',
        'icon-flag',
        'dark_mode'
      )
    },
    status_card: {
      name: 'covtags-status',
      data: new Array(
        'title-text',
        'hide-title',
        'use-graph-with',
        'status-type',
        'country',
        'icon-flag',
        'show-percentage',
        'dark-mode'
      )
    },
    map_card: {
      name: 'covtags-map',
      data: new Array(
        'dark-mode',
        'country'
      )
    }
  };

  /*********************************************
    Build and concatenate shortcode contents by select box
  *********************************************/
  var selectedShortcode = jQuery( '#covtags-data-provider' );
  selectedShortcode.on( 'change', function(){

    // Get Current Shortcode name
    var shortcodeSlug = jQuery( this ).val();
    var shortcodename = 'covtags-standard-card';

    // Enable all inputs
    jQuery( 'input[type="text"]' ).parent( 'label' ).parent( 'li' ).removeClass( 'close-this-label' );

    // Get Data From Array
    var findShortcode = shortcodeObjData[ shortcodeSlug ];

    // Store Current Shortcode and display it in contents
    shortcodeObjData.shortcode_body.name = findShortcode.name;
    shortcodeObjData.shortcode_body.data = findShortcode.data;
    jQuery( '.tags-shorcode-contents' ).html( '[' + findShortcode.name + ']' );

    // Hide All Options for this shortcode
    jQuery( '.tags-data-opt-field' ).removeClass( 'shortcode-open-option-data' );

    // Display Target Options
    if( shortcodeObjData.shortcode_body.data.length !== 0 ) {
      shortcodeObjData.shortcode_body.data.forEach( function ( item, i ) {
         jQuery( '.tags-data-opt-field[data-name=\'' + item + '\']' ).addClass( 'shortcode-open-option-data' );
      } );
    }

    // Reset Form Data
    document.getElementById( 'shorcodeform' ).reset();
    shortcodeObjData.shortcode_body.shortcode_list = new Array();

    // Assign Old Value to select box
    jQuery( this ).val( shortcodeSlug );

  } );


  /*********************************************
    Custom with help data inside select box
  *********************************************/
  var tickerDataType     = jQuery( '.ticker-data-type' );
  tickerDataType.on( 'click', function(){

    var getField      = jQuery( this ).children( 'input[type=\'radio\']' ),
        fieldIndex    = parseInt( jQuery( this ).parent( 'li' ).index() ) + 1 ,
        shortcodeSlug = selectedShortcode.val(),
        optionals     = ( undefined !== shortcodeObjData[ shortcodeSlug ].optional ) ? shortcodeObjData[ shortcodeSlug ].optional : undefined;

    if( undefined === optionals ) {
      return;
    }

    // Looking to get a specific object
    var elementData = optionals[ 'data_' + fieldIndex ];

    // Hide Fields
    for ( var i = 0; i < elementData.hide.length; i++ ) {

      var hideData = elementData.hide[i];
      jQuery( '.tags-data-opt-field[data-name=\'' + hideData + '\']' ).removeClass( 'shortcode-open-option-data' );

      var isAttached = shortcodeObjData.shortcode_body.shortcode_list[ hideData ] === undefined ? false : true;
      if( isAttached === true ){

        // remove Data Fields or rtl
        delete shortcodeObjData.shortcode_body.shortcode_list[ hideData ];

        /* Remove Texts from shortcode */
        if( undefined !== shortcodeObjData.shortcode_body.shortcode_list[ 'cases-text' ] ) {
          delete shortcodeObjData.shortcode_body.shortcode_list[ 'cases-text' ];
        }

        if( undefined !== shortcodeObjData.shortcode_body.shortcode_list[ 'deaths-text' ] ) {
          delete shortcodeObjData.shortcode_body.shortcode_list[ 'deaths-text' ];
        }

        if( undefined !== shortcodeObjData.shortcode_body.shortcode_list[ 'today-cases-text' ] ) {
          delete shortcodeObjData.shortcode_body.shortcode_list[ 'today-cases-text' ];
        }

        if( undefined !== shortcodeObjData.shortcode_body.shortcode_list[ 'today-deaths-text' ] ) {
          delete shortcodeObjData.shortcode_body.shortcode_list[ 'today-deaths-text' ];
        }

        if( undefined !== shortcodeObjData.shortcode_body.shortcode_list[ 'active-text' ] ) {
          delete shortcodeObjData.shortcode_body.shortcode_list[ 'active-text' ];
        }

        if( undefined !== shortcodeObjData.shortcode_body.shortcode_list[ 'recovered-text' ] ) {
          delete shortcodeObjData.shortcode_body.shortcode_list[ 'recovered-text' ];
        }

        if( undefined !== shortcodeObjData.shortcode_body.shortcode_list[ 'critical-text' ] ) {
          delete shortcodeObjData.shortcode_body.shortcode_list[ 'critical-text' ];
        }

      }

    }

    // Display Needed Fields
    for ( var i = 0; i < elementData.data.length; i++ ) {

      var targetData = elementData.data[i];
      jQuery( '.tags-data-opt-field[data-name=\'' + targetData + '\']' ).addClass( 'shortcode-open-option-data' );

    }

  } );

  /*********************************************
    To recieving values and keys then build body of shortcode
  *********************************************/
  var buildShortcodeData = function ( shortcodeAttKey, shortcodeAttValue ) {

    // Store into default array to prevent duplications
    shortcodeObjData.shortcode_body.shortcode_list[ shortcodeAttKey ] = shortcodeAttValue ;

    // Some Givens
    var shortcodeAttributes = '',
        shortcodeData       = shortcodeObjData.shortcode_body.shortcode_list;

    // Build In Array
    for ( var key in shortcodeData ) {
      shortcodeAttributes  += key + "=" + "'" + shortcodeData[ key ] +"' ";
    }

    // Remove any spaces from string
    shortcodeAttributes     = jQuery.trim( shortcodeAttributes );

    // Get Current shortcode name
    var shortcodeBody       = "[" + shortcodeObjData.shortcode_body.name + " " + shortcodeAttributes + "]";

    // Assign it to html dom
    jQuery( '#concatenated-shortcode-id' ).html( shortcodeBody );

  }

  /*********************************************
    Inputs, selects, checkbox, etc events
  *********************************************/
  // Inputs
  var shortcodeInputTexts = jQuery( '.shortcode_input_texts' );
  shortcodeInputTexts.on( 'input', function(){

    // Getting Name Of current input
    var inputName  = jQuery( this ).attr( 'name' );

    // Getting Shortcode Field Keys
    var inputValue = jQuery( this ).val();

    // Send Data with key into function
    buildShortcodeData( inputName, inputValue );

  } );

  // Select
  var shortcodeSelectBox = jQuery( '.shortcode_select_texts' );
  shortcodeSelectBox.on( 'change', function(){

    // Getting Name Of current Select box
    var inputName  = jQuery( this ).attr( 'name' );

    // Getting Shortcode Field Keys
    var inputValue = jQuery( this ).val();

    // Send Data with key into function
    buildShortcodeData( inputName, inputValue );

  } );

  // Checkbox items
  var shortcodeCheckBox = jQuery( '.shortcode_check_box, .shortcode_check_box_collecter' );
  shortcodeCheckBox.on( 'click', function(){

    // Getting Name Of current Checkbox
    var fields       = '',
        fieldKey     = jQuery( this ).parent( 'li' )
                                     .parent( 'ul' )
                                     .parent( '.covtags-field' )
                                     .parent( '.tags-data-opt-field' )
                                     .attr( 'data-name' ),
        checkedItems = jQuery( this ).parent( 'li' )
                                     .parent( 'ul' )
                                     .find( 'input:checked' );

    // Store all checked inputs inside concatenated string with comma
    checkedItems.each( function( i, item ){

      fields += item.value;
      if( i !== ( checkedItems.length - 1 ) ) {
        fields += ',';
      }

    } );


    // Send Data with key into function
    buildShortcodeData( fieldKey, fields );

  } );

  // Checked Radio Boxes
  var shortcodeRadio  = jQuery( '.shortcode_radio_box' );
  shortcodeRadio.on( 'click', function(){

    // Some Givens
    var checkedItem = 'cases',
        keyName     = jQuery( this ).parent( 'label' )
                                    .parent( 'li' )
                                    .parent( 'ul' )
                                    .parent( '.covtags-field' )
                                    .parent( '.tags-data-opt-field' )
                                    .attr( 'data-name' );

    // Get Current Value of checked item
    if( jQuery( this ).prop( 'checked' ) ){
       checkedItem = jQuery( this ).val();
    }

    // Send Data with key into function
    buildShortcodeData( keyName, checkedItem );

  } );

  /*********************************************
    Saving Covid 19 Options
  *********************************************/
  var saveSettings = jQuery( '#save-coronavirus-tags' );
  saveSettings.on( 'click', function( e ){

    // Some Givens
    var currentElement      = jQuery( this ),
        title               = jQuery( '#covtags_corona_title' ).val(),
        description         = jQuery( '#covtags_corona_desc' ).val();

    // Collect Data Object
    var dataObject          = {
        action: 'CoronaVirusTags_action',
        secure: eratags_obj.nonce,
        description: description,
        title: title
    };

    // Prepare Ajax Method
    jQuery.ajax( {
        url: eratags_obj.ajaxurl,
        data: dataObject,
        beforeSend: function( xhr ){
          currentElement.html( eratags_obj.saving );
        },
        success: function( successData ){
          currentElement.html( eratags_obj.save_changes );
        }
    } );

    // Prevent Page From Load
    e.preventDefault();

  } );

  /*********************************************
    Menu Tabs
  *********************************************/
  var tabAnchors            = jQuery( '.open-anchor-element' );
  tabAnchors.on( 'click', function( e ){

    // Some Givens
    var thElement = jQuery( this ).attr( 'href' );

    // Remove and hide unclicked tab
    jQuery( '.open-anchor-element' ).each(function(i, item){

      if( item.getAttribute( 'href' ) !== thElement ) {
        jQuery( item.getAttribute( 'href' ) ).hide();
        jQuery( this ).removeClass( 'tags-selected-anchor' );
      }

    });

    // Show Shortcode Body For Shortcode Builder
    if( '#covtags-shortcodes' === thElement ){
      jQuery( '.tags-shortcode-block-contents' ).addClass( 'activate-shotcode-body' );
    }else {
      jQuery( '.tags-shortcode-block-contents' ).removeClass( 'activate-shotcode-body' );
    }

    // Seleced Tab
    jQuery( this ).addClass( 'tags-selected-anchor' );
    jQuery( thElement ).show();

  } );

  /*********************************************
    Open Menu Tabs during load the window
  *********************************************/
  jQuery( window ).on( 'load', function(){

    // Getting Window Url
    var currentUrl = window.location.href;

    // Split and get last word in url
    var parts         = currentUrl.split( '#' );
    var tabSlug       = parts.pop();
    var acceptedSlugs = new Array(
      'covtags-settings',
      'covtags-shortcodes'
    );

    // Case Window has no slugs
    if( -1 === acceptedSlugs.indexOf( tabSlug ) ) {
      return;
    }

    // Build and get target id
    var tabId      = jQuery( '#' + tabSlug );

    // Remove and hide unclicked tab
    jQuery( '.open-anchor-element' ).each(function(i, item){

      if( item.getAttribute( 'href' ) !== ( '#' + tabSlug ) ) {
        jQuery( item.getAttribute( 'href' ) ).hide();
        jQuery( this ).removeClass( 'tags-selected-anchor' );
      }

    });

    // Show Shortcode Body For Shortcode Builder
    if( 'covtags-shortcodes' === tabSlug ){
      jQuery( '.tags-shortcode-block-contents' ).addClass( 'activate-shotcode-body' );
    }else {
      jQuery( '.tags-shortcode-block-contents' ).removeClass( 'activate-shotcode-body' );
    }

    // Select Target Anchor and open target tab
    jQuery( '.open-anchor-element[href=\'' + '#' + tabSlug + '\']' ).addClass( 'tags-selected-anchor' );
    tabId.show();

  } );

} )( jQuery );
