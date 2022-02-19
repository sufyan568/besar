
<?php
  $banners = \App\Models\BannerLeft::getBannerImages(12); 

  // print_r($content[0]->content1);exit;
?>
<!--================= Page Wellcome Area ===================-->
@if($banners != NULL)
<div id="subcatCarousel" class="carousel slide subcat-slider" data-ride="carousel">
  
  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    @foreach($banners as $key =>$banner)
    <div class="item @if($key == 0) active @endif" style="background-image:url('{{ asset('public/admin/images/banner/left/'.$banner->banner) }}');height:350px">  
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
<div class="blog-page-area bg-grey">
    <div class="container">
        <div class="row">
          <div class="col-md-8 col-full-width">
            <div class="section-title-area text-center">
                <?=$content[0]->content1?>
              <!-- <h2 class="section-title">Promotions</h2>
              <p class="section-title-dec">From simple white coffee and toast to German and Italy selection or even for a taste of Oriental delight, you will not be disappointed!</p> -->
            </div><!--/.section-title-area-->
          </div><!--/.col-md-6-->
        </div>
        <div class="row">
          <div class="col-md-12">
                <?=$content[0]->content2?>       
                <?=$content[0]->content3?>
                <?=$content[0]->content4?>
                <?=$content[0]->content5?>
                <?=$content[0]->content6?>
                <?=$content[0]->content7?>
                <?=$content[0]->content8?>
                <?=$content[0]->content9?>
                <?=$content[0]->content10?>
                <?=$content[0]->content11?>
                <?=$content[0]->content12?>
                <?=$content[0]->content13?>
                <?=$content[0]->content14?>
                <?=$content[0]->content15?>
                <?=$content[0]->content16?>
                <?=$content[0]->content17?>
                <?=$content[0]->content18?>
                <?=$content[0]->content19?>
                <?=$content[0]->content20?>
            </div>
        </div>
    </div>
</div>
    <!-- ================= Content Area ===================--
    <section class="best-place-section best-place-style-two">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div id="gallery" class="our-place-gallery"><img alt="" src="{{ asset('public/front/images/about_us/img1.jpg') }}"><img alt="" src="{{ asset('public/front/images/about_us/img2.jpg') }}"><img alt="" src="{{ asset('public/front/images/about_us/img3.jpg') }}"><img alt="" src="{{ asset('public/front/images/about_us/img4.jpg') }}"><img alt="" src="{{ asset('public/front/images/about_us/img5.jpg') }}"><img alt="" src="{{ asset('public/front/images/about_us/img6.jpg') }}"></div><!--/.our-place-gallery-->
          </div><!--/.col-md-6--
          <div class="col-md-6 title">
            <div class="section-align-title-area">
              <h3 class="section-name">Truly a home away from home</h3>
              <h2 class="section-align-title">A uniquely sophisticated living experience</h2>
              <p class="section-title-dec">Strategically located within the main business hub of Ipoh city centre, Tower Regency Hotel & Apartments combine refined luxury with unparalleled accessibility to provide a uniquely sophisticated living experience. Tower Regency comes with 17 floors of elegantly appointed rooms and apartments, each with a spectacular view of the city that will take your breath away. It is the single hotel in Ipoh which combines all the professionalism required of a business hotel and all the recreational facilities expected of a holiday abode - all within the best location in the city.</p>
              <p class="section-title-dec">Awarded with a 4-star rating, the hotel warmly greets you with comfortably cosy yet modern interiors as you step into its cool confines. Truly a home away from home, allow us to make your stay in Ipoh a pleasant and memorable experience.</p>
              
              
            </div><!-/.section-align-title-area--
          </div><!-/.col-md-6--
          
          
          
              
            </div><!-/.section-align-title-area--
          </div><!-/.col-md-6--
        
        </div><!-/.row--
        
        	        
        
      </div><--/.container--
    </section><!-/.best-place-section -->