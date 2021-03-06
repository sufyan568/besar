@extends('adminLayout')
@section('title', 'Loader')
@section('styles')
    <link href="https://unpkg.com/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
    <style>
        .footer-position{
            position: absolute !important;
            bottom: 0 !important;
            height: 62px !important;
        }
    </style>
@endsection
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <div id="page-wrapper" style="min-height: 100vh"><!--BEGIN PAGE HEADER & BREADCRUMB-->

        <div class="page-header-breadcrumb">
            <div class="page-heading hidden-xs">
                <h1 class="page-title">Loader</h1>
            </div>
            <ol class="breadcrumb page-breadcrumb">
                <li><i class="fa fa-home"></i>&nbsp;<a href="{{ url('web88cms/dashboard') }}">Dashboard</a>&nbsp<i
                            class="fa fa-angle-right"></i>&nbsp;
                </li>
                <li>Global Setup <i class="fa fa-angle-right"></i>&nbsp;
                </li>
                <li class="active">Loader - Listing</li>
            </ol>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Loader - Listing </h2>
                    <div class="clearfix" id="flash_message"></div>
                    <p>
                    @if (Session::has('flash_message'))
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" data-dismiss="alert" aria-hidden="true"
                                    onClick="$('.form-horizontal').trigger('reset');" class="close">&times;
                            </button>
                            <i class="fa fa-check-circle"></i>
                            <strong>Success!</strong> {{ Session::get('flash_message') }}
                        </div>
                        @endif
                        </p>

                    <!-- {{ $error = Session::get('error') }}
                        {{ Session::get('error') }}-->
                        @if($error)
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" data-dismiss="alert" aria-hidden="true"
                                        onClick="$('.form-horizontal').trigger('reset');" class="close">&times;
                                </button>
                                <i class="fa fa-times-circle"></i> <strong>Error!</strong>
                                <p>{{ $error }}</p>
                            </div>
                        @endif

                        <div class="clearfix"></div>
                        <p></p>
                        <div class="clearfix"></div>
                        <div class="portlet">
                            <div class="portlet-header">
                                <div class="caption">Loader</div>
                                <br/>
                                <p class="margin-top-10px"></p>
                                <div class="row">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($loader as $p)
                                            <tr>
                                                <td>1</td>
                                                <td>{{$p->name}}</td>
                                                <td>
                                                    @if($p->status)
                                                        <span class="label label-sm label-success">Active</span>
                                                    @else
                                                        <span class="label label-sm label-danger">Deactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="#" data-hover="tooltip" data-placement="top"
                                                       data-target="#modal-edit-popup" data-toggle="modal" title=""
                                                       data-original-title="Edit"><span
                                                                class="label label-sm label-success"><i
                                                                    class="fa fa-pencil"></i></span></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="modal-edit-popup" tabindex="-1" role="dialog"
                                     aria-labelledby="modal-login-label" aria-hidden="false" class="modal fade in">
                                    <div class="modal-dialog modal-wide-width">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">??
                                                </button>
                                                <h4 id="modal-login-label3" class="modal-title">Edit Loader</h4>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form">
                                                    <form id="popUpForm" class="form-horizontal" method="post"
                                                          action="{{ url('/web88cms/loader') }}"
                                                          enctype="multipart/form-data">
                                                        <input type="hidden" name="_token"
                                                               value="<?php echo csrf_token(); ?>">

                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Status</label>
                                                            <div class="col-md-6">
                                                                <input type="checkbox" data-on="Active" data-off="deactive"
                                                                 <?php if(!empty($loader) && $loader['0']->status){?>
                                                                 checked
                                                                <?php }?>
                                                                  data-toggle="toggle" name="status" data-onstyle="success" data-width="100"  data-offstyle="danger">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Name <span
                                                                        class="require">*</span></label>
                                                            <div class="col-md-6">
                                                                <input id="text" type="text" class="form-control"
                                                                       value="{{$loader[0]->name}}" name="name">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="model" value="popup">
                                                        <div class="form-actions">
                                                            <div class="col-md-offset-5 col-md-8">
                                                                <button class="btn btn-red">Save &nbsp;<i
                                                                            class="fa fa-floppy-o"></i></button>&nbsp;
                                                                <a href="#" data-dismiss="modal"
                                                                    class="btn btn-green">Cancel &nbsp;<i
                                                                            class="glyphicon glyphicon-ban-circle"></i></a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>
        <!--BEGIN FOOTER-->
        <div class="page-footer footer-position">
            <div class="copyright"><span class="text-15px">2019 ?? <a href="http://www.webqom.com" target="_blank">Webqom Technologies Sdn Bhd.</a> Any queries, please contact <a
                            href="mailto:support@webqom.com">Webqom Support</a>.</span>
                <div class="pull-right"><img src="{{ asset('/public/admin/images/logo_webqom.png')}}"
                                             alt="Webqom Technologies Sdn Bhd"></div>
            </div>
        </div>
        <!--END FOOTER-->

    </div>

     		<!--END PAGE WRAPPER
    	</div>
    </div> -->

    <script src="{{ asset('/public/admin/js/jquery-migrate-1.2.1.min.js') }}"></script>
    <script src="{{ asset('/public/admin/js/jquery-ui.js') }}"></script>
    <!--loading bootstrap js-->
    <script src="{{ asset('/public/admin/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/bootstrap-hover-dropdown/bootstrap-hover-dropdown.js') }}"></script>
    <script src="{{ asset('/public/admin/js/html5shiv.js') }}"></script>
    <script src="{{ asset('/public/admin/js/respond.min.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/slimScroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/jquery-cookie/jquery.cookie.js') }}"></script>
    <script src="{{ asset('/public/admin/js/jquery.menu.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/jquery-pace/pace.min.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/moment/moment.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/bootstrap-clockface/js/clockface.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script>
    <!--script src="{{ asset('/public/admin/vendors/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script-->
    <script src="{{ asset('/public/admin/vendors/jquery-maskedinput/jquery-maskedinput.js') }}"></script>
    <script src="{{ asset('/public/admin/js/form-components.js') }}"></script>

    <script src="{{ asset('/public/admin/vendors/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/public/admin/vendors/ckeditor/ckeditor.js') }}"></script>

    <script src="{{ asset('/public/admin/js/ui-tabs-accordions-navs.js') }}"></script>
    <script src="{{ asset('/ajax/libs/modernizr/2.8.2/modernizr.js') }}"></script>

    <!-- InstanceBeginEditable name="EditRegion4" -->
    <!-- InstanceEndEditable -->

    <!--CORE JAVASCRIPT-->

    <script src="{{ asset('/public/admin/js/main.js') }}"></script>
    <script src="{{ asset('/public/admin/js/holder.js') }}"></script>
    <script src="{{ asset('/public/admin/js/functions.js') }}"></script>
    <script type="text/javascript">
        // Wait for window load
        $(window).load(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");;
        });
    </script>
    <script src="{{ URL::asset('public/admin/js/jquery-1.9.1.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/jquery-migrate-1.2.1.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/jquery.validate.min.js') }}"></script>

    <script src="{{ URL::asset('public/admin/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/bootstrap-hover-dropdown/bootstrap-hover-dropdown.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/html5shiv.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/respond.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/slimScroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/jquery-cookie/jquery.cookie.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/jquery.menu.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/jquery-pace/pace.min.js') }}"></script>


    <script src="{{ URL::asset('public/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/bootstrap-clockface/js/clockface.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/jquery-maskedinput/jquery-maskedinput.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/form-components.js') }}"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script src="{{ URL::asset('public/admin/vendors/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/vendors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/ui-tabs-accordions-navs.js') }}"></script>


    <script src="{{ URL::asset('public/admin/js/main.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/holder.js') }}"></script>
    <script src="https://unpkg.com/bootstrap-switch"></script>
@endsection
