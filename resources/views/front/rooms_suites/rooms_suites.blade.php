@extends('front/templateFront')
@section('styles')
    <style>
        .span-style {
            display: inline-block;
            /*background-color: rgba(140, 80, 19, 0.8);*/
			background-color: rgba(232, 158, 85, 0.8);
            width: auto;
            padding: 10px;
            border: none;
            color: #fff;
            position: absolute;
            left: 15px;
            top: 10px;
            text-align: center;
            text-transform: uppercase
        }
        .btn{
          border:none !important;
        }
    </style>
@endsection
@section('content')

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
            <div class="room-grid-area bg-grey">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-full-width">
                            <div class="section-title-area text-center">
                                <h2 class="section-title">Rooms &amp; Suites</h2>
                                <p class="section-title-dec">Truly a home away from home.</p>
                            </div><!--/.section-title-area-->
                        </div><!--/.col-md-8-->
                    </div><!--/.row-->
                    <div class="row" style="margin:15px">
                      <div class="col-md-2">
                          <label class="text">Sort by :</label>
                          <div class="input box-radius">
                              <select name="room" id='cmbSorting'>
                                  <!-- <option value="new">New Arrivals</option> -->
                                  <option value="priceAsc">Price: Low - High</option>
                                  <option value="priceDesc">Price: High - Low</option>
                              </select>
                          </div>
                      </div>
                    </div>
                    <div class="row align-items-start" id='roomsection'>
                        @foreach($product as $products)
                            <div class="col-md-4 col-sm-6 col-xs-6">
                                <div class="single-room grid">
                                    @if(!empty($products->discount))
                                        <span class="span-style">Save
                                            @if($products->discount_by == 'percentage')
                                            {{$products->discount}}%
                                            @endif
                                            @if($products->discount_by == 'amount')
                                            RM{{$products->discount}}
                                            @endif
                                        </span>
                                    @endif
                                    <img src="{{ asset('public/admin/products/medium/'.$products->thumbnail_image_1) }}" alt="{{$products->type}}" class="room-thumb">
                                    @if($products->quantity_in_stock == 1 || $products->quantity_in_stock == 2 || $products->quantity_in_stock == 3)
                                      <div style="width: 30%;" class="text-center"><div class="highlight second-color text-12px" style="position: absolute; top:290px;"><b>ONLY {{$products->quantity_in_stock}} LEFT</b></div></div>
                                    @endif
                                    <?php
                                    $packages = DB::table('product_to_quantity_discount')->where('product_id',$products->id)->get();
                                    ?>

                                    {{-- 
                                    @if (count($packages) > 0)
                                        <span class="label" style="background-color: orangered;top: -13px !important;position: absolute !important;left: 117px !important;height: 30px;font-size: large;">Sell as "package"</span>
                                        <span class="label" style="background-color: green;top: 115px !important;position: absolute !important;right: 17px!important;font-size: inherit;">{{ $packages[0]->package_name }}</span>
                                    @endif
                                    --}}
                                    @if($products->promo_behaviour === 'sale')
                                          {{-- 
                                            @if (count($packages) <= 0)
                                                <span class="label" style="background-color: orangered;top: -13px !important;position: absolute !important;left: 117px !important;height: 30px;font-size: large;">Sell as "room promo"</span>
                                            @endif
                                            --}}
                                      <img class="promo" src="{{ asset('public/promo/sale_label.png') }}">
                                    @elseif($products->promo_behaviour === 'hot')
                                      <img class="promo" src="{{ asset('public/promo/hot_label.png') }}">
                                    @elseif($products->promo_behaviour === 'new')
                                     <img class="promo" src="{{ asset('public/promo/new_label.png') }}">
                                    @elseif($products->promo_behaviour === 'pwp')
                                     <img class="promo" src="{{ asset('public/promo/pwp_label.png') }}">
                                    @elseif($products->promo_behaviour === 'last_minute')
                                     <img class="promo" src="{{ asset('public/promo/last_minute.png') }}">
                                    @elseif($products->promo_behaviour === '24hoursale')
                                     <img class="promo" src="{{ asset('public/promo/24hour_sale.png') }}">
                                    @elseif($products->promo_behaviour === 'popular')
                                     <img class="promo" src="{{ asset('public/promo/popular.png') }}">
                                    @elseif($products->promo_behaviour === 'early_bird')
                                     <img class="promo" src="{{ asset('public/promo/early_bird.png') }}">

                                     @elseif($products->promo_behaviour === 'black_friday')
                                    <img class="promo" src="{{ asset('public/promo/black_friday.png') }}">
                                     @elseif($products->promo_behaviour === 'singles_day')
                                    <img class="promo" src="{{ asset('public/promo/singles_day.png') }}">
                                     @elseif($products->promo_behaviour === 'merdeka')
                                    <img class="promo" src="{{ asset('public/promo/merdeka.png') }}">
                                     @elseif($products->promo_behaviour === 'valentines')
                                    <img class="promo" src="{{ asset('public/promo/valentine.png') }}">

                                    @endif
                                    @if (count($packages) > 0)
                                        <span class="label" style="background-color: green;top: 115px !important;position: absolute !important;right: 17px!important;font-size: inherit;">{{ $packages[0]->package_name }}</span>
                                    @endif
                                    <div class="room-info box-radius">
                                        @if ($products->sale_price)
                                            <h5 style="font-size:17px !important">
                                                {{ ($products->starting_from == 1)?"Starting from ":"" }}
                                                RM {{ number_format((float)$products->sale_price, 2, '.', '') }}
                                                {{-- {{ ($products->is_tax == 0)?"nett":"" }} --}}
                                               @if($products->gross_price_per_night == 1)
                                               gross price
                                               @elseif($products->net_price_per_night == 1 ||  $products->is_tax == 0)
                                               nett price
                                               @endif / night </h5>
                                        @else
                                          <h5 style="font-size: 17px !important">Please call to enquire.</h5>
                                        @endif
                                        <h3 class="room-title"><a href="{{route('rooms-suites/show',$products->id)}}">{{$products->type}}</a></h3>
                                        <h4 class="room-structure">{{$products->guest}}</h4>
                                        <div class="room-services">
                                            <?php $amen = json_decode($products->amenities); ?>
                                            @if(!empty($amen))
                                              @if(isset($amen->computer)) <i class="fa fa-computer"></i> @endif
                                              @if(isset($amen->tv)) <i class="fa fa-television"></i> @endif
                                              @if(isset($amen->air)) <i class="fa-air-conditioner"></i> @endif
                                              @if(isset($amen->awesome)) <i class="fa fa-eye"></i> @endif
                                              @if(isset($amen->service )) <i class="fa fa-diamond"></i> @endif
                                              @if(isset($amen->pickup)) <i class="fa fa-plane"></i> @endif
                                              @if(isset($amen->wifi)) <i class="fa fa-wifi"></i> @endif
                                              @if(isset($amen->coffee )) <i class="fa fa-coffee"></i> @endif
                                              @if(isset($amen->lock )) <i class="fa fa-key"></i> @endif
                                            @else
                                              <i class="fa fa-computer" style="visibility: hidden;"></i>
                                            @endif
                                        </div>
                                        <a href="{{route('rooms-suites/show',$products->id)}}" class="btn btn-details">more
                                            details</a>
                                    </div>
                                </div><!--/.single-room-->
                            </div><!--/.col-md-4-->

                        @endforeach

                        <?php /*?>
          <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="single-room grid"><img src="{{ asset('public/front/images/rooms/img_super_deluxe.jpg') }}" alt="Super Deluxe Room" class="room-thumb">
              <div class="room-info box-radius">
                <h5>RM 168 nett / Night </h5><a href="#" class="room-title"></a>
                <h3 class="room-title"><a href="#">Super Deluxe Room</a></h3>
                <h4 class="room-structure">Max 2 People  </h4>
                <div class="room-services"><i class="fa fa-television"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-wifi"></i></div><a href="#" class="btn btn-details">more details</a>
              </div>
            </div><!--/.single-room-->
          </div>
          <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="single-room grid"><img src="{{ asset('public/front/images/rooms/img_family_deluxe.jpg') }}" alt="Family Deluxe Room" class="room-thumb">
              <div class="room-info box-radius">
                <h5>RM 200 nett / Night </h5>
                <h3 class="room-title"><a href="#">Family Deluxe Room</a></h3>
                <h4 class="room-structure">Max 2 Adults / 2 Kids  </h4>
                <div class="room-services"><i class="fa fa-television"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-wifi"></i></div><a href="#" class="btn btn-details">more details</a>
              </div>
            </div><!--/.single-room-->
          </div><?php */?>
                        <div class="clearfix hidden-xs"></div>


                        <?php /*?>  <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="single-room grid"><img src="{{ asset('public/front/images/rooms/img_premier.jpg') }}" alt="Premier Room" class="room-thumb">
              <div class="room-info box-radius">
                <h5>RM 240 nett / Night </h5>
                <h3 class="room-title"><a href="#">Premier Room</a></h3>
                <h4 class="room-structure">Max 2 People</h4>
                <div class="room-services"><i class="fa fa-television"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-wifi"></i></div><a href="#" class="btn btn-details">more details</a>
              </div>
            </div><!--/.single-room-->
          </div>
          <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="single-room grid"><img src="{{ asset('public/front/images/rooms/img_family_premier.jpg') }}" alt="Family Premier Room" class="room-thumb">
              <div class="room-info box-radius">
                <h5>Starting from RM 270 nett / Night </h5>
                <h3 class="room-title"><a href="#">Family Premier Room</a></h3>
                <h4 class="room-structure">Max 3 People / Max 4 People</h4>
                <div class="room-services"><i class="fa fa-television"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-wifi"></i></div><a href="#" class="btn btn-details">more details</a>
              </div>
            </div><!--/.single-room-->
          </div>
          <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="single-room grid"><img src="{{ asset('public/front/images/rooms/img_executive_suite.jpg') }}" alt="Executive Suite" class="room-thumb">
              <div class="room-info box-radius">
                <h5>RM 260 nett / Night </h5>
                <h3 class="room-title"><a href="#">Executive Suite</a></h3>
                <h4 class="room-structure">Max 2 People  </h4>
                <div class="room-services"><i class="fa fa-television"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-wifi"></i></div><a href="#" class="btn btn-details">more details</a>
              </div>
            </div><!--/.single-room-->
          </div>
          <div class="clearfix hidden-xs"></div>
          <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="single-room grid"><img src="{{ asset('public/front/images/rooms/img-5.jpg') }}" alt="Family Suite" class="room-thumb">
              <div class="room-info box-radius">
                <h5>RM 402 nett/ Night </h5>
                <h3 class="room-title"><a href="#">Family Suite</a></h3>
                <h4 class="room-structure">Max 2 Adults / 2 Kids</h4>
                <div class="room-services"><i class="fa fa-television"></i><i class="fa fa-microphone-slash"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-wifi"></i></div><a href="#" class="btn btn-details">more details</a>
              </div>
            </div><!--/.single-room-->
          </div>
          <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="single-room grid"><img src="{{ asset('public/front/images/rooms/img-2.jpg') }}" alt="Garden Suite" class="room-thumb">
              <div class="room-info box-radius">
                <h5>RM 460 nett / Night </h5>
                <h3 class="room-title"><a href="#">Garden Suite</a></h3>
                <h4 class="room-structure">Max 2 People</h4>
                <div class="room-services"><i class="fa fa-television"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-wifi"></i></div><a href="#" class="btn btn-details">more details</a>
              </div>
            </div><!--/.single-room-->
          </div>
          <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="single-room grid"><img src="{{ asset('public/front/images/rooms/img-4.jpg') }}" alt="Ritz Suite" class="room-thumb">
              <div class="room-info box-radius">
                <h5>RM 1500 nett / Night </h5>
                <h3 class="room-title"><a href="room-single-page.html">Ritz Suite</a></h3>
                <h4 class="room-structure">Max 2 People</h4>
                <div class="room-services"><i class="fa fa-television"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-wifi"></i></div><a href="room-single-page.html" class="btn btn-details">more details</a>
              </div>
            </div><!--/.single-room-->
          </div><?php */?>

                    </div><!--/.row-->


                </div><!--/.container-->
            </div><!--/.room-grid-area-->
        <script>
            $('#cmbSorting').change(function (){
                var type =$(this).val();
                $('#roomsection').empty();
                $.ajax({
                  type : 'POST',
                  url: '{{ url("/sort-rooms-suites") }}',
                  data : {id:{{ Request::segment(2) }}, type:type, _token: "{{ csrf_token() }}"},
                  success : function (data) {
                    var html='';
                    $.each(data,function (i,value) {
                      html +='<div class="col-md-4 col-sm-6 col-xs-6">'+
                                '<div class="single-room grid">';
                                    if(value['discount']){
                                      html +='<span class="span-style">Save';
                                          if(value['discount_by'] == 'percentage'){
                                            html+= value['discount'] + '%';
                                          }
                                          if(value['discount_by'] == 'amount'){
                                            html+= 'RM '+value['discount'];
                                            }
                                      html+=  '</span>';
                                    }
                                    html+='<img src="/public/admin/products/medium/'+value['thumbnail_image_1']+'"  alt="" class="room-thumb">'+
                                    '<div class="room-info box-radius">';
                                    if(value['promo_behaviour'] === 'sale')
                                    html+='<img class="promo" src="/public/promo/sale_label.png">';
                                    if(value['promo_behaviour'] === 'hot')
                                    html+='<img class="promo" src="/public/promo/hot_label.png">';
                                    if(value['promo_behaviour'] === 'new')
                                    html+='<img class="promo" src="/public/promo/new_label.png">';
                                    if(value['promo_behaviour'] === 'pwp')
                                    html+='<img class="promo" src="/public/promo/pwp_label.png">';

                                    if (value['sale_price']){
                                        html+="<h5 style='font-size:17px !important'>";
                                        if(value['starting_from'] == 1){
                                            html+= " Starting from ";
                                        }
                                        html+= "RM "+ parseFloat(value["sale_price"]).toFixed(2);
                                        if(value['gross_price_per_night'] == 1){
                                            html+=" gross price";
                                        } else if(value['net_price_per_night'] == 1 ||  value['is_tax'] == 0){
                                            html+=" nett price";
                                        }
                                        html+=" / night </h5>";
                                    } else {
                                        html+='<h5 style="font-size: 17px !important">Please call to enquire.</h5>';
                                    }

                                        // if (value['sale_price']){
                                        //     html+= "<h5> RM "+ parseFloat(value["sale_price"]).toFixed(2) ;
                                        //     html += (value["is_tax"] == 0)?"nett":""; +" / Night </h5>"+
                                        //     "<h5 style='font-size: 17px !important'>Please call to enquire.</h5>";
                                        // }
                                        html+='<h3 class="room-title"><a href="#">'+value['type']+'</a></h3>'+
                                        '<h4 class="room-structure">'+value['guest']+'</h4>'+
                                        '<div class="room-services">';
                                            var amen = value['amenities'];
                                            if(amen['computer'])
                                              html+='<i class="fa fa-computer"></i>';
                                            if(amen['tv'])
                                              html+='<i class="fa fa-television"></i>';
                                            if(amen['air'])
                                              html+='<i class="fa-air-conditioner"></i> ';
                                            if(amen['awesome'])
                                              html+=' <i class="fa fa-eye"></i>';
                                            if(amen['service'] )
                                              html+='<i class="fa fa-diamond"></i>';
                                            if(amen['pickup'])
                                              html+=' <i class="fa fa-plane"></i>';
                                            if(amen['wifi'])
                                              html+=' <i class="fa fa-wifi"></i>';
                                            if(amen['coffee'] )
                                              html+=' <i class="fa fa-coffee"></i>';
                                            if(amen['lock'] )
                                              html+=' <i class="fa fa-key"></i> ';
                                              html+='</div>'+ '<a href="{{ url("rooms-suites/show") }}/'+value["id"]+'" class="btn btn-details">more details</a>'+
                                    '</div>'+
                                '</div></div>';

                    });
                      $('#roomsection').html(html);
                  }
                });
            });
        </script>
@endsection
