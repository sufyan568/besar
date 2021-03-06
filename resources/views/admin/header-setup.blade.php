@extends('adminLayout')
@section('title', 'Header Setup')
@section('content')
  <div id="page-wrapper"><!--BEGIN PAGE HEADER & BREADCRUMB-->

    <div class="page-header-breadcrumb">
      <div class="page-heading hidden-xs">
        <h1 class="page-title">Global Settings</h1>
      </div>

      <!-- InstanceBeginEditable name="EditRegion1" -->
      <ol class="breadcrumb page-breadcrumb">
        <li><i class="fa fa-home"></i>&nbsp;<a href="dashboard.html">Dashboard</a>&nbsp; <i class="fa fa-angle-right"></i>&nbsp;</li>
        <li>Global Settings &nbsp;<i class="fa fa-angle-right"></i>&nbsp;</li>
        <li class="active">Header - Edit</li>
      </ol>
      <!-- InstanceEndEditable -->
    </div>
    <!--END PAGE HEADER & BREADCRUMB--><!--BEGIN CONTENT-->
    <!-- InstanceBeginEditable name="EditRegion2" -->
    <div class="page-content">
      <div class="row">
        <div class="col-lg-12">
          <h2>Header <i class="fa fa-angle-right"></i> Edit</h2>
          <div class="clearfix"></div>
          <div class="alert alert-success alert-dismissable"
               @if( Session::has('success') )
               style="display: block;">
            <script>setTimeout(function(){$("body").animate({"scrollTop":0},100);},3000);</script>
            <? Session::forget('success') ?>
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
            <script>setTimeout(function(){$("body").animate({"scrollTop":0},100);},3000);</script>
            <? Session::forget('fail') ?>
            @else
              style="display: none;">
            @endif
            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
            <i class="fa fa-times-circle"></i> <strong>Error!</strong>
            <p>The information has not been saved/updated. Please correct the errors.</p>
          </div>
          <div class="pull-left"> Last updated: <span class="text-blue">{{ date('d M, Y @ g.iA', strtotime($data['updated_at'])) }}</span> </div>
          <div class="clearfix"></div>
          <p></p>
          <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#headertext" data-toggle="tab">Header Text</a></li>
          </ul>
          <div id="myTabContent" class="tab-content">
            <div id="headertext" class="tab-pane fade in active">
              <div class="portlet">
                <div class="portlet-header">
                  <div class="caption">Header Text</div>
                  <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
                </div>
                <div class="portlet-body">
                  <div class="table-responsive mtl">
                    <table class="table table-hover table-striped">
                      <thead>
                      <tr>
                        <th width="1%"><input type="checkbox"/></th>
                        <th>#</th>
                        <th>Title</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td><input type="checkbox"/></td>
                        <td>1</td>
                        <td><?php echo '<i class="'.$data['info']['icon_1'].'"></i> &nbsp;'.$data['info']['icon_1_text'].' <i class="'.$data['info']['icon_2'].'"></i> &nbsp;'.$data['info']['icon_2_text'].' <i class="'.$data['info']['icon_3'].'"></i> &nbsp;'.$data['info']['icon_3_text'].''; ?></td>
                        <td><a href="#" data-hover="tooltip" data-placement="top" data-target="#modal-edit-text" data-toggle="modal" title="Edit"><span class="label label-sm label-success"><i class="fa fa-pencil"></i></span></a> <!--<a href="#" data-hover="tooltip" data-placement="top" title="Delete" data-target="#modal-delete-1" data-toggle="modal"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a>-->
                          <!--Modal Edit copyright start-->
                          <div id="modal-edit-text" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog modal-wide-width">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                  <h4 id="modal-login-label3" class="modal-title">Header Text Edit</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form">
                                    <form method="POST" action="{{url('web88cms/header_setup')}}" accept-charset="UTF-8" id="wbx_change_info" class="form-horizontal">
                                      <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                      <div class="form-group">
                                        <label class="col-md-3 control-label">Icon 1</label>
                                        <div class="col-md-9">
                                          <input type="text" name="header[icon_1]" value="{{$data['info']['icon_1']}}">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="col-md-3 control-label">Icon 1 Text</label>
                                        <div class="col-md-9">
                                          <input type="text" name="header[icon_1_text]" value="{{$data['info']['icon_1_text']}}">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="col-md-3 control-label">URL</label>
                                        <div class="col-md-9">
                                          <input type="text" name="header[url]" value="{{$data['info']['url']}}">
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <label class="col-md-3 control-label">Icon 2</label>
                                        <div class="col-md-9">
                                          <input type="text" name="header[icon_2]" value="{{$data['info']['icon_2']}}">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="col-md-3 control-label">Icon 2 Text</label>
                                        <div class="col-md-9">
                                          <input type="text" name="header[icon_2_text]" value="{{$data['info']['icon_2_text']}}">
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <label class="col-md-3 control-label">Icon 3</label>
                                        <div class="col-md-9">
                                          <input type="text" name="header[icon_3]" value="{{$data['info']['icon_3']}}">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="col-md-3 control-label">Icon 3 Text</label>
                                        <div class="col-md-9">
                                          <input type="text" name="header[icon_3_text]" value="{{$data['info']['icon_3_text']}}">
                                        </div>
                                      </div>

                                      <div class="form-actions">
                                        <div class="col-md-offset-5 col-md-8"> <button href="#" data="header-settings" class="wbx_submit btn btn-red" type="submit">Save &nbsp;<i class="fa fa-floppy-o"></i></button>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--END MODAL Edit copyright -->
                          <!--Modal delete start-->
                          <div id="modal-delete-1" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                  <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this? </h4>
                                </div>
                                <div class="modal-body">
                                  <p><strong>#1:</strong></p>
                                  <div class="form-actions">
                                    <div class="col-md-offset-4 col-md-8"> <a href="#" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- modal delete end -->
                        </td>
                      </tr>
                      </tbody>
                      <tfoot>
                      <tr>
                        <td colspan="4"></td>
                      </tr>
                      </tfoot>
                    </table>
                  </div>
                  <!-- end table responsive -->
                </div>
                <!-- end portlet body -->
              </div>
              <!-- End porlet -->
            </div>
            <!-- end tab header text -->
          </div>
          <!-- end tab content -->
          <div class="clearfix"></div>
        </div>
        <!-- end col-lg-12 -->
      </div>
      <!-- end row -->
    </div>
    <!-- InstanceEndEditable -->
    <!--END CONTENT-->
    <!--Begin footer-->
    <div class="page-footer" style="position: relative;">
      <div class="copyright">
        <span class="text-15px">2015 © <a href="http://www.webqom.com" target="_blank">Webqom Technologies Sdn Bhd.</a> Any queries, please contact <a href="mailto:support@webqom.com">Webqom Support</a>.</span>
        <div class="pull-right"><img src="https://book.towerregency.com.my/public/admin/images/logo_webqom.png" alt="Webqom Technologies Sdn Bhd"></div>
      </div>
    </div>
  </div>
  <!-- <End footer> -->
  <script src="{{ URL::asset('public/admin/js/jquery-1.9.1.js') }}"></script>
  <script src="{{ URL::asset('public/admin/js/jquery-migrate-1.2.1.min.js') }}"></script>
  <script src="{{ URL::asset('public/admin/js/jquery-ui.js') }}"></script>

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


  <script src="{{ URL::asset('public/admin/vendors/tinymce/js/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ URL::asset('public/admin/vendors/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ URL::asset('public/admin/js/ui-tabs-accordions-navs.js') }}"></script>


  <script src="{{ URL::asset('public/admin/js/main.js') }}"></script>
  <script src="{{ URL::asset('public/admin/js/holder.js') }}"></script>

  <script type="text/javascript">
    setInterval(function () {
      document.getElementById("save_data_hidden").value = document.getElementById("save_data").innerHTML;
    });
  </script>
@endsection
