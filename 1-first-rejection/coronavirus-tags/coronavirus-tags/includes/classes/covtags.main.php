<?php

if( !class_exists( 'CovTags' ) ){
  class CovTags {

    // Plugin Activation
    public static function _Run (){
      register_activation_hook( COVTAGS_FILE,  'CovTags::covtags_hook_activation' );
    }

    // Plugin Deactivation
    public static function _Disable(){
      register_deactivation_hook( COVTAGS_FILE, 'CovTags::covtags_hook_deactivation'  );
    }

    // Initialization
    public static function _Init (){
      add_action( 'init',  'CovTags::covtags_hook_initialization' );
    }

    // Hook Activation ( Install Options )
    public static function covtags_hook_activation (){

      # Software Compatibility
      $CovTagsCompatibility = new CovTags_Compatibility();
      $CovTagsCompatibility->_Load();

      # Get All Countries of current api and save it
      $CovTagsReqInstance   = new CovTags_Request();
      $CovTagsReqInstance->Countries( NULL , true , NULL );
      $CovTagsReqInstance->save();

      # Clear the permalinks
      flush_rewrite_rules();
    }

    // Hook Unregister ( Options )
    public static function covtags_hook_deactivation (){

      # Clear the permalinks
      flush_rewrite_rules();

    }

    // Hook Deactivation ( Options )
    public static function covtags_hook_initialization (){}

  }
}
