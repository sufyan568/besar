@extends('adminLayout')
@section('title', 'Drop Off List')

@push('styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('/public/admin/css/price-calendar.css') }}">
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
    <!--BEGIN PAGE WRAPPER-->
    <div id="page-wrapper"><!--BEGIN PAGE HEADER & BREADCRUMB-->

        <div class="page-header-breadcrumb">
            <div class="page-heading hidden-xs">
                <h1 class="page-title">Drop Off List</h1>
            </div>


            <ol class="breadcrumb page-breadcrumb">
                <li><i class="fa fa-home"></i>&nbsp;<a href="{{ url('/web88cms/dashboard') }}">Dashboard</a>&nbsp; <i class="fa fa-angle-right"></i>&nbsp;</li>
                <li>Drop Off List &nbsp;<i class="fa fa-angle-right"></i>&nbsp;</li>
                <li class="active">Drop Off List- Listing</li>
            </ol>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Drop Off List <i class="fa fa-angle-right"></i> Listing</h2>
                    <div class="clearfix"></div>
                    @if(Session::has('response'))
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
                            <i class="fa fa-check-circle"></i> <strong>Success!</strong>
                            <p>{{ Session::get('response') }}</p>
                        </div>
                        <?php \Session::forget('response'); ?>
                    @endif
                    <div class="clearfix"></div>
                </div>

                <!-- end col-md-6 -->
                <div class="col-lg-12">

                    <p>@if (Session::has('flash_message'))
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" data-dismiss="alert" aria-hidden="true" onClick="$('.form-horizontal').trigger('reset');" class="close">&times;</button>
                            <i class="fa fa-check-circle"></i> <strong>Success!</strong> {{ Session::get('flash_message') }}</div>
                        @endif
                        </p>

                        @if(isset($error))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
                                <i class="fa fa-times-circle"></i> <strong>Error!</strong>
                                <p>{{ $error }}</p>
                            </div>
                        @endif
                </div>

                <ul id="myTab" class="nav nav-tabs">
                    <li class="active"><a href="#brand-image" data-toggle="tab">Drop Off List Listing</a></li>
                </ul>

                <div id="myTabContent" class="tab-content">
                    <div id="brand-image" class="tab-pane fade in active">
                        <div class="portlet">
                            <div class="portlet-header">
                                <div class="caption">Drop Off List Listing</div>
                                <br/>
                                <p class="margin-top-10px"></p>
                                <button class="btn btn-success" data-toggle="modal" data-target="#modal-add-property">Add New Drop Off List &nbsp;<i class="fa fa-plus"></i></button>&nbsp;
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Delete</button>
                                    <button type="button" data-toggle="dropdown" class="btn btn-red dropdown-toggle"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                                    <ul role="menu" class="dropdown-menu">
                                        <?php /*?> <li><a href="#" data-target="#modal-delete-selected" data-toggle="modal">Delete selected item(s)</a></li>
				                         <?php */?>
                                        <li><a href="#" onclick="deleteSelected()">Delete selected item(s)</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" data-target="#modal-delete-all" data-toggle="modal">Delete all</a></li>
                                    </ul>
                                </div>
                                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
                                <!--modal delete selected  at least one items start-->
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

                                <!--modal delete selected  items start-->
                                <div id="modal-delete-selected" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete the selected item(s)? </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-actions">
                                                    <div class="col-md-offset-4 col-md-8"> <a href="javascript:void(0)" onclick="delete_selected('drop_off_list')" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" onclick="cancel_delete()" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- modal delete selected  items  end -->





                                <!--Modal delete all items start-->
                                <div id="modal-delete-all" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete all items? </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-actions">
                                                    <div class="col-md-offset-4 col-md-8"> <a href="javascript:void(0)" onclick="delete_all('drop_off_list')" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" onclick="cancel_delete()"  class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- modal delete all items end -->
                            </div>


                            <div class="portlet-body">
                                <div class="table-responsive mtl">

                                    <table class="table table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th width="1%"><input type="checkbox" id="select_items"/></th>
                                            <td>#</td>
                                            <td>Status</td>
                                            <td>Name</td>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($property) > 0)
                                            @foreach($property as $p)
                                                <?php
                                                $status_class = ($p->status == '0') ? 'label-red' : 'label-success';
                                                $status = ($p->status == '0') ? 'In-active' : 'Active';
                                                ?>

                                                <tr>
                                                    <td><input type="checkbox" data-id="<?php echo $p->drop_list_id; ?>" class="select_items"/></td>
                                                    <td>{{$p->drop_list_id}}</td>
                                                    <td><span class="label label-sm <?php echo $status_class; ?>" id="brand-status-<?php echo $p->id; ?>"><?php echo $status; ?></span></td>
                                                    <td>{{$p->name}}</td>
                                                    <td>

                                                        <a class="edit_property" data-hover="tooltip" data-placement="top" title="Edit" data-id="{{$p->drop_list_id}}" data-status="{{$p->status}}" data-name="{{$p->name}}" data-type="{{$p->type}}" data-address="{{$p->address}}" data-city="{{$p->city}}" data-postal_code="{{$p->postal_code}}" data-state="{{$p->state}}" data-country="{{$p->country}}" data-website_url="{{$p->website_url}}" data-telephone="{{$p->telephone}}" data-fax="{{$p->fax}}" data-administrative_email="{{$p->administrative_email}}" data-reservation_email="{{$p->reservation_email}}"><span class="label label-sm label-success"><i class="fa fa-pencil"></i></span></a>

                                                        <a href="#" data-hover="tooltip" data-placement="top" title="Delete" data-target="#modal-delete-<?php echo $p->drop_list_id; ?>" data-toggle="modal" onclick="delete_item(<?php echo $p->drop_list_id; ?>)"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a>
                                                        <!--Modal delete start-->
                                                        <div id="modal-delete-<?php echo $p->drop_list_id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                        <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this? </h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Drop Off List Name : <?php echo $p->name; ?></p>
                                                                        <div class="form-actions">
                                                                            <div class="col-md-offset-4 col-md-8"> <a href="javascript:void(0)" class="btn btn-red" onclick="continue_delete_process_drop_list()">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-green" onclick="cancel_delete()">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- modal delete end -->
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" style="text-align: center;">No records found</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>

                                    @if(count($property) > 0)
                                        <div class="tool-footer text-right">
                                            <p class="pull-left"><?php echo "Showing ".$property->firstItem()." to ".$property->lastItem()." of ". $property->total() ." entries"; ?></p>

                                            <?php echo $property->setPath(Request::url())->appends(Input::get())->render(); ?>
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div id="modal-add-property" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade" style="display: none;">
            <div class="modal-dialog modal-wide-width">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">??</button>
                        <h4 id="modal-login-label2" class="modal-title">Add New Drop Off List</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form">
                            <form class="form-horizontal" method="post" id="add_property_info" action="{{ url('web88cms/store-drop-off-list

') }}">

                                <div class="alert alert-danger alert-dismissable" style="display: none;" id="add_property_errors_div">
                                    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
                                    <i class="fa fa-times-circle"></i> <strong>Error!</strong>
                                    <p id="add_property_errors"></p>
                                </div>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <h5>Drop Off List Information</h5>
                                <hr>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Status <span class="text-red">*</span></label>
                                    <div class="col-md-6">
                                        <div data-on="success" data-off="primary" class="make-switch">
                                            <input type="checkbox" name="status" checked="checked" value="1" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Name <span class="text-red">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="" required="">
                                        <p id="name" style="color:red;"></p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Type</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="type" id="type">
                                            <option value="Hotel">Hotel</option>
                                            <option value="Resort Village">Resort Village</option>
                                            <option value="Villa">Villa</option>
                                            <option value="Inn (+ 4stars)">Inn (+ 4stars)</option>
                                            <option value="Chalet">Chalet</option>
                                            <option value="Apartment">Apartment</option>
                                            <option value="Hostel">Hostel</option>
                                            <option value="Budget Hotel">Budget Hotel</option>
                                            <option value="Homestay">Homestay</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Address </label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="address" id="address"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">City</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="city" id="city">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Post Code</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="postal_code" id="postal_code">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">State</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="state" id="state">
                                            <option value="Johor">Johor</option>
                                            <option value="Kedah">Kedah</option>
                                            <option value="Kelantan">Kelantan</option>
                                            <option value="Kuantan">Kuantan</option>
                                            <option value="Melaka">Melaka</option>
                                            <option value="Negeri Sembilan">Negeri Sembilan</option>
                                            <option value="Labuan">Labuan</option>
                                            <option value="Pahang">Pahang</option>
                                            <option value="Pulau Pinang">Pulau Pinang</option>
                                            <option value="Perak">Perak</option>
                                            <option value="Perlis">Perlis</option>
                                            <option value="Putrajaya">Putrajaya</option>
                                            <option value="Sabah">Sabah</option>
                                            <option value="Sarawak">Sarawak</option>
                                            <option value="Selangor">Selangor</option>
                                            <option value="Terengganu">Terengganu</option>
                                            <option value="Wilayah Persekutuan">Wilayah Persekutuan</option>
                                            <option value="-">-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Country</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="country" name="country">
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="all">List all countries</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <h5>Contacts</h5>
                                <hr>

                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Website URL</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="website_url" id="website_url">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Telephone </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="telephone" id="telephone">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Fax </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="fax" id="fax">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Administrative Email </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="administrative_email" id="administrative_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Reservation Email </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="reservation_email" id="reservation_email">
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="col-md-offset-5 col-md-8">
                                        <a href="#" class="btn btn-red" id="submitProperty">Save &nbsp;<i class="fa fa-floppy-o"></i></a>&nbsp;
                                        <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="modal-edit-property" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade" style="display: none;">
            <div class="modal-dialog modal-wide-width">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">??</button>
                        <h4 id="modal-login-label2" class="modal-title">Edit Drop Off List</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form">
                            <form class="form-horizontal" method="post" id="edit_property_info" action="{{ url('web88cms/store-drop-off-list') }}">

                                <div class="alert alert-danger alert-dismissable" style="display: none;" id="edit_property_errors_div">
                                    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
                                    <i class="fa fa-times-circle"></i> <strong>Error!</strong>
                                    <p id="edit_property_errors"></p>
                                </div>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="drop_list_id" id="edit_drop_list_id" value="">
                                <h5>Drop Off List Information</h5>
                                <hr>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Status <span class="text-red">*</span></label>
                                    <div class="col-md-6">
                                        <div data-on="success" data-off="primary" class="make-switch">
                                            <input type="checkbox" name="status" id="edit_status" checked="checked" value="1" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Name <span class="text-red">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" id="edit_name" class="form-control" placeholder="" required="">
                                        <p id="name" style="color:red;"></p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Type</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="type" id="edit_type">
                                            <option value="Hotel">Hotel</option>
                                            <option value="Resort Village">Resort Village</option>
                                            <option value="Villa">Villa</option>
                                            <option value="Inn (+ 4stars)">Inn (+ 4stars)</option>
                                            <option value="Chalet">Chalet</option>
                                            <option value="Apartment">Apartment</option>
                                            <option value="Hostel">Hostel</option>
                                            <option value="Budget Hotel">Budget Hotel</option>
                                            <option value="Homestay">Homestay</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Address </label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="address" id="edit_address"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">City</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="city" id="edit_city">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Post Code</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="postal_code" id="edit_postal_code">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">State</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="state" id="edit_state">
                                            <option value="Johor">Johor</option>
                                            <option value="Kedah">Kedah</option>
                                            <option value="Kelantan">Kelantan</option>
                                            <option value="Kuantan">Kuantan</option>
                                            <option value="Melaka">Melaka</option>
                                            <option value="Negeri Sembilan">Negeri Sembilan</option>
                                            <option value="Labuan">Labuan</option>
                                            <option value="Pahang">Pahang</option>
                                            <option value="Pulau Pinang">Pulau Pinang</option>
                                            <option value="Perak">Perak</option>
                                            <option value="Perlis">Perlis</option>
                                            <option value="Putrajaya">Putrajaya</option>
                                            <option value="Sabah">Sabah</option>
                                            <option value="Sarawak">Sarawak</option>
                                            <option value="Selangor">Selangor</option>
                                            <option value="Terengganu">Terengganu</option>
                                            <option value="Wilayah Persekutuan">Wilayah Persekutuan</option>
                                            <option value="-">-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Country</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="country" name="edit_country">
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="all">List all countries</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <h5>Contacts</h5>
                                <hr>

                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Website URL</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="website_url" id="edit_website_url">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Telephone </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="telephone" id="edit_telephone">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Fax </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="fax" id="edit_fax">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Administrative Email </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="administrative_email" id="edit_administrative_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputFirstName" class="col-md-4 control-label">Reservation Email </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="reservation_email" id="edit_reservation_email">
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="col-md-offset-5 col-md-8">
                                        <a href="#" class="btn btn-red" id="submitEditProperty">Save &nbsp;<i class="fa fa-floppy-o"></i></a>&nbsp;
                                        <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!--BEGIN FOOTER-->
        <div class="page-footer">
            <div class="copyright"><span class="text-15px">2015 &copy; <a href="http://www.webqom.com" target="_blank">Webqom Technologies Sdn Bhd.</a> Any queries, please contact <a href="mailto:support@webqom.com">Webqom Support</a>.</span>
                <div class="pull-right"><img src="{{ asset('/public/admin/images/logo_webqom.png') }}" alt="Webqom Technologies Sdn Bhd"></div>
            </div>
        </div>


        <input type="hidden" id="delete_item_ids" value="0" />
        <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}" />


    </div>
    <!--END PAGE WRAPPER-->

    <style type="text/css">
        .status-button .btn{
            opacity: 0.4
        }
        .status-button .btn.active{
            opacity: 1
        }
    </style>

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
    <script src="{{ asset('/public/admin/js/price-calendar.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('/public/admin/js/ui-tabs-accordions-navs.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script type="text/javascript">
        function deleteSelected(){
            values = $('.select_items:checked');
            // alert(values.length);
            if (values.length==0){

                $('#modal-selected-least-one').modal('show');

            }else{

                $('#modal-delete-selected').modal('show');
            }
        }

        // select all checkboxes
        $(document).ready(function(){
            $('#select_items').click(function(){
                if($('#select_items').is(':checked'))
                {
                    $('.select_items').prop('checked',true);
                }
                else
                    $('.select_items').prop('checked',false);
            });
        });


        $("#submitProperty").click(function(e){
            e.preventDefault();
            form = $("#add_property_info");
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                error: function(jqXHR, textStatus, errorThrown) {
                    responseText = jQuery.parseJSON(jqXHR.responseText);
                    $("#add_property_errors").html();
                    $.each(responseText.message, function( index, value ) {
                        $("#add_property_errors").append(value);
                    });
                    $("#add_property_errors_div").show();
                }
            }).done(function( msg ) {
                $('#modal-add-property').modal('hide');
                window.location.reload();
            });
        });

        $("#submitEditProperty").click(function(e){
            e.preventDefault();
            form = $("#edit_property_info");
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                error: function(jqXHR, textStatus, errorThrown) {
                    responseText = jQuery.parseJSON(jqXHR.responseText);
                    $("#edit_property_errors").html();
                    $.each(responseText.message, function( index, value ) {
                        $("#edit_property_errors").append(value);
                    });
                    $("#edit_property_errors_div").show();
                }
            }).done(function( msg ) {
                $('#modal-edit-property').modal('hide');
                window.location.reload();
            });
        });




        $(document).on('click',".edit_property",function(e){
            e.preventDefault();
            self = $(this);

            if(self.attr("data-status") == 1){
                $("#edit_status").parent('div').removeClass('switch-off');
                $("#edit_status").parent('div').addClass('switch-on');
                $("#edit_status").prop( "checked", true );
            }else{
                $("#edit_status").parent('div').addClass('switch-off');
                $("#edit_status").parent('div').removeClass('switch-on');
                $("#edit_status").prop( "checked", false );
            }

            $("#edit_name").val(self.attr("data-name"));
            $("#edit_type").val(self.attr("data-type"));
            $("#edit_address").html(self.attr("data-address"));
            $("#edit_city").val(self.attr("data-city"));
            $("#edit_postal_code").val(self.attr("data-postal_code"));
            $("#edit_state").val(self.attr("data-state"));
            $("#edit_country").val(self.attr("data-country"));
            $("#edit_website_url").val(self.attr("data-website_url"));
            $("#edit_telephone").val(self.attr("data-telephone"));
            $("#edit_fax").val(self.attr("data-fax"));
            $("#edit_administrative_email").val(self.attr("data-administrative_email"));
            $("#edit_reservation_email").val(self.attr("data-reservation_email"));

            $("#edit_drop_list_id").val(self.attr("data-id"));

            $('#modal-edit-property').modal('show');
        });


    </script>

@endsection