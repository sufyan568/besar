<!DOCTYPE html>
<html lang="en">
<head>

<title>Forgot Your Password?</title>

<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="{{ asset('public/admin/images/icons/favicon.ico') }}" rel="shortcut icon">
<!--Loading bootstrap css-->
<link type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic">
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,700,400italic,700italic' rel='stylesheet' type='text/css'>


<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/vendors/jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/vendors/font-awesome/css/font-awesome.min.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/vendors/bootstrap/css/bootstrap.min.css') }}">
<!--LOADING SCRIPTS FOR PAGE-->
<!--LOADING SCRIPTS FOR PAGE-->
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/vendors/bootstrap-datepicker/css/datepicker.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/vendors/bootstrap-switch/css/bootstrap-switch.css') }}">

<!--Loading style vendors-->
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/vendors/animate.css/animate.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/vendors/jquery-pace/pace.css') }}">
<!--Loading style-->
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/css/style.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/css/style-mango.css') }}" id="theme-style">
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/css/vendors.css') }}">
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/css/themes/grey.css') }}" id="color-style">
<link type="text/css" rel="stylesheet" href="{{ asset('public/admin/css/style-responsive.css') }}">
    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body id="signin-page" class="animated bounceInDown">
<div id="signin-page-content">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="token" value="{{ $token }}">
    	<div class="text-center"><a href="http://www.webqom.com/web88.html" target="_blank"><img src="{{ asset('public/admin/images/login/logo_web88.jpg') }}" alt="Web88"></a></div>
        <h1 class="block-heading">Forgot Your Password?</h1>

        <p>Please enter your registered email to reset the password.</p>
        
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

       <!-- <div class="form-group">
            <div class="input-icon"><i class="fa fa-user"></i><input type="email" placeholder="Your Email" name="email" value="{{ old('email') }}" class="form-control"></div>
        </div>-->
        
        
       		 <div class="form-group">
                <label class="col-md-4 control-label">E-Mail Address</label>
                <div class="col-md-6">
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Password</label>
                <div class="col-md-6">
                    <input type="password" class="form-control" name="password">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-6">
                    <input type="password" class="form-control" name="password_confirmation">
                </div>
            </div>
        
        

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Reset Password
                &nbsp;<i class="fa fa-chevron-circle-right"></i></button>
        </div>
        <hr>
        <a href="mailto:hock@webqom.com"><i class="fa fa-envelope"></i> hock@webqom.com</a>
        <a href="http://www.facebook.com/webqomtechnologies" class="pull-right" target="_blank"><i class="fa fa-facebook-square"></i> /webqomtechnologies</a><br/>
        <i class="fa fa-phone"></i>+(603) 8688 3850
        <span class="margin-top-5px text-12px pull-right">&copy; 2019 <a href="http://www.webqom.com" target="_blank">Webqom Technologies Sdn. Bhd.</a></span>
        
    </form>
</div>
<script src="{{ asset('public/admin/js/jquery-1.9.1.js') }}"></script>
<script src="{{ asset('public/admin/js/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ asset('public/admin/js/jquery-ui.js') }}"></script>
<!--loading bootstrap js-->
<script src="{{ asset('public/admin/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/admin/vendors/bootstrap-hover-dropdown.js') }}"></script>
<script src="{{ asset('public/admin/js/html5shiv.js') }}"></script>
<script src="{{ asset('public/admin/js/respond.min.js') }}"></script>
<script src="{{ asset('public/admin/vendors/jquery-cookie/jquery.cookie.js') }}"></script>
</body>
</html>