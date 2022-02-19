<?php
$rateData=DB::table('gst_rates')->first();
?>
@extends('adminLayout')

@section('content')
@push('styles')
<link type="text/css" rel="stylesheet" href="{{ asset('/public/admin/css/price-calendar.css') }}">
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
  .cke_editable{ min-height:20px;}
  .radio-inline + .radio-inline, .checkbox-inline + .checkbox-inline{
    margin-left: 0px;
  }
  .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td {
      border-top: 0;
      border: 1px solid #ddd;
      background: #f9f9f9;
      text-align: center;
  }
  .table-striped > tbody > tr:nth-child(odd) > td, .table-striped > tbody > tr:nth-child(odd) > th {
      background-color: #f9f9f9;
      text-align: center;
      border: 1px solid #ddd;
  }
</style>
@endpush
<!--BEGIN PAGE WRAPPER-->
<div id="page-wrapper">
  <!--BEGIN PAGE HEADER & BREADCRUMB-->
  <div class="page-header-breadcrumb">
    <div class="page-heading hidden-xs">
      <h1 class="page-title">Services</h1>
    </div>

    <ol class="breadcrumb page-breadcrumb">
      <li><i class="fa fa-home"></i>&nbsp;<a href="{{ url('/web88cms/dashboard') }}">Dashboard</a>&nbsp; <i class="fa fa-angle-right"></i>&nbsp;</li>
      <li>Services &nbsp;<i class="fa fa-angle-right"></i>&nbsp;</li>
      <li><a href="{{ url('/web88cms/products/list') }}">Services Listing</a> &nbsp;<i class="fa fa-angle-right"></i>&nbsp;</li>
      <li class="active">Service - Edit</li>
    </ol>
  </div>
  <!--END PAGE HEADER & BREADCRUMB-->

  <!--BEGIN CONTENT-->
  <div class="page-content">
    <div class="row">
      <div class="col-lg-12">
        <h2>Service <i class="fa fa-angle-right"></i> Edit</h2>
        <div class="clearfix"></div>

        <!--Message alert-->
        @if (Session::has('success'))
        <div class="alert alert-success alert-dismissable">
          <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
          <i class="fa fa-check-circle"></i> <strong>Success!</strong>
          <p>{{Session::get('success')}}</p>
        </div>
        @elseif (Session::has('error'))
        <div class="alert alert-danger alert-dismissable">
          <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
          <i class="fa fa-times-circle"></i> <strong>Error!</strong>
          <p>{{Session::get('error')}}</p>
        </div>
        @endif

        @if(session()->has('data.success'))
        <div class="alert alert-success alert-dismissable">
          <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
          <i class="fa fa-check-circle"></i> <strong>Success!</strong>
          <p>{{  session('data.success') }}</p>
        </div>
        @endif

        @if($success_response)
        <div class="alert alert-success alert-dismissable">
          <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
          <i class="fa fa-check-circle"></i> <strong>Success!</strong>
          <p>{{ $success_response }}</p>
        </div>
        @endif

        @if(session()->has('data.error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
            <i class="fa fa-check-circle"></i> <strong>Error!</strong>
            <p>{{  session('data.error') }}</p>
          </div>
        @endif

        <!-- validation errors -->
			 	@if($errors->has())
        <div class="alert alert-danger alert-dismissable">
            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
            <i class="fa fa-times-circle"></i> <strong>Error!</strong>
            @foreach ($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
        </div>
				@endif

        <?php
			 // dd($details);
			  $productDetails = $details['productDetails'];
			  ?>

        <div class="pull-left"> Last updated: <span class="text-blue"><?php echo date('d M, Y @ g:i A',strtotime($details['productDetails']->last_modified)); ?></span> </div>
        <div class="clearfix"></div>
        <p></p>
        <ul id="myTab" class="nav nav-tabs">
          <li {{ ((!$tab) || ($tab=='general'))?'class=active':'' }}><a href="#general" data-toggle="tab" onclick="document.getElementById('description-feature').style.height='0px'; document.getElementById('description-feature').style.overflow='hidden';">General</a></li>
          <li {{ (($tab) && ($tab=='images'))?'class=active':'' }}><a href="#images" data-toggle="tab" onclick="document.getElementById('description-feature').style.height='0px'; document.getElementById('description-feature').style.overflow='hidden';">Images</a></li>
          <li {{ (($tab) && ($tab=='description-feature'))?'class=active':'' }}><a href="#description-feature" data-toggle="tab" onclick="document.getElementById('description-feature').style.height='auto'; document.getElementById('description-feature').style.overflow='none';">Description &amp; Features</a></li>
          <li {{ (($tab) && ($tab=='room-amenities'))?'class=active':'' }}><a href="#room-amenities" data-toggle="tab" onclick="document.getElementById('description-feature').style.height='0px'; document.getElementById('description-feature').style.overflow='hidden';">Room Amenities</a></li>
          <li {{ (($tab) && ($tab=='quantity-discount'))?'class=active':'' }}><a href="#quantity-discount" data-toggle="tab" onclick="document.getElementById('description-feature').style.height='0px'; document.getElementById('description-feature').style.overflow='hidden';">Packages</a></li>
          <li {{ (($tab) && ($tab=='pwp'))?'class=active':'' }}><a href="#pwp" data-toggle="tab" onclick="document.getElementById('description-feature').style.height='0px'; document.getElementById('description-feature').style.overflow='hidden';">PWP Setup</a></li>
        </ul>

        <div id="myTabContent" class="tab-content">
          <div id="general" class="tab-pane fade in {{ ((!$tab) || ($tab=='general'))?'in active':'' }}">
            <form class="form-horizontal" method="post" action="{{ url('web88cms/products/editProduct/'.$productDetails->id) }}" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <div class="portlet">
                <div class="portlet-header">
                  <div class="caption">General</div>
                  <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
                </div>

                <div class="portlet-body">
                  <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Status <span class="text-red">*</span></label>
                            <div class="col-md-6">
                              <div data-on="success" data-off="primary" class="make-switch">
                                <input type="checkbox" name="status" <?php if($productDetails->status == '1'){ if(true || ($productDetails->quantity_in_stock > 0)) echo 'checked="checked"'; } ?> />
                              </div>
                              <br>
                              <span><b>Note:</b> 'Active' = to show room in frontend</span><br>
                              <span>'InActive' = to hide room in frontend</span>
                            </div>
                        </div>

                        <div class="form-group">
                        <label for="inputFirstName" class="col-md-3 control-label">Pick Up List <span class="text-red">*</span></label>
                        <div class="col-md-6">
                          <select name="property_id" id="property_id" class="form-control">
                              <option>Please Select Pick Up List</option>
                              @if(count($property) > 0)
                                @foreach($property as $p)
                                    <option @if($p->property_id == $productDetails->property_id) selected @endif  value="{{$p->property_id}}">{{$p->name}}</option>
                                @endforeach
                              @endif
                          </select>
                        </div>
                      </div>
                        <div class="form-group">
                        <label for="inputFirstName" class="col-md-3 control-label">Drop Off List <span class="text-red">*</span></label>
                        <div class="col-md-6">
                          <select name="drop_list_id" id="drop_list_id" class="form-control">
                              <option>Please Select Drop Off List</option>
                              @if(count($drop_off_list) > 0)
                                @foreach($drop_off_list as $p)
                                    <option @if($p->drop_list_id == $productDetails->drop_list_id) selected @endif  value="{{$p->drop_list_id}}">{{$p->name}}</option>
                                @endforeach
                              @endif
                          </select>
                        </div>
                      </div>

                        <div class="form-group">
                            <label for="inputFirstName" class="col-md-3 control-label">Type <span class="text-red">*</span></label>
                            <div class="col-md-6">
                              <input type="text" class="form-control" placeholder="eg. Premier Room" name="type" value="<?php echo htmlspecialchars($productDetails->type,ENT_QUOTES); ?>">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="inputFirstName" class="col-md-3 control-label">Vehicle Code <span class="text-red">*</span></label>
                            <div class="col-md-6">
                              <input type="text" class="form-control" placeholder="eg. PR-XXXXX01" name="room_code" value="<?php echo $productDetails->room_code; ?>">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="inputFirstName" class="col-md-3 control-label">Vehicle </label>
                            <div class="col-md-6">
                              <input type="text" name="bed" class="form-control" placeholder="eg. 1 King or 2 Singles" value="<?php echo $productDetails->bed; ?>">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="inputFirstName" class="col-md-3 control-label">Guest </label>
                            <div class="col-md-6">
                              <input type="text" name="guest" class="form-control" placeholder="eg. Max. 2 guests" value="<?php echo $productDetails->guest; ?>">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="inputFirstName" class="col-md-3 control-label">Baggage </label>
                            <div class="col-md-6">
                              <input type="text" name="meal" class="form-control" placeholder="eg. 2 breakfasts" value="<?php echo $productDetails->meal; ?>">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                          <label for="inputFirstName" class="col-md-3 control-label">Promo Behaviour</label>
                          <div class="col-md-6">
                            <div class="xss-margin"></div>
                            <div class="checkbox-list">
                              <label><input id="inlineCheckbox0" name="promo_behaviour[]" type="radio" value="none" <?php if(preg_match('/none/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; None</label>
                              <label><input id="inlineCheckbox1" name="promo_behaviour[]" type="radio" value="hot" <?php if(preg_match('/hot/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Hot</label>
                              <label><input id="inlineCheckbox2" name="promo_behaviour[]" type="radio" value="new" <?php if(preg_match('/new/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; New</label>
                              <label><input id="inlineCheckbox3" name="promo_behaviour[]" type="radio" value="sale" <?php if(preg_match('/sale/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Sale</label>
                              <label><input id="inlineCheckbox4" name="promo_behaviour[]" type="radio" value="pwp" <?php if(preg_match('/pwp/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; PWP</label>
                              <label><input id="inlineCheckbox5" name="promo_behaviour[]" type="radio" value="last_minute" <?php if(preg_match('/last_minute/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Last Minute</label>
                              <label><input id="inlineCheckbox6" name="promo_behaviour[]" type="radio" value="24hoursale" <?php if(preg_match('/24hoursale/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; 24 Hour Sale</label>
                              <label><input id="inlineCheckbox8" name="promo_behaviour[]" type="radio" value="popular" <?php if(preg_match('/popular/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Popular</label>
                              <label><input id="inlineCheckbox9" name="promo_behaviour[]" type="radio" value="early_bird" <?php if(preg_match('/early_bird/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Early Bird</label>

                              <label><input id="inlineCheckbox10" name="promo_behaviour[]" type="radio" value="black_friday" <?php if(preg_match('/black_friday/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Black Friday</label>
                              <label><input id="inlineCheckbox11" name="promo_behaviour[]" type="radio" value="singles_day" <?php if(preg_match('/singles_day/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Singles day</label>
                              <label><input id="inlineCheckbox12" name="promo_behaviour[]" type="radio" value="merdeka" <?php if(preg_match('/merdeka/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Merdeka</label>
                              <label><input id="inlineCheckbox13" name="promo_behaviour[]" type="radio" value="valentines" <?php if(preg_match('/valentines/',$productDetails->promo_behaviour)){ echo 'checked="checked"'; } ?> />&nbsp; Valentine's</label>

                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputFirstName" class="col-md-3 control-label">Category <span class="text-red">*</span></label>
                          <div class="col-md-6">
                            note to programmer: this is the multiple selection. Admin can select multiple categories for this product.
                            <select multiple="multiple" name="categories[]" class="form-control" style="height: 350px;"><?php echo $categories; ?></select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputFirstName" class="col-md-3 control-label">Vehicle Price / Qty<span class="text-red">*</span></label>
                          <div class="col-md-6">
                              <div class="xss-margin"></div>
                              <a href="#" id="addRoomPrice" data-target="#modal-add-new" data-toggle="modal" class="btn btn-success">Add New Vehicle Price / Qty &nbsp;<i class="fa fa-plus"></i></a>
                              {{--<a href="#" id="addRoomPrice"  class="btn btn-success">Add New Room Price &nbsp;<i class="fa fa-plus"></i></a>--}}
                              <div class="xss-margin"></div>
                          </div>
                          <input type="hidden" id="roomPrices" name="roomPrices" />
                          <div id="productId" data-product-id="{{$productDetails->id}}" ></div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-3 control-label">Upload Thumbnail Image</label>
                          <div class="col-md-6">
                            <div class="text-15px margin-top-10px">
                              <div class="text-blue text-12px">Thumbnails displayed on "Products Listing" pages.</div>
                              @if($productDetails->thumbnail_image_1 != '')
                              <img src="{{ asset('/public/admin/products/medium/'. $productDetails->thumbnail_image_1) }}" alt="<?php echo htmlspecialchars($productDetails->type,ENT_QUOTES); ?>" class="img-responsive">
                              @if($productDetails->promo_behaviour === 'sale')
                              <img class="promo" src="{{ asset('public/promo/sale_label.png') }}" style="position: absolute;top: 30px;left: 250px;">
                              @elseif($productDetails->promo_behaviour === 'hot')
                              <img class="promo" src="{{ asset('public/promo/hot_label.png') }}" style="position: absolute;top: 30px;left: 250px;">
                              @elseif($productDetails->promo_behaviour === 'new')
                              <img class="promo" src="{{ asset('public/promo/new_label.png') }}" style="position: absolute;top: 30px;left: 250px;">
                              @elseif($productDetails->promo_behaviour === 'pwp')
                              <img class="promo" src="{{ asset('public/promo/pwp_label.png') }}" style="position: absolute;top: 30px;left: 250px;">
                              @elseif($productDetails->promo_behaviour === 'last_minute')
                              <img class="promo" src="{{ asset('public/promo/last_minute.png') }}" style="position: absolute;top: 30px;left: 250px;">
                              @elseif($productDetails->promo_behaviour === '24hoursale')
                              <img class="promo" src="{{ asset('public/promo/24hour_sale.png') }}" style="position: absolute;top: 30px;left: 250px;">
                              @elseif($productDetails->promo_behaviour === 'popular')
                              <img class="promo" src="{{ asset('public/promo/popular.png') }}" style="position: absolute;top: 30px;left: 250px;">
                              @elseif($productDetails->promo_behaviour === 'early_bird')
                              <img class="promo" src="{{ asset('public/promo/early_bird.png') }}" style="position: absolute;top: 30px;left: 250px;">

                               @elseif($productDetails->promo_behaviour === 'black_friday')
                              <img class="promo" src="{{ asset('public/promo/black_friday.png') }}"  style="position: absolute;top: 30px;left: 250px;">
                               @elseif($productDetails->promo_behaviour === 'singles_day')
                              <img class="promo" src="{{ asset('public/promo/singles_day.png') }}"  style="position: absolute;top: 30px;left: 250px;">
                               @elseif($productDetails->promo_behaviour === 'merdeka')
                              <img class="promo" src="{{ asset('public/promo/merdeka.png') }}"  style="position: absolute;top: 30px;left: 250px;">
                               @elseif($productDetails->promo_behaviour === 'valentines')
                              <img class="promo" src="{{ asset('public/promo/valentine.png') }}"  style="position: absolute;top: 30px;left: 250px;">

                              @endif

                              <a href="javascript:void(0)" data-target="#modal-delete-thumbnail-1" data-toggle="modal" data-hover="tooltip" data-placement="top" title="Delete"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a>
                              @endif
                              <div class="margin-top-5px"></div>
                              <input id="exampleInputFile1" type="file" name="thumbnail_image_1"/>
                              <span class="help-block">(Image dimension: 360 x 314 pixels, JPEG/GIF/PNG only, Max. 2MB) </span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputFirstName" class="col-md-3 control-label">Quantity in Stock (Vehicle)</label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="quantity_in_stock" placeholder="" value="<?php echo $productDetails->quantity_in_stock; ?>">
                            <div class="xss-margin"></div>
                            <div class="text-blue text-12px">Vehicle remaining in the hotel.</div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputFirstName" class="col-md-3 control-label">Low Level in Stock (Vehicle)</label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" name="low_level_in_stock" placeholder="" value="<?php echo $productDetails->low_level_in_stock; ?>">
                              <div class="xss-margin"></div>
                              <div class="text-blue text-12px">Shows the minimum level of a product in the warehouse, at which the stock is considered to be low.</div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputFirstName" class="col-md-3 control-label">Display Order</label>
                          <div class="col-md-3">
                            <input type="text" class="form-control" name="display_order"  placeholder="" value="<?php echo $details['productCategories'][0]->display_order; ?>">
                              <div class="xss-margin"></div>
                              <div class="text-blue text-12px">The display order of the product.</div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputFirstName" class="col-md-3 control-label">Tax</label>
                          <div class="col-md-6">
                            <div class="xss-margin"></div>
                            <input type="checkbox" id="is_tax" @if($productDetails->reverse_tax_calculation  == '1') disabled @endif name="is_tax" <?php if($productDetails->is_tax == '1'){ echo 'checked="checked"';} ?>> Tax Rates
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputFirstName" class="col-md-3 control-label">Tag</label>
                          <div class="col-md-5">
                            <div class="xss-margin"></div>
                            <!--<div class="text-blue text-15px border-bottom">Please click the text below to edit tag.</div>
                            <div contenteditable="true">
                              <p class="form-control-static"> <?php echo $productDetails->tags; ?></p>
                            </div>-->
                            <div class="input-group">
                              <textarea class="form-control" name="tags" placeholder="eg. Digital Gadget "style="width: 488px;"><?php echo $productDetails->tags; ?></textarea>
                              <!--<input type="text"  name="tags" class="form-control" placeholder="eg. Digital Gadget"/><span class="input-group-btn"><button type="button" class="btn btn-primary">Add</button></span>-->
                            </div>
                            <div class="xss-margin"></div>
                            <div class="text-blue text-12px">eg. Hotel Rooms, Premier Room, 50% Room Sales.</div>
                          </div>
                        </div>


                         <div class="form-group">
                          <label for="starting_from" class="col-md-3 control-label">Price Indicate As:</label>
                          <div class="col-md-6">
                            <div class="xss-margin"></div>
                            <input type="checkbox" name="starting_from"  <?php if($productDetails->starting_from == '1'){ echo 'checked="checked"';} ?>> <span style="coolor:#000">Starting From </span><i>(Optional)</i>
                          </div>
                        </div>

                      <div class="form-group">
                        <div class="col-mdcol-md-9 col-md-offset-3">
                          <div class="row" style="margin-left: 0px;margin-top: -15px;">
                              <div class="col-md-4">
                                  <div class="xss-margin"></div>
                                  <input type="checkbox" id="gross_price_per_night" name="gross_price_per_night"   <?php if($productDetails->gross_price_per_night == '1'){ echo 'checked="checked"';} ?>>  <span style="color:#f00"> Gross Price Per Trip</span>
                              </div>
                              <div class="col-md-4" style="margin-left: -65px;">
                                  <div class="xss-margin"></div>
                                  <input type="checkbox" id="net_price_per_night" name="net_price_per_night"  <?php if($productDetails->net_price_per_night  == '1'){ echo 'checked="checked"';} ?>>  <span style="color:#f00">Nett Price Per Trip</span>{{-- {{var_dump($productDetails->net_price_per_night)}} --}}
                                  <div class="xss-margin"></div>
                                  <input type="checkbox" @if($productDetails->is_tax == '1') disabled @endif id="reverse_tax_calculation" name="reverse_tax_calculation" @if($productDetails->reverse_tax_calculation == '1') checked @endif>  <span style="color:#f00">Reverse Tax Calculation</span>{{-- {{var_dump($productDetails->reverse_tax_calculation)}} --}}
                              </div>
                              <div class="col-md-4">
                                  <label class="ontrol-label" style="position: absolute;left: -82px;top: 7px;"><i>(Choose One)</i></label>
                              </div>
                          </div>
                        </div>

                      </div>



                      </div>
                      <!-- end col-md-12 -->
                    </div>
                    <!-- end row -->
                    <div class="clearfix"></div>

                    <!--Modal Add New Banner start-->
                    <div id="modal-add-new" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                      <div class="modal-dialog modal-wide-width">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                            <h4 id="modal-login-label2" class="modal-title">Add New Price</h4>
                          </div>

                          <div class="modal-body">
                            <div id="calendar"></div>
                            <div class="clearfix"></div>
                            <i class="fa fa-square alert-success"></i> <span class="text-success">Availability</span>
                            <i class="fa fa-square alert-error"></i> <span class="text-danger">No Availability</span>
                            <div class="form-actions">
                              <div class="col-md-offset-5 col-md-8">
                                <a href="#" class="btn btn-red" onclick="savePrices(); event.preventDefault();">Save &nbsp;<i class="fa fa-floppy-o"></i></a>&nbsp;
                                <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--END MODAL Add New -->
                    <style type="text/css">
                      .status-button .btn{
                        opacity: 0.4
                      }
                      .status-button .btn.active{
                        opacity: 1
                      }
                    </style>

                    <!--Modal add schedule start-->
                    <div id="modal-add-schedule" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                      <div class="modal-dialog modal-wide-width">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                            <h4 id="modal-login-label3" class="modal-title">Add Price / Qty / Restriction</h4>
                          </div>

                          <div class="modal-body">
                            <div class="form-group">
                              <label class="col-md-3 control-label">Status </label>
                              <div class="col-md-9">
                                <div class="btn-group status-button" data-toggle="buttons">
                                  <label class="btn btn-success">
                                    <input id="roomStatusAvailable" type="radio" value='1' checked> Availability
                                  </label>
                                  <label class="btn btn-danger">
                                    <input id="roomStatusUnavailable" type="radio" value='0'> No Availability
                                  </label>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="salePrice" class="col-md-3 control-label">Sale Price (Per Night/nett) <span class="text-red">*</span></label>
                              <div class="col-md-6">
                                <input id="salePrice" type="text" class="form-control" placeholder="0.00" value="0.00">
                                <div class="xss-margin"></div>
                                <div class="text-blue text-12px">The product sale price. The product is sold to customers at this price.</div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="listPrice" class="col-md-3 control-label">List Price (Per Night/nett)</label>
                              <div class="col-md-6">
                                <input id="listPrice" type="text" class="form-control" placeholder="0.00" value="0.00">
                                <div class="xss-margin"></div>
                                <div class="text-blue text-12px">Original room tariff rates.</div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Quantity in Stock (rooms)</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" id="quantity_in_stock" placeholder="0.00" value="0.00">
                                <div class="xss-margin"></div>
                                <div class="text-blue text-12px">Rooms remaining in the hotel.</div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Low Level in Stock (rooms)</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" id="low_level_in_stock" placeholder="0.00" value="0.00">
                                <div class="xss-margin"></div>
                                <div class="text-blue text-12px">Shows the minimum level of a service in the warehouse, at which the stock is considered to be low.</div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-3 control-label">Bulk Options </label>
                              <div class="col-md-9">
                                <div class="xss-margin"></div>
                                <div class="col-md-9">
                                  <label><input id="radioBulkDateRange" type="radio" name="radioBulkOptions" value="1" />&nbsp; By Date Range</label>
                                  <div class="margin-top-10px text-blue border-bottom">You may set up a single day or a range of dates for the status "No Availability" or promotional price for festive period.</div>

                                  <div class="xss-margin"></div>
                                  <div class="input-group input-daterange">
                                    <input id="start" type="text" class="form-control" placeholder="eg. 01 March, 2017"/>
                                    <span class="input-group-addon">to</span>
                                    <input id="end" type="text" class="form-control" placeholder="eg. 01 April, 2017"/>
                                  </div>
                                  <!-- end input daterange -->

                                  <div class="xss-margin"></div>
                                    <div class="input-group input-daterange">
                                    <input id="start" type="text" class="form-control" placeholder="eg. 01 March, 2017"/>
                                    <span class="input-group-addon">to</span>
                                    <input id="end" type="text" class="form-control" placeholder="eg. 01 April, 2017"/>
                                  </div>
                                  <!-- end input daterange -->

                                  <div class="xss-margin"></div>
                                  <div class="input-group input-daterange">
                                    <input id="start" type="text" class="form-control" placeholder="eg. 01 March, 2017"/>
                                    <span class="input-group-addon">to</span>
                                    <input id="end" type="text" class="form-control" placeholder="eg. 01 April, 2017"/>
                                  </div>
                                  <!-- end input daterange -->

                                  <div class="xss-margin"></div>
                                  <a href="#" id="btnAddMoreDate" onclick="addMoreDate(); event.preventDefault();" class="btn btn-dark">Add More Date &nbsp;<i class="fa fa-plus"></i></a>&nbsp;
                                </div>
                                <!-- end col-md-9 -->

                                <div class="clearfix xs-margin"></div>
                                <div class="col-md-4">
                                  <label><input id="radioByDayOfMonth" name="radioBulkOptions" type="radio" value="1" />&nbsp; By Day / Month</label>
                                  <div class="clearfix"></div>
                                  <span class="inline">Every
                                    <select id="day" multiple="multiple" style="height: 180px" class="form-control">
                                      <option>- Select -</option>
                                      <option value="MON">Monday</option>
                                      <option value="TUE">Tuesday</option>
                                      <option value="WED">Wednesday</option>
                                      <option value="THU">Thursday</option>
                                      <option value="FRI">Friday</option>
                                      <option value="SAT">Saturday</option>
                                      <option value="SUN">Sunday</option>
                                    </select>
                                    of
                                    <select id="month" multiple="multiple" style="height: 200px;" class="form-control">
                                      <option>- Select Month -</option>
                                      <option value="ALL">Every Month</option>
                                      <option value="1">January</option>
                                      <option value="2">February</option>
                                      <option value="3">March</option>
                                      <option value="4">April</option>
                                      <option value="5">May</option>
                                      <option value="6">June</option>
                                      <option value="7">July</option>
                                      <option value="8">August</option>
                                      <option value="9">September</option>
                                      <option value="10">October</option>
                                      <option value="11">November</option>
                                      <option value="12">December</option>
                                    </select>
                                  </span>
                                </div>
                                <!-- end col-md-4 -->

                                <div class="col-md-4">
                                  <label><input id="radioByDaysOfYear" type="radio" name="radioBulkOptions" value="1"/>&nbsp; By Days / Year</label>
                                  <div class="clearfix"></div>
                                  <span class="inline">All
                                    <select id="days" multiple="multiple" style="height: 180px;" class="form-control">
                                      <option>- Select -</option>
                                      <option value="MON">Mondays</option>
                                      <option value="TUE">Tuesdays</option>
                                      <option value="WED">Wednesdays</option>
                                      <option value="THU">Thursdays</option>
                                      <option value="FRI">Fridays</option>
                                      <option value="SAT">Saturdays</option>
                                      <option value="SUN">Sundays</option>
                                    </select>
                                    of
                                    <select id="year" multiple="multiple" style="height: 200px;" class="form-control">
                                      <option>- Select Year -</option>
                                      @for($i = 0; $i < 10; $i++)
                                      <option value="{{ date('Y') + $i }}">{{ date('Y') + $i }}</option>
                                      @endfor
                                    </select>
                                  </span>
                                  <div class="margin-top-10px"></div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-3 control-label">Room Restriction Text</label>
                              <div class="col-md-9">
                                <textarea id="roomRestrictionText" class="form-control" placeholder="eg. This special rate is for online booking only."></textarea>
                              </div>
                            </div>
                            <hr>

                            <div class="form-group">
                              <label class="col-md-3 control-label"></label>
                              <div class="col-md-9">
                                <a href="#" class="btn btn-dark" onclick="addMorePrices(); event.preventDefault();">
                                  Add More Price &nbsp;
                                  <i class="fa fa-plus"></i>
                                </a>&nbsp;
                              </div>
                            </div>
                            <hr>

                            <div class="form-actions">
                              <div class="col-md-offset-5 col-md-8">
                                <a href="#" class="btn btn-red" onclick="renderPricesToCalendar(); event.preventDefault();">Save &nbsp;
                                  <i class="fa fa-floppy-o"></i>
                                </a>&nbsp;
                                <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--END MODAL add schedule-->
                  </div>
                  <!-- end portlet body -->
                </div>
                <!-- end portlet -->
                <!-- end general -->

                <div class="portlet">
                  <div class="portlet-header">
                    <div class="caption">Purchase Availability</div>
                    <div class="tools"> <i class="fa fa-chevron-up"></i>
                  </div>
                </div>

                <div class="portlet-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputFirstName" class="col-md-3 control-label">Creation Date</label>
                        <div class="col-md-4">
                          <div class="input-group">
                            <input type="text" name="created" class="datepicker-default form-control" data-date-format="dd/mm/yyyy" placeholder="17 Apr, 2015" value="<?php echo date('d M, Y',strtotime($productDetails->created)); ?>"/>
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="inputFirstName" class="col-md-3 control-label">Out of Stock Actions</label>
                        <div class="col-md-4">
                          <select class="form-control" name="out_of_stock_action">
                            <option value="none" <?php if($productDetails->out_of_stock_action == 'none'){ echo 'selected="selected"'; } ?>>None</option>
                            <option value="signup" <?php if($productDetails->out_of_stock_action == 'signup'){ echo 'selected="selected"'; } ?> >Sign up for notification</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- end col-md-12 -->
                  </div>
                  <!-- end row -->
                  <div class="clearfix"></div>
                </div>
                <!-- end portlet body -->
              </div>
              <!-- end portlet -->
              <!-- end purchase availability -->

              <div class="form-actions">
                <div class="col-md-offset-5 col-md-7">
                  <button type="submit" class="btn btn-red" onclick="load_data()">Save &nbsp;<i class="fa fa-floppy-o"></i></button>
                  <!-- <a onclick="$('#addColorForm').submit()" class="btn btn-red" href="#">Save &nbsp;<i class="fa fa-floppy-o"></i></a>-->&nbsp;
                  <a class="btn btn-green" data-dismiss="modal" href="{{ url('/web88cms/products/list') }}">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a>
                </div>
              </div>
            </form>
          </div>

          <!--Modal delete images start-->
          <div id="modal-delete-large-image" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                  <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this image? </h4>
                </div>
                <div class="modal-body">
                  <div class="form-actions">
                    <div class="col-md-offset-4 col-md-8"> <a href="{{ url('/web88cms/products/deleteImage/large_image/'. $productDetails->id) }}" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- modal delete images end -->

          <!--Modal delete images start-->
          <div id="modal-delete-thumbnail-1" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                  <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this image? </h4>
                </div>
                <div class="modal-body">
                  <div class="form-actions">
                    <div class="col-md-offset-4 col-md-8"> <a href="{{ url('/web88cms/products/deleteImage/thumbnail_image_1/'. $productDetails->id) }}" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- modal delete images end -->

          <!--Modal delete images start-->
          <div id="modal-delete-thumbnail-2" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                  <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this image? </h4>
                </div>
                <div class="modal-body">
                  <div class="form-actions">
                    <div class="col-md-offset-4 col-md-8"> <a href="{{ url('/web88cms/products/deleteImage/thumbnail_image_2/'. $productDetails->id) }}" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- modal delete images end -->
          <!-- end tab general -->


          <div id="images"  class="tab-pane fade in {{ (($tab) && ($tab=='images'))?'in active':'' }}">
            <div class="portlet">
              <div class="portlet-header">
                <div class="caption">Additional Room Images</div>
                <div class="clearfix"></div>
                <span class="text-blue text-15px">Additional product images will be displayed in "Product Details" page. Thumbnails will be generated from detailed images automatically. Thumbnails will be resized to 128 x 128 pixels.</span>
                <div class="xs-margin"></div>
                <div class="clearfix"></div>
                <a href="javascript:void(0)" class="btn btn-success" id="add_more">Add More Image &nbsp;<i class="fa fa-plus"></i></a>&nbsp;
                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
              </div>

              <div class="portlet-body">
                <div class="row">
                  <div class="col-md-12">
                    <form class="form-horizontal" method="post" action="{{ url('/web88cms/products/addImages/'. $productDetails->id) }}" enctype="multipart/form-data">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}"  />
                      <div class="form-group border-bottom">
                      <?php if(sizeof($additional_images) > 0) {
                              foreach($additional_images as $product_image) {
                                echo '<div style="float:left; margin:0 10px 10px;"><img src="'.asset('/public/admin/products/medium/'. $product_image->file_name).'" alt="'.htmlspecialchars($productDetails->type,ENT_QUOTES).'" class="img-responsive" style="width: 125px; height: 75px;">
                                  <a href="javascript:void(0)" data-hover="tooltip" data-placement="top" data-target="#modal-delete-selected-image-'. $product_image->id.'" data-toggle="modal" title="Delete"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a></div>';

                                echo '<div id="modal-delete-selected-image-'. $product_image->id.'" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                        <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this item? </h4>
                                      </div>
                                      <div class="modal-body">
                                        <div class="form-actions">
                                        <div class="col-md-offset-4 col-md-8"> <a href="'.url('/web88cms/products/deleteAdditionalImage/'. $product_image->id.'/'. $productDetails->id).'" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                              }
                            }
                      ?>
                        <div class="clearfix"></div>
                        <label class="col-md-3 control-label">Upload Popup Larger Image of Additional Thumbnail</label>
                        <div class="col-md-6">
                          <div class="text-15px">
                            <span id="additional_image">
                              <div class="margin-top-5px"></div>
                              <input id="exampleInputFile1" type="file" name="large_image[]"/>
                            </span>
                            <span id="more_image"></span>
                            <span class="help-block">(Image dimension: 720 x 450 pixels, JPEG/GIF/PNG only, Max. 2MB) </span>
                          </div>
                        </div>
                      </div>

                      <div class="form-actions">
                        <div class="col-md-offset-5 col-md-7">
                          <button type="submit" class="btn btn-red" onclick="load_data()">Save &nbsp;<i class="fa fa-floppy-o"></i></button>
                          <!-- <a onclick="$('#addColorForm').submit()" class="btn btn-red" href="#">Save &nbsp;<i class="fa fa-floppy-o"></i></a>-->&nbsp;
                          <a class="btn btn-green" data-dismiss="modal" href="{{ url('/web88cms/products/list') }}">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- end col-md-12 -->
                </div>
                <!-- end row -->
                <div class="clearfix"></div>
              </div>
              <!-- end portlet body -->
            </div>
            <!-- end portlet -->
            <!-- end images -->
          </div>
          <!-- end tab images -->

          <div id="description-feature" class="tab-pane fade {{ (($tab) && ($tab=='description-feature'))?'in active':'' }}" style="display:block; height:0px; overflow:hidden;">
            <div class="portlet">
              <div class="portlet-header">
                <div class="caption">Description</div>
                <div class="clearfix"></div>
                <span class="text-blue text-15px">You can edit the text by clicking the content below. </span>
                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
              </div>

              <div class="portlet-body">
                <div class="row">
                  <div class="col-md-12">
                    <div id="description" contenteditable="true" onclick="$('#description').attr('contenteditable', true)">
                      <?php echo $productDetails->description; ?>
                    </div>
                  </div>
                  <!-- end col-md-12 -->
                </div>
                <!-- end row -->
                <div class="clearfix"></div>
              </div>


              <div class="portlet-header">
                <div class="caption">Terms and Conditions</div>
                <div class="clearfix"></div>
                <span class="text-blue text-15px">You can edit the text by clicking the content below. </span>
                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
              </div>

              <div class="portlet-body">
                <div class="row">
                  <div class="col-md-12">
                    <div id="terms_and_conditions" contenteditable="true" onclick="$('#terms_and_conditions').attr('contenteditable', true)">
                      <?php echo $productDetails->terms_and_conditions; ?>
                    </div>
                  </div>
                  <!-- end col-md-12 -->
                </div>
                <!-- end row -->
                <div class="clearfix"></div>
              </div>


              <div class="portlet-header">
                <div class="caption">Cancellation Policy</div>
                <div class="clearfix"></div>
                <span class="text-blue text-15px">You can edit the text by clicking the content below. </span>
                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
              </div>

              <div class="portlet-body">
                <div class="row">
                  <div class="col-md-12">
                    <div id="cancellation_policy" contenteditable="true" onclick="$('#cancellation_policy').attr('contenteditable', true)">
                      <?php echo $productDetails->cancellation_policy; ?>
                    </div>
                  </div>
                  <!-- end col-md-12 -->
                </div>
                <!-- end row -->
                <div class="clearfix"></div>
              </div>

              <!-- end portlet body -->
            </div>
            <!-- end portlet -->
            <!-- end description -->
            <!-- end portlet -->
            <!-- end features & video -->
            <script>
              function load_data(){
                toastr.info('Please wait while Saving Data..', 'Info', {closeButton: true});
                toastr.options = {
                  "showDuration": "10000",
                  "hideDuration": "10000",
                  "timeOut": "10000",
                  "extendedTimeOut": "10000"
                }
              }

              $(document).ready(function() {
                // The inline editor should be enabled on an element with "contenteditable" attribute set to "true".
                // Otherwise CKEditor will start in read-only mode.
                //var introduction = document.getElementById( 'description' );
                //introduction.setAttribute( 'contenteditable', true );

                //var featured_video = document.getElementById( 'featured_video' );
                //featured_video.setAttribute( 'contenteditable', true );

                /*CKEDITOR.inline( 'description', {
                  // Allow some non-standard markup that we used in the introduction.
                  extraAllowedContent: 'a(documentation);abbr[title];code',
                  removePlugins: 'stylescombo',
                  extraPlugins: 'sourcedialog',
                  // Show toolbar on startup (optional).
                  startupFocus: true
                } );*/

                CKEDITOR.inline( 'description', { entities: false, basicEntities: false, basicEntities : false, entities_greek: false, entities_latin: false,
                  on: {
                    blur: function( event ) {
                      //var editor_data = event.editor.getData();
                      // var editor_data = $('#description').html();
                      // Do sth with your data...
                      //alert(data);

                      $.ajax({
                        type : 'post',
                        data : 'content='+CKEDITOR.instances.description.getData().replace('&','')+'&_token=<?php echo csrf_token(); ?>',
                        url  : '<?php echo url("/web88cms/products/updateDescription/". $productDetails->id );  ?>',
                        success : function(response) {
                          //alert(response);
                        }
                      });
                    }
                  }
                });


                CKEDITOR.inline( 'terms_and_conditions', { entities: false, basicEntities: false, basicEntities : false, entities_greek: false, entities_latin: false,
                  on: {
                    blur: function( event ) {
                      //var editor_data = event.editor.getData();
                      // var editor_data = $('#terms_and_conditions').html();
                      // Do sth with your data...
                      //alert(data);

                      $.ajax({
                        type : 'post',
                        data : 'content='+CKEDITOR.instances.terms_and_conditions.getData().replace('&','')+'&_token=<?php echo csrf_token(); ?>',
                        url  : '<?php echo url("/web88cms/products/updateTermsConditions/". $productDetails->id );  ?>',
                        success : function(response) {
                          //alert(response);
                        }
                      });
                    }
                  }
                });

                CKEDITOR.inline( 'cancellation_policy', { entities: false, basicEntities: false, basicEntities : false, entities_greek: false, entities_latin: false,
                  on: {
                    blur: function( event ) {
                      //var editor_data = event.editor.getData();
                      // var editor_data = $('#cancellation_policy').html();
                      // Do sth with your data...
                      //alert(data);

                      $.ajax({
                        type : 'post',
                        data : 'content='+CKEDITOR.instances.cancellation_policy.getData().replace('&','')+'&_token=<?php echo csrf_token(); ?>',
                        url  : '<?php echo url("/web88cms/products/updateCancellationPolicy/". $productDetails->id );  ?>',
                        success : function(response) {
                          //alert(response);
                        }
                      });
                    }
                  }
                });


              });

              $(document).ready(function() {
                $('#add_more').click(function() {
                  $('#more_image').append($('#additional_image').html());
                });
              });
            </script>
          </div>
          <!-- end tab description & features -->

          <div id="quantity-discount" class="tab-pane fade {{ (($tab) && ($tab=='quantity-discount'))?'in active':'' }}">
            <div class="portlet">
              <div class="portlet-header">
                <div class="caption">Packages</div>
                <div class="clearfix"></div>
                <p class="margin-top-10px"></p>
                <a href="#" class="btn btn-success" data-hover="tooltip" data-placement="top" data-target="#modal-add-discount" data-toggle="modal">Add New Package &nbsp;<i class="fa fa-plus"></i></a>&nbsp;
                <div class="btn-group">
                  <button type="button" class="btn btn-primary">Delete</button>
                  <button type="button" data-toggle="dropdown" class="btn btn-red dropdown-toggle"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                  <ul role="menu" class="dropdown-menu">
                    <li><a href="#" onclick="deleteSelected()">Delete selected item(s)</a></li>
                    <li class="divider"></li>
                    <li><a href="#" data-target="#modal-delete-all" data-toggle="modal">Delete all</a></li>
                  </ul>
                </div>

                <div class="tools"> <i class="fa fa-chevron-up"></i></div>

                <!--Modal add discount start-->
                <div id="modal-add-discount" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                  <div class="modal-dialog modal-wide-width">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-login-label3" class="modal-title">Add Package</h4>
                      </div>
                      <div class="modal-body">
                        <div class="form">
                          <form class="form-horizontal" method="post" action="{{ url('/web88cms/products/addQuantityDiscount') }}">
                            <div class="form-group">
                              <label class="col-md-3 control-label">Status <span class="text-red">*</span></label>
                              <div class="col-md-6">
                                <div data-on="success" data-off="primary" class="make-switch">
                                  <input type="checkbox" name="status" checked="checked"/>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Package Name</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" name="package_name" placeholder="Package Name">
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Package Code</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" name="package_code" placeholder="Package Code">
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">StartDate To EndDate </label>
                              <div class="col-md-3" style="margin-right:-15px;">
                                <input type="date" class="form-control" name="start_date" placeholder="Start Date">
                              </div>
                              <div class="col-md-3 input-group">
                                <span class="input-group-addon" id="basic-addon1">To</span>
                                <input type="date" class="form-control" name="end_date" placeholder="End Date">
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <!-- <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">From Quantity</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" name="from_quantity" placeholder="Qty">
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">To Quantity</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" name="to_quantity" placeholder="Qty">
                              </div>
                            </div>
                            <div class="clearfix"></div> -->

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label"><input type="radio" id="Discount" name="package_discount" value="discount"> Discount </label>
                              <div class="col-md-6">
                                <input type="text" class="form-control"  name="discount" placeholder="Amount">
                                <div class="xs-margin"></div>
                                <select name="discount_by" class="form-control">
                                  <option value="percentage" >%</option>
                                  <option value="amount">RM</option>
                                </select>
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label"><input type="radio" id="Price" name="package_discount" value="price"> Price</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" name="price" placeholder="Price">
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Check-In </label>
                              <div class="col-md-6">
                                <span class="checkbox-inline" style="margin-right: -17px;">
                                  Days
                                </span>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkin_mo" value=""> Mo
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkin_tu" value=""> Tu
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkin_we" value=""> We
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkin_th" value=""> Th
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkin_fr" value=""> Fr
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkin_sa" value=""> Sa
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkin_su" value=""> Su
                                </label>
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Check-Out </label>
                              <div class="col-md-6">
                                <span class="checkbox-inline" style="margin-right: -17px;">
                                  Days
                                </span>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkout_mo" value=""> Mo
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkout_tu" value=""> Tu
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkout_we" value=""> We
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkout_th" value=""> Th
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkout_fr" value=""> Fr
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkout_sa" value=""> Sa
                                </label>
                                <label class="checkbox-inline">
                                  <input type="checkbox" name="checkout_su" value=""> Su
                                </label>
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Minimum Stay</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" name="minimum_stay" placeholder="Minimum Stay">
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Value Added Service</label>
                              <div class="col-md-6">
                                <input type="text" class="form-control" name="value_added_service" placeholder="eg.2 nights with breakfast">
                              </div>
                            </div>

                            <div class="form-actions">
                              <div class="col-md-offset-5 col-md-8">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="product_id" value="{{ $productDetails->id }}" />
                                <button type="submit" class="btn btn-red">Save &nbsp;<i class="fa fa-floppy-o"></i></button>&nbsp;
                                <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--END MODAL add new discount -->

                <!--Modal delete selected items start-->
                <div id="modal-delete-selected" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-login-label3" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete the selected item(s)? </h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-actions">
                          <div class="col-md-offset-4 col-md-8"> <a href="javascript:void(0)" onclick="delete_selected('quantity_discounts')" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-green" onclick="cancel_delete()">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- modal delete selected items end -->

                <!--Modal delete all items start-->
                <div id="modal-delete-all" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-login-label3" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete all items? </h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-actions">
                          <div class="col-md-offset-4 col-md-8"> <a href="javascript:void(0)" onclick="delete_all('quantity_discounts')" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-green" onclick="cancel_delete()">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- modal delete all items end -->

                              <!---Modal select at least one-->
                   <div id="modal-selected-least-one" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                            <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete the selected item(s)? </h4>
                          </div>
                          <div class="modal-body">
                            <div class="alert alert-danger" role="alert">
                              Please select at least one service before delete.
                            </div>
                            <div class="form-actions">
                              <div class="col-md-offset-4 col-md-8">
                               <a href="javascript:void(0)" data-dismiss="modal" onclick="cancel_delete()" class="btn btn-green">OK &nbsp;<i class="fa fa-times-circle"></i>
                               </a>

                             </div>
                           </div>
                         </div>
                       </div>
                     </div>
                   </div>
                   <!-- modal delete selected  at least one items end -->

                <!--Modal delete start-->
                <div id="modal-delete-2" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this item? </h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-actions">
                          <div class="col-md-offset-4 col-md-8"> <a href="javascript:void(0)" class="btn btn-red" onclick="continue_delete_process_quantity_discount()">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-green" onclick="cancel_delete()">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- modal delete end -->
              </div>
              <?php if(count($quantity_discounts) > 0) {
                $arr_get = Input::get();
                unset($arr_get['page']);
                $query_string = http_build_query($arr_get);

                $per_page = (Session::has('quantity_discount.per_page')) ? Session::get('quantity_discount.per_page') : 30;
              ?>
              <div class="portlet-body">
                <div class="form-inline pull-left">
                  <div class="form-group">
                    <select name="select" class="form-control" onchange="set_per_page(this.value,'quantity_discount','{{ Request::path() }}','{{ ($query_string)?$query_string:'activetab=quantity-discount' }}')">
                      <option <?php if($per_page == 10){ echo 'selected="selected"'; } ?>>10</option>
                      <option <?php if($per_page == 20){ echo 'selected="selected"'; } ?>>20</option>
                      <option <?php if($per_page == 30){ echo 'selected="selected"'; } ?>>30</option>
                      <option <?php if($per_page == 50){ echo 'selected="selected"'; } ?>>50</option>
                      <option <?php if($per_page == 100){ echo 'selected="selected"'; } ?>>100</option>
                    </select>
                    &nbsp;
                    <label class="control-label">Records per page</label>
                  </div>
                </div>
                <div class="clearfix"></div>
                <br/>
                <div class="table-responsive mtl">
                  {{-- <span class="text-red"><b>Sale Price: RM {{ number_format($productDetails->sale_price,2) }}</b></span> --}}
                  <table id="example1" class="table table-hover table-striped">
                    <thead>
                      <tr>
                        <th width="1%">
                            <div style="padding-top: 8px;padding-bottom: 8px;">
                                <input type="checkbox" id="select_items"/>
                            </div>
                        </th>
                        <th>
                            <div style="padding-top: 8px;padding-bottom: 8px;">
                            #
                            </div>
                        </th>
                        <th>
                            <div style="background: #fff;padding-top: 8px;padding-bottom: 8px;">
                                Package Name
                            </div>
                        </th>
                        <th>
                            <div style="background: #fff;padding-top: 8px;padding-bottom: 8px;">
                                Package Code
                            </div>
                        </th>
                        <th>
                            <div style="background: #fff;padding-top: 8px;padding-bottom: 8px;">
                                Status
                            </div>
                        </th>
                        <th>
                            <div style="background: #fff;padding-top: 8px;padding-bottom: 8px;">
                            Action
                            </div>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $i = 1;
                      foreach($quantity_discounts as $details) {
                        $status_class = ($details->status == '0') ? 'label-red' : 'label-success';
                        $status = ($details->status == '0') ? 'In-active' : 'Active';
                    ?>
                      <tr>
                        <td>
                            <div style="padding-top: 8px;padding-bottom: 8px;">
                            <input type="checkbox" data-id="<?php echo $details->id; ?>" class="select_items"/>
                            </div>
                        </td>
                        <td>
                            <div style="padding-top: 8px;padding-bottom: 8px;">
                                {{ $i }}
                            </div>
                        </td>
                        <td>
                            <div style="background: #fff;padding-top: 8px;padding-bottom: 8px;">
                            {{ $details->package_name }}
                            </div>
                        </td>
                        <td>
                            <div style="background: #fff;padding-top: 8px;padding-bottom: 8px;">
                            {{ $details->package_code }}<br/>
                            </div>
                        </td>
                        <td>
                            <div style="padding-top: 8px;padding-bottom: 8px;">
                            <span class="label label-sm <?php echo $status_class; ?>"><?php echo $status; ?></span>
                            </div>
                        </td>
                        <td>
                            <div style="padding-top: 8px;padding-bottom: 8px;">
                            <a href="#" data-hover="tooltip" data-placement="top" title="Edit" data-target="#modal-edit-discount-<?php echo $details->id; ?>" data-toggle="modal">
                                <span class="label label-sm label-success"><i class="fa fa-pencil"></i></span>
                            </a>
                            <a href="#" data-hover="tooltip" data-placement="top" title="Delete" data-target="#modal-delete-2" data-toggle="modal" onclick="delete_item(<?php echo $details->id; ?>)"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a>
                            </div>
                          <!--Modal edit discount start-->
                          <div id="modal-edit-discount-<?php echo $details->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog modal-wide-width">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                  <h4 id="modal-login-label3" class="modal-title">Edit Package</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form">
                                    <form class="form-horizontal" method="post" action="{{ url('/web88cms/products/updateQuantityDiscount/') }}">
                                      <div class="form-group">
                                        <label class="col-md-3 control-label">Status<span class="text-red">*</span></label>
                                        <div class="col-md-6">
                                          <div data-on="success" data-off="primary" class="make-switch">
                                            <input type="checkbox" name="status" <?php if($details->status == '1'){ echo 'checked="checked"'; } ?>/>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label">Package Name</label>
                                        <div class="col-md-6">
                                          <input type="text" class="form-control" name="package_name" placeholder="Package Name" value="{{ $details->package_name }}">
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label">Package Code</label>
                                        <div class="col-md-6">
                                          <input type="text" class="form-control" name="package_code" placeholder="Package Code" value="{{ $details->package_code }}">
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label">StartDate To EndDate </label>
                                        <div class="col-md-3" style="margin-right:-15px;">
                                          <input type="date" class="form-control" name="start_date" placeholder="Start Date" value="{{ $details->start_date }}">
                                        </div>
                                        <div class="col-md-3 input-group">
                                          <span class="input-group-addon" id="basic-addon1">To</span>
                                          <input type="date" class="form-control" name="end_date" placeholder="End Date" value="{{ $details->end_date }}">
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label"><input type="radio" id="Discount" name="package_discount" value="discount" @if($details->package_discount == 'discount') checked @endif> Discount </label>
                                        <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="Amount" name="discount" value="{{ number_format($details->discount,2) }}">
                                          <div class="xs-margin"></div>
                                          <select name="discount_by" class="form-control">
                                            <option value="percentage" <?php if($details->discount_by == 'percentage'){ echo 'selected="selected"'; } ?> >%</option>
                                            <option value="amount" <?php if($details->discount_by == 'amount'){ echo 'selected="selected"'; } ?>>RM</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label"><input type="radio" id="Price" name="package_discount" value="price" @if($details->package_discount == 'price') checked @endif> Price</label>
                                        <div class="col-md-6">
                                          <input type="text" class="form-control" name="price" placeholder="Price" value="{{ $details->price }}">
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label">Check-In </label>
                                        <div class="col-md-6">
                                          <span class="checkbox-inline" style="margin-right: -17px;">
                                            Days
                                          </span>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkin_mo" <?php if($details->checkin_mo == '1'){ echo 'checked="checked"'; }?>> Mo
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkin_tu" <?php if($details->checkin_tu == '1'){ echo 'checked="checked"'; }?>> Tu
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkin_we" <?php if($details->checkin_we == '1'){ echo 'checked="checked"'; }?>> We
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkin_th" <?php if($details->checkin_th == '1'){ echo 'checked="checked"'; }?>> Th
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkin_fr" <?php if($details->checkin_fr == '1'){ echo 'checked="checked"'; }?>> Fr
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkin_sa" <?php if($details->checkin_sa == '1'){ echo 'checked="checked"'; }?>> Sa
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkin_su" <?php if($details->checkin_su == '1'){ echo 'checked="checked"'; }?>> Su
                                          </label>
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label">Check-Out </label>
                                        <div class="col-md-6">
                                          <span class="checkbox-inline" style="margin-right: -17px;">
                                            Days
                                          </span>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkout_mo" <?php if($details->checkout_mo == '1'){ echo 'checked="checked"'; }?>> Mo
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkout_tu" <?php if($details->checkout_tu == '1'){ echo 'checked="checked"'; }?>> Tu
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkout_we" <?php if($details->checkout_we == '1'){ echo 'checked="checked"'; }?>> We
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkout_th" <?php if($details->checkout_th == '1'){ echo 'checked="checked"'; }?>> Th
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkout_fr" <?php if($details->checkout_fr == '1'){ echo 'checked="checked"'; }?>> Fr
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkout_sa" <?php if($details->checkout_sa == '1'){ echo 'checked="checked"'; }?>> Sa
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="checkout_su" <?php if($details->checkout_su == '1'){ echo 'checked="checked"'; }?>> Su
                                          </label>
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label">Minimum Stay</label>
                                        <div class="col-md-6">
                                          <input type="text" class="form-control" name="minimum_stay" placeholder="Minimum Stay" value="{{ $details->minimum_stay }}">
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label">Value Added Service</label>
                                        <div class="col-md-6">
                                          <input type="text" class="form-control" name="value_added_service" placeholder="eg.2 nights with breakfast" value="{{ $details->value_added_service }}">
                                        </div>
                                      </div>

                                      <div class="form-actions">
                                        <div class="col-md-offset-5 col-md-8">
                                          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                          <input type="hidden" name="discount_id" value="{{ $details->id }}" />
                                          <button type="submit" class="btn btn-red">Save &nbsp;<i class="fa fa-floppy-o"></i></button>&nbsp;
                                          <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--END MODAL edit discount -->
                        </td>
                      </tr>
                    <?php
                        $i++;
                      } // end foreach
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="6"></td>
                      </tr>
                    </tfoot>
                  </table>
                  <div class="tool-footer text-right">
                    <p class="pull-left"><?php echo $pagination_report; ?></p>
                    <?php echo $quantity_discounts->setPath(Request::url())->appends(['activetab' => 'quantity-discount'])->render(); ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <input type="hidden" id="delete_item_ids" value="0" />
              <input type="hidden" id="product_id" value="{{ $productDetails->id }}" />
              <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}" />
              <input type="hidden" id="query_string" value="{{ $query_string }}" />
              <script>
                // select all checkboxes
                $(document).ready(function() {
                  $('#select_items').click(function() {
                    //alert('asd');
                    //if($('.select_items').length() > 0)
                    if($('#select_items').is(':checked')) {
                      $('.select_items').prop('checked',true);
                    } else {
                      $('.select_items').prop('checked',false);
                    }
                  });
                });
              </script>
            <?php
              }
            ?>
            <!-- end portlet body -->
            </div>
            <!-- end portlet -->
            <!-- end images -->
          </div>
          <!-- end tab quantity discounts -->

          <div id="pwp" class="tab-pane fade {{ (($tab) && ($tab=='pwp'))?'in active':'' }}">
            <div class="portlet">
              <div class="portlet-header">
                <div class="caption">PWP Setup</div>
                <div class="clearfix"></div>
                <p class="margin-top-10px"></p>
                <a href="#" class="btn btn-success" data-hover="tooltip" data-placement="top" data-target="#modal-pwp-product-new" data-toggle="modal">Add PWP Product &nbsp;<i class="fa fa-plus"></i></a>&nbsp;
                <div class="btn-group">
                  <button type="button" class="btn btn-primary">Delete</button>
                  <button type="button" data-toggle="dropdown" class="btn btn-red dropdown-toggle"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                  <ul role="menu" class="dropdown-menu">
                    <li><a href="#" onclick="deleteSelectedPwp()">Delete selected item(s)</a></li>
                    <li class="divider"></li>
                    <li><a href="#" data-target="#modal-delete-all-pwp" data-toggle="modal">Delete all</a></li>
                  </ul>
                </div>
                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>

                <!--Modal add pwp product start-->
                <div id="modal-pwp-product-new" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                  <div class="modal-dialog modal-wide-width">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-login-label3" class="modal-title">Add PWP Product</h4>
                      </div>
                      <div class="modal-body">
                        <div class="form">
                          <form class="form-horizontal" id="form-add-pwp">
                            <input type="hidden" id="_token" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="product_id" value="{{ $productDetails->id }}" />
                            <div class="form-group">
                              <label class="col-md-3 control-label">Status <span class="text-red">*</span></label>
                              <div class="col-md-6">
                                <div data-on="success" data-off="primary" class="make-switch">
                                  <input type="checkbox" name="status" checked="checked"/>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">Please select a Category <span class="text-red">*</span></label>
                              <div class="col-md-6">
                                <div class="xs-margin"></div>
                                <select class="form-control" name="category_id" id="category_id">
                                  <option value="0">--</option>
                                  <?php echo $categories; ?>
                                </select>
                              </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                              <label class="col-md-3 control-label">Please select a Product <span class="text-red">*</span></label>
                              <div class="col-md-9">
                                <div class="clearfix"></div>
                                <div id="add-pwp-table">
                                  <table class="table checkout-table table-responsive">
                                    <thead>
                                      <tr>
                                        <th width="1%"></th>
                                        <th class="table-title">Type</th>
                                        <th class="table-title">Room Code</th>
                                        <th class="table-title">Sale Price</th>
                                        <th class="table-title">Quantity</th>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
                                  </table>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="inputFirstName" class="col-md-3 control-label">PWP Price (RM) <span class="text-red">*</span></label>
                              <div class="col-md-6">
                                <input type="text" name="price" class="form-control" placeholder="0.00" value="0.00">
                                <div class="xss-margin"></div>
                              </div>
                            </div>

                            <div class="form-actions">
                              <div class="col-md-offset-5 col-md-8"> <a href="#" id="add-pwp-button" class="btn btn-red">Save &nbsp;<i class="fa fa-floppy-o"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--END MODAL add new pwp product-->

                <!--Modal delete selected items start-->
                <div id="modal-delete-selected-pwp" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-login-label3" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete the selected item(s)? </h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-actions">
                          <div class="col-md-offset-4 col-md-8"> <a href="{{ route('pwp.delete') }}" onclick="return deleteItem($(this), 'selected');" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- modal delete selected items end -->

                <!---Modal select at least one-->
                   <div id="modal-selected-least-one-pwp" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                            <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete the selected item(s)? </h4>
                          </div>
                          <div class="modal-body">
                            <div class="alert alert-danger" role="alert">
                              Please select at least one service before delete.
                            </div>
                            <div class="form-actions">
                              <div class="col-md-offset-4 col-md-8">
                               <a href="javascript:void(0)" data-dismiss="modal" onclick="cancel_delete()" class="btn btn-green">OK &nbsp;<i class="fa fa-times-circle"></i>
                               </a>

                             </div>
                           </div>
                         </div>
                       </div>
                     </div>
                   </div>
                   <!-- modal delete selected  at least one items end -->


                <!--Modal delete all items start-->
                <div id="modal-delete-all-pwp" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                        <h4 id="modal-login-label3" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete all items? </h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-actions">
                          <div class="col-md-offset-4 col-md-8"> <a href="{{ route('pwp.delete') }}" onclick="return deleteItem($(this), 'all');" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- modal delete all items end -->
              </div>

              <div class="portlet-body">
                {{-- <div class="form-inline pull-left">
                  <div class="form-group">
                    <select name="select" class="form-control">
                      <option>10</option>
                      <option>20</option>
                      <option selected="selected">30</option>
                      <option>50</option>
                      <option>100</option>
                    </select>
                    &nbsp;
                    <label class="control-label">Records per page</label>
                  </div>
                </div> --}}
                <div class="clearfix"></div>
                <br/>

                <div class="table-responsive mtl">
                  <table class="table table-hover table-striped">
                    <thead>
                      <tr>
                        <th width="1%"><input type="checkbox"/></th>
                        <th>#</th>
                        <th><a herf="sort by status">Status</a></th>
                        <th>Image</th>
                        <th><a herf="sort by type">Type</a> / <a herf="sort by room code">Vehicle Code</a></th>
                        <th><a herf="sort by pwp price">PWP Price (RM)</a></th>
                        <th><a herf="sort by sale price">Sale Price (RM)</a></th>
                        <th><a herf="sort by list price">List Price (RM)</a></th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if ($pwp_products->isEmpty())
                      No pwp product yet!
                      @else
                      @foreach ($pwp_products as $pwp)
                      <tr>
                        <td><input type="checkbox" name="pwp_ids[]" value="{{$pwp->id}}" class="chk-item" /></td>
                        <td>1</td>
                        <td>
                          @if ($pwp->status == 1)
                          <span class="label label-sm label-success">Active</span>
                          @else
                          <span class="label label-sm label-red" id="brand-status-26">In-active</span>
                          @endif
                        </td>
                        <td><img src="{{ asset('/public/admin/products/medium/'. $pwp->product->thumbnail_image_1) }}" alt="Panasonic Camera" class="img-responsive" width="100px"></td>
                        <td><a href="product_edit.html">{{$pwp->product->type}}</a> <br/>Product Code: {{$pwp->product->room_code}}</td>
                        <td>{{number_format($pwp->price, 2)}}</td>
                        <td>{{number_format($pwp->product->sale_price, 2)}}</td>
                        <td>{{number_format($pwp->product->list_price, 2)}}</td>
                        <td>
                          <a href="#" data-hover="tooltip" data-placement="top" title="Edit" data-target="#modal-pwp-product-edit-{{$pwp->id}}" data-toggle="modal">
                            <span class="label label-sm label-success">
                              <i class="fa fa-pencil"></i>
                            </span>
                          </a>
                          <a href="#" data-hover="tooltip" data-placement="top" title="Delete" data-target="#modal-delete-pwp-{{$pwp->id}}" data-toggle="modal">
                            <span class="label label-sm label-red">
                              <i class="fa fa-trash-o"></i>
                            </span>
                          </a>

                          <!--Modal edit pwp product start-->
                          <div id="modal-pwp-product-edit-{{$pwp->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog modal-wide-width">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                  <h4 id="modal-login-label3" class="modal-title">Edit PWP Product</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form">
                                    <form class="form-horizontal" id="form-edit-pwp-{{$pwp->id}}">
                                      <input type="hidden" name="_token" value="{{csrf_token()}}">
                                      <input type="hidden" name="id" value="{{$pwp->id}}">
                                      <div class="form-group">
                                        <label class="col-md-3 control-label">Status <span class="text-red">*</span></label>
                                        <div class="col-md-6">
                                          <div data-on="success" data-off="primary" class="make-switch">
                                            <?php $checked = ($pwp->status == 1) ? 'checked="checked"' : ''; ?>
                                            <input name="status" value="1" type="checkbox" {{$checked}}/>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="clearfix"></div>

                                      <div class="form-group">
                                        <label for="inputFirstName" class="col-md-3 control-label">PWP Price (RM) <span class="text-red">*</span></label>
                                        <div class="col-md-6">
                                          <input type="text" name="price" class="form-control" placeholder="0.00" value="{{$pwp->price}}">
                                          <div class="xss-margin"></div>
                                        </div>
                                      </div>

                                      <div class="form-actions">
                                        <div class="col-md-offset-5 col-md-8"> <a href="#" class="edit-pwp btn btn-red">Save &nbsp;<i class="fa fa-floppy-o"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--END MODAL edit pwp product-->

                          <!--Modal delete pwp start-->
                          <div id="modal-delete-pwp-{{$pwp->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                  <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this? </h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form-actions">
                                    <div class="col-md-offset-4 col-md-8"> <a href="{{ route('pwp.delete') }}" rel="{{$pwp->id}}" onclick="return deleteItem($(this), 'id');" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- modal delete pwp end -->
                        </td>
                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="9"></td>
                      </tr>
                    </tfoot>
                  </table>
                  {{-- <div class="tool-footer text-right">
                    <p class="pull-left">Showing 1 to 10 of 57 entries</p>
                    <ul class="pagination pagination mtm mbm">
                      <li class="disabled"><a href="#">&laquo;</a></li>
                      <li class="active"><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">4</a></li>
                      <li><a href="#">5</a></li>
                      <li><a href="#">&raquo;</a></li>
                    </ul>
                  </div> --}}
                  <div class="clearfix"></div>
                </div>
              </div>
              <!-- end portlet body -->
            </div>
            <!-- end portlet -->
          </div>
          <!-- end pwp -->
          @include('admin.products.tabs.room_amenities')
        </div>
        <!-- end tab content -->
        <div class="clearfix"></div>
      </div>
      <!-- end col-lg-12 -->
    </div>
    <!-- end row -->
  </div>
  <!--END CONTENT-->
  <!--BEGIN FOOTER-->
  <div class="page-footer">
    <div class="copyright">
      <span class="text-15px">2015 &copy; <a href="http://www.webqom.com" target="_blank">Webqom Technologies Sdn Bhd.</a> Any queries, please contact<a href="mailto:support@webqom.com">Webqom Support</a>.</span>
      <div class="pull-right"><img src="{{ asset('/public/admin/images/logo_webqom.png') }}" alt="Webqom Technologies Sdn Bhd"></div>
    </div>
  </div>
  <!--END FOOTER-->
</div>
<!--END PAGE WRAPPER-->

<!--LOADING SCRIPTS FOR PAGE-->
<script>

    /* if tax rate checked then reserve tax collection will be disbaled */
    $('#is_tax').click(function () {
        console.log("$(this).is(':checked')");
        console.log($(this).is(':checked'));
        if($(this).is(':checked')) {
            $("#reverse_tax_calculation").prop('disabled', true);
        } else {
            $("#reverse_tax_calculation").prop('disabled', false);
        }
    });

    $("#reverse_tax_calculation").click(function () {
        console.log("$(this).is(':checked')");
        console.log($(this).is(':checked'));
        if($(this).is(':checked')) {
            $('#is_tax').prop('disabled', true);
            $("#net_price_per_night").prop('disabled', true);
            $("#net_price_per_night").prop('checked', false);
        } else {
            $('#is_tax').prop('disabled', false);
        }
    });

    $("#net_price_per_night").click(function () {
        console.log("$(this).is(':checked')");
        console.log($(this).is(':checked'));
        if($(this).is(':checked')) {
            $("#reverse_tax_calculation").prop('disabled', true);
            $("#reverse_tax_calculation").prop('checked', false);
        } else {
            $("#reverse_tax_calculation").prop('disabled', false);
            $("#reverse_tax_calculation").prop('checked', true);
        }
    });

    $(document).ready(function(){

        var checkbox=$('#net_price_per_night')
        // checkbox.on('click',checkStatus);

function checkStatus() {

  // if at least one checkbox in selected checkboxes is checked then
  // disable target checkboxes
  if($(checkbox).is(':checked'))
  {
      $("#reverse_tax_calculation").prop('disabled', false);

  }
  else{
      $("#reverse_tax_calculation").prop('disabled', true);
      $("#reverse_tax_calculation").prop('checked', false);
  }
}
    })
</script>
<script>

  // $(document).on('click','#addRoomPrice',function(){
  //     $('.se-pre-con').removeAttr('style');
  //     $('#modal-add-new').modal('show');
  //     setTimeout(function(){
  //         $('.se-pre-con').css('display','none');
  //         }, 2000);
  // });
function deleteSelected() {
  values = $('.select_items:checked');
  if (values.length==0) {
    //alert('Please select at least one discount before delete.');
      $('#modal-selected-least-one').modal('show');
    //return false;
  }else{
     $('#modal-delete-selected').modal('show');
   }
}

function deleteSelectedPwp() {
  values = $('.chk-item:checked');
  if (values.length==0) {
    //alert('Please select at least one PWP before delete.');
    //return false;
    $('#modal-selected-least-one-pwp').modal('show');
  }else
  {
     $('#modal-delete-selected-pwp').modal('show');
  }
}

var clickable = true;
function savePwp(obj, form_id) {
  if (!clickable) {
    return false;
  }

  var url = '{{route('pwp.save')}}';

  // Create a formdata object and add the files
  var data = new FormData(document.getElementById(form_id));

  clickable = false;
  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    dataType: 'json',
    processData: false,
    contentType: false,
    beforeSend:function () {
      obj.html('Saving... <i class="fa fa-floppy-o"></i>');
    },
    complete: function() {
      obj.html('Save <i class="fa fa-floppy-o"></i>');
    },
    success: function (response) {
      clickable = true;
      var html = '';

      $('#warning-box').remove();
      $('#success-box').remove();

      if(response['error']) {
        html += '<div id="warning-box" class="alert alert-danger alert-dismissable">';
        html += '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>';
        html += '<i class="fa fa-times-circle"></i> <strong>Error!</strong>';

        for(var i in response['error']) {
          html += '<p>'+ response['error'][i] +'</p>';
        }

        html += '</div>';
        $('#'+form_id).before(html);
      }

      if(response['success']) {
        window.location.reload();
      }
    }
  });
}

$(document).ready(function () {
  $('#category_id').val(0).change(function(val, inst){
    var val = $(this).val();
    if(val != '') {
      $.ajax({
        url: "{{ route('pwp.list') }}",
        type: 'POST',
        data: {category_id:val, _token: '{{ csrf_token() }}'},
        dataType: 'html',
        async: false,
        cache: false,
        beforeSend:function () {},
        complete: function(){},
        success: function (response) {
          if(response['error']) {
            html += '<div id="warning-box" class="alert alert-danger alert-dismissable">';
            html += '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>';
            html += '<i class="fa fa-times-circle"></i> <strong>Error!</strong>';

            for(var i in response['error']) {
              html += '<p>'+ response['error'][i] +'</p>';
            }

            html += '</div>';
            $('#'+form_id).before(html);
          } else {
            $('#add-pwp-table').html(response);
          }
        }
      });
    } else {}
  });

  $('#add-pwp-button').click(function() {
    savePwp($(this), 'form-add-pwp');
    return false;
  });

  $('.edit-pwp').click(function() {
    savePwp($(this), $(this).closest('form').attr('id'));
    return false;
  });
});
</script>
@push('scripts')
<script src="{{ asset('/public/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/moment/moment.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/bootstrap-clockface/js/clockface.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/jquery-maskedinput/jquery-maskedinput.js') }}"></script>
<script src="{{ asset('/public/admin/js/form-components.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/fullcalendar/fullcalendar.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('/public/admin/js/price-calendar.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('/public/admin/vendors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/public/admin/js/ui-tabs-accordions-navs.js') }}"></script>
<script src="{{ asset('/public/admin/js/action.js') }}"></script>
<!--<script src="{{asset('/public/admin/js/gliss.rooms.js')}}"></script>-->
<script src="{{asset('/public/admin/js/save_amenities.js')}}"></script>
<!--LOADING SCRIPTS FOR PAGE-->
@endpush
@endsection
