<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href='{{ asset("uploads/$favicon") }}' type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="{{ asset('frontend/css/fontawesome_all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/select2.min.css') }}" rel="stylesheet">
    <!-- MAIN SITE STYLE SHEETS -->
    <link href="{{ asset('frontend/css/main.css') }}" rel="stylesheet">

    <title>Home - {{ config('app.name')}}</title>
 @yield('styles')
 <style>
    .blink_me {
        
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
    <style>
        .ajax-loader {
            visibility: hidden;
            background-color: rgba(255,255,255,0.7);
            position: fixed;
            z-index: +100 !important;
            width: 100%;
            height:100%;
        }

    .ajax-loader img {
        position: relative;
        top:50%;
        left:50%;
    }
    </style>
</head>
