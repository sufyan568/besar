<?php
  $banners = \App\Models\BannerLeft::getBannerImages(25);
  $left = DB::table('pages')->where('page','=','events')->first();
  //$mid = DB::table('pages')->where('page','=','dining_two')->first();
  $right = DB::table('pages')->where('page','=','events_three')->first();
  $header = DB::table('page_header')->where('page','events')->get();
  //$page_heading = DB::table('page_header')->where('page', 'dining')->first();
  //$heading= unserialize($page_heading->content);
  $left = unserialize($left->new_content);
 // $mid = unserialize($mid->new_content);
  $right = unserialize($right->new_content);
  $page_images = DB::table('page_images')
    ->where(array('page' => 'about','type' => 'images','status'=>1))
    ->Orderby('order', 'asc')->limit(6)->get();

  $page_images_left = DB::table('page_images')
    ->where(array('page' => 'events','type' => 'left','status'=>1))
    ->Orderby('order', 'asc')->limit(2)->get();

  $page_images_right = DB::table('page_images')
    ->where(array('page' => 'events','type' => 'right','status'=>1))
    ->Orderby('order', 'asc')->limit(2)->get();

  $page_images_bottom = DB::table('page_images')
    ->where(array('page' => 'events','type' => 'bottom','status'=>1))
    ->Orderby('order', 'asc')->limit(10)->get();
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
    <!--================= Content Area ===================-->
    <div class="spa-page-area">
      <div class="container">
            <div class="row">
              <div class="col-md-8 col-full-width">
                <div class="section-title-area text-center">
                    <?= $header[0]->content ?>
                  {{-- <h2 class="section-title">Functions &amp; Meetings</h2>
                  <p class="section-title-dec">The leading choice in Ipoh for business meetings and functions.</p> --}}
                </div><!--/.section-title-area-->
              </div><!--/.col-md-6-->
            </div><!--/.row-->
            <div class="row">
              <div class="col-md-7">
                <div class="section-gallery-group">
                  <div class="row">
                    @if($page_images_left) @foreach($page_images_left as $image)
                    <div class="col-sm-6 col-sm-6 col-xs-6">
                     <figure class="item"><img src="{{ asset('public/images/events/'.$image->image) }}" alt="{{ $image->title }}"></figure>
                   </div>
                 @endforeach @endif
                    {{-- <div class="col-sm-6 col-sm-6 col-xs-6">
                      <figure class="item"><img src="{{ asset('public/front/images/events_meetings/img_cinnamon_room1.jpg') }}" alt="Cinnamon Function Hall"></figure>
                    </div> --}}
                    {{-- <div class="col-sm-6 col-sm-6 col-xs-6">
                      <figure class="item"><img src="{{ asset('public/front/images/events_meetings/img_cinnamon_room2.jpg') }}" alt="Cinnamon Function Hall"></figure> --}}
                    <div class="clearfix"></div>
                  </div>
                </div><!--/.section-gallery-group-->
              </div><!--/.col-md-7-->

              <div class="col-md-5">
                <div class="section-align-title-area">
                  <div class="section-align-title-area">
                   {{-- <h2 class="section-align-title">Events beyond expectations.</h2>
                    <h3 class="dining-title">Cinnamon Function Halls</h3>
                    <p class="section-title-dec">Tower Regency is the leading choice in Ipoh for business meetings and functions. Whether you are hosting a Conference, Seminar, Workshop, Theatre Performance or a formal dinner, you can rest assure that all your needs and requirements will be well taken care.</p>
                    <p class="section-title-dec">Cinnamon Function Hall is located at 6th floor.</p> --}}
                    {!! $left[0] !!}


                  </div>
                </div><!--/.section-align-title-area-->
              </div><!--/.col-md-5-->


            </div><!--/.row-->
            <div class="clearfix margin-top"></div>

      </div><!--/.container-->
    </div><!--/.Cinnamon Function Halls-->

    <div class="spa-page-area bg-grey padding-top-70px">
      <div class="container">

        <div class="row">
          <div class="col-md-5">
            <div class="section-align-title-area">
              <div class="section-align-title-area">
                {!! $right[0] !!}
                {{-- <h2 class="section-align-title">Make your day a success.</h2>
                <h3 class="dining-title">Regency Function Halls</h3>
                <p class="section-title-dec">There are 6 Regency rooms which hold between 20 to 110 pax each. With 9 functions rooms with varying sizes and configuration to choose from, each supported with excellent audio-visual equipment, sound systems, white board and stationary.</p>
                <p class="section-title-dec">Various meeting and dining configurations can be arranged to suit each need and requirement. Other areas which can be accommodated for meetings and functions include the pool side terrace, and the Sky Lounge.</p> --}}
              </div>
            </div><!--/.section-align-title-area-->
          </div><!--/.col-md-5-->

          <div class="col-md-7">
            <div class="section-gallery-group">
              <div class="row">
                @if($page_images_right) @foreach($page_images_right as $image)
                <div class="col-sm-6 col-sm-6 col-xs-6">
                 <figure class="item"><img src="{{ asset('public/images/events/'.$image->image) }}" alt="{{ $image->title }}"></figure>
               </div>
             @endforeach @endif
              {{--   <div class="col-sm-6 col-sm-6 col-xs-6">
                  <figure class="item"><img src="{{ asset('public/front/images/events_meetings/img_regency_hall1.jpg') }}" alt="Regency Hall"></figure>
                </div>
                <div class="col-sm-6 col-sm-6 col-xs-6">
                  <figure class="item"><img src="{{ asset('public/front/images/events_meetings/img_regency_hall2.jpg') }}" alt="Regency Hall"></figure>
                </div> --}}
                <div class="clearfix"></div>
              </div>
            </div><!--/.section-gallery-group-->
          </div><!--/.col-md-7-->

        </div><!--/.row-->

        <div class="clearfix margin-top"></div>

      </div><!--/.container-->
    </div><!--/.Regency Function Halls -->

    <div class="page-gallery-area">
      <div class="page-gallery-carousel owl-carousel">
        @if($page_images_bottom) @foreach($page_images_bottom as $image)
        <div class="item"><img src="{{ asset('public/images/events/'.$image->image) }}" alt="{{ $image->title }}"></div>
  @endforeach @endif
        {{-- <div class="item"><img src="{{ asset('public/front/images/events_meetings/img_cinnamon_room3.jpg') }}" alt="Cinnamon room theatre style"></div><!--/.item-->
        <div class="item"><img src="{{ asset('public/front/images/events_meetings/img_regency_hall3.jpg') }}" alt="Regency hall"></div><!--/.item-->
        <div class="item"><img src="{{ asset('public/front/images/events_meetings/img_regency_hall4.jpg') }}" alt="Regency hall classroom style"></div><!--/.item-->
        <div class="item"><img src="{{ asset('public/front/images/events_meetings/img_regency_hall5.jpg') }}" alt="Regency hall board room style"></div><!--/.item--> --}}
      </div><!--/.page-gallery-carousel-->
    </div><!--/.page-gallery-area-->
