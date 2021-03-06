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
              <p class="section-title-dec">Our varied choice of rooms has been designed to suit your needs, be it for business travellers, family or group leisure.</p>
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
        <div class="row align-items-start" id='apartsection'>
         @foreach($apartments as $product)
         <?php
         $packages = DB::table('product_to_quantity_discount')->where('product_id',$product->id)->get();
        ?>
         @if (count($packages) && count($packages) > 1)
         @foreach ($packages as $package)
         <div class="col-md-4 col-sm-6 col-xs-6">
          <div class="single-room grid">
            @if(!empty($product->discount))
                <span class="span-style">Save
                 @if($product->discount_by == 'percentage')
                 {{$product->discount}}%
                 @endif
                 @if($product->discount_by == 'amount')
                 RM{{$product->discount}}
                 @endif
                </span>
            @endif
            <img src="{{ asset('public/admin/products/medium/'.$product->thumbnail_image_1) }}" alt="deluxe room" class="room-thumb">
             @if($product->quantity_in_stock == 1 || $product->quantity_in_stock == 2 || $product->quantity_in_stock == 3)
               <div style="width: 30%;" class="text-center"><div class="highlight second-color text-12px" style="position: absolute; top:290px;"><b>ONLY {{$product->quantity_in_stock}} LEFT</b></div></div>
             @endif
             @if($product->promo_behaviour === 'sale')
             <img class="promo" src="{{ asset('public/promo/sale_label.png') }}">
             @elseif($product->promo_behaviour === 'hot')
             <img class="promo" src="{{ asset('public/promo/hot_label.png') }}">
             @elseif($product->promo_behaviour === 'new')
             <img class="promo" src="{{ asset('public/promo/new_label.png') }}">
             @elseif($product->promo_behaviour === 'pwp')
             <img class="promo" src="{{ asset('public/promo/pwp_label.png') }}">
              @elseif($product->promo_behaviour === 'last_minute')
             <img class="promo" src="{{ asset('public/promo/last_minute.png') }}">
              @elseif($product->promo_behaviour === '24hoursale')
             <img class="promo" src="{{ asset('public/promo/24hour_sale.png') }}">
              @elseif($product->promo_behaviour === 'popular')
             <img class="promo" src="{{ asset('public/promo/popular.png') }}">
              @elseif($product->promo_behaviour === 'early_bird')
             <img class="promo" src="{{ asset('public/promo/early_bird.png') }}">

              @elseif($product->promo_behaviour === 'black_friday')
             <img class="promo" src="{{ asset('public/promo/black_friday.png') }}">
              @elseif($product->promo_behaviour === 'singles_day')
             <img class="promo" src="{{ asset('public/promo/singles_day.png') }}">
              @elseif($product->promo_behaviour === 'merdeka')
             <img class="promo" src="{{ asset('public/promo/merdeka.png') }}">
              @elseif($product->promo_behaviour === 'valentines')
             <img class="promo" src="{{ asset('public/promo/valentine.png') }}">
             
             @endif
           
                <span class="label" style="background-color: green;top: 115px !important;position: absolute !important;right: 17px!important;font-size: inherit;">{{ $package->package_name }}</span>
            <div class="room-info box-radius">
             @if ($product->sale_price)
             <h5 style="font-size:17px !important">
               {{ ($product->starting_from == 1)?"Starting from ":"" }}
               RM {{ number_format((float)$product->sale_price, 2, '.', '') }} 
               {{-- {{ ($product->is_tax == 0)?"nett":"" }}  --}}
              @if($product->gross_price_per_night == 1)
              gross price
              @elseif($product->net_price_per_night == 1 || $product->is_tax == 0)
              nett price
              @endif / night 
             </h5>
             @else
            <h5 style="font-size: 17px !important">Please call to enquire.</h5>
             @endif
              <h3 class="room-title"><a href="{{route('apartments/show',[$product->id, $package->id])}}">{{$product->type}}</a></h3>
              <h4 class="room-structure">{{$product->guest}}</h4>
              <div class="room-services">
                  <?php $amen = json_decode($product->amenities); ?>
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
                <a href="{{route('apartments/show',[$product->id,$package->id])}}" class="btn btn-details">more details</a>
            </div>
          </div><!--/.single-room-->
        </div><!--/.col-md-4-->
         @endforeach
         @else
         <div class="col-md-4 col-sm-6 col-xs-6">
          <div class="single-room grid">
            @if(!empty($product->discount))
                <span class="span-style">Save
                 @if($product->discount_by == 'percentage')
                 {{$product->discount}}%
                 @endif
                 @if($product->discount_by == 'amount')
                 RM{{$product->discount}}
                 @endif
                </span>
            @endif
            <img src="{{ asset('public/admin/products/medium/'.$product->thumbnail_image_1) }}" alt="deluxe room" class="room-thumb">
             @if($product->quantity_in_stock == 1 || $product->quantity_in_stock == 2 || $product->quantity_in_stock == 3)
               <div style="width: 30%;" class="text-center"><div class="highlight second-color text-12px" style="position: absolute; top:290px;"><b>ONLY {{$product->quantity_in_stock}} LEFT</b></div></div>
             @endif
             @if($product->promo_behaviour === 'sale')
             <img class="promo" src="{{ asset('public/promo/sale_label.png') }}">
             @elseif($product->promo_behaviour === 'hot')
             <img class="promo" src="{{ asset('public/promo/hot_label.png') }}">
             @elseif($product->promo_behaviour === 'new')
             <img class="promo" src="{{ asset('public/promo/new_label.png') }}">
             @elseif($product->promo_behaviour === 'pwp')
             <img class="promo" src="{{ asset('public/promo/pwp_label.png') }}">
              @elseif($product->promo_behaviour === 'last_minute')
             <img class="promo" src="{{ asset('public/promo/last_minute.png') }}">
              @elseif($product->promo_behaviour === '24hoursale')
             <img class="promo" src="{{ asset('public/promo/24hour_sale.png') }}">
              @elseif($product->promo_behaviour === 'popular')
             <img class="promo" src="{{ asset('public/promo/popular.png') }}">
              @elseif($product->promo_behaviour === 'early_bird')
             <img class="promo" src="{{ asset('public/promo/early_bird.png') }}">

              @elseif($product->promo_behaviour === 'black_friday')
             <img class="promo" src="{{ asset('public/promo/black_friday.png') }}">
              @elseif($product->promo_behaviour === 'singles_day')
             <img class="promo" src="{{ asset('public/promo/singles_day.png') }}">
              @elseif($product->promo_behaviour === 'merdeka')
             <img class="promo" src="{{ asset('public/promo/merdeka.png') }}">
              @elseif($product->promo_behaviour === 'valentines')
             <img class="promo" src="{{ asset('public/promo/valentine.png') }}">
             
             @endif
           
            @if (count($packages) > 0)
                <span class="label" style="background-color: green;top: 115px !important;position: absolute !important;right: 17px!important;font-size: inherit;">{{ $packages[0]->package_name }}</span>
            @endif
            <div class="room-info box-radius">
             @if ($product->sale_price)
             <h5 style="font-size:17px !important">
               {{ ($product->starting_from == 1)?"Starting from ":"" }}
               RM {{ number_format((float)$product->sale_price, 2, '.', '') }} 
               {{-- {{ ($product->is_tax == 0)?"nett":"" }}  --}}
              @if($product->gross_price_per_night == 1)
              gross price
              @elseif($product->net_price_per_night == 1 || $product->is_tax == 0)
              nett price
              @endif / night 
             </h5>
             @else
            <h5 style="font-size: 17px !important">Please call to enquire.</h5>
             @endif
              <h3 class="room-title"><a href="{{route('apartments/show',$product->id)}}">{{$product->type}}</a></h3>
              <h4 class="room-structure">{{$product->guest}}</h4>
              <div class="room-services">
                  <?php $amen = json_decode($product->amenities); ?>
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
                <a href="{{route('apartments/show',$product->id)}}" class="btn btn-details">more details</a>
            </div>
          </div><!--/.single-room-->
        </div><!--/.col-md-4-->
         @endif
           
        @endforeach
        </div>

      </div><!--/.container-->
    </div><!--/.room-grid-area-->
    <script>
            $('#cmbSorting').change(function (){
                var type =$(this).val();
                $('#apartsection').empty();
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


                                        // if (value['sale_price']){
                                        //     html+= "<h5> RM "+ parseFloat(value["sale_price"]).toFixed(2) ;
                                        //     html += (value["is_tax"] == 0)?"nett":""; +" / Night </h5>"+
                                        //     "<h5>Please call to enquire.</h5>";
                                        // }
                                        

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
                                              html+='</div>'+ '<a href="{{ url("apartments/show") }}/'+value["id"]+'" class="btn btn-details">more details</a>'+
                                    '</div>'+
                                '</div></div>';
                    
                    });
                      $('#apartsection').html(html);
                  }
                });
            });
        </script>
