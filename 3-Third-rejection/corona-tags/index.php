<?php
  get_header();
?>

<!-- Title -->
<div class="template-header-container">
  <div class="container text-center tags-title title-container-d">

      <h1 class="covid-title">
        Conrona<span class="colored-item">virus</span> Tags
      </h1>
      <p>
        Coronavirus disease (COVID-19) is an infectious disease caused by a newly discovered coronavirus.
      </p>
  </div>

  <div class="container text-center tags-title">
    <p>
      Customize your own shortcode for <span class="colored-item">COVID 19</span> to be proper with your website
    </p>
  </div>
</div>

<div class="container">
  <?php echo do_shortcode( '[covtags-tricker-world-card tricker_speed="0" title="New Cases" dark_mode="yes" field="todayCases"]' ); ?>
</div>

</div>



<div class="container-fluid wp-section-dark">
    <div class="container clbox-data">
      <div class="row">
        <div class="col-xs-12 col-md-8">
          <div class="tags-headtitle">
            <h6>
              Standard Card ( Getting the updates every 10 minutes without refresh the page )
            </h6>
          </div>
          <div class="widget-contents">
            <?php echo do_shortcode( '[covtags-standard-card dark_mode="yes"]' );?>
          </div>

          <div class="widget-contents">
            <div class="tags-headtitle">
              <h6>
                Status Card With Graph Option ( Active or Closed )
              </h6>
            </div>
            <div class="row">
              <div class="col-xs-12 col-md-4">
                <div class="widget-contents">
                  <?php echo do_shortcode( '[covtags-status title="Closed Cases" dark_mode="yes" rounded="10" status_type="active" use_graph_with="bar"]' ); ?>
                </div>
              </div>
              <div class="col-xs-12 col-md-4">
                <div class="widget-contents">
                  <?php echo do_shortcode( '[covtags-status title="Active Cases" dark_mode="yes" rounded="10" status_type="closed" use_graph_with="line"]' ); ?>
                </div>
              </div>
              <div class="col-xs-12 col-md-4">
                <div class="widget-contents">
                  <?php echo do_shortcode( '[covtags-status title="USA" country="usa" dark_mode="yes" status_type="closed"  rounded="10" use_graph_with="doughnut"]' ); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 widget">
          <div class="tags-headtitle">
            <h6>
              Stats Card
            </h6>
          </div>
          <div class="widget-contents">
            <?php echo do_shortcode( '[covtags-statistics layout="flat" style="style-2" dark_mode="yes" rounded="15" title="World Wide" align_text="center" fields="cases,deaths,recovered,active,critical" icon_flag="yes"]' ); ?>
          </div>
          <div class="widget-contents">
            <?php echo do_shortcode( '[covtags-statistics layout="table" align_text="center" style="style-2" dark_mode="yes" rounded="15" title="World Wide" fields="cases,deaths,recovered,critical,active" icon_flag="yes"]' ); ?>
          </div>

          <div class="widget-contents">
            <?php echo do_shortcode( '[covtags-statistics layout="flat" style="style-1" dark_mode="yes" rounded="15" title="World Wide" align_text="center" fields="cases,deaths,recovered,active,critical" icon_flag="yes"]' ); ?>
          </div>

        </div>
      </div>
    </div>
</div>

<div class="container-fluid wp-section-white">
  <div class="container clbox-data">
    <div class="row">

      <div class="col-xs-12 col-md-6">
        <div class="tags-headtitle">
          <h6>
            Datatable with historical graph for each country
          </h6>
        </div>
        <div class="widget-contents">
          <?php echo do_shortcode( '[covtags-all-countries rows_per_page="6" dark_mode="no" graph_type="line" fields="cases,recovered,deaths" icon_flag="yes" paging_type="loadmore" desc_by="cases"]' ); ?>
        </div>
      </div>
      <div class="col-xs-12 col-md-6">
        <div class="tags-headtitle">
          <h6>
            Status With many options
          </h6>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-6">
            <div class="widget-contents">
              <?php echo do_shortcode( '[covtags-status title="USA" country="usa" dark_mode="no" status_type="active"  rounded="0" use_graph_with="polarArea"]' ); ?>
            </div>
          </div>
          <div class="col-xs-12 col-md-6">
            <div class="widget-contents">
              <?php echo do_shortcode( '[covtags-status title="USA" country="usa" dark_mode="no" status_type="active"  rounded="0" use_graph_with="line"]' ); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12">
        <div class="widget-contents">
          <?php echo do_shortcode( '[covtags-all-countries rows_per_page="10" dark_mode="yes" graph_type="bar" fields="cases,todayCases,todayDeaths,recovered,deaths,critical,active,tests" icon_flag="yes" paging_type="serials" desc_by="cases"]' ); ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-md-4">
        <div class="tags-headtitle">
          <h6>
            Many Layout and settings
          </h6>
        </div>
        <div class="widget-contents">
          <?php echo do_shortcode( '[covtags-statistics dark_mode="no" style="style-1" layout="flat" align_text="center" title="Spain" fields="cases,deaths,recovered" icon_flag="yes" country="spain"]' ); ?>
        </div>
        <div class="widget-contents">
          <?php echo do_shortcode( '[covtags-statistics dark_mode="no" style="style-2" layout="flat" align_text="center" title="Italy" fields="cases,deaths,recovered,critical,active" icon_flag="yes" country="italy"]' ); ?>
        </div>
      </div>
      <div class="col-xs-12 col-md-4">
        <div class="widget-contents">
          <?php echo do_shortcode( '[covtags-statistics dark_mode="no" style="style-1" layout="table" align_text="center" title="United States" fields="todayDeaths,todayCases,cases,deaths,recovered,active,critical" icon_flag="yes" country="usa"]' ); ?>
        </div>
      </div>
      <div class="col-xs-12 col-md-4">
        <div class="widget-contents widget">
          <?php echo do_shortcode( '[covtags-standard-card dark_mode="no"]' );?>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container-fluid wp-section-set-color">
    <div class="container clbox-data">
      <div class="row">
        <div class="col-xs-12 col-md-8">
          <div class="tags-headtitle">
            <h6>
              Map With Dark mode
            </h6>
          </div>
          <?php echo do_shortcode( '[covtags-map dark_mode="yes"]' ); ?>
        </div>
        <div class="col-xs-12 col-md-4">
          <div class="tags-headtitle">
            <h6>
              World wide ticker
            </h6>
          </div>
          <div class="widget-contents">
            <?php echo do_shortcode( '[covtags-tricker-world-card title="Total Confirmed" dark_mode="no" field="cases" tricker_speed="35"]' ); ?>
          </div>
          <div class="tags-headtitle">
            <h6>
              Stats Card
            </h6>
          </div>
          <div class="widget-contents">
            <?php echo do_shortcode( '[covtags-statistics  layout="table" align_text="left" style="style-3" dark_mode="yes" rounded="0" title="China" fields="todayCases,todayDeaths,deaths,critical,recovered,active" icon_flag="yes"]' ); ?>
          </div>
          <div class="widget-contents">
            <?php echo do_shortcode( '[covtags-statistics country="china" layout="flat" align_text="center" style="style-3" dark_mode="yes" rounded="15" title="China" fields="deaths,critical,active" icon_flag="yes"]' ); ?>
          </div>
        </div>
      </div>
    </div>
</div>

<?php
  get_footer();
?>
