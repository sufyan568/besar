@extends('adminLayout')



@section('content')
<div id="page-wrapper"><!--BEGIN PAGE HEADER & BREADCRUMB-->
    <div class="page-header-breadcrumb">
        <div class="page-heading hidden-xs">
            <h1 class="page-title">CMS Pages</h1>
        </div>

        <!-- InstanceBeginEditable name="EditRegion1" -->
        <ol class="breadcrumb page-breadcrumb">
            <li><i class="fa fa-home"></i>&nbsp;<a href="dashboard.html">Dashboard</a>&nbsp; <i class="fa fa-angle-right"></i>&nbsp;</li>
            <li>CMS Pages &nbsp;<i class="fa fa-angle-right"></i>&nbsp;</li>
            <li class="active">Facilities - Edit</li>
        </ol>
      </div>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>Facilities <i class="fa fa-angle-right"></i> Edit</h2>
                <div class="clearfix"></div>
                <div class="alert alert-success alert-dismissable"
                     @if( Session::has('success') ) style="display: block;">
                      <script>setTimeout(function () {
                        $("body").animate({"scrollTop": 0}, 100);
                    }, 3000);</script>
                    <?php Session::forget('success'); ?>
                    @else
                      style="display: none;">
                    @endif
                    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
                    <i class="fa fa-check-circle"></i> <strong>Success!</strong>
                    <p>The information has been saved/updated successfully.</p>
                </div>
                <div class="alert alert-danger alert-dismissable" @if( Session::has('fail') ) style="display: block;">
                      <script>
                        setTimeout(function () {
                          $("body").animate({"scrollTop": 0}, 100);
                        }, 3000);</script>
                    <?php Session::forget('fail'); ?>
                    @else
                      style="display: none;">
                    @endif
                    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
                    <i class="fa fa-times-circle"></i> <strong>Error!</strong>
                    <p>The information has not been saved/updated. Please correct the errors.</p>
                </div>

                @if(null!==$updated)
                <div class="pull-left"> Last updated: <span class="text-blue">{{ $updated }}</span> </div>
                @endif
                <div class="clearfix"></div>
                <p></p>
                <div class="clearfix"></div>
                <div class="portlet">
                    <div class="portlet-header">
                        <div class="caption">Page Content</div>
                        <div class="clearfix"></div>
                        <span class="text-blue text-12px">You can edit the content by clicking the text section below.</span>
                        <div class="tools"><i class="fa fa-chevron-up"></i></div>
                    </div>
                    <div class="portlet-body">
                        <section class="best-place-section best-place-style-two">
                            <div class="wbx_info" name="facility[first]" contenteditable="true">
                                {!! html_entity_decode($data[0]) !!}
                            </div>
                        </section><!--/.best-place-section-->
                        <form method="post" action="{{URL('/web88cms/facility_editor')}}" id="wbx_change_info">
                          <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        </form>
                       
                    </div>
                    <!-- End portlet body -->
                    <!-- save button start -->
                    <div class="form-actions none-bg"> 
                      <a href="#preview in browser/not yet published" target="_blank" class="wbx_submit_preview btn btn-red">Save &amp; Preview &nbsp;<i class="fa fa-search"></i></a>&nbsp; 
                      <a href="#publish online" class="btn btn-blue wbx_submit_publish">Save &amp; Publish &nbsp;<i class="fa fa-globe"></i></a>&nbsp; 
                      <a href="#" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> 
                    </div>
                    <!-- save button end -->
                </div>
                <!-- End portlet -->
                {{-- NEW TAB --}}
                <h4 class="block-heading">Hotel Facilities Icons & Background Images</h4>
                <div id="errorBox"></div>
                <div id="successBox"></div>
                <ul id="myTab" class="nav nav-tabs">
                    <li class="active"><a href="#hotelfacilities" data-toggle="tab">Hotel Facilities Icons</a></li>
                    <li><a href="#backgroundimage" data-toggle="tab">Background Image</a></li>
                    <li><a href="#video" data-toggle="tab">Video With Background</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div id="hotelfacilities" class="tab-pane fade in active">
                        <div class="portlet">
                            <div class="portlet-header">
                                <div class="caption">Hotel Facilities Icons Lsiting </div>
                                <br/>
                                <p class="margin-top-10px"></p>
                                <a href="#" data-target="#modal-add-objective" data-toggle="modal" class="btn btn-success">Add New Facility &nbsp;<i class="fa fa-plus"></i></a>&nbsp;
                                {{-- <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Delete</button>
                                    <button type="button" data-toggle="dropdown" class="btn btn-red dropdown-toggle"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="#" data-target="#modal-delete-selected" data-toggle="modal">Delete selected item(s)</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" data-target="#modal-delete-all" data-toggle="modal">Delete all</a></li>
                                    </ul>
                                </div> --}}

                                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
                                <!--Modal Add New Objective start-->
                                <div id="modal-add-objective" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog modal-wide-width">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                <h4 id="modal-login-label3" class="modal-title">Add New Facility</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form">
                                                    <form id="addObj" class="form-horizontal" method="post">
                                                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Status</label>
                                                            <div class="col-md-6">
                                                                <div data-on="success" data-off="primary" class="make-switch">
                                                                    <input type="checkbox" checked="checked" name="status" value="1" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Facility Name </label>
                                                            <div class="col-md-6">
                                                                <input name="name" rows="3" class="form-control">
                                                            </div>
                                                            <div class="col-md-3"> </div>
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Upload Icon Image <span class='require'>*</span></label>
                                                            <div class="col-md-9">
                                                                <div class="text-15px margin-top-10px"> <img src="../../images/testimonialsbg.jpg" alt="Facility" class="img-responsive">
                                                                    <br/>
                                                                    <input name="icon" id="exampleInputFile2" type="file" />
                                                                    <br/>
                                                                    <span class="help-block">(Image dimension: min. 64 x 64 pixels, JPEG/GIF/PNG only, Max. 1MB) </span> </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-actions">
                                                            <div class="col-md-offset-5 col-md-8"> <a href="javascript:void(0);" onClick="addFacility();" class="btn btn-red">Save &nbsp;<i class="fa fa-floppy-o"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                                                        </div>
                                                        <script>
                                                            function addFacility() {
                                                                var form_data = new window.FormData($('#addObj')[0]);
                                                                // var form_data = new FormData();
                                                                // var files = $('#exampleInputFile2')[0].files[0];
                                                                // form_data.append('icon',files);
                                                                // console.log(form_data);
                                                                $.ajax({
                                                                    url: 'facilityAdd',
                                                                    type: 'post',
                                                                    dataType: 'json',
                                                                    data: form_data,
                                                                    enctype: 'multipart/form-data',
                                                                    processData: false,
                                                                    contentType: false,
                                                                    success: function(response) {
                                                                        if (response['error']) {
                                                                            $('#errorBox').remove();
                                                                            $('#successBox').remove();
                                                                            var error = '<div id="errorBox" class="alert alert-danger"><i class="fa fa-times-circle"></i> <strong>Error!</strong>';
                                                                            for (var i = 0; i < response['error'].length; i++) {
                                                                                error += '<p>' + response['error'][i] + '</p>';
                                                                            }
                                                                            error += '</div>';
                                                                            $('#addObj').before(error);
                                                                        }

                                                                        if (response['success']) {
                                                                            $('#errorBox').remove();
                                                                            $('#successBox').remove();
                                                                            var success = '<div id="successBox" class="alert alert-success"><i class="fa fa-check-circle"></i> <strong>Success!</strong><p>The information has been saved/updated successfully.</p></div>';
                                                                            $('#addObj').before(success);                                                                            
                                                                            $('#addObj').live('load');  
                                                                            setInterval(function(){
                                                                                // $("#screen").load('banners.php')
                                                                                window.location.reload();
                                                                            }, 2000);                                                                          
                                                                            
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--END MODAL Add New Objective-->
                                <!--Modal delete selected items start-->
                                <div id="modal-delete-selected" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete the selected item(s)? </h4>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>#1:</strong> To build long-term relationship with all stakeholders for sustainable growth.</p>
                                                <div class="form-actions">
                                                    <div class="col-md-offset-4 col-md-8"> <a href="#" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
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
                                                <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete all items? </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-actions">
                                                    <div class="col-md-offset-4 col-md-8"> <a href="#" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- modal delete all items end -->
                            </div>
                            <div class="portlet-body">
                                <div class="form-inline pull-left">
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
                                </div>
                                <div class="clearfix"></div>
                                <br/>
                                <br/>
                                <div class="table-responsive mtl">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                             {{--    <th width="1%">
                                                    <input type="checkbox" />
                                                </th> --}}
                                                {{-- <th>#</th> --}}
                                                <th>Status</th>
                                                <th>Facility Name</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;foreach($facilities as $key1 => $facility) { ?>
                                                <tr>
                                                   {{--  <td>
                                                        <input type="checkbox" />
                                                    </td> --}}
                                                    <td>
                                                        <?php echo $i++;?>
                                                    </td>
                                                    <td>
                                                        <?php if($facility->status==1){?><span class="label label-sm label-success">Active</span>
                                                            <?php }else{ ?><span class="label label-sm label-red">In-Active</span>
                                                                <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $facility->name;?>
                                                    </td>
                                                    <td>
                                                        <img src="{{ asset($facility->icon) }}" style="width: 50px;height: 50px;background:#000">
                                                    </td>
                                                    <td>
                                                        <a href="#" data-hover="tooltip" data-placement="top" data-target="#modal-edit-facility{{ $key1 }}" data-toggle="modal" title="Edit"><span class="label label-sm label-success"><i class="fa fa-pencil"></i></span></a>
                                                        <a href="#" data-hover="tooltip" data-placement="top" itle="Delete" data-target="#modal-delete-facility{{ $key1 }}" data-toggle="modal"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a>
                                                            <!--Modal Edit banner start-->
                                                            <div id="modal-edit-facility{{ $key1 }}" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog modal-wide-width">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                        <h4 id="modal-login-label3" class="modal-title">
                                                                                                                                  Edit Facility</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form">

                                                                            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ url('/web88cms/facilityUpdate') }}">
                                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                                                <div class="form-group">
                                                                                    <label class="col-md-3 control-label">Status</label>
                                                                                    <div class="col-md-6">
                                                                                        <div data-on="success" data-off="primary" class="make-switch">
                                                                                            @if ($facility->status == 1)
                                                                                            <input name="status" type="checkbox" checked="checked" /> @else
                                                                                            <input name="status" type="checkbox" /> @endif

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-md-3 control-label">Facility Name
                                                                                        <span class="require">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-6">
                                                                                        <input name="name" required="required" id="text" type="text" class="form-control" placeholder="eg. Health Care" value="{{$facility->name}}" autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                @if($facility->icon)
                                                                                <input type="hidden" name="old_icon" value="{{ $facility->icon }}"> @endif
                                                                                <input type="hidden" name="objId" value="<?php echo $facility->id;?>" /> @if($facility->icon)
                                                                                <div class="form-group">
                                                                                    <label class="col-md-3 control-label">Facility Previous Icon </label>
                                                                                    <div class="col-md-5">
                                                                                        <div data-on="success" data-off="primary" class="make-switch" style="border:none !important">
                                                                                            <img id="iconHolder" src="{{ asset($facility->icon) }}" style="width: 50px;height: 50px;background:#dfdfdf">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @endif
                                                                                <div class="form-group">
                                                                                    <label class="col-md-3 control-label">Upload Facility Icon </label>
                                                                                    <div class="col-md-9">
                                                                                        {{--
                                                                                        <div class="text-15px margin-top-10px"> <img src="../../images/testimonialsbg.jpg" alt="Facility" class="img-responsive"> --}}
                                                                                            <br/>
                                                                                            <input name="icon" type="file" />
                                                                                            <br/>
                                                                                            <span class="help-block">(Image dimension: min. 65 x 65 pixels, JPEG/GIF/PNG only, Max. 500KB) </span>
                                                                                        </div>
                                                                                    </div>

                                                                                    {{--
                                                                                    <div class="form-group ">
                                                                                        <label class="col-md-3 control-label">Order <span class="require">*</span></label>
                                                                                        <div class="col-md-6">
                                                                                            <input type="number" name="order" class="form-control" placeholder="eg. 1" required="required" value="{{$facility->order}}" autocomplete="off" min="1">
                                                                                        </div>
                                                                                    </div> --}}
                                                                                    <div class="clearfix"></div>

                                                                                    <div class="form-actions">
                                                                                        <div class="col-md-offset-5 col-md-8">
                                                                                            <button type="submit" class="btn btn-red">Save &nbsp;
                                                                                                <i class="fa fa-floppy-o"></i>
                                                                                            </button>
                                                                                            &nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel
                                                                                                                                                  &nbsp;<i
                                                                                                                                                          class="glyphicon glyphicon-ban-circle"></i></a>
                                                                                        </div>
                                                                                    </div>
                                                                            </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--END MODAL Edit promotion-->

                                                            <!--Modal delete start-->
                                                            <div id="modal-delete-facility{{ $key1 }}" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                            <h4 id="modal-login-label4" class="modal-title">
                                                                                                                                  <a href=""><i
                                                                                                                                              class="fa fa-exclamation-triangle"></i></a>
                                                                                                                                  Are you sure you want to delete this facility? </h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p><strong>#{{$facility->id}}
                                                                                                                                      :</strong> {{$facility->name}}</p>
                                                                            <div class="form-actions">
                                                                                <div class="col-md-offset-4 col-md-8">

                                                                                    <form action="{{ url('/web88cms/facilityDelete') }}" method="post">
                                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                                                                                        <input type="hidden" name="objId" value="<?php echo $facility->id;?>" />
                                                                                        <button type="submit" class="btn btn-red">Yes &nbsp;
                                                                                            <i class="fa fa-check"></i>
                                                                                        </button>
                                                                                        &nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No
                                                                                                                                              &nbsp;<i
                                                                                                                                                      class="fa fa-times-circle"></i></a>
                                                                                    </form>

                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </td>
                                                   
                                                </tr>
                                                <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="tool-footer text-right">
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
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!-- end table responsiev -->
                            </div>
                        </div>
                        <!-- End porlet -->
                    </div>
                    <!-- end tab objective text -->
                    <div id="backgroundimage" class="tab-pane fade">
                        <div class="portlet">
                            <div class="portlet-header">
                                <div class="caption">Edit Facility Background Image</div>
                                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Status</th>
                                            <th>Title</th>
                                            <th>Background</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            {{-- {{ dd($page['title']) }} --}}
                                            <td>
                                                @if($page['status'] == NULL)
                                                    <span class="label label-sm label-warning">Inctive</span>
                                                @else
                                                    <span class="label label-sm label-success">Active</span>
                                                @endif
                                            </td>
                                            <td>{{ ($page['title'])?$page['title']:'No Title' }}</td>
                                            <td>
                                                @if($page['background'])
                                                    <img id="backgroundHolder" src="{{ asset($page['background']) }}" style="width: 200px;height: 75px;">
                                                @else
                                                    No Background
                                                @endif
                                            </td>
                                            <td><a href="#" data-hover="tooltip" data-placement="top" data-target="#modal-edit-objective-background" data-toggle="modal" title="Edit"><span class="label label-sm label-success"><i class="fa fa-pencil"></i></span></a>
                                                <!--Modal Edit Objective Background image start-->
                                                <div id="modal-edit-objective-background" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-wide-width">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                <h4 id="modal-login-label3" class="modal-title">Edit Facility Background Image</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form">
                                                                    <form id="bgImg" class="form-horizontal" method="post">
                                                                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Status</label>
                                                                             <div class="col-md-6">
                                                                                <div data-on="success" data-off="primary" class="make-switch">
                                                                                    <input name="status" value="1" type="checkbox" <?php if($page['status']){?>checked="checked"
                                                                                    <?php } ?>/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Title </label>
                                                                            <div class="col-md-6">
                                                                                <input id="title" name="title" value="{{ $page['title']}}" type="text" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        @if($page['background'])
                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Facility Previous Background </label>
                                                                            <div class="col-md-5">
                                                                                <div data-on="success" data-off="primary" class="make-switch" style="border:none !important">
                                                                                    <img id="backgroundHolder" src="{{ asset($page['background']) }}" style="width: 200px;height: 75px;">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif

                                                                        <div class="form-group">
                                                                            <label class="col-md-3 control-label">Upload Background Image <span class='require'>*</span></label>
                                                                            <div class="col-md-9">
                                                                                <div class="text-15px margin-top-10px"> <img src="../../images/testimonialsbg.jpg" alt="Objective" class="img-responsive">
                                                                                    <br/>
                                                                                    <input name="background" id="exampleInputFile2" type="file" />
                                                                                    <br/>
                                                                                    <span class="help-block">(Image dimension: min. 1543 x 600 pixels, JPEG/GIF/PNG only, Max. 1MB) </span> </div>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="old_background" value="{{ $page['background'] }}">
                                                                        <div class="form-actions">
                                                                            <div class="col-md-offset-5 col-md-8"> <a href="javascript:void(0);" onClick="backgroundUpdate()" class="btn btn-red">Save &nbsp;<i class="fa fa-floppy-o"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                                                                        </div>
                                                                    </form>

                                                                       <script>
                                                                                function backgroundUpdate() {
                                                                                    var form_data = new window.FormData($('#bgImg')[0]);
                                                                                    $.ajax({
                                                                                        url: 'backgroundUpdate',
                                                                                        type: 'post',
                                                                                        dataType: 'json',
                                                                                        data: form_data,
                                                                                        enctype: 'multipart/form-data',
                                                                                        processData: false,
                                                                                        contentType: false,
                                                                                        success: function(response) {
                                                                                            if (response['error']) {
                                                                                                $('#errorBox').remove();
                                                                                                $('#successBox').remove();
                                                                                                var error = '<div id="errorBox" class="alert alert-danger"><i class="fa fa-times-circle"></i> <strong>Error!</strong>';
                                                                                                for (var i = 0; i < response['error'].length; i++) {
                                                                                                    error += '<p>' + response['error'][i] + '</p>';
                                                                                                }
                                                                                                error += '</div>';
                                                                                                $('#bgImg').before(error);
                                                                                            }

                                                                                            if (response['success']) {
                                                                                                $('#errorBox').remove();
                                                                                                $('#successBox').remove();
                                                                                                var success = '<div id="successBox" class="alert alert-success"><i class="fa fa-check-circle"></i> <strong>Success!</strong><p>The information has been saved/updated successfully.</p></div>';
                                                                                                $('#bgImg').before(success);
                                                                                                window.location.reload();
                                                                                                $('#bgImg').live('load');
                                                                                                setInterval(function() {
                                                                                                    window.location.reload();
                                                                                                }, 2000);
                                                                                                $("#backgroundHolder").imageReloader();
                                                                                            }
                                                                                        }
                                                                                    });
                                                                                }
                                                                            </script>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--END MODAL Edit Objective background image -->
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end background image -->
                    {{-- video  --}}
                    <div id="video" class="tab-pane fade">
                         <div class="portlet">
                            <div class="portlet-header">
                                <div class="caption">Videos </div>
                                <br/>
                                <p class="margin-top-10px"></p>
                                <a href="#" data-target="#modal-add-video" data-toggle="modal" class="btn btn-success">Add New Video &nbsp;<i class="fa fa-plus"></i></a>&nbsp; 
                                <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
                                <!--Modal Add New video start-->
                                <div id="modal-add-video" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog modal-wide-width">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                <h4 id="modal-login-label3" class="modal-title">Add New Video</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form">
                                                    <form id="addVideo" class="form-horizontal" method="post">
                                                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Status</label>
                                                            <div class="col-md-6">
                                                                <div data-on="success" data-off="primary" class="make-switch">
                                                                    <input type="checkbox" checked="checked" name="status" value="1" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Video Title</label>
                                                            <div class="col-md-6">
                                                                <input name="title" rows="3" class="form-control">
                                                            </div>
                                                            <div class="col-md-3"> </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Video URL</label>
                                                            <div class="col-md-6">
                                                                <input name="video"  type="url" rows="3" class="form-control">
                                                            </div>
                                                            <div class="col-md-3"> </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Upload Icon Image <span class='require'>*</span></label>
                                                            <div class="col-md-9">
                                                                <div class="text-15px margin-top-10px"> <img src="../../images/testimonialsbg.jpg" alt="Facility" class="img-responsive">
                                                                    <br/>
                                                                    <input name="background" id="backgroundFile" type="file" />
                                                                    <br/>
                                                                    <span class="help-block">(Image dimension: min. 700 x 490 pixels, JPEG/GIF/PNG only, Max. 1MB) </span> </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-actions">
                                                            <div class="col-md-offset-5 col-md-8"> <a href="javascript:void(0);" onClick="addVideo()" class="btn btn-red">Save &nbsp;<i class="fa fa-floppy-o"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                                                        </div>
                                                        <script>
                                                            function addVideo() {
                                                                var form_data = new window.FormData($('#addVideo')[0]);
                                                                // var form_data = new FormData();
                                                                // var files = $('#backgroundimag')[0].files[0];
                                                                // form_data.append('icon', files);
                                                                // console.log(form_data);
                                                                $.ajax({
                                                                    url: 'videoAdd',
                                                                    type: 'post',
                                                                    dataType: 'json',
                                                                    data: form_data,
                                                                    enctype: 'multipart/form-data',
                                                                    processData: false,
                                                                    contentType: false,
                                                                    success: function(response) {
                                                                        if (response['error']) {
                                                                            $('#errorBox').remove();
                                                                            $('#successBox').remove();
                                                                            var error = '<div id="errorBox" class="alert alert-danger"><i class="fa fa-times-circle"></i> <strong>Error!</strong>';
                                                                            for (var i = 0; i < response['error'].length; i++) {
                                                                                error += '<p>' + response['error'][i] + '</p>';
                                                                            }
                                                                            error += '</div>';
                                                                            $('#addVideo').before(error);
                                                                        }

                                                                        if (response['success']) {
                                                                            $('#errorBox').remove();
                                                                            $('#successBox').remove();
                                                                            var success = '<div id="successBox" class="alert alert-success"><i class="fa fa-check-circle"></i> <strong>Success!</strong><p>The information has been saved/updated successfully.</p></div>';
                                                                            $('#addVideo').before(success);
                                                                            $('#addVideo').live('load');
                                                                            setInterval(function() {
                                                                                window.location.reload();
                                                                            }, 2000);

                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--END MODAL Add New video-->                               
                            </div>
                            <div class="portlet-body">
                              {{--   <div class="form-inline pull-left">
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
                                <br/>
                                <div class="table-responsive mtl">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                {{-- <th width="1%">
                                                    <input type="checkbox" />
                                                </th> --}}
                                                
                                                <th>#</th> 
                                                <th>Status</th>
                                                <th>Video Title</th>
                                                <th>Video URL</th>
                                                <th>Background</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;foreach($videos as $key => $video) { ?>
                                                <tr>
                                                    {{--
                                                    <td>
                                                        <input type="checkbox" />
                                                    </td> --}}
                                                    <td>
                                                        <?php echo $i++;?>
                                                    </td>
                                                    <td>
                                                        <?php if($video->status==1){?><span class="label label-sm label-success">Active</span>
                                                            <?php }else{ ?><span class="label label-sm label-red">In-Active</span>
                                                                <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $video->title;?>
                                                    </td>
                                                    <td>
                                                        <?php echo $video->video;?>
                                                    </td>
                                                    <td>
                                                        <img src="{{ asset($video->background) }}" style="width: 50px;height: 50px;">
                                                    </td>
                                                    <td>
                                                        <a href="#" data-hover="tooltip" data-placement="top" data-target="#modal-edit-banner{{ $key }}" data-toggle="modal" title="Edit"><span class="label label-sm label-success"><i class="fa fa-pencil"></i></span></a>
                                                        <a href="#" data-hover="tooltip" data-placement="top" itle="Delete" data-target="#modal-delete-{{ $key }}" data-toggle="modal"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a>
                                                        <!--Modal Edit banner start-->
                                                        <div id="modal-edit-banner{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog modal-wide-width">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                        <h4 id="modal-login-label3" class="modal-title">
                                                                              Edit Video</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form">

                                                                            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ url('/web88cms/videoUpdate') }}">
                                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                                                <div class="form-group">
                                                                                    <label class="col-md-3 control-label">Status</label>
                                                                                    <div class="col-md-6">
                                                                                        <div data-on="success" data-off="primary" class="make-switch">
                                                                                            @if ($video->status == 1)
                                                                                            <input name="status" type="checkbox" checked="checked" /> @else
                                                                                            <input name="status" type="checkbox" /> @endif

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-md-3 control-label">Title
                                                                                        <span class="require">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-6">
                                                                                        <input name="title" required="required" id="text" type="text" class="form-control" placeholder="eg. Western Set Lunch Promo" value="{{$video->title}}" autocomplete="off">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group ">
                                                                                    <label class="col-md-3 control-label">Video URL
                                                                                        <span class="require">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-6">
                                                                                        <input name="video" id="video" type="text" class="form-control" placeholder="eg. https://youtube.com/X235d63=" value="{{$video->video}}" autocomplete="off">
                                                                                    </div>
                                                                                </div>

                                                                                {{--
                                                                                <div class="form-group ">
                                                                                    <label class="col-md-3 control-label">Order <span class="require">*</span></label>
                                                                                    <div class="col-md-6">
                                                                                        <input type="number" name="order" class="form-control" placeholder="eg. 1" required="required" value="{{$video->order}}" autocomplete="off" min="1">
                                                                                    </div>
                                                                                </div> --}} 
                                                                                @if($video->background)
                                                                                <input type="hidden" name="old_background" value="{{ $video->background }}"> @endif
                                                                                <input type="hidden" name="objId" value="<?php echo $video->id;?>" /> 
                                                                                @if($video->background)
                                                                                <div class="form-group">
                                                                                    <label class="col-md-3 control-label">Previous Background </label>
                                                                                    <div class="col-md-5">
                                                                                        <div data-on="success" data-off="primary" class="make-switch" style="border:none !important">
                                                                                            <img src="{{ asset($video->background) }}" style="width: 50px;height: 50px;">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @endif
                                                                                <div class="form-group">
                                                                                    <label class="col-md-3 control-label">Upload Backgound Image </label>
                                                                                    <div class="col-md-9">
                                                                                        <br/>
                                                                                        <input type="file" name="background" type="file" />
                                                                                        <br/>
                                                                                        <span class="help-block">(Image dimension: min. 700 x 490 pixels, JPEG/GIF/PNG only, Max. 1MB) </span> </div>
                                                                                </div>
                                                                        </div>

                                                                        <div class="clearfix"></div>

                                                                        <div class="form-actions">
                                                                            <div class="col-md-offset-5 col-md-8">
                                                                                <button type="submit" class="btn btn-red">Save &nbsp;
                                                                                    <i class="fa fa-floppy-o"></i>
                                                                                </button>
                                                                                &nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel
                                                                                              &nbsp;<i
                                                                                                      class="glyphicon glyphicon-ban-circle"></i></a>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <!--END MODAL Edit promotion-->

                                                        <!--Modal delete start-->
                                                        <div id="modal-delete-{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                        <h4 id="modal-login-label4" class="modal-title">
                                                                              <a href=""><i
                                                                                          class="fa fa-exclamation-triangle"></i></a>
                                                                              Are you sure you want to delete this
                                                                              image? </h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p><strong>#{{$video->id}}
                                                                                  :</strong> {{$video->title}}</p>
                                                                        <div class="form-actions">
                                                                            <div class="col-md-offset-4 col-md-8">

                                                                                <form action="{{ url('/web88cms/videoDelete') }}" method="post">
                                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                                                                                    <input type="hidden" name="objId" value="<?php echo $video->id;?>" />
                                                                                    <button type="submit" class="btn btn-red">Yes &nbsp;
                                                                                        <i class="fa fa-check"></i>
                                                                                    </button>
                                                                                    &nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No
                                                                                          &nbsp;<i
                                                                                                  class="fa fa-times-circle"></i></a>
                                                                                </form>

                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5"></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- end table responsiev -->
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end tab content -->
            </div>
        </div>
    </div>
    <!-- InstanceEndEditable -->
    <!--END CONTENT-->

    <!--BEGIN FOOTER-->
    <div class="page-footer">
        <div class="copyright"><span class="text-15px">2015 ?? <a href="http://www.webqom.com" target="_blank">Webqom Technologies Sdn Bhd.</a> Any queries, please contact <a href="mailto:support@webqom.com">Webqom Support</a>.</span>
            <div class="pull-right"><img src="{{ asset('/public/admin/images/logo_webqom.png') }}" alt="Webqom Technologies"></div>
        </div>
    </div>
    
</div>

<script src="{{ asset('/public/admin/vendors/jquery-maskedinput/jquery-maskedinput.js')}}"></script>
<script src="{{ asset('/public/admin/js/form-components.js')}}"></script>
<script src="{{ asset('/public/admin/vendors/tinymce/js/tinymce/tinymce.min.js')}}"></script>
<script src="{{ asset('/public/admin/vendors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/public/admin/js/ui-tabs-accordions-navs.js')}}"></script>

<script src="{{ asset('/public/admin/js/main.js') }}"></script>
<script src="{{ asset('/public/admin/js/holder.js') }}"></script>
<script src="{{ asset('/public/admin/js/functions.js') }}"></script>
<script>
    $('.wbx_submit_preview').click(function (e) {
        e.preventDefault();
        $('.wbx_info').each(function (i, e) {
            // var value = addslashes($(e).html());
            var value = $(e).html();
            var name = $(e).attr('name');
            $check = 1;
            // $('#wbx_change_info').append("<input type='hidden' name='" + name + "' value='" + value + "' />");
            $('#wbx_change_info').append('<textarea style="display: none" name="' + name + '">' + value + '</textarea>');
            $('#wbx_change_info').append('<input type=hidden value="' + $check + '" name="wbx_preview">');

        });
        $('#wbx_change_info').submit();
    });

    $('.wbx_submit_publish').click(function (e) {
        e.preventDefault();
        $('.wbx_info').each(function (i, e) {
            // var value = addslashes($(e).html());
            var value = $(e).html();
            var name = $(e).attr('name');
            $check = 1;
            // $('#wbx_change_info').append("<input type='hidden' name='" + name + "' value='" + value + "' />");
            $('#wbx_change_info').append('<textarea style="display: none" name="' + name + '">' + value + '</textarea>');
            $('#wbx_change_info').append('<input type=hidden value="' + $check + '" name="wbx_publish">');
        });
        $('#wbx_change_info').submit();
    });
</script>
@endsection
