<?php   
    //$page_images = DB::table('page_images')
    //->where('page','about')->get();
    // ->where('page','about');
    //->where('type','image')->get(); 
    //->where('type','image')->paginate(6); 
    //->where('page','dining')->get();
    //$gallery = DB::table('gallery')->paginate(10);
    //$category = DB::table('gallery_category')->paginate(10);
    // $page_images = DB::table('page_images')->where('page','about')->get();
    $page_images = DB::table('page_images')
    ->where(array('page' => 'about','type' => 'images'))->get();
    //->where(array('page' => 'about','type' => 'images'))->take(6)->get();
    //dd($page_images);
?>
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
            <li class="active">About Us - Edit</li>
        </ol>
      </div>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>About Us <i class="fa fa-angle-right"></i> Edit</h2>
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
                            <div class="wbx_info" name="about[first]" contenteditable="true">
                                {!! html_entity_decode($data[0]) !!}
                            </div>
                        </section><!--/.best-place-section-->
                        <form method="post" action="{{URL('/web88cms/about_editor')}}" id="wbx_change_info">
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



            </div>
        </div>

        {{-- TAB --}}
        <div class="clearfix"></div>
        <div class="row">
        <div class="col-lg-12">
        <h2>Images <i class="fa fa-angle-right"></i> Listing</h2>
        <div class="clearfix"></div>
        <div class="alert alert-success alert-dismissable"
             @if( Session::has('success') )
             style="display: block;">
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
        <div class="alert alert-danger alert-dismissable"
             @if( Session::has('fail') )
             style="display: block;">
            <script>setTimeout(function () {
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
        {{--  @if(count($page_images))
            <div class="pull-left"> Last updated: <span
                        class="text-blue">{{ date('d M, Y @ g.iA', strtotime($page_images->last()->updated_at)) }}</span>
            </div>

        @endif --}}


        <div class="clearfix"></div>
        <p></p>
        <div class="portlet">
            <div class="portlet-header">
                <div class="caption">Page Image Listing</div>
                <br/>
                <p class="margin-top-10px"></p>
                <a href="#" data-target="#modal-add-new" data-toggle="modal" class="btn btn-success">Add New
                    &nbsp;<i class="fa fa-plus"></i></a>&nbsp;
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">Delete</button>
                    <button type="button" data-toggle="dropdown" class="btn btn-red dropdown-toggle"><span
                                class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="#" id="dellselobjapp">Delete selected item(s)</a></li>
                        <li class="divider"></li>
                        <li><a href="#" data-target="#modal-delete-all" data-toggle="modal">Delete all</a>
                        </li>
                    </ul>
                </div>
                 
                <div class="tools"><i class="fa fa-chevron-up"></i></div>
                <!--Modal Add New start-->
                <div id="modal-add-new" tabindex="-1" role="dialog" aria-labelledby="modal-login-label"
                     aria-hidden="true" class="modal fade">
                    <div class="modal-dialog modal-wide-width">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                        class="close">&times;</button>
                                <h4 id="modal-login-label3" class="modal-title">Add New Page Image</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form">
                                    <form action="{{ url('/web88cms/page_images_list') }}" method="POST"
                                          class="form-horizontal" enctype="multipart/form-data">
                                        <input type="hidden" name="_token"
                                               value="{{ csrf_token() }}"/>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Status</label>
                                            <div class="col-md-6">
                                                <div data-on="success" data-off="primary"
                                                     class="make-switch">
                                                    <input type="checkbox" name="status_chk"
                                                           checked="checked"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-error">
                                            <label class="col-md-3 control-label">Title <span
                                                        class="require">*</span></label>
                                            <div class="col-md-6">
                                                <input type="text" name="title" class="form-control"
                                                       placeholder="eg. Western Set Lunch Promo"
                                                       required="required" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group has-error">
                                            <label class="col-md-3 control-label">Order <span
                                                        class="require">*</span></label>
                                            <div class="col-md-6">
                                                <input type="number" name="order" class="form-control"
                                                       placeholder="eg. 1"
                                                       required="required" autocomplete="off" min="1">
                                            </div>
                                        </div>
                                        <input type="hidden" name="page" value="about">
                                        <input type="hidden" name="type" value="images">
                                       {{--  <div class="form-group">
                                            <label class="col-md-3 control-label">Short Description </label>
                                            <div class="col-md-6">
                                                <textarea class="form-control" name="sh_des"
                                                          placeholder="eg. With complimentary cordial drink"
                                                          required="required"></textarea>
                                            </div>
                                        </div> --}}
                                        
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Upload Image <span
                                                        class='require'>*</span></label>
                                            <div class="col-md-9">
                                                <div class="text-15px margin-top-10px">
                                                    <input id="exampleInputFile2" required="required"
                                                           name="file_name" type="file"/>
                                                    <br/>
                                                    <span class="help-block">(For positions of 2 and 3, image size shall be : 286 x 364 pixels, JPEG/GIF/PNG only, Max. 1MB) </span>
                                                    <span class="help-block">(For positions 0f 1,4,5 and 6, image size shall be : 360 x 314 pixels, JPEG/GIF/PNG only, Max. 1MB) </span>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label class="col-md-3 control-label">Upload Enlarge
                                                Image</label>
                                            <div class="col-md-9">

                                                <input id="exampleInputFile2" required="required"
                                                       name="large_file" type="file"/>
                                                <br/>
                                                <span class="help-block">(JPEG/GIF/PNG only, Max. 2MB) </span>
                                            </div>
                                        </div> --}}
                                        <div class="clearfix"></div>
                                        <div class="form-actions">
                                            <div class="col-md-offset-5 col-md-8">
                                                <button type="submit" class="btn btn-red">Save &nbsp;<i
                                                            class="fa fa-floppy-o"></i></button>
                                                &nbsp;

                                                <a href="#" data-dismiss="modal" class="btn btn-green">Cancel
                                                    &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--END MODAL Add New banner -->
                <div id="modal-select-confirm" tabindex="-1" role="dialog"
                     aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">
                                    ×
                                </button>
                                <h4 class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"
                                                                      style="color:#2598b0"></i></a> Please
                                    Select at least one item.</h4>
                            </div>

                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    Please Select atleast one Promotion to delete.

                                </div>
                            </div>

                        </div>
                    </div>
                </div><!-- modal select confirm -->
                <form class="delete_text_objective form-horizontal" action="{{ url('/web88cms/page_images_list_selected_all_del') }}" method="POST">
                    <input type="hidden" name="_token"
                           value="{{ csrf_token() }}"/>
                    <input type="hidden" name="page" value="about">
                    <input type="hidden" name="img_path">
                    <input type="hidden" name="index">
                </form>
                <!--Modal delete selected items start-->

                <div id="modal-delete-selected" tabindex="-1" role="dialog"
                     aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                        class="close">&times;</button>
                                <h4 id="modal-login-label4" class="modal-title"><a href=""><i
                                                class="fa fa-exclamation-triangle"></i></a> Are you sure you
                                    want to delete the selected item(s)? </h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-actions">
                                    <div class="col-md-offset-4 col-md-8"><a href="#" id="dellselobj"
                                                                             class="btn btn-red">Yes
                                            &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#"
                                                                                           data-dismiss="modal"
                                                                                           class="btn btn-green">No
                                            &nbsp;<i class="fa fa-times-circle"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal delete selected items end -->
                <!--Modal delete all items start-->
                <div id="modal-delete-all" tabindex="-1" role="dialog" aria-labelledby="modal-login-label"
                     aria-hidden="true" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                        class="close">&times;</button>
                                <h4 id="modal-login-label4" class="modal-title"><a href=""><i
                                                class="fa fa-exclamation-triangle"></i></a> Are you sure you
                                    want to delete all items? </h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-actions">
                                    <div class="col-md-offset-4 col-md-8">
                                        <form action="{{ url('/web88cms/page_images_list_all_del') }}"
                                              method="POST">
                                            <input type="hidden" name="_token"
                                                   value="{{ csrf_token() }}"/>
                                            <input type="hidden" name="page" value="about">

                                            <input type="hidden" name="chk" value="1">
                                            <button type="submit" class="btn btn-red">Yes &nbsp;<i
                                                        class="fa fa-check"></i></button>
                                            &nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No
                                                &nbsp;<i class="fa fa-times-circle"></i></a>
                                        </form>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal delete all items end -->
            </div>
            <div class="portlet-body">
                <!--
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
                -->
                <div class="clearfix"></div>
                <br/>
                <br/>
                <div class="portlet-body">
                    <div class="table-responsive mtl">
                        <table id="table-title-slider" class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th width="1%"><input type="checkbox" onclick="$('input[type=checkbox]').prop('checked', $(this).is(':checked'))" /></th>
                                <th>#</th>
                                <th>Status</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Order</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                           
                                    <tbody>
                                    @if(isset($page_images) && !empty($page_images))
                                        @foreach ($page_images as $key => $value)


                                    <tr>
                                        <td><input value="<?php echo $value->id; ?>" data="{{$value->id}}" type="checkbox" class="mooncake-mod-checkbox"/></td>
                                       <!-- <td><input type="checkbox" value="<?php //echo $value->id; ?>" onclick="selectForDelete();" class="select_items"/></td> -->
                                        <td>{{ $value->id }}</td>
                                        <td>
                                            @if($value->status === 1)
                                            <span class="label label-sm label-success">Active</span>
                                            @else
                                            <span class="label label-sm label-danger">Inactive</span>
                                            @endif

                                        </td>
                                        <td>{{ $value->title }}</td>
                                        <td>
                                            <img src="{{asset('/public/images/about/'.$value->image) }}" style="height:75px">    
                                        </td>
                                        <td>{{ $value->order }}</td>
                                        <td><a href="#" data-hover="tooltip" data-placement="top"
                                               data-target="#modal-edit-banner{{ $key }}"
                                               data-toggle="modal" title="Edit"><span
                                                        class="label label-sm label-success"><i
                                                            class="fa fa-pencil"></i></span></a> <a href="#"
                                                                                                    data-hover="tooltip"
                                                                                                    data-placement="top"
                                                                                                    title="Delete"
                                                                                                    data-target="#modal-delete-{{ $key }}"
                                                                                                    data-toggle="modal"><span
                                                        class="label label-sm label-red"><i
                                                            class="fa fa-trash-o"></i></span></a>
                                            <!--Modal Edit banner start-->
                                            <div id="modal-edit-banner{{ $key }}" tabindex="-1"
                                                 role="dialog" aria-labelledby="modal-login-label"
                                                 aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog modal-wide-width">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal"
                                                                    aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 id="modal-login-label3" class="modal-title">
                                                                Edit Page Image</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form">

                                                                <form class="form-horizontal" method="post"
                                                                      enctype="multipart/form-data"
                                                                      action="{{ url('/web88cms/edit_page_images_list') }}">
                                                                    <input type="hidden" name="_token"
                                                                           value="{{ csrf_token() }}"/>
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label">Status</label>
                                                                        <div class="col-md-6">
                                                                            <div data-on="success"
                                                                                 data-off="primary"
                                                                                 class="make-switch">
                                                                                @if ($value->status == 1)
                                                                                    <input name="status_chk"
                                                                                           type="checkbox"
                                                                                           checked="checked"/>
                                                                                @else
                                                                                    <input name="status_chk"
                                                                                           type="checkbox"/>
                                                                                @endif

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label">Title
                                                                            <span class="require">*</span>
                                                                        </label>
                                                                        <div class="col-md-6">
                                                                            <input name="title"
                                                                                   required="required"
                                                                                   id="text" type="text"
                                                                                   class="form-control"
                                                                                   placeholder="eg. Western Set Lunch Promo"
                                                                                   value="{{$value->title}}"
                                                                                   autocomplete="off">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group has-error">
                                                                        <label class="col-md-3 control-label">Order <span
                                                                                    class="require">*</span></label>
                                                                        <div class="col-md-6">
                                                                            <input type="number" name="order" class="form-control"
                                                                                   placeholder="eg. 1"
                                                                                   required="required"  value="{{$value->order}}" autocomplete="off" min="1">
                                                                        </div>
                                                                    </div>
                                                                    @if($value->image)
                                                                    <input type="hidden" name="old_image" value="{{ $value->image }}">
                                                                    @endif
                                                                    <input type="hidden" name="page" value="about">
                                                                    <input type="hidden" name="type" value="images">                                                                 
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label">Upload
                                                                            Image <span
                                                                                    class='require'>*</span></label>
                                                                        <div class="col-md-9">
                                                                            <div class="text-15px margin-top-10px">
                                                                                <?php if($value->image){?>
                                                                                <img src="<?php echo "https://" . $_SERVER['HTTP_HOST'] . '/public/images/about/', $value->image?>"
                                                                                     alt="Image"
                                                                                     class="img-responsive" style="width:50%"><br/>
                                                                                <a href="#"
                                                                                   data-hover="tooltip"
                                                                                   data-placement="top"
                                                                                   title="Delete"
                                                                                   data-target="#modal-delete-page-image{{ $key }}"
                                                                                   data-toggle="modal"><span
                                                                                            class="label label-sm label-red"><i
                                                                                                class="fa fa-trash-o"></i></span></a>
                                                                                <div class="clearfix"></div>
                                                                                <br/>
                                                                                <?php }?>
                                                                                <input name="file_name" <?php if(!$value->image){?> required="required" <?php }?>
                                                                                       id="exampleInputFile2"
                                                                                       type="file"/>
                                                                                <br/>
                                                                                  <span class="help-block">(For positions of 2 and 3, image size shall be : 286 x 364 pixels, JPEG/GIF/PNG only, Max. 1MB) </span>
                                                    <span class="help-block">(For positions 0f 1,4,5 and 6, image size shall be : 360 x 314 pixels, JPEG/GIF/PNG only, Max. 1MB) </span>
                                               
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="clearfix"></div>
                                                                    <input type="hidden"
                                                                           value="{{$value->id}}"
                                                                           name="key"/>
                                                                    <div class="form-actions">
                                                                        <div class="col-md-offset-5 col-md-8">
                                                                            <button type="submit"
                                                                                    class="btn btn-red">Save
                                                                                &nbsp;<i
                                                                                        class="fa fa-floppy-o"></i>
                                                                            </button>
                                                                            &nbsp; <a href="#"
                                                                                      data-dismiss="modal"
                                                                                      class="btn btn-green">Cancel
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
                                            <!--Modal delete promotion image start-->
                                            <div id="modal-delete-page-image{{ $key }}" tabindex="-1"
                                                 role="dialog" aria-labelledby="modal-login-label"
                                                 aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal"
                                                                    aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 id="modal-login-label3" class="modal-title">
                                                                <a href=""><i
                                                                            class="fa fa-exclamation-triangle"></i></a>
                                                                Are you sure you want to delete this
                                                                promotion image? </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>
                                                                <img src="<?php echo "https://" . $_SERVER['HTTP_HOST'] . '/public/images/about/', $value->image?>"
                                                                     alt="Delete Img"
                                                                     class="img-responsive"></p>
                                                            <input id="images_icon_del" type="hidden"
                                                                   data="{{$value->image}}">
                                                            <div class="form-actions">
                                                                <div class="col-md-offset-4 col-md-8">
                                                                    <a href="#preview in browser/not yet published"
                                                                       class="del_img_t btn btn-red">Yes
                                                                        &nbsp;<i
                                                                                class="fa fa-check"></i></a>
                                                                    &nbsp;
                                                                    <a href="#" data-dismiss="modal"
                                                                       class="btn btn-green">No &nbsp;<i
                                                                                class="fa fa-times-circle"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          
                                            <!--Modal delete start-->
                                            <div id="modal-delete-{{ $key }}" tabindex="-1" role="dialog"
                                                 aria-labelledby="modal-login-label" aria-hidden="true"
                                                 class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal"
                                                                    aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 id="modal-login-label4" class="modal-title">
                                                                <a href=""><i
                                                                            class="fa fa-exclamation-triangle"></i></a>
                                                                Are you sure you want to delete this
                                                                image? </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>#{{$value->id}}
                                                                    :</strong> {{$value->title}}</p>
                                                            <div class="form-actions">
                                                                <div class="col-md-offset-4 col-md-8">

                                                                    <form action="{{ url('/web88cms/page_images_list_del') }}"
                                                                          method="post">
                                                                        <input type="hidden" name="_token"
                                                                               value="{{ csrf_token() }}"/>

                                                                        <input type="hidden"
                                                                               value="{{$value->id}}"
                                                                               name="keys"/>
                                                                        <input type="hidden" name="page" value="{{ $value->page }}">
                                                                        <input type="hidden" name="image_path" value="{{ $value->image }}">
                                                                        <button type="submit"
                                                                                class="btn btn-red">Yes
                                                                            &nbsp;<i
                                                                                    class="fa fa-check"></i>
                                                                        </button>
                                                                        &nbsp; <a href="#"
                                                                                  data-dismiss="modal"
                                                                                  class="btn btn-green">No
                                                                            &nbsp;<i
                                                                                    class="fa fa-times-circle"></i></a>
                                                                    </form>

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <form class="t_icon_del form-horizontal" method="post"
                                                  action="{{ url('/web88cms/page_images_del_img') }}">
                                                <input type="hidden" name="_token"
                                                       value="{{ csrf_token() }}"/>

                                                <input type="hidden" name="image_path" value="{{ $value->image }}">
                                                <input type="hidden" name="page" value="{{ $value->page }}">
                                            </form>
                                        </td>
                                    </tr>
                                      @endforeach
                                    @endif

                                    </tbody>

                              
                            <tfoot>
                            <tr>
                                <td colspan="8"></td>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="tool-footer text-right">
                            {{--   <p class="pull-left">Showing 1 to 10 of 57 entries</p> --}}

                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
            <!-- end portlet -->
        </div>
        </div>
        </div>
    </div>
    <!-- InstanceEndEditable -->
    <!--END CONTENT-->
 
    <!--BEGIN FOOTER-->
    <div class="page-footer">
        <div class="copyright"><span class="text-15px">2015 © <a href="http://www.webqom.com" target="_blank">Webqom Technologies Sdn Bhd.</a> Any queries, please contact <a href="mailto:support@webqom.com">Webqom Support</a>.</span>
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

<!--LOADING SCRIPTS FOR PAGE-->
<script type="text/javascript">

    $('#dellselobjapp').on('click', function () {

        if ($('.mooncake-mod-checkbox:checked').length == 0) {
            $("#modal-select-confirm").modal();
        } else {
            $("#modal-delete-selected").modal();

        }

    });


    // Del select objective
    $('#dellselobj').click(function (e) {
        e.preventDefault();
        var str = '';
        $("#table-title-slider td:first-child input:checked").each(function (i, e) {
            str += $(e).attr('data') + ",";
        });
        $('.delete_text_objective > input[name=index]').val(str);
        // alert('Here');
        $('.delete_text_objective').submit();
    });

    // Del Small Image
    $('.del_img_t').on('click', function (e) {
        e.preventDefault();
        var str = '';
        $("#images_icon_del").each(function (i, e) {
            str += $(e).attr('data');
        });

        $('.t_icon_del > input[name=img_path]').val(str);
        $('.t_icon_del').submit();
    });
</script>
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
