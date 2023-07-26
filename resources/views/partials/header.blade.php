<!-- Offcanvas Menu Start -->
<div class="offcanvas offcanvas-start offcanvas-lg" data-bs-scroll="true" tabindex="-1" id="offcanvasMenu">
   <div class="offcanvas-header">
      <a href="{{ url('/') }}">
      <img alt="Cheapsst" src="{{ asset('frontend/images/logo.png') }}" width="150" />
      </a>
      <button type="button" class="btn_offcanvas_close" data-bs-dismiss="offcanvas" aria-label="Close">
      <i class="fas fa-times-circle"></i>
      </button>
   </div>
   <div class="offcanvas-body p-0">
      <ul class="navbar-nav">
         <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <span class="nav-link-title">Stores</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-multicol">
               @foreach($stores as $store)
               <li >
                  <a class="dropdown-item" href="{{url("products/$store->slug")}}">
                  <span class="nav-link-title">{{ $store->name }}</span>
                  </a>
               </li>
               @endforeach
            </ul>
         </li>
         <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">

                <span class="nav-link-title">Deals</span>

            </a>

            <ul class="dropdown-menu">

                <li>

                    <a class="dropdown-item" href="#">

                        <span class="nav-link-title">Deals 1</span>

                    </a>

                </li>

                <li>

                    <a class="dropdown-item" href="#">

                        <span class="nav-link-title">Deals 2</span>

                    </a>

                </li>

                <li>

                    <a class="dropdown-item" href="#">

                        <span class="nav-link-title">Deals 2</span>

                    </a>

                </li>

                <li>

                    <a class="dropdown-item" href="#">

                        <span class="nav-link-title">Deals 3</span>

                    </a>

                </li>

                <li>

                    <a class="dropdown-item" href="#">

                        <span class="nav-link-title">Deals 4</span>

                    </a>

                </li>

            </ul>

            </li> -->
         <li class="nav-item">
            <a class="nav-link" href="{{route('about-us')}}">
            <span class="nav-link-title">About Us</span>
            </a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="{{route('contact-us')}}">
            <span class="nav-link-title">Contact Us</span>
            </a>
         </li>
         <!--<li class="nav-item blink_me">
            <a class="nav-link" href="javascript:void(0)" onclick="return ChangeLocation()">

                <span class="nav-link-title"> {{ \Session::get('post_code') }}</span>

            </a>

            </li>-->
      </ul>
   </div>
</div>
<!-- Offcanvas Menu End -->
<!-- Offcanvas Account Menu Start -->
<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasAccount">
   <div class="offcanvas-header">
      <h4 class="mb-0">Account</h4>
      <button type="button" class="btn_offcanvas_close" data-bs-dismiss="offcanvas" aria-label="Close">
      <i class="fas fa-times-circle"></i>
      </button>
   </div>
   <div class="offcanvas-body">
      <ul class="navbar-nav">
         <li class="nav-item">
            <a class="nav-link" href="{{ url('/login')}}">
            <span class="nav-link-title">Sign in</span>
            </a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="{{ url('register') }}">
            <span class="nav-link-title">Create Account</span>
            </a>
         </li>
         <!-- <li class="nav-item">
            <a class="nav-link d-flex justify-content-md-between" href="#">

                <span class="nav-link-title">Orders</span>

                <span class="font_80">Track + manage</span>

            </a>

            </li> -->
         <li class="nav-item">
            <a class="nav-link d-flex justify-content-md-between" href="{{ url('/') }}">
            <img alt="Cheapsst" src="{{ asset('frontend/images/logo.png')}}" width="150" />
            </a>
         </li>
         <!-- <li class="nav-item">
            <a class="nav-link d-flex justify-content-md-between" href="#">

                <span class="nav-link-title">Gift Cards</span>

                <span class="font_80">Check balances</span>

            </a>

            </li>

            <li class="nav-item">

            <a class="nav-link d-flex justify-content-md-between" href="#">

                <span class="nav-link-title">RedCard</span>

                <span class="font_80">Apply now</span>

            </a>

            </li>

            <li class="nav-item">

            <a class="nav-link d-flex justify-content-md-between" href="#">

                <span class="nav-link-title">Registry</span>

                <span class="font_80">Create a registry</span>

            </a>

            </li>

            <li class="nav-item">

            <a class="nav-link d-flex justify-content-md-between" href="#">

                <span class="nav-link-title">My Store</span>

                <span class="font_80">Lake Genev</span>

            </a>

            </li> -->
      </ul>
   </div>
</div>
<!-- Offcanvas Account Menu End -->
<!-- Header Start -->
<header class="sticky-top">
   <nav class="navbar navbar-expand-lg py-2 py-lg-0 px-2 px-lg-5">
      <div class="container-fluid">
         <a class="navbar-brand" href="{{ url('/') }}">
         <img alt="Cheapsst" src="{{ asset('frontend/images/logo.png')}}" width="150" />
         </a>
         <div class="collapse navbar-collapse" id="main_nav">
            <ul class="navbar-nav">
               <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                  <span class="nav-link-title">Stores</span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-multicol">
                     @foreach($stores as $store)
                     <li class="col-md-6">
                        <a class="dropdown-item" href='{{url("products/$store->slug")}}'>
                        <span class="nav-link-title">{{ $store->name }}</span>
                        </a>
                     </li>
                     @endforeach
                  </ul>
               </li>
               <!-- <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">

                      <span class="nav-link-title">Deals</span>

                  </a>

                  <ul class="dropdown-menu">

                      <li>

                          <a class="dropdown-item" href="#">

                              <span class="nav-link-title">Deals 1</span>

                          </a>

                      </li>

                      <li>

                          <a class="dropdown-item" href="#">

                              <span class="nav-link-title">Deals 2</span>

                          </a>

                      </li>

                      <li>

                          <a class="dropdown-item" href="#">

                              <span class="nav-link-title">Deals 2</span>

                          </a>

                      </li>

                      <li>

                          <a class="dropdown-item" href="#">

                              <span class="nav-link-title">Deals 3</span>

                          </a>

                      </li>

                      <li>

                          <a class="dropdown-item" href="#">

                              <span class="nav-link-title">Deals 4</span>

                          </a>

                      </li>

                  </ul>

                  </li> -->
               <li class="nav-item">
                  <a class="nav-link" href="{{route('about-us')}}">
                  <span class="nav-link-title">About Us</span>
                  </a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="{{route('contact-us')}}">
                  <span class="nav-link-title">Contact Us</span>
                  </a>
               </li>
               <!--<li class="nav-item blink_me">
                  <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modelStoreLocation">

                      <span class="nav-link-title" style="color: #d63384 !important; font-size:20px !important">{{ \Session::get('post_code') }}</span>

                  </a>

                  </li>-->
            </ul>
         </div>
         <!-- navbar-collapse.// -->
         <div class="nav_icons d-flex align-items-center flex-wrap justify-content-between">
            <div class="google_translate_element_wrapper order-1 d-inline-flex">
               <div id="google_translate_element"></div>
            </div>
            <a class="btn_store_loc mx-2 order-3 d-flex align-items-center" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modelStoreLocation">
            <i class="fas fa-store me-1"></i>
            <span class="btn_store_loc_text me-1">Zip Code</span>
            <span class="btn_store_loc_code">{{ \Session::get('post_code') }}</span>
            </a>
            <!-- <div class="header_search_area mx-md-2 order-2 order-md-3 d-lg-none d-xl-flex">
               <form action="{{ route('search-product')}}" method="get" id="header_search" class="d-lg-none d-xl-flex" @if(count($stores)==0) disabled=true @endif>

                   @csrf

                   <div class="input-group p-0">

                       <input type="text" name="title" required class="form-control" placeholder="Search" @if(count($stores)==0) disabled=true @endif>

                       <div class="input-group-text">

                           <button type="submit" class="btn"  @if(count($stores)==0) disabled=true @endif><i class="fas fa-search"></i></button>

                       </div>

                   </div>

               </form>
               -->
         </div>
         <!-- <div class="header_user mx-1 mx-sm-2">
            <button class="btn p-0 d-flex align-items-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAccount">

                <i class="fas fa-user me-2"></i> <span class="d-none d-lg-inline-block">Sign In</span>

            </button>

            </div> -->
            <a href="{{ url('cart')}}" class="btn btn_compare mx-1 mx-md-2 ms-auto order-4 d-inline-flex" title="Click here to compare">
                <!--<i class="fas fa-balance-scale"></i>-->
                <i class="fas fa-shopping-cart"></i>
                <span class="position-absolute top-0 start-100 translate-middle rounded-pill badge rounded-circle" >
                @php $total = 0 @endphp
                <!-- @foreach((array) session('cart') as $id => $details)
                    @php
                        print_r($id);
                    $total += $details['quantity'] @endphp



                    @endforeach -->
                @php
                if((array) session('cart')){
                $total = count((array) session('cart'));
                }
                @endphp
                {{ $total }}
                </span>
            </a>
         <button class="navbar-toggler ms-3 order-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
         <i class="fas fa-bars"></i>
         </button>
         @auth
         @if (auth()->user()->is_admin == 1)
             <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 underline btn btn_compare mx-1 mx-md-2 ms-auto order-4 d-inline-flex">Dashboard</a>
         @else
             <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 underline btn btn_compare mx-1 mx-md-2 ms-auto order-4 d-inline-flex">Dashboard</a>
         @endif

     @else
         <a href="{{ route('login') }}" class="text-sm text-gray-700 underline btn btn_compare mx-1 mx-md-2 ms-auto order-4 d-inline-flex">Login</a>

     @endauth
      </div>

      </div>
   </nav>
</header>
<!-- Header End -->
