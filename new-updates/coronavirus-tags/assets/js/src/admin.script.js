(function(){
  "use Strict"

  // Some Givens
  var tabHandler        = jQuery( '.covtags-tab-contents li a' ),
      saveSettings      = jQuery( '#save-coronavirus-tags' ),
      dataProvider      = jQuery( '#covtags-data-provider' ),
      dataTableRow      = jQuery( '#number-of-rows-covtags' ),
      radioBoxData      = jQuery( '.covtags-radio-handler' ),
      checkBoxData      = jQuery( '.covtags-checkbox-handler' ),
      inputData         = jQuery( '.covtag-shortcode-input' ),
      selectedData      = jQuery( '.covtags-select-handler' ),
      currentShortcodeObj  = {
        shortcodeName   : 'covtags-all-countries',
        shortcodeFields : new Array()
      },

      // Fill Shortcode Generator
      shortcodeDataVal    = new Array ();
      shortcodeDataVal['covtags-statistics']          = new Array(
          'title',
          'fields',
          'layout',
          'country',
          'align_text',
          'icon_flag',
          'dark_mode'
        );

      shortcodeDataVal['covtags-all-countries']       = new Array(
          'fields',
          'desc_by',
          'rows_per_page',
          'graph_type',
          'paging_type',
          'icon_flag',
          'dark_mode'
        );
      shortcodeDataVal['covtags-status']              = new Array(
          'title',
          'hide_title',
          'use_graph_with',
          'status_type',
          'country',
          'icon_flag',
          'show_percentage',
          'dark_mode'
        );
      shortcodeDataVal['covtags-map']                 = new Array( 'dark_mode' ),
      shortcodeDataVal['covtags-standard-card']       = new Array( 'dark_mode' ),
      shortcodeDataVal['covtags-tricker-world-card']  = new Array(
          'title',
          'field',
          'tricker_speed',
          'desc_by',
          'inner-spacing',
          'dark_mode'
        );

  // Prevent Anchor from refresh
  tabHandler.on( 'click', function ( e ){
    e.preventDefault();
  });

  // Saving Options
  saveSettings.on( 'click', function( e ){

    e.preventDefault();

    var currentElement      = jQuery( this ),
        cache_period        = jQuery( '#covtags_corona_cacheperiod' ).val(),
        title               = jQuery( '#covtags_corona_title' ).val(),
        description         = jQuery( '#covtags_corona_desc' ).val();


    jQuery.ajax({
      url: eratags_obj.ajaxurl,
      data: { action : 'CoronaVirusTags_action', secure: eratags_obj.nonce, description : description , title : title , cache_period : cache_period},
      beforeSend: function (){
        currentElement.html( eratags_obj.saving );
      },
      success: function ( success ){
        console.log(success);
          // Reset Button With Default Text
          currentElement.html( eratags_obj.save_changes );
      },
      faild : function (){}
    });
  });

  // Display Shortcode with box ( Generator )
  var applyShortcodeValues = function ( key, value ){

    // Data Of Array
    currentShortcodeObj.shortcodeFields[key] = value ;


    // Set it as a default.
    if( dataProvider.val() === 'covtags-statistics' ){
      currentShortcodeObj.shortcodeFields['style'] = 'style-3';
    }
      // Extract Data of array and set it into shortcode box
    var shortcodeFieldData = '';
    for( var keyName in currentShortcodeObj.shortcodeFields  ) {
       shortcodeFieldData += keyName + '="' + currentShortcodeObj.shortcodeFields[keyName] + '" ';
     }
     var shortcodeIs = '[' + currentShortcodeObj.shortcodeName + ' '+ jQuery.trim( shortcodeFieldData ) + ']';
     jQuery( '.covtags_shortcode_container' ).html( shortcodeIs );
  };

  // Get Data Value of select box
  dataProvider.on( 'change', function (){
    // Reset Values
    currentShortcodeObj.shortcodeFields = new Array();

    var currentShortcode = jQuery( this ).val();
    var targetShortcode = shortcodeDataVal[currentShortcode] ;

    currentShortcodeObj.shortcodeName =   currentShortcode  ;

    jQuery( '.covtags_shortcode_container' ).html( '[' + currentShortcodeObj.shortcodeName + ']' );

    // Display Target Fields -- open-section
    jQuery( '.tble-fields' ).removeClass( 'open-section' );
    shortcodeDataVal[currentShortcodeObj.shortcodeName].forEach( function(item, i ){
      jQuery( '.tble-fields[data-name="'+item+'"]' ).addClass( 'open-section' );
    });

    document.getElementById( 'shorcodeform' ).reset();

    // Reassign the variable back to the value
    jQuery( this ).val(currentShortcode);
  });

  // Prevent User from write a string in integer input
  dataTableRow.on( 'keypress keypress', function(e){
    var value = jQuery( this ).val();

    if ( e.which < 48 || e.which > 57 ){
        evt.preventDefault();
    }

  });


  // Radio Box Handlers
  radioBoxData.on( 'click', function(){
      var thisHandler = jQuery( this );
      var key = thisHandler.find( 'input[type="radio"]:checked' ).attr( 'name' ) ;
      var value = thisHandler.find( 'input[type="radio"]:checked' ).val();
      if( key !== undefined && value !== undefined ){
        return applyShortcodeValues( key, value );
      }
  });

 // Check Box Handler
 // Check Box Handler
 checkBoxData.on( 'click', function(){
   var thisHandler = jQuery( this );
   var keyName = thisHandler.parent( 'li' ).parent( 'ul' ).attr( 'data-name' );
   var checkedItems  = thisHandler.parent( 'li' ).parent( 'ul' ).find( 'input[type="checkbox"]:checked' );
   var values = '';


   checkedItems.each( function( i, item ){
      values += item.value;
      if( i !== ( checkedItems.length - 1 ) ) {
        values += ',';
      }
   });

   applyShortcodeValues( keyName, values );
 });

 // Inputs ( Numbers and text )
 inputData.on( 'input', function (){
    var keyName = jQuery( this ).attr( 'name' ) ;
    var value  = jQuery( this ).val();
    return applyShortcodeValues( keyName, value );
 });

 // Selected Data
 selectedData.on( 'change', function (){
   var keyName = jQuery( this ).attr( 'name' ) ;
   var value  = jQuery( this ).val();
   return applyShortcodeValues( keyName, value );
 });

 // Open and toggle divs
 jQuery( '.open-anchor-element' ).on( 'click', function(){
   var thElement = jQuery( this ).attr( 'href' );

    jQuery( '.open-anchor-element' ).each(function(i, item){
      if( item.getAttribute( 'href' ) !== thElement ) {
        jQuery( item.getAttribute( 'href' ) ).hide();
        jQuery( this ).removeClass( 'selectedAnc' );
      }
    });

    jQuery( this ).addClass( 'selectedAnc' );
    jQuery( thElement ).show();
 });

})(jQuery);
