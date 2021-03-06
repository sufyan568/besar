<?php
  $banners = \App\Models\BannerLeft::getBannerImages(4); 
  $header = DB::table('page_header')->where('page','facilities')->get();

  $page_images_left = DB::table('page_images')
    ->where(array('page' => 'facilities','type' => 'left','status'=>1))
    ->Orderby('order', 'asc')->get();

  $page_images_right = DB::table('page_images')
    ->where(array('page' => 'facilities','type' => 'right','status'=>1))
    ->Orderby('order', 'asc')->get();

  $page_images_bottom = DB::table('page_images')
    ->where(array('page' => 'facilities','type' => 'bottom','status'=>1))
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
              <?= isset($data[0]) ? $data[0] : ''  ?>
              
            </div><!--/.section-title-area-->
          </div><!--/.col-md-6-->
        </div><!--/.row-->
        <div class="row">
          <div class="col-md-7">
            <div class="section-gallery-group">
              <div class="row">
                @if(!empty($page_images_left)) 
                    <?php $cnt = 0; ?>
                    @foreach($page_images_left as $key => $image)
                       <div class="col-sm-6 col-sm-6 col-xs-6">                   
                        <figure class="item"><img src="{{ asset('public/images/facilities/'.$image->image) }}" alt="{{ $image->title }}"></figure>
                      </div>
                      <?php 
                        unset($page_images_left[$key]); 
                        $cnt++;
                        if($cnt == 2){
                            break;
                        }
                      ?>
                    @endforeach 
                @endif
               <!--  <div class="col-sm-6 col-sm-6 col-xs-6">                   
                  <figure class="item"><img src="{{ asset('public/front/images/facilities/img_swimming_pool1.jpg') }}" alt="Swimming Pool"></figure>
                </div>
                <div class="col-sm-6 col-sm-6 col-xs-6">
                  <figure class="item"><img src="{{ asset('public/front/images/facilities/img_swimming_pool2.jpg') }}" alt="Swimming Pool"></figure>
                </div> -->
                <div class="clearfix"></div>
              </div>
            </div><!--/.section-gallery-group-->
          </div><!--/.col-md-7-->
          <div class="col-md-5">
            <div class="section-align-title-area">
              <div class="section-align-title-area">
                {{-- <h2 class="section-align-title">Relax &amp; rejuvenate your day.</h2>
                <h3 class="dining-title">Swimming Pool</h3>
                <p class="section-title-dec">The open air swimming pool is located on the 6th floor, next to Cafe@6. Offering panoramic views of the limestone hills and of the Ipoh city skyline, it offers a relaxing atmosphere as you rejuvenate your day in the morning or wind down with a leisurely swim later in the evening.</p>
                <p class="section-title-dec"> You can even choose to have your dinner by the poolside, from the sumptuous selection of Cafe@6.</p> --}}
                {!! (isset($data[1]) ? $data[1] : '') !!}
              </div>
            </div><!--/.section-align-title-area-->
          </div><!--/.col-md-5-->
          
        </div><!--/.row-->
        
        <div class="clearfix margin-top"></div>

      </div><!--/.container-->
    </div><!--/.swimming pool-->
    
    
    <div class="spa-page-area bg-grey padding-top-70px">
      <div class="container">
        
        <div class="row">
          <div class="col-md-5">
            <div class="section-align-title-area">
              <div class="section-align-title-area">
                {{-- <h2 class="section-align-title">Keep up with your daily exercise.</h2>
                <h3 class="dining-title">Gymnasium</h3>
                <p class="section-title-dec">Need to keep up with your daily exercise routine while you are away from home? Step into our gymnasium on Level 6, equipped with the latest exercise equipment from treadmills, to stationary bicycles, chest and shoulder machines, work stations and weights. Lockers are available at the gym for the storage of clothing and valuables while you work out.</p>
                <p class="section-title-dec">Open from 7am until 9pm.</p> --}}
                {!! (isset($data[2]) ? $data[2] : '') !!}
              </div>
            </div><!--/.section-align-title-area-->
          </div><!--/.col-md-5-->
          <div class="col-md-7">
            <div class="section-gallery-group">
              <div class="row">
                @if(!empty($page_images_right)) 
                    <?php $cnt = 0; ?>
                    @foreach($page_images_right as $key => $image)
                       <div class="col-sm-6 col-sm-6 col-xs-6">                   
                        <figure class="item"><img src="{{ asset('public/images/facilities/'.$image->image) }}" alt="{{ $image->title }}"></figure>
                      </div>
                      <?php 
                        unset($page_images_right[$key]); 
                        $cnt++;
                        if($cnt == 2){
                            break;
                        }
                      ?>
                    @endforeach 
                @endif
                <!-- <div class="col-sm-6 col-sm-6 col-xs-6">                   
                  <figure class="item"><img src="{{ asset('public/front/images/facilities/img_gym1.jpg') }}" alt="Gym"></figure>
                </div>
                <div class="col-sm-6 col-sm-6 col-xs-6">
                  <figure class="item"><img src="{{ asset('public/front/images/facilities/img_gym2.jpg') }}" alt="Gym"></figure>
                </div> -->
                <div class="clearfix"></div>
              </div>
            </div><!--/.section-gallery-group-->
          </div><!--/.col-md-7-->
          
        </div><!--/.row-->
        
        <div class="clearfix margin-top"></div>

      </div><!--/.container-->
    </div><!--/.gym-->
    
    
    <div class="spa-page-area padding-top-70px">
      <div class="container">
        
        <div class="row">
          
          <div class="col-md-7">
            <div class="section-gallery-group">
              <div class="row">
                @if(!empty($page_images_left)) 
                    <?php $cnt = 0; ?>
                    @foreach($page_images_left as $key => $image)
                       <div class="col-sm-6 col-sm-6 col-xs-6">                   
                        <figure class="item"><img src="{{ asset('public/images/facilities/'.$image->image) }}" alt="{{ $image->title }}"></figure>
                      </div>
                      <?php 
                        unset($page_images_left[$key]); 
                        $cnt++;
                        if($cnt == 2){
                            break;
                        }
                      ?>
                    @endforeach 
                @endif
                <!-- <div class="col-sm-6 col-sm-6 col-xs-6">                   
                  <figure class="item"><img src="{{ asset('public/front/images/facilities/img_health_centre1.jpg') }}" alt="Spa"></figure>
                </div>
                <div class="col-sm-6 col-sm-6 col-xs-6">
                  <figure class="item"><img src="{{ asset('public/front/images/facilities/img_health_centre2.jpg') }}" alt="Jacuzzi"></figure>
                </div> -->
                <div class="clearfix"></div>
              </div>
            </div><!--/.section-gallery-group-->
          </div><!--/.col-md-7-->
          
          <div class="col-md-5">
            <div class="section-align-title-area">
              <div class="section-align-title-area">
              	{{-- <h2 class="section-align-title">Relaxing and soothing.</h2>
                                <h3 class="dining-title">Health Centre</h3>
                                <p class="section-title-dec">Located next to the swimming pool on Level 6, is the hotel's Health Centre. An ideal option to help you relax those tensed muscles after gruelling business sessions or to sooth aching feet after shopping and touring the city.</p>
                                <p class="section-title-dec">Open daily between 9am and 10pm.</p> --}}
                {!! (isset($data[3]) ? $data[3] : '') !!}
              </div>
            </div><!--/.section-align-title-area-->
          </div><!--/.col-md-5-->
        </div><!--/.row-->
        
        <div class="clearfix margin-top"></div>

      </div><!--/.container-->
    </div><!--/.health centre-->
    
    <div class="spa-page-area bg-grey padding-top-70px">
      <div class="container">
        
        <div class="row">
          <div class="col-md-5">
            <div class="section-align-title-area">
              <div class="section-align-title-area">
                {{-- <h2 class="section-align-title">Various snacks, drinks and amenities.</h2>
                <h3 class="dining-title">T-Mart</h3>
                <p class="section-title-dec">T-Mart, our very own in-house convenience store offering a variety of snacks, drinks and amenities. Ipoh's famous Yee Hup fragrant biscuits (????????????) are also available here. A must try is our very own Regency White Coffee.</p>
                <p class="section-title-dec">Open daily from 10am-8pm, located at the Lobby Arcade area.</p> --}}
                {!! (isset($data[4]) ? $data[4] : '') !!}
              </div>
            </div><!--/.section-align-title-area-->
          </div><!--/.col-md-5-->
          
          <div class="col-md-7">
            <div class="section-gallery-group">
              <div class="row">
                 @if(!empty($page_images_right)) 
                    <?php $cnt = 0; ?>
                    @foreach($page_images_right as $key => $image)
                       <div class="col-sm-6 col-sm-6 col-xs-6">                   
                        <figure class="item"><img src="{{ asset('public/images/facilities/'.$image->image) }}" alt="{{ $image->title }}"></figure>
                      </div>
                      <?php 
                        unset($page_images_right[$key]); 
                        $cnt++;
                        if($cnt == 2){
                            break;
                        }
                      ?>
                    @endforeach 
                @endif
               <!--  <div class="col-sm-6 col-sm-6 col-xs-6">                   
                  <figure class="item"><img src="{{ asset('public/front/images/facilities/img_tmart1.jpg') }}" alt="T-Mart"></figure>
                </div>
                <div class="col-sm-6 col-sm-6 col-xs-6">
                  <figure class="item"><img src="{{ asset('public/front/images/facilities/img_tmart2.jpg') }}" alt="T-Mart"></figure>
                </div> -->
                <div class="clearfix"></div>
              </div>
            </div><!--/.section-gallery-group-->
          </div><!--/.col-md-7-->
          
        </div><!--/.row-->
        
        <div class="clearfix margin-top"></div>

      </div><!--/.container-->
    </div><!--/.t mart -->
    
     <div class="page-gallery-area padding-top-70px">
      <div class="page-gallery-carousel owl-carousel">
        @if($page_images_bottom) @foreach($page_images_bottom as $image)                            
              <div class="item"><img src="{{ asset('public/images/facilities/'.$image->image) }}" alt="{{ $image->title }}"></div>
        @endforeach @endif
        {{-- <div class="item"><img src="{{ asset('public/front/images/facilities/img_swimming_pool3.jpg') }}" alt="Swimming pool"></div>
        <div class="item"><img src="{{ asset('public/front/images/facilities/img_gym3.jpg') }}" alt="Gym"></div>
        <div class="item"><img src="{{ asset('public/front/images/facilities/img_gym4.jpg') }}" alt="Gym"></div>
        <div class="item"><img src="{{ asset('public/front/images/facilities/img_gym5.jpg') }}" alt="Gym"></div> --}}
      </div><!--/.page-gallery-carousel-->
    </div><!--/.page-gallery-area-->
    
