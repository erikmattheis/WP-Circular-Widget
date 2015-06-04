<?php /*  */ ?>
<div data-ng-app="storeApp">
<div data-bs-display-mode="bsDisplayMode"><!-- do not hide this div, it is use to detect display size --></div> 
  <div class="layout-container">
    <div class="mainContent container" data-ng-view=""></div>
  </div>    
  <!-- above the fold scripts 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->

  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>   
  <script src="https://clientapix.gsn2.com/api/v1/store/sitecontentscript/<?php echo get_option('gsn_chain_id') ?>?nocache=1"></script>                                                  
  <script data-gsnid="<?php echo get_option('gsn_chain_id') ?>" src="http://cdn-staging.gsngrocers.com/script/gsndfp/gsndfp.js?nocache=1"></script>
  <script src="http://cdn-staging.gsngrocers.com/script/gsncore/latest/gsncore.js" class="gsn-noads"></script>
  <?php 
  echo '<script src="' . plugins_url( 'storeApp.js', __FILE__) . '"></script>';
  ?>                     
  <!-- angular depencencies -->                                             
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular-sanitize.min.js"></script><!-- security -->   
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular-route.min.js"></script><!-- routing and history -->  
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular-animate.min.js"></script><!-- carousel -->
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular-touch.min.js"></script> <!-- touch polyfill for ng-click -->

  <!-- below the fold: js   -->                                                                            
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script><!-- bootstrap -->   
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script><!-- bootstrap to angular bridge --> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.3.0/handlebars.min.js"></script><!-- for circular imagemap --> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/3.0.5/klass.min.js"></script><!-- required for photoswipe -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/3.0.5/code.photoswipe.jquery.min.js"></script><!-- for image gallery --> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/3.0.5/photoswipe.min.css" rel="stylesheet" type="text/css"> 

  <!--begin:analytics-->                 
  <script>
    (function (i, s, o, g, r, a, m) {
      i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments);
      }, i[r].l = 1 * new Date(); a = s.createElement(o),
      m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g;
      m.parentNode.insertBefore(a, m);
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
  </script>
  <div class="hidden" data-gsn-path-pixel="https://secure.adnxs.com/getuid?https://dataapi.gsn2.com/api/v1/partner/linkadnx/[[ProfileId]]/[[ChainId]]?adnxs_uid=$UID&nocache=[[CACHEBUSTER]]"></div>
  <!--end:analytics-->  
  <script>
    <!-- mobile collapse issue: https://github.com/twbs/bootstrap/issues/12852 -->
    jQuery(document).ready(function ($) {
      $(document).on('click.nav','.navbar-collapse.in',function(e) {
        if( $(e.target).is('a') ) {
            $(this).removeClass('in').addClass('collapse');
        }
      });
    });
  </script>  

</div>