<?php


if( !function_exists( 'tags_load_theme_scripts_callback' ) ){
  function tags_load_theme_scripts_callback (){




    wp_enqueue_style( 'tags-bootstrap', "https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css", false, '1.0', 'all' );
    wp_enqueue_style( 'tags-awesomefonts', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css", false, '1.0', 'all' );
    wp_enqueue_script( 'tags-jquery', "https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", false, '1.0', true );
    wp_enqueue_script( 'tags-boot-script', "https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js", false, '1.0', true );
    wp_enqueue_script( 'tags-main-script', get_template_directory_uri(). "/js/scripts.js", ['jquery'], '1.0', true );

    wp_enqueue_style( 'tags-fonts-1', "https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap", false, '1.0', 'all' );
    wp_enqueue_style( 'tags-fonts-2', "https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap", false, '1.0', 'all' );
    wp_enqueue_style( 'tags-icons', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css", false, '1.0', 'all' );
    wp_enqueue_style( 'tags-style', get_theme_file_uri( '/css/theme-styles.css' ), false, '1.0', 'all' );
  }
}
add_action( 'wp_enqueue_scripts', 'tags_load_theme_scripts_callback' );


function tags_remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'tags_remove_admin_login_header');


function tags_add_icon_beside_title ( $header_content ) {
  ?>
  <link rel = "icon" href="<?php echo esc_url( get_theme_file_uri( '/img/covid-icon.png' ) ) ?>" type = "image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
}

add_action ( 'wp_head', 'tags_add_icon_beside_title' );


add_action( 'after_setup_theme', function (){
  add_theme_support( 'menus' );
   register_nav_menus(array(
     'covtags-menu' => 'Corona Virus Item',
   ));
});
