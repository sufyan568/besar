<?php
  $banners = \App\Models\BannerLeft::getBannerImages(2); 
?>
<!--================= Page Wellcome Area ===================-->
@if($banners != NULL)
<div id="subcatCarousel" class="carousel slide subcat-slider" data-ride="carousel">
  
  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    @foreach($banners as $key =>$banner)
    <div class="item @if($key == 0) active @endif" style="background-image:url('{{ asset('public/admin/images/banner/left/'.$banner->banner) }}')">
      
    </div>
    @endforeach
  <!-- Left and right controls -->
  <a class="left carousel-control" href="#subcatCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#subcatCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
@endif

    <!--================= Content Area ===================-->
    <div class="blog-page-area bg-grey">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-full-width">
            <div class="section-title-area text-center">
              <h2 class="section-title">Promotions</h2>
              <p class="section-title-dec">From simple white coffee and toast to German and Italy selection or even for a taste of Oriental delight, you will not be disappointed!</p>
            </div><!--/.section-title-area-->
          </div><!--/.col-md-6-->
        </div><!--/.row-->
        
        <div class="row">
          <div class="col-md-12">

                <div class="row">
                  
                  <div class="col-md-4 col-sm-6 col-xs-6">
                  <!-- Single post-->
                  <div class="post hentry box-radius text-center">
                    <div class="entry-header">
                      <figure class="post-thumb"><a href="{{ asset('public/front/images/promotions/img_ramadhan_special_large.jpg') }}" class="image-popup-btn"><img src="{{ asset('public/front/images/promotions/img_ramadhan_special.jpg') }}" alt="Ramadhan Special Deluxe Room Promotion "></a></figure><!--/.post-thumb-->
                      <div class="entry-meta"><span class="entry-date">May 06, 2019  </span><!--<span class="entry-cat"><a href="#">Meeting & Events </a></span>--></div><!--/.entry-meta-->
                    </div><!--/.entry-header-->
                    <h2 class="entry-title"><a href="{{ asset('public/front/images/promotions/img_ramadhan_special_large.jpg') }}" class="image-popup-btn">Ramadhan Special Deluxe Room Promotion</a></h2><!--/.entry-title-->
                    <div class="entry-footer">
                      <div class="entry-footer-meta entry-meta"><span class="entry-see"></span></div><!--/.entry-footer-meta-->
                    </div><!--/.entry-footer-->
                  </div><!--/.post-->
                </div><!--/.col-md-4--> 
                  <div class="clearfix hidden-md hidden-lg"></div>
                  <div class="col-md-4 col-sm-6 col-xs-6">
                  <!-- Single post-->
                  <div class="post hentry box-radius text-center">               
                    <div class="entry-header">
                      <figure class="post-thumb"><a href="{{ asset('public/front/images/promotions/img_ramadhan_buffet_large.jpg') }}" class="image-popup-btn"><img src="{{ asset('public/front/images/promotions/img_ramadhan_buffet.jpg') }}" alt="Semarak Rasa Tower Ramadhan Buffet"></a></figure><!--/.post-thumb-->
                      <div class="entry-meta"><span class="entry-date">May 06, 2019  </span><!--<span class="entry-cat"><a href="#">Dining</a></span>--></div><!--/.entry-meta-->
                    </div><!--/.entry-header-->
                    <h2 class="entry-title"><a href="{{ asset('public/front/images/promotions/img_ramadhan_buffet_large.jpg') }}" class="image-popup-btn">Semarak Rasa Tower Ramadhan Buffet</a></h2><!--/.entry-title-->
                    <div class="entry-footer">
                      <div class="entry-footer-meta entry-meta"><span class="entry-see"></span></div><!--/.entry-footer-meta-->
                    </div><!--/.entry-footer-->
                  </div><!--/.post-->
                </div><!--/.col-md-4--> 
                  <div class="clearfix hidden-xs hidden-sm"></div>
                  
                  <div class="clearfix hidden-md hidden-lg"></div>
                  
                </div><!--/.row--> 
       
          </div><!--/. col-md-12-->
        </div>
      </div>
    </div><!--/.promotions-->