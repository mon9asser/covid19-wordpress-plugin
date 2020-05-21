<?php /* Template Name: Coronavirus-Tags */ ?>
    <?php get_header(); ?>

    <!-- Title -->
    <div class="template-header-container">
      <div class="container text-center tags-title title-container-d">

          <h1 class="covid-title">
            Conrona<span class="colored-item">virus</span> Tags
          </h1>
          <p>
            Welcome to documentation
          </p>
      </div>
    </div>
</div>



<div class="container clbox-data">
  <div class="row">
    <div class="col-4">
      <div class="list-group group-ul" id="list-tab" role="tablist">
        <a class="list-group-item list-group-item-action active" id="add-plugin-tags" data-toggle="list" href="#install-plugin" role="tab" aria-controls="home">How to install a WordPress plugin</a>
        <a class="list-group-item list-group-item-action" id="add-widget-tags" data-toggle="list" href="#add-widget" role="tab" aria-controls="profile">Add covid19 widget</a>
        <a class="list-group-item list-group-item-action" id="list-shortcodes-list" data-toggle="list" href="#list-shortcodes" role="tab" aria-controls="shortcodes">how to insert shortcodes in WordPress</a> 
      </div>
    </div>
    <div class="col-8">
      <div class="tab-content" id="nav-tabContent">

        <!-- Install Plugin  -->
        <div class="tab-pane fade show active" id="install-plugin" role="tabpanel" aria-labelledby="add-plugin-tags">
          <h5>
            How to install a WordPress plugin
          </h5>
          <p class="lead remove-padding">
            After Download "Coronavirus-tags" from "envato codecanyon" , Go to your admin panel – http://your-domain.com/wp-admin/ and click on “Plugins” in the sidebar menu:
          </p>
          <div class="img-container">
            <img class="img-thumbnail" src="<?php echo get_template_directory_uri() . "/img/plugins-menu.png"?>" alt="">
          </div>
          <p class="lead">
            Then, click on the “Add New” button that’s at the top:
          </p>
          <div class="img-container">
            <img class="img-thumbnail" src="<?php echo get_template_directory_uri() . "/img/add-new-plugin.png"?>" alt="">
          </div>
          <p class="lead">
            Then, click on the “Upload Plugin” button
          </p>
          <div class="img-container">
            <img class="img-thumbnail" src="<?php echo get_template_directory_uri() . "/img/upload.png"?>" alt="">
          </div>
          <p class="lead">
            Then, Select "Coronavirus-tags.zip" file
          </p>
          <div class="img-container">
            <img class="img-thumbnail" src="<?php echo get_template_directory_uri() . "/img/plugin.png"?>" alt="">
          </div>
        </div>

        <!-- Add New Widget -->
        <div class="tab-pane fade" id="add-widget" role="tabpanel" aria-labelledby="add-widget-tags">

          <h5>How to display Coronavirus-tags widget into your wordpress site</h5>
          <div class="lead remove-padding">
            If your Theme supports Theme Customizer then you can use the following Steps. In Theme Customizer
            <ol>
              <li class="lead">Go to Appearance > Customize in the WordPress Administration Screens.</li>
              <li class="lead">Click the Widget menu in the Theme Customizer to access to the Widget Customize Screen.</li>
              <li class="lead">Click the down arrow of Widget Area to list the already registered Widgets.</li>
              <li class="lead">Click Add a Widget button at the bottom of sidebar. It shows the list of available widgets.</li>
              <li class="lead">Click a widget you want to add. The widget should be added in the sidebar that started with "Coronavirus Tags".</li>
            </ol>
          </div>
          <div class="img-container" style="max-width:550px;">
            <img class="img-thumbnail" src="<?php echo get_template_directory_uri() . "/img/appearance-customize-widgets.png"?>" alt="">
          </div>
          <p class="lead">
            If your Theme does not support Theme Customizer then you can use the following conventional steps:
          </p>
          <ol>
            <li class="lead">Go to Appearance > Widgets in the WordPress Administration Screens.</li>
            <li class="lead">Choose a Widget and either drag it to the sidebar where you wish it to appear, or click the widget, (select a destination sidebar if your theme has more than one) and click the Add Widget button. There might be more than one sidebar option, so begin with the first one. Once in place</li>
            <li class="lead">Don't forget our widgets are starting with "Coronavirus Tags"</li>
          </ol>
          <div class="img-container" style="max-width:550px;">
            <img class="img-thumbnail" src="<?php echo get_template_directory_uri() . "/img/widget-place.png"?>" alt="">
          </div>
        </div>

        <!-- Shortcode -->
        <div class="tab-pane fade" id="list-shortcodes" role="tabpanel" aria-labelledby="list-messages-list">
          <h5>How to build and add covid19 shortcode ? </h5>
          <p class="lead remove-padding">
            Did you ever see a text within brackets in WordPress? Then you have seen a shortcode. Shortcuts add new features to your content and ease your work
          </p>
          <h6>Where can I use the shorcode ? </h6>
          <div class="lead remove-padding">
            <ol>
              <li class="lead">WordPress Posts</li>
              <li class="lead">WordPress Pages</li>
              <li class="lead">WordPress Widgets</li>
              <li class="lead">WordPress Themes</li>
            </ol>
          </div>

          <h6>How Can I Build Covid19 shorcode ? </h6>
          <div class="lead remove-padding">
            It's easy to start to build your own covid19 shorcode
          </div>
          <div class="lead remove-padding">
            <ol>
              <li class="lead">Go to admin panel of your website</li>
              <li class="lead">Click on "Covid19-Tags" that listed in admin menu</li>
              <li class="lead">Then Click On "Shortcode Builder"</li>
              <li class="lead">
                You Will see Two Parts
                <ol>
                  <li>
                    <b>Data Provider And Fields</b> : To Customize and build your covid19 shorcode

                  </li>
                  <li>
                    <b>Shortcode Content</b>: The content you want to copy it into your wordpress post , page, etc ..
                  </li>
                </ol>
              </li>
            </ol>
            <div class="img-container" style="max-width:550px;">
              <img class="img-thumbnail" src="<?php echo get_template_directory_uri() . "/img/shortcode-builder.png"?>" alt="">
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>





<?php get_footer(); ?>
