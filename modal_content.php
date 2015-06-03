<?php

defined( 'ABSPATH' ) or die( 'Sorry, nothing to see here!' );

?>

<div class="hidden-meta" data-gsn-title="{{ChainName}} circulars"></div>
<div data-ctrl-circular="" data-ng-init="isListView = (currentPath == '/circular/list' || currentPath == '/circular/text')">
  <h1>Weekly Ads</h1>
  <div class="col-md-9">
    <div data-gsn-ad-unit="1" data-ng-if="',visible-lg,visible-md'.indexOf(bsDisplayMode) > 0"></div> 
    <div style="padding-top: 5px">
      <div class="col-md-3" style="text-align: left; padding-left: 0; margin-left: 0;">
        <div data-gsn-ad-unit="6" 
          data-ng-if="',visible-lg,visible-md'.indexOf(bsDisplayMode) > 0" 
          data-gsn-sticky=".adUnit6" 
          data-bottom="200" data-top="60"></div>
      </div>
      <div class="col-md-9">
        <div data-ng-show="vm.noCircular">
          <h3>No circular for this store.</h3>
        </div>
        <div class="productImagePopOver hidden" data-ng-non-bindable="">
          <img data-ng-src="{{item.ImageUrl || getThemeUrl('/images/no_image.jpg')}}" style="width: 100%; max-height: 400px" />
        </div>
        <div data-gsn-spinner="{radius:30, width:8, length: 16}" data-show-if="vm.digitalCirc == null || vm.noCircular"></div>
        <div data-ng-if="!isListView">
            <div data-ng-include="getThemeUrl('/views/engine/circular-view-flyer.html')"></div>
        </div>
        <div data-ng-show="isListView">
          <div data-ng-include="getThemeUrl('/views/engine/circular-view-list.html')"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div data-ng-non-bindable="">
      <div class="circplus"></div>
    </div>
    <div data-ng-include="getThemeUrl('/views/engine/available-varieties.html')"></div>
    <div data-gsn-ad-unit="4" data-ng-if="',visible-lg,visible-md'.indexOf(bsDisplayMode) > 0" style="margin-top: -6px;"></div> 
  </div>
</div>
<style>
  .availableVarieties 
  {
    background-color: #d3d3d3;
    border: 1px solid #000000;
    border-top: none;
    border-radius: 3px;
    margin-bottom: 10px;
    margin-left: -15px;
    padding: 5px;
    max-height: 472px;
    width: 300px;
  }

  .varietiesHeader 
  {
    background-color: #767676;
    border-top: 1px solid #000000;
    margin: -5px -5px 5px;
    padding: 0 0 1px;
  }

  .varietiesHeader h4 
  {
    color: #ffffff;
    font-size: 12px;
    text-align: center;
  }

  .availableVarietiesImage 
  {
    margin-left: 46px;
  }

  .availableDescription 
  {
    height: 90px;
    overflow: hidden;
  }

  .availableVarietiesList 
  {
    height: 118px;
    overflow: scroll;
  }

  .availableVarieties ul 
  {
    padding: 0;
  }

  .availableVarieties ul li 
  {
    list-style-type: none;
  }

  .varieties li 
  {
    padding-bottom: 4px;
  }

  .varieties button 
  {
    background-color: #d5704b;
    color: #ffffff;
  }
</style>
<link href="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/jquery.qtip.min.css" rel="stylesheet" type="text/css"><!-- for circular imagemap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/3.0.5/photoswipe.min.css" rel="stylesheet" type="text/css"> 
<script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.3.0/handlebars.min.js"></script><!-- for circular imagemap -->              
<script src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/jquery.qtip.min.js"></script><!-- for circular image map -->