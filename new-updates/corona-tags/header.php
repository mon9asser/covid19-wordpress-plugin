<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Coronavirus Tags</title>
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>

    <div class="container-fluid tags-banner tags-dark">
      <div class="container">
        <div class="row">
          <?php
            wp_nav_menu(array(
              'menu_class' => 'tags-navbar',
              'menu'       => 'covtags-menu',
              'container'  => 'ul'
            ));
          ?>
        </div>
      </div>
