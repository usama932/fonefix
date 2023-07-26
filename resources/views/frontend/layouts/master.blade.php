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

    @yield('meta')

</head>

<body class="d-flex flex-column min-vh-100 justify-content-center justify-content-md-between">
<!-- Header Start -->
<header>
    <!-- Top Header -->
    <section class="top-head border-bottom d-none d-md-inline">
        <div class="container">
            <div class="d-flex justify-content-between py-2">
                <ul class="list-inline m-0">
                    <li class="list-inline-item me-2 align-middle"><i class="fa-regular fa-clock me-2 cblue"></i>Open Hours: Mon - Sat - 9:00 - 18:00</li>
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
                <div class="col-3 me-auto py-2">
                    <a class="navbar-brand d-flex align-items-center" href="{{route("home")}}">
                        <img src="{{asset($logo)}}" alt="Logo" width="150" >
{{--                        <span class="fs-2 ms-3">LOGO</span>--}}
                    </a>
                </div>
                <div class="col-auto info d-flex justify-content-md-end">
                    <div class="d-flex align-items-center px-5">
                        <span class="cblue me-3"><img src="{{asset("frontend/")}}/images/email.png" alt="" class="img-fluid" width="35px"></span>
                        <span><strong>Email:</strong><br/><span class="clight">demo@example.com</span></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="cblue me-3"><img src="{{asset("frontend/")}}/images/house.png" alt="" class="img-fluid" width="35px"></span>
                        <span><strong>Location:</strong><br/><span class="clight">1256 floor new world.</span></span>
                    </div>
                </div>
                <div class="col-auto nav-social d-md-none d-lg-inline d-flex justify-content-md-end ps-5">
                    <span class="rounded-circle social_icons shadow-sm me-2"><i class="fa-brands fa-facebook-f"></i></span>
                    <span class="rounded-circle social_icons shadow-sm me-2 twitter active"><i class="fa-brands fa-twitter"></i></span>
                    <span class="rounded-circle social_icons shadow-sm"><i class="fa-brands fa-pinterest-p"></i></span>
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
                            <a class="nav-link text-uppercase @if(Request::segment(1) == '') active @endif" aria-current="page" href="{{route("home")}}">Home</a>
                        </li>
                        <li class="nav-item py-2">
                            <a class="nav-link text-uppercase" href="#">Our Service</a>
                        </li>
                        <li class="nav-item py-2">
                            <a class="nav-link text-uppercase" href="#">Solution</a>
                        </li>
                        <li class="nav-item dropdown py-2">
                            <a class="nav-link dropdown-toggle text-uppercase" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menu 2
                            </a>
                            <ul class="dropdown-menu rounded-0">
                                <li><a class="dropdown-item text-uppercase" href="#">Menu 2(a)</a></li>
                                <li><a class="dropdown-item text-uppercase" href="#">Menu 2(b)</a></li>
                                <li><a class="dropdown-item text-uppercase" href="#">Menu 2(c)</a></li>
                            </ul>
                        </li>
                        <li class="nav-item py-2">
                            <a class="nav-link text-uppercase  @if(Request::segment(1) == 'contact') active @endif" href="{{route("contact")}}">Contact</a>
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
<!-- Header End -->

<!-- Content Start -->
@yield('content')
<!-- Content End -->

<!-- footer Start-->
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
                        <span class="ms-3">Request Call Us<br/><span>(+099) 565 698 65</span></span>
                    </div>
                    <div class="col-auto nav-social my-4">
                        <span class="rounded-0 social_icons text-white bg-white bg-opacity-10 me-2"><i class="fa-brands fa-facebook-f"></i></span>
                        <span class="rounded-0 social_icons text-white bg-white bg-opacity-10 me-2"><i class="fa-brands fa-instagram"></i></span>
                        <span class="rounded-0 social_icons text-white bg-white bg-opacity-10 twitter"><i class="fa-brands fa-twitter"></i></span>
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

<!-- Js -->
<script src="{{asset("frontend/js/jquery-3.6.0.min.js")}}"></script>
<script src="{{asset("frontend/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("frontend/js/swiper-bundle.min.js")}}"></script>
<script src="{{asset("frontend/js/custom.js")}}"></script>

</body>

</html>
