<?php
  $banners = \App\Models\BannerLeft::getBannerImages(3); 
  $left = DB::table('pages')->where('page','=','dinning')->first();
  $mid = DB::table('pages')->where('page','=','dining_two')->first();
  $right = DB::table('pages')->where('page','=','dining_three')->first();
  $header = DB::table('page_header')->where('page','dining')->get();
  //$page_heading = DB::table('page_header')->where('page', 'dining')->first();
  //$heading= unserialize($page_heading->content);
  $left = unserialize($left->new_content);
  $mid = unserialize($mid->new_content);
  $right = unserialize($right->new_content);
  $page_images = DB::table('page_images')
    ->where(array('page' => 'about','type' => 'images','status'=>1))
    ->Orderby('order', 'asc')->limit(6)->get();

  $page_images_left = DB::table('page_images')
    ->where(array('page' => 'dining','type' => 'left','status'=>1))
    ->Orderby('order', 'asc')->limit(2)->get();

  $page_images_right = DB::table('page_images')
    ->where(array('page' => 'dining','type' => 'right','status'=>1))
    ->Orderby('order', 'asc')->limit(2)->get();

  $page_images_bottom = DB::table('page_images')
    ->where(array('page' => 'dining','type' => 'bottom','status'=>1))
    ->Orderby('order', 'asc')->limit(10)->get();

  // dd($banner);
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

     {{-- {!! html_entity_decode($data[0]) !!} --}}
     <!--================= Content Area ===================-->
    <div class="spa-page-area">
      <div class="container">
            <div class="row">
              <div class="col-md-8 col-full-width">
                <div class="section-title-area text-center">
                   <?= $header[0]->content ?>
                   <?php //$heading[0] ?>
                {{--   <h2 class="section-title">Restaurant</h2>
                  <p class="section-title-dec">Tuck into our dining specials while feasting your eyes on the resplendent views surrounding Ipoh.</p> --}}

                </div><!--/.section-title-area-->
              </div><!--/.col-md-6-->
            </div><!--/.row-->
            <div class="row">
              <div class="col-md-7">
                <div class="section-gallery-group">
                  <div class="row">
                    @if($page_images_left) @foreach($page_images_left as $image)
                       <div class="col-sm-6 col-sm-6 col-xs-6">                   
                        <figure class="item"><img src="{{ asset('public/images/dining/'.$image->image) }}" alt="{{ $image->title }}"></figure>
                      </div>
                    @endforeach @endif
                    {{--  <div class="col-sm-6 col-sm-6 col-xs-6">                   
                      <figure class="item"><img src="{{ asset('public/front/images/dining/img_cafe6_1.jpg') }}" alt="Cafe@6"></figure>
                    </div>
                    <div class="col-sm-6 col-sm-6 col-xs-6">
                      <figure class="item"><img src="{{ asset('public/front/images/dining/img_cafe6_2.jpg') }}" alt="Cafe@6"></figure>
                    </div> --}}

                    <div class="clearfix"></div>
                  </div>
                </div><!--/.section-gallery-group-->
              </div><!--/.col-md-7-->
              
              <div class="col-md-5">
                <div class="section-align-title-area">
                  <div class="section-align-title-area">
                    {{--    <h2 class="section-align-title">Tantalize your tastebuds.</h2>
                    <h3 class="dining-title">Cafe@6</h3>
                    <p class="section-title-dec">Food lovers will be delighted with the culinary creations available at Cafe@6. Located on the 6th floor, this delightful all-day eatery serves up sumptuous western and oriental preparations that are sure to tantalize your tastebuds. </p>
                    <p class="section-title-dec">Offering all-day dining, Cafe@6 is open daily between 6:30am to 11pm while offering 24-hour room service. </p> --}}

                      {!! $left[0] !!}
                      
                  </div>
                </div>
              </div>
              
              
            </div>
            <div class="clearfix margin-top"></div>      
            
            <div class="row">
              <div class="col-md-12">
                  {{-- <p class="section-title-dec">Seat yourself down for a sumptous buffet breakfast or a'la carte lunch or tuck into the dinner specials while feasting your eyes on the resplendent views surrounding Ipoh. Offering a fusion of Western and local cuisine, Cafe@6 delectable delights are affordably priced. </p>
                    <p class="section-title-dec">Our coffee house located at the 6th floor serves a daily buffet breakfast (6:30am-10:30am) and buffet lunch (12noon-2:30pm). Our Hi-tea buffet (3pm-5:30pm) and dinner buffet (7:00pm-10:00pm) is served on Friday, Saturday, Sunday &amp; public holidays. Along with that our a la carte section is opened daily from 11am-10:30pm.</p> --}}

                  {!! $mid[0] !!}
                </div>
            </div>
            


            <div class="clearfix margin-top"></div>


      </div><!--/.container-->
    </div><!--/.cafe@6-->
    
    <div class="spa-page-area">
      <div class="container">
            
            <div class="row">
            
              <div class="col-md-5">
                <div class="section-align-title-area">
                  <div class="section-align-title-area">

                   {{--  <h2 class="section-align-title">Unwind in our cosy T Lounge.</h2>
                    <h3 class="dining-title">T Lounge</h3>
                    <p class="section-title-dec">The best place to sit back and relax in after a satisfying day, or simply to meet up with friends and family as music soothes your senses by our resident saxophonist. Ideal meeting place to enjoy these fun activities of pool, darts and beer.</p>
                    <p class="section-title-dec">The lounge is located at the ground floor of the hotel operating daily from 4pm to 1am from Mondays to Thursdays and to 2am on Fridays, Saturdays and eve of public holidays, closed on Sundays. We serve a variety of alcoholic and non-alcoholic beverages along with a selection of snacks.</p> --}}
                      {!! $right[0] !!}
                    
                  </div>
                </div><!--/.section-align-title-area-->
              </div><!--/.col-md-5-->
              
              <div class="col-md-7">
                <div class="section-gallery-group">
                  <div class="row">
                     @if($page_images_right) @foreach($page_images_right as $image)
                       <div class="col-sm-6 col-sm-6 col-xs-6">                   
                        <figure class="item"><img src="{{ asset('public/images/dining/'.$image->image) }}" alt="{{ $image->title }}"></figure>
                      </div>
                    @endforeach @endif
                  {{--   <div class="col-sm-6 col-sm-6 col-xs-6">                   
                      <figure class="item"><img src="{{ asset('public/front/images/tlounge/img_tlounge.jpg') }}" alt="T Lounge"></figure>
                    </div>
                    <div class="col-sm-6 col-sm-6 col-xs-6">
                      <figure class="item"><img src="{{ asset('public/front/images/tlounge/img_wine.jpg') }}" alt="T Lounge"></figure>
                    </div> --}}

                    <div class="clearfix"></div>
                  </div>
                </div><!--/.section-gallery-group-->
              </div><!--/.col-md-7-->
              
            </div><!--/.row-->        

            <div class="clearfix margin-top"></div>


      </div><!--/.container-->
    </div><!--/.T Lounge-->
    
    <div class="page-gallery-area">
      <div class="page-gallery-carousel owl-carousel">
         @if($page_images_bottom) @foreach($page_images_bottom as $image)                            
              <div class="item"><img src="{{ asset('public/images/dining/'.$image->image) }}" alt="{{ $image->title }}"></div>
        @endforeach @endif
        {{-- <div class="item"><img src="{{ asset('public/front/images/dining/img_cafe6_3.jpg') }}" alt="Cafe@6"></div>
        <div class="item"><img src="{{ asset('public/front/images/dining/img_food1.jpg') }}" alt="Cafe@6"></div>
        <div class="item"><img src="{{ asset('public/front/images/dining/img_cafe6_4.jpg') }}" alt="Cafe@6"></div>
        <div class="item"><img src="{{ asset('public/front/images/dining/img_food2.jpg') }}" alt="Cafe@6"></div>
        <div class="item"><img src="{{ asset('public/front/images/dining/img_cafe6_5.jpg') }}" alt="Cafe@6"></div>
        <div class="item"><img src="{{ asset('public/front/images/tlounge/img_tlounge2.jpg') }}" alt="T Lounge"></div>
        <div class="item"><img src="{{ asset('public/front/images/tlounge/img_tlounge3.jpg') }}" alt="T Lounge"></div>
        <div class="item"><img src="{{ asset('public/front/images/tlounge/img_tlounge4.jpg') }}" alt="T Lounge"></div>
        <div class="item"><img src="{{ asset('public/front/images/tlounge/img_tlounge5.jpg') }}" alt="T Lounge"></div> --}}
      </div><!--/.page-gallery-carousel-->
    </div><!--/.page-gallery-area-->
    
