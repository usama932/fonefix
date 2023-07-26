<!doctype html>
<html lang="en">
<?php
$setting = \App\Models\Setting::pluck('value','name')->toArray();
$auth_logo = isset($setting['auth_logo']) ? 'uploads/'.$setting['auth_logo'] : 'assets/media/logos/logo-light.png';
$logo = isset($setting['logo']) ? 'uploads/'.$setting['logo'] : 'assets/media/logos/logo-light.png';
$favicon = isset($setting['favicon']) ? 'uploads/'.$setting['favicon'] : 'assets/media/logos/logo-light.png';
$auth_page_heading = isset($setting['auth_page_heading']) ? $setting['auth_page_heading'] : 'wwww.webexert.com';
$auth_image = isset($setting['auth_image']) ? 'uploads/'.$setting['auth_image'] : 'assets/media/svg/illustrations/login-visual-1.svg';
$copy_right = isset($setting['copy_right']) ? $setting['copy_right'] : 'wwww.webexert.com';
$site_title = isset($setting['site_title']) ? $setting['site_title'] : 'FoneFix';
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset($favicon)}}" type="image/x-icon">
    <!-- CSS -->
    <script src="https://kit.fontawesome.com/de584875eb.js" crossorigin="anonymous"></script>
    <link href="{{asset("frontend/css/fontawesome.min.css")}}" rel="stylesheet">
    <link href="{{asset("frontend/css/regular.min.css")}}" rel="stylesheet">
    <link href="{{asset("frontend/css/solid.min.css")}}" rel="stylesheet">
    <link href="{{asset("frontend/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{asset("frontend/css/swiper-bundle.min.css")}}" rel="stylesheet">
    <!-- MAIN SITE STYLE SHEETS -->
    <link href="{{asset("frontend/css/custom.css")}}" rel="stylesheet">

    <title>{{$title}}</title>

</head>

<body class="d-flex flex-column min-vh-100 justify-content-center justify-content-md-between">
<!-- Header Start -->
@if($shop->basicSetting)
@php $basic = $shop->basicSetting; @endphp
<header>
    <!-- Top Header -->
    <section class="top-head border-bottom d-none d-md-inline">
        <div class="container">
            <div class="d-flex justify-content-between py-2">
                <ul class="list-inline m-0">
                    <li class="list-inline-item me-2 align-middle"><i class="fa-regular fa-clock me-2 cblue"></i>Open Hours: {!! $basic->open_hours !!}</li>
                    <li class="list-inline-item cblue bancher align-middle">
                        <i class="fa-sharp fa-solid fa-location-dot me-2 cblue"></i><a href="#">Get Directions</a></li>
                </ul>
                <ul class="list-inline ancher float-end m-0">
                    <li class="list-inline-item"><a href="#">News & Media</a></li>
                    <li class="list-inline-item ms-2"><a href="#">Careers</a></li>
                    <li class="list-inline-item ms-2"><a href="#">FAQs</a></li>
                </ul>
            </div>
        </div>
    </section>
    <!-- Main Header -->
    <section class="main-header border-top py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2 me-auto py-2">
                    <a class="navbar-brand d-flex align-items-center" href="{{route("shop",$shop->slug)}}">
                        <img src="{{asset("uploads/$basic->image")}}" alt="Logo" width="150" >
                        {{--                        <span class="fs-2 ms-3">LOGO</span>--}}
                    </a>
                </div>
                <div class="col-7  d-flex ">
                    <div class="d-flex align-items-center ">
                        <span class="cblue me-3"><img src="{{asset("frontend/")}}/images/email.png" alt="" class="img-fluid" width="35px"></span>
                        <span><strong>Email:</strong><br/><span class="text-wrap">{!! $basic->email !!}</span></span>
                    </div>
                    <div class="d-flex align-items-center  mx-1">
                        <span class="cblue me-3"><img src="{{asset("frontend/")}}/images/house.png" alt="" class="img-fluid" width="35px !important"></span>
                        <span><strong>Location:</strong><br/><span class="clight text-wrap " style="
                            overflow-wrap: break-word !important;
                            overflow: hidden;
                        ">{!! $basic->address !!}</span></span>
                    </div>
                </div>
                <div class="col-3 nav-social d-md-none d-lg-inline d-flex justify-content-md-end ps-5">
                    <a href="{!! $basic->facebook !!}" class="rounded-circle social_icons shadow-sm me-2"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="{!! $basic->twitter !!}" class="rounded-circle social_icons shadow-sm me-2 twitter active"><i class="fa-brands fa-twitter"></i></a>
                    <a href="{!! $basic->pinterest !!}" class="rounded-circle social_icons shadow-sm"><i class="fa-brands fa-pinterest-p"></i></a>
                </div>
            </div>
        </div>
        <!-- Nav Header -->
        <div class="container sticky-top">

        </div>
    </section>
    <!-- Nav Header -->
    <section class="nav-header">
        <nav class="navbar navbar-expand-lg py-lg-0">
            <div class="container">
                <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav nav-fill w-100">
                        <li class="nav-item py-2">
                            <a class="nav-link text-uppercase @if(Request::segment(1) == '') active @endif" aria-current="page" href="{{route("shop",$shop->slug)}}">Home</a>
                        </li>
                        <li class="nav-item py-2">
                            <a class="nav-link text-uppercase" href="#services" >Our Service</a>
                        </li>
                        <li class="nav-item py-2">
                            <a class="nav-link text-uppercase" href="#" >Solution</a>
                        </li>
                        {{--  <li class="nav-item dropdown py-2">
                            <a class="nav-link dropdown-toggle text-uppercase" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menu 2
                            </a>
                            <ul class="dropdown-menu rounded-0">
                                <li><a class="dropdown-item text-uppercase" href="#">Menu 2(a)</a></li>
                                <li><a class="dropdown-item text-uppercase" href="#">Menu 2(b)</a></li>
                                <li><a class="dropdown-item text-uppercase" href="#">Menu 2(c)</a></li>
                            </ul>
                        </li>  --}}
                        <li class="nav-item py-2">
                            <a   class="nav-link text-uppercase  @if(Request::segment(1) == 'contact') active @endif" href="{{route("shop-contact",$shop->slug)}}">Contact</a>
                        </li>
                        <li class="nav-item dropdown py-2">
                            <a class="nav-link dropdown-toggle text-uppercase" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                My Account
                            </a>
                            <ul class="dropdown-menu rounded-0">
                                @auth
                                    <li><a class="dropdown-item text-uppercase" href="{{route("admin.dashboard")}}">Dashboard</a></li>

                                    <li><a class="dropdown-item text-uppercase" href="{{route("main-logout")}}">Logout</a></li>
                                @else
                                    <li><a class="dropdown-item text-uppercase" href="#loginModal" data-bs-toggle="modal">Login</a></li>
                                    {{--                                    <li><a class="dropdown-item text-uppercase" href="#registerModal" data-bs-toggle="modal">Signup</a></li>--}}

                                @endauth

                            </ul>
                        </li>
                        <div class="nav-item search dropdown pt-3">
                            <a onclick="myFunction()" class="dropbtn"><i class="fa-solid fa-magnifying-glass"></i></a>
                            <div id="myDropdown" class="dropdown-content">
                                <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                            </div>
                        </div>
                        <li class="nav-item bg-white btn-blue bg-opacity-10 py-2">
                            <a class="nav-link text-uppercase" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Request Service
                            </a>
                            <!-- <a type="button" class="text-decoration-none text-uppercase py-2" href="#">Request Service</a> -->
                        </li>
                    </ul>
                </div>
                <div class="col-auto nav-social d-none d-md-block d-lg-none d-flex justify-content-md-end">
                    <span class="rounded-circle social_icons bg-white shadow-sm me-2"><i class="fa-brands fa-facebook-f"></i></span>
                    <span class="rounded-circle social_icons bg-white shadow-sm me-2 twitter"><i class="fa-brands fa-twitter"></i></span>
                    <span class="rounded-circle social_icons bg-white shadow-sm"><i class="fa-brands fa-pinterest-p"></i></span>
                </div>
            </div>
        </nav>
    </section>
    <!-- login modal -->
    <div id="loginModal" class="modal-style modal ">
        <div class="modal-dialog modal-login">
            <div class="modal-content">
                <div class="modal-header p-0 mb-3 mt-3">
                    <h4 class="modal-title">login</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- dont forget to add action and action method  -->
                    <form class="form" action="{{ route('login') }}" method="post" novalidate="novalidate" >

                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Enter email address" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" placeholder="Enter password" required="required" autocomplete="on">
                            </div>
                        </div>
                        <div class="pl-1 pr-1 form-group d-flex justify-content-between">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="item_checkbox" name="item_checkbox" value="option1">
                                <span class="custom-control-label">&nbsp;Remember Me</span>
                            </label>
                            <a class="cursor" href="{{ route('password.request') }}">Forgot Password?</a>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-signin" >login</button>
                        </div>
                        {{--                        <p class="hint-text mt-3">or login with</p>--}}
                    <!-- social login area -->
                        {{--                        <div class="social-login text-center">--}}
                        {{--                            <a class=" btn-facebook  text-uppercase" href="redirect/facebook"><i class="fab fa-facebook-f mr-2 ml-2"></i> </a>--}}
                        {{--                            <a class=" btn-google  text-uppercase" href="redirect/google"><i class="fab fa-google mr-2 ml-2"></i></a>--}}
                        {{--                            <a class=" btn-twitter  text-uppercase" href="redirect/twitter"><i class="fab fa-twitter mr-2 ml-2"></i></a>--}}
                        {{--                        </div>--}}
                    </form>
                </div>
                {{--                <div class="text-center mb-3">Don't have an account? <a class="register" data-bs-target="#registerModal" data-bs-toggle="modal">Register</a></div>--}}
            </div>
        </div>
    </div>
    <!-- resetPassword modal -->
    <div id="resetModal" class="modal-style modal ">
        <div class="modal-dialog modal-login">
            <div class="modal-content">
                <div class="modal-header p-0 mb-3 mt-3">
                    <h4 class="modal-title">Reset Password</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- dont forget to add action and action method  -->
                    <form action="" method="">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Enter email address" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" placeholder="Enter new password" required="required" autocomplete="on">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-eye-slash"></i></span>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Retype new password" required="required" autocomplete="on">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">OTP</span>
                                <input class="me-2 text-center form-control rounded-0" type="text" id="first" maxlength="1" />
                                <input class="me-2 text-center form-control rounded-0" type="text" id="second" maxlength="1" />
                                <input class="me-2 text-center form-control rounded-0" type="text" id="third" maxlength="1" />
                                <input class="me-2 text-center form-control rounded-0" type="text" id="fourth" maxlength="1" />
                                <input class="me-0 text-center form-control rounded-0" type="text" id="fifth" maxlength="1" />
                                <div class="mt-2 d-flex">
                                    <span class="d-block mobile-text pe-3">Don't receive the code?</span>
                                    <span class="font-weight-bold text-primary cursor">Resend</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-signin">Submit</button>
                        </div>
                    {{--                        <p class="hint-text mt-3">or login with</p>--}}
                    <!-- social login area -->
                        {{--                        <div class="social-login text-center">--}}
                        {{--                            <a class=" btn-facebook  text-uppercase" href="redirect/facebook"><i class="fab fa-facebook-f mr-2 ml-2"></i> </a>--}}
                        {{--                            <a class=" btn-google  text-uppercase" href="redirect/google"><i class="fab fa-google mr-2 ml-2"></i></a>--}}
                        {{--                            <a class=" btn-twitter  text-uppercase" href="redirect/twitter"><i class="fab fa-twitter mr-2 ml-2"></i></a>--}}
                        {{--                        </div>--}}
                    </form>
                </div>
                <div class="text-center mb-3">Back to login account? <a class="login" data-bs-target="#loginModal" data-bs-toggle="modal">Login</a></div>
            </div>
        </div>
    </div>
    <!-- OTP modal -->
    <div id="verificationModal" class="modal-style modal">
        <div class="modal-dialog modal-login">
            <div class="modal-content">
                <div class="modal-header p-0 mb-3 mt-3">
                    <h4 class="modal-title">OTP Verification</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- dont forget to add action and action method  -->
                    <div class="d-flex justify-content-center align-items-center container">
                        <div class="card py-4 px-3">
                            <span class="mobile-text">Enter the code we just send on your mobile phone <b class="text-primary">....4833</b>/ email <b class="text-primary">ema..@...</b></span>
                            <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                                <input class="me-2 text-center form-control rounded-0" type="text" id="first" maxlength="1" />
                                <input class="me-2 text-center form-control rounded-0" type="text" id="second" maxlength="1" />
                                <input class="me-2 text-center form-control rounded-0" type="text" id="third" maxlength="1" />
                                <input class="me-2 text-center form-control rounded-0" type="text" id="fourth" maxlength="1" />
                                <input class="me-0 text-center form-control rounded-0" type="text" id="fifth" maxlength="1" />
                            </div>
                            <div class="form-group mt-3 text-center">
                                <button type="submit" class="btn btn-primary btn-signin">Submit</button>
                            </div>
                            <div class="text-center mt-3">
                                <span class="d-block mobile-text">Don't receive the code?</span>
                                <span class="font-weight-bold text-primary cursor">Resend</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- register modal -->
    <div id="registerModal" class="modal-style modal ">
        <div class="modal-dialog modal-login">
            <div class="modal-content">
                <div class="modal-header p-0 mb-3 mt-3">
                    <h4 class="modal-title">Register</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- dont forget to add action and action method  -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="name" placeholder="Enter your name" required="required">
                            </div>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Enter email address" required="required">
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" placeholder="Enter password" required="required" autocomplete="on">
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-eye-slash"></i></span>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Retype password" required="required" autocomplete="on">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-signin">Register</button>

                        </div>
                        {{--                        <p class="hint-text mt-3">or register with</p>--}}
                    <!-- social login area -->
                        {{--                        <div class="social-login text-center">--}}
                        {{--                            <a class=" btn-facebook  text-uppercase" href="redirect/facebook"><i class="fab fa-facebook-f mr-2 ml-2"></i> </a>--}}
                        {{--                            <a class=" btn-google  text-uppercase" href="redirect/google"><i class="fab fa-google mr-2 ml-2"></i></a>--}}
                        {{--                            <a class=" btn-twitter  text-uppercase" href="redirect/twitter"><i class="fab fa-twitter mr-2 ml-2"></i></a>--}}
                        {{--                        </div>--}}
                    </form>
                </div>
                <div class="text-center mb-3">Already have an account? <a class="login" data-bs-target="#loginModal" data-bs-toggle="modal">Login</a></div>
            </div>
        </div>
    </div>
</header>
@endif
<!-- Header End -->
@if($shop->cmsSetting)
    @php $cms = $shop->cmsSetting; @endphp
<main class="d-flex flex-column justify-content-start flex-grow-1 ">
        <!-- Hero Start-->
        <section class="hero">
            <!-- Banner Section -->
            <div id="carouselCaptions" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselCaptions" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselCaptions" data-bs-slide-to="1"></li>
                </ol>
                <div class="carousel-inner">
                    @foreach ($sliders as $slider)
                        <div class="carousel-item @if($loop->iteration == 1) active @endif">
                            <img src="{{asset("uploads/$slider->image")}}" class="d-block w-100" alt="..." style="height:600px !important;">
                            <div class="container carousel-caption">
                                <h1 class="py-sm-4 lh-1">{!! $slider->title !!}</h1>
                                @if($slider->button_text)
                                <a class="btn btn-blue border fs-6 d-none d-sm-inline" href="{{ $slider->button_text  ?? '#'}}" role="button">{{ $slider->button_text }}</a>
                                @endif
                            </div>
                        </div>
                    @endforeach


                </div>
                <!-- <a class="carousel-control-prev" href="#carouselCaptions" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselCaptions" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a> -->
            </div>
            <!-- End Banner Section -->
        </section>
        <!-- Features Area Start -->
        <section class="feature bg-cdblue">
            <!-- Features -->
            <div class="container">
                <div class="row pb-3">
                    <div class="col-12 col-sm-8">
                        <h2 class="text-white py-4 py-sm-4 py-md-5">Make Your Mobile Repair Success<br> & XXXXXXX With Us</h2>
                    </div>
                    <div class="col-12 col-sm-4 mt-sm-4 mt-md-5 pt-md-3 text-end">
                        <button class="btn btn-light rounded-0 border fs-6">Contact Now</button>
                    </div>
                </div>
            </div>
        </section>
        <!-- About -->
        <section class="about" id="about">
            <!-- Call To Action -->
            <div class="container action px-0">
                <div class="row row-cols-2 row-cols-md-4 w-100 mx-0">
                    <div class="col h-100 mb-3">
                        <div class="card rounded-1 onhover">
                            <img src="{{asset("uploads/$cms->feature_image1")}}" class="mx-auto mt-5 cblue" alt="Phone" width="40px" height="auto">
                            <div class="card-body mt-2">
                                <h6 class="card-title text-center">{!! $cms->feature_text1 !!}</h6>
                            </div>
                            <small class="ms-2"><hr class="w-75 mx-4 mb-0"></small>
                        </div>
                    </div>
                    <div class="col h-100 mb-3">
                        <div class="card rounded-1 onhover">
                            <img src="{{asset("uploads/$cms->feature_image2")}}" class="mx-auto mt-5 cblue" alt="Phone" width="40px" height="auto">
                            <div class="card-body mt-2">
                                <h6 class="card-title text-center">{!! $cms->feature_text2 !!}</h6>
                            </div>
                            <small class="ms-2"><hr class="w-75 mx-4 mb-0"></small>
                        </div>
                    </div>
                    <div class="col h-100 mb-3">
                        <div class="card rounded-1 onhover">
                            <img src="{{asset("uploads/$cms->feature_image3")}}" class="mx-auto mt-5 cblue" alt="Phone" width="40px" height="auto">
                            <div class="card-body mt-2">
                                <h6 class="card-title text-center">{!! $cms->feature_text3 !!}</h6>
                            </div>
                            <small class="ms-2"><hr class="w-75 mx-4 mb-0"></small>
                        </div>
                    </div>
                    <div class="col h-100 mb-3">
                        <div class="card rounded-1 onhover">
                            <img src="{{asset("uploads/$cms->feature_image4")}}" class="mx-auto mt-5 cblue" alt="Phone" width="40px" height="auto">
                            <div class="card-body mt-2">
                                <h6 class="card-title text-center">{!! $cms->feature_text4 !!}</h6>
                            </div>
                            <small class="ms-2"><hr class="w-75 mx-4 mb-0"></small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- About -->
            <div class="container my-5">
                <div class="row">
                    <div class="col-md-6 mb-5 mb-md-2 h-100">
                        <div class="row img-hover-zoom me-md-5 video-area">
                            <!-- <img src="images/Layer-6151.jpg" alt="" class="img-fluid mb-2"> -->
                            <a data-title="Play (k)" id="play">
                                <i class="fas fa-circle-play cblue bg-white rounded-circle"></i>
                            </a>
                            <video controls class="video mb-2" id="video" preload="metadata" poster="{{asset("frontend/")}}/images/Layer-6151.jpg">
                                <source src="{!! $cms->about_video !!}" type="video/mp4"></source>
                            </video>
                            <div class="col-9 about-img pe-2 zoom"><img src="{{asset("uploads/$cms->about_image")}}" alt="" class="img-fluid" width="100%"></div>
                            <div class="col-3 about-img ps-0"><img src="{{asset("frontend/")}}/images/image.jpg" alt="" class="img-fluid" width="100%"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 h-100">
                        <div class="row">
                            <div class="col">
                                <h6 class="text-uppercase cblue"><span class="bline">&nbsp;&nbsp;About Us</span></h6>
                                <h2 class="">{!! $cms->about_title !!}</h2>
                                <p>{!! $cms->description !!}</p>
                                <!-- Tabs content -->
                                <div class="container px-0" style="margin-top: 10px;">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-fill w-100 about-tab">
                                        <li class="nav-item">
                                            <a class="nav-link d-flex active" data-bs-toggle="tab" href="#elec">
                                                <img class="img-tab" src="{{asset("frontend/")}}/images/electrician.png" alt="">
                                                <h6 class="ms-3 text-start">Gaurantee & <br>Maintenance</h6>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link d-flex" data-bs-toggle="tab" href="#plum">
                                                <img class="img-tab" src="{{asset("frontend/")}}/images/plumber.png" alt="">
                                                <h6 class="ms-3 text-start">The Best Quilty <br>Services</h6>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content my-4">
                                        <div class="tab-pane container px-0 active" id="elec">{!! $cms->guarantee !!}</div>
                                        <div class="tab-pane container px-0 fade" id="plum">{!! $cms->quality !!}</div>
                                    </div>
                                </div>
                                <!-- Process Bar -->
                                <p class="process-bar mb-1"><span class="">Repair Done</span><span class="bar-device">{{$cms->repairs}} Devices</span></p>
                                <div class="progress rounded-0" style="height: 10px;">
                                    <div class="progress-bar bg-cblue" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <a class="btn btn-blue border mt-5 fs-6" href="#!" role="button">More About Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services Area Start -->
        <section class="services-area bg-light" id="services">
            <!-- Service -->
            <div class="container my-4 my-md-5">
                <div class="row text-center pb-3">
                    <h6 class="text-uppercase cblue"><span class="bline">&nbsp;&nbsp;Our Service</span></h6>
                    <h2>What Fixes We Provide</h2>
                </div>
            </div>
        </section>
        <!-- Service Box -->
        <section class="services-box w-100">
            <div class="container-fluid service bg-white py-md-5">
                <div class="container">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col item">
                            <div class="card text-white rounded-0 h-100">
                                <img class="card-img img-cover rounded-0" src="{{asset("uploads/$cms->service_image1")}}" alt="Card image" height="" width="auto">
                                <div class="card-img-overlay mt-auto mb-0 p-0">
                                    <div class="card-body bg-cblue rounded-0">
                                        <h5 class="card-title text-white mb-0">{{$cms->service_text1}}</h5>
                                    </div>
                                    <div class="card-footer bg-cblue border-0 rounded-0">
                                        <small class="text-white cover"><i class="fa-solid fa-arrow-right"></i></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col item">
                            <div class="card bg-dark text-white rounded-0 h-100">
                                <img class="card-img rounded-0 h-100" src="{{asset("uploads/$cms->service_image2")}}" alt="Card image">
                                <div class="card-img-overlay mt-auto mb-0 p-0">
                                    <div class="card-body bg-cblue rounded-0">
                                        <h5 class="card-title text-white mb-0">{{$cms->service_text2}}</h5>
                                    </div>
                                    <div class="card-footer bg-cblue border-0 rounded-0">
                                        <small class="text-white cover"><i class="fa-solid fa-arrow-right"></i></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col item">
                            <div class="card bg-dark text-white rounded-0 h-100">
                                <img class="card-img rounded-0 h-100" src="{{asset("uploads/$cms->service_image3")}}" alt="Card image">
                                <div class="card-img-overlay mt-auto mb-0 p-0">
                                    <div class="card-body bg-cblue rounded-0">
                                        <h5 class="card-title text-white mb-0">{{$cms->service_text3}}</h5>
                                    </div>
                                    <div class="card-footer bg-cblue border-0 rounded-0">
                                        <small class="text-white cover"><i class="fa-solid fa-arrow-right"></i></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- service-box-bottom -->
            <div class="container-fluid bg-cblue mb-5">
                <div class="container service-bottom py-3">
                    <div class="card rounded-0 border-0 bg-cblue">
                        <div class="row g-0">
                            <div class="col-md-2">
                                <img src="https://via.placeholder.com/175x100/cccccc/" class="img-fluid rounded-0 w-100" alt="...">
                            </div>
                            <div class="col-md-7 col-sm-7">
                                <div class="card-body px-0 px-md-3">
                                    <h6 class="text-uppercase text-white"><span class="wline">&nbsp;&nbsp;Facilities we provide</span></h6>
                                    <h3 class="text-white mb-0">Providing Solutions Of Every Kind</h3>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-5 text-end">
                                <div class="card-body px-0">
                                    <a class="btn btn-lblue mt-3">See More Service</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Recent Projects -->
        <section class="project-area bg-cdblue text-center py-5 pb-md-1" id="project">
            <h6 class="text-uppercase text-white"><span class="wline">&nbsp;&nbsp;We make Portfolio</span></h6>
            <h2 class="text-white">Explore Recent Projects</h2>
            <div class="container-fluid projects pt-4 py-md-5">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <div class="col item">
                        <div class="card bg-dark text-white rounded-0 h-100">
                            <img class="card-img rounded-0 h-100" src="{{asset("uploads/$cms->project_image1")}}" alt="Card image">
                            <div class="card-img-overlay mt-auto mb-4">
                                <div class="card-body cover d-flex justify-content-between bg-white text-start rounded-0 px-4">
                                    <div class="align-self-center w-75">
{{--                                        <p class="card-title cblue mb-0">Training</p>--}}
                                        <h5 class="card-title clight mb-0">{{$cms->project_text1}}</h5>
                                    </div>
                                    <div class="align-self-center text-end w-25">
                                        <i class="fa-solid fa-arrow-right cblue fs-5 fw-bold"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col item">
                        <div class="card bg-dark text-white rounded-0 h-100">
                            <img class="card-img rounded-0 h-100" src="{{asset("uploads/$cms->project_image2")}}" alt="Card image">
                            <div class="card-img-overlay mt-auto mb-4">
                                <div class="card-body cover d-flex justify-content-between bg-white text-start rounded-0 px-4">
                                    <div class="align-self-center w-75">
{{--                                        <p class="card-title cblue mb-0">Training</p>--}}
                                        <h5 class="card-title clight mb-0">{{$cms->project_text2}}</h5>
                                    </div>
                                    <div class="align-self-center text-end w-25">
                                        <i class="fa-solid fa-arrow-right cblue fs-5 fw-bold"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col item">
                        <div class="card bg-dark text-white rounded-0 h-100">
                            <img class="card-img rounded-0 h-100" src="{{asset("uploads/$cms->project_image3")}}" alt="Card image">
                            <div class="card-img-overlay mt-auto mb-4">
                                <div class="card-body cover d-flex justify-content-between bg-white text-start rounded-0 px-4">
                                    <div class="align-self-center w-75">
{{--                                        <p class="card-title cblue mb-0">Training</p>--}}
                                        <h5 class="card-title clight mb-0">{{$cms->project_text3}}</h5>
                                    </div>
                                    <div class="align-self-center text-end w-25">
                                        <i class="fa-solid fa-arrow-right cblue fs-5 fw-bold"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-action border btn-lg fs-6 mt-5" href="#!" role="button">See More Portofolio</a>
            </div>
        </section>
        <!-- Our Team -->
        <section class="ourTeam team-area text-center pt-4 py-md-5" id="our-team">
            <h6 class="text-uppercase cblue"><span class="bline">&nbsp;&nbsp;Our Member</span></h6>
            <h2>Meet The Our Team</h2>
            <div class="container my-5">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 mb-5 g-4">
                    <div class="col i">
                        <div class="c shadow text-center">
                            <div class="wrap">
                                <img src="https://via.placeholder.com/250x300/0e5fb2" alt="#" width="250" height="300" class="img-fluid">
                                <div class="infos text-start ms-4">
                                    <p class="card-title cblue mb-0">Engineer</p>
                                    <h5 class="card-text">Names</h5>
                                </div>
                            </div>
                            <div class="more">
                                <p>Ut sed consectetur ligula. Aenean id nibh accumsan, pre tium nulla in, lacinia aecenas mollis. Sed mauris at sollicitudin. Etiam maximus mauris vel leo mattis, non venenatis magna finibus vestibulum.</p>
                                <div class="socials">
                                    <a href="#" title="#" class="facebook"><i class="fa fa-facebook"></i></a>
                                    <a href="#" title="#" class="twitter"><i class="fa fa-twitter"></i></a>
                                    <a href="#" title="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                                    <a href="#" title="#" class="linkedin ps-2"><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col i">
                        <div class="c shadow text-center">
                            <div class="wrap">
                                <img src="https://via.placeholder.com/250x300/0e5fb2" alt="#" width="250" height="300" class="img-fluid">
                                <div class="infos text-start ms-4">
                                    <p class="card-title cblue mb-0">Engineer</p>
                                    <h5 class="card-text">Names</h5>
                                </div>
                            </div>
                            <div class="more">
                                <p>Ut sed consectetur ligula. Aenean id nibh accumsan, pre tium nulla in, lacinia aecenas mollis. Sed mauris at sollicitudin. Etiam maximus mauris vel leo mattis, non venenatis magna finibus vestibulum.</p>
                                <div class="socials">
                                    <a href="#" title="#" class="facebook"><i class="fa fa-facebook"></i></a>
                                    <a href="#" title="#" class="twitter"><i class="fa fa-twitter"></i></a>
                                    <a href="#" title="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                                    <a href="#" title="#" class="linkedin ps-2"><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col i">
                        <div class="c shadow text-center">
                            <div class="wrap">
                                <img src="https://via.placeholder.com/250x300/0e5fb2" alt="#" width="250" height="300" class="img-fluid">
                                <div class="infos text-start ms-4">
                                    <p class="card-title cblue mb-0">Engineer</p>
                                    <h5 class="card-text">Names</h5>
                                </div>
                            </div>
                            <div class="more">
                                <p>Ut sed consectetur ligula. Aenean id nibh accumsan, pre tium nulla in, lacinia aecenas mollis. Sed mauris at sollicitudin. Etiam maximus mauris vel leo mattis, non venenatis magna finibus vestibulum.</p>
                                <div class="socials">
                                    <a href="#" title="#" class="facebook"><i class="fa fa-facebook"></i></a>
                                    <a href="#" title="#" class="twitter"><i class="fa fa-twitter"></i></a>
                                    <a href="#" title="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                                    <a href="#" title="#" class="linkedin ps-2"><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3 my-5 border-0 rounded-0 shadow">
                    <div class="row g-0">
                        <div class="col-12 col-md-9 h-100">
                            <img src="{{asset("frontend/")}}/images/h1-bg5.jpg" class="card-img cta-img" alt="...">
                            <div class="card-img-overlay text-start rounded-0 pt-md-5 ps-md-5">
                                <div class="d-flex align-items-center text-start">
                                    <span class="cblue">
                                        <img src="{{asset("frontend/")}}/images/contact.png" alt="" class="img-fluid">
                                    </span>
                                    <span class="ms-3">
                                        <h5 class="card-title mb-0">We’re ready to discover and unlock your potential.</h5>
                                        <h4 class="card-title cblue mb-0">Call us Today! {!! $basic->phone !!}</h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 rounded-0 video-clip">
                            <video controls class="video w-100 h-100" id="videoclip" preload="metadata" poster="">
                                <source src="https://www.youtube.com/watch?v=ScMzIvxBSi4" type="video/mp4"></source>
                            </video>
                            <a data-title="Play (k)" id="playbtn">
                                <i class="fas fa-circle-play cblue bg-white rounded-circle fs-5"></i>
                            </a>
                            <!-- <video width="100%" controls
                                poster="https://via.placeholder.com/300x175/ececec">
                                <source src="https://archive.org/download/ElephantsDream/ed_hd.ogv" type="video/ogg" />
                                <a href="https://archive.org/download/ElephantsDream/ed_1024_512kb.mp4"></a>
                            </video> -->
                            <!-- <img src="https://via.placeholder.com/300x175/ececec" class="img-fluid rounded-0 cta-img w-100" alt="..."> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Reviews -->
        <section class="review-area text-center py-md-4" id="testimonials">
            <h6 class="text-uppercase cblue"><span class="bline">&nbsp;&nbsp;Testimonial</span></h6>
            <h2>Read Customers Thoughts</h2>
            <div class="container swiper px-2 my-5">
                <div class="slide-content team mb-5">
                    <div class="card-wrapper swiper-wrapper mt-3">
                        <div class="card swiper-slide shadow-sm text-start p-4">
                            <div class="mt-2">
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star-half-alt active-star"></span>
                            </div>
                            <div class="testimonial">Labore sed dolore magna aliquay enim ad minim veniam quis nostr ud exercitation ullamco laboris ni ut aliquip ex ea reprehen deritin voluptate.</div>
                            <div class="d-flex flex-row profile pt-4 mt-auto">
                                <img src="{{asset("frontend/")}}/images/logo.png" alt="" class="rounded-circle me-3">
                                <div class="d-flex flex-column pl-2">
                                    <div class="name">Donald H. James</div>
                                    <p class="text-muted designation mb-0">Rental Customer</p>
                                </div>
                            </div>
                        </div>
                        <div class="card swiper-slide shadow-sm active text-start p-4">
                            <div class="mt-2">
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                            </div>
                            <div class="testimonial">Labore sed dolore magna aliquay enim ad minim veniam quis nostr ud exercitation ullamco laboris ni ut aliquip ex ea reprehen deritin voluptate.</div>
                            <div class="d-flex flex-row profile pt-4 mt-auto">
                                <img src="{{asset("frontend/")}}/images/logo.png" alt="" class="rounded-circle me-3">
                                <div class="d-flex flex-column pl-2">
                                    <div class="name">Donald H. James</div>
                                    <p class="text-muted designation mb-0">Rental Customer</p>
                                </div>
                            </div>
                        </div>
                        <div class="card swiper-slide shadow-sm text-start p-4">
                            <div class="mt-2">
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star active-star"></span>
                                <span class="fas fa-star-half-alt active-star"></span>
                            </div>
                            <div class="testimonial">Labore sed dolore magna aliquay enim ad minim veniam quis nostr ud exercitation ullamco laboris ni ut aliquip ex ea reprehen deritin voluptate.</div>
                            <div class="d-flex flex-row profile pt-4 mt-auto">
                                <img src="{{asset("frontend/")}}/images/logo.png" alt="" class="rounded-circle me-3">
                                <div class="d-flex flex-column pl-2">
                                    <div class="name">Donald H. James</div>
                                    <p class="text-muted designation mb-0">Rental Customer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination px-5"></div>
            </div>
        </section>
        <!-- Blog Posts -->
        <section class="blog-area text-center py-md-4">
            <h6 class="text-uppercase cblue"><span class="bline">&nbsp;&nbsp;FROM OUR BLOG</span></h6>
            <h2>Here’s What’s Happening</h2>
            <div class="container py-5">
                <!-- Card deck -->
                <div class="card-deck row">
                    <div class="col-xs-12 col-sm-6 col-md-4 mb-3">
                        <!-- Card -->
                        <div class="card blog-post bg-white border-0">
                            <!--Card image-->
                            <div class="view overlay">
                                <img class="card-img-top shadow-sm rounded-0" src="https://via.placeholder.com/100/1b1a1a" alt="Card image cap">
                                <a href="#!">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>
                            <!--Card content-->
                            <div class="card-body bg-white shadow text-start me-4">
                                <p class="cblue s-text mb-2">03 JUN 2020    - MARKETING</p>
                                <h5 class="card-title mb-2">Which construction tool is to choose?</h5>
                                <p class="">Duis aute sit amet consectetur pisici elit, sed do eiusmod…</p>
                                <a class="btn btn-blue border fs-6" href="#!" role="button">Read More</a>
                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 mb-3">
                        <!-- Card -->
                        <div class="card blog-post bg-white border-0">
                            <!--Card image-->
                            <div class="view overlay">
                                <img class="card-img-top shadow-sm rounded-0" src="https://via.placeholder.com/100/1b1a1a" alt="Card image cap">
                                <a href="#!">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>
                            <!--Card content-->
                            <div class="card-body bg-white shadow text-start me-4">
                                <p class="cblue s-text mb-2">03 JUN 2020    - MARKETING</p>
                                <h5 class="card-title mb-2">Which construction tool is to choose?</h5>
                                <p class="">Duis aute sit amet consectetur pisici elit, sed do eiusmod…</p>
                                <a class="btn btn-blue border fs-6" href="#!" role="button">Read More</a>
                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 mb-3">
                        <!-- Card -->
                        <div class="card blog-post bg-white border-0">
                            <!--Card image-->
                            <div class="view overlay">
                                <img class="card-img-top shadow-sm rounded-0" src="https://via.placeholder.com/100/1b1a1a" alt="Card image cap">
                                <a href="#!">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>
                            <!--Card content-->
                            <div class="card-body bg-white shadow text-start me-4">
                                <p class="cblue s-text mb-2">03 JUN 2020    - MARKETING</p>
                                <h5 class="card-title mb-2">Which construction tool is to choose?</h5>
                                <p class="">Duis aute sit amet consectetur pisici elit, sed do eiusmod…</p>
                                <a class="btn btn-blue border fs-6" href="#!" role="button">Read More</a>
                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                </div>
                <!-- Card deck -->
            </div>
        </section>
        <!-- Brand Logo -->
        <section class="brand-area">
            <div class="container-fluid bg-light px-2">
                <div class="d-flex brand justify-content-center flex-wrap flex-md-nowrap shadow w-100">
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/apple-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/samsong-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/mi-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/oppo-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/vivo-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/oneplus-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/realme-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/motorla-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                </div>
                <div class="d-flex align-content-center sm-hide w-100">
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/nokia-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/honor-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/google-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/asus-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/panasonic-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/gionee-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/huawei-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                    <div class="order-3 p-2 border border-3 border-light bd-highlight zoom-brand">
                        <img src="{{asset("frontend/")}}/images/more-logo.jpg" alt="" class="img-fluid" width="139px " height="auto">
                    </div>
                </div>
            </div>
        </section>
    </main>
<footer>
    <!-- Footer Main -->
    <section class="footer bg-cdblue">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 align-items-start text-white py-sm-5">
                <div class="col">
                    <h5 class="mt-md-3">&nbsp;</h5>
                    <p class="text-white fs-6">There are many variations of pass Lorem Ipsum available.</p>
                    <div class="d-flex align-items-center">
                        <span class="cblue"><img src="{{asset("frontend/")}}/images/icon.png" alt="" class="img-fluid" width="35px"></span>
                        <span class="ms-3">Request Call Us<br/><span>{!! $basic->phone !!}</span></span>
                    </div>
                    <div class="col-auto nav-social my-4">
                        <a href="{{$basic->facebook}}" class="rounded-0 social_icons text-white bg-white bg-opacity-10 me-2"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="{{$basic->instagram}}" class="rounded-0 social_icons text-white bg-white bg-opacity-10 me-2"><i class="fa-brands fa-instagram"></i></a>
                        <a href="{{$basic->twittter}}" class="rounded-0 social_icons text-white bg-white bg-opacity-10 twitter"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                </div>
                <div class="col">
                    <h5 class="mb-3">Our Service</h5>
                    <ul class="list-unstyled">
                        <li class="text-white fs-6 mb-1">iPhone Service Center</li>
                        <li class="text-white fs-6 mb-1">Oneplus Service Center</li>
                        <li class="text-white fs-6 mb-1">Mi Service Center</li>
                        <li class="text-white fs-6 mb-1">Realme Service Center</li>
                        <li class="text-white fs-6 mb-1">Nokia Service Center</li>
                        <li class="text-white fs-6 mb-1">Samsung Service Center</li>
                        <li class="text-white fs-6 mb-1">Google Pixel Repair</li>
                    </ul>
                </div>
                <div class="col">
                    <h5 class="mb-3">Sucess Project</h5>
                    <form>
                        <div class="mb-3">
                            <input type="email" class="form-control rounded-0" id="email" placeholder="Enter Email*" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control rounded-0" id="comment" rows="3" placeholder="Your Comment"></textarea>
                        </div>
                        <a type="submit" class="btn text-white rounded-0 border-0 bg-cblue bg-opacity-10 fs-6 px-3 py-2">Send Message</a>
                    </form>
                </div>
                <div class="col">
                    <h5 class="mb-3">Recent Post</h5>
                    <div class="d-flex align-items-center mb-2">
                        <span class="cblue"><img src="https://via.placeholder.com/70/cccccc" alt="" class="img-fluid" width="70px"></span>
                        <span class="ms-3 lh-1">Which construction to choose?<br/><span class="clight s-text fw-normal">October 23, 2021</span></span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="cblue"><img src="https://via.placeholder.com/70/cccccc" alt="" class="img-fluid" width="70px"></span>
                        <span class="ms-3 lh-1">Which construction to choose?<br/><span class="clight s-text fw-normal">October 23, 2021</span></span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="cblue"><img src="https://via.placeholder.com/70/cccccc" alt="" class="img-fluid" width="70px"></span>
                        <span class="ms-3 lh-1">Which construction to choose?<br/><span class="clight s-text fw-normal">October 23, 2021</span></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Bottom -->
    <section class="border-bottom border-2 border-light py-2" style="background: #15283b;">
        <div class="container text-white">
            <div class="row">
                <div class="col-md-4">
                    <p class="fs-6 mb-0 mt-2">Copyright 2020. All Rights Reserved.</p>
                </div>
                <div class="col-md-4 ms-md-auto">
                    <ul class="nav justify-content-md-end">
                        <li class="nav-item">
                            <a class="nav-link text-white fs-6" aria-current="page" href="faqs.html">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white fs-6" href="about.html">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white fs-6" href="contact.html">Contact Us</a>
                        </li>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</footer>
<!-- footer End-->
@endif
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<!-- Js -->
<script src="{{asset("frontend/js/jquery-3.6.0.min.js")}}"></script>
<script src="{{asset("frontend/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("frontend/js/swiper-bundle.min.js")}}"></script>
<script src="{{asset("frontend/js/custom.js")}}"></script>

</body>

</html>
