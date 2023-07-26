@extends('layouts.master')

@section('content')
@auth()
    @php
        $expiry_date = auth()->user()->expiry_date;
        $dt =  Carbon\Carbon::now()->toDateString();
    @endphp
@endauth
    <main class="d-flex flex-column justify-content-start flex-grow-1 ">



        <div>

            <div class="container-fluid">

                <div class="row">



                    <!-- Sidebar Start -->

                    <div class="page_sidebar dsd">



                        <div class="page_sidebar_header position-sticky">

                            <div class="page_sidebar_header_image">

                                <a href="javascript:void(0)">

                                    <img alt="{{ $store->name}}" loading="lazy" src='{{ asset("uploads/$store->image")}}'/>

                                </a>

                            </div>

                            <a href="javascript:void(0)" class="page_sidebar_header_title">



                                {{ $store->name}}

                            </a>
                            <span class="d-block text-primary">



                                {{ $store->address}}

                            </span>
                            <!-- <a href="#" class="d-block btn px-2 py-0 my-2 text-nowrap font_80 text-capitalize text-secondary">View Privacy Policy <i class="fas fa-chevron-right"></i></a> -->

                            <!-- <a href="#" class="d-block btn px-2 py-0 text-nowrap font_80 text-capitalize text-primary"><i class="far fa-check-circle"></i> 100% satisfaction guarantee <i class="fas fa-chevron-right"></i></a> -->

                        </div>



                        <ul class="page_sidebar_menu d-none d-md-block">

                            @forelse($categories as $category)
							@php
                                $sub_categories = $category->children->toArray();
                                $sub_categories_count = count($sub_categories);
                            @endphp
                            <li @if($sub_categories_count>0 ) class="dropdown" @endif>

                                <a href='{{url("category-products/$category->slug")}}' @if($sub_categories_count>0 ) class="dropdown-toggle" data-bs-toggle="dropdown" @endif>{{ $category->title}} </a>
								@if($sub_categories_count >0 )
								<ul class="dropdown-menu">
                                    @foreach($category->children as $sub_cat)
									<li><a class="dropdown-item" href="{{url("category-products/$sub_cat->slug")}}">{{ $sub_cat->title}}</a></li>
									@endforeach
								</ul>
								@endif
                            </li>

                            @empty

                            <li>

                                No category in this store

                            </li>

                            @endforelse



                        </ul>

                    </div>

                    <!-- Sidebar Start -->



                    <!-- Content Start -->

                    <div class="page_content py-3 py-lg-4 px-3 px-lg-5">
                        @guest()
						<!-- ad Start-->
                            <div class="row py-3">
                                <div class="col-md-4 text-center">
                                    <img alt="Ad image" src="{{ asset('frontend/images/480_320.jpg')}}"/>
                                </div>
                                <div class="col-md-4 text-center">
                                    <img alt="Ad image" src="{{ asset('frontend/images/480_320.jpg')}}"/>
                                </div>
                                <div class="col-md-4 text-center">
                                    <img alt="Ad image" src="{{ asset('frontend/images/480_320.jpg')}}"/>
                                </div>
                            </div>
						<!-- ad end-->
                        @endguest()

                        @auth()
                            @if($expiry_date == null || $expiry_date < $dt )
                                <div class="row py-3">
                                    <div class="col-md-4 text-center">
                                        <img alt="Ad image" src="{{ asset('frontend/images/480_320.jpg')}}"/>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <img alt="Ad image" src="{{ asset('frontend/images/480_320.jpg')}}"/>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <img alt="Ad image" src="{{ asset('frontend/images/480_320.jpg')}}"/>
                                    </div>
                                </div>
                            @endif
                        @endauth
                        <div class="row">

                            <div class="col-md-12">
                                <form action="{{ route('search-product')}}" method="get" id="header_search" class="d-lg-none d-xl-flex">

                                    @csrf

                                    <div class="input-group p-0">
                                        <input type="text" name="title" required class="form-control" placeholder="Search">

                                        <div class="input-group-text">

                                            <button type="submit" class="btn"   ><i class="fas fa-search"></i></button>

                                        </div>

                                    </div>

                                </form>
                            </div>

                            <!-- <div class="col-md-12 mb-3">

                                <h3 class="cat_main_title mb-0">Shop By Store</h3>

                            </div>



                            <div class="col-md-12">

                                <a class="shop_banner" href="#" style="width: 100%;">

                                    <img alt="" src='{{ asset("uploads/$store->store_banner")}}' style="width: 100%;"/>

                                </a>

                            </div> -->

                            @forelse($categories as $category)

                            <!-- Category products Start -->

                            <div class="col-md-12 mt-5">

                                <!-- Products Slider Start -->

                                <div class="swiper productsSwiper">



                                    <div class="d-flex justify-content-between align-items-center mb-5">

                                        <!-- Category info Start -->

                                        <div class="productsSwiper_head ">

                                            <h4 class="cat_title">{{$category->title}}</h4>

                                        </div>

                                         <!-- Category info End -->



                                        <!-- Buttons Start -->

                                        <div class="productsSwiperNavigate">

                                            <a href='{{url("category-products/$category->slug")}}' class="btn px-2 py-2 text-nowrap">View more <i class="fas fa-chevron-right"></i></a>



                                            <!-- Nav Buttons Start -->

                                            <div class="productsSwiper_button productsSwiper_prev d-none d-md-inline-block"><i class="fas fa-chevron-left"></i></div>

                                            <div class="productsSwiper_button productsSwiper_next d-none d-md-inline-block"><i class="fas fa-chevron-right"></i></div>

                                            <!-- Nav Buttons End -->

                                        </div>

                                        <!-- Buttons End -->

                                    </div>



                                    <div class="swiper-wrapper">

                                        @forelse( $category->cat_products as $product)

                                            <!-- slide Start-->

                                            <div class="swiper-slide h-auto">

                                                <div class="block_type_product d-flex flex-column h-100">

                                                    <div class="block_type_product_wrapper position-relative">

                                                        <a href="#" class="stretched-link"></a>



                                                        <div class="image_wrapper mb-2">

                                                            <img loading="lazy" alt="" src='{{ $product->image }}'/>

                                                        </div>

                                                        <div class="block_content">

                                                            <h5 class="cat_product_price">${{$product->price}}</h5>

                                                            <!-- <div class="cat_product_labels">

                                                                <span class="cat_product_labels_badge">{{$product->name}}</span>

                                                            </div> -->

                                                            <h5 class="cat_product_title">{{$product->name}}</h5>

                                                            <span class="cat_product_weight">{{$product->size}}</span>

                                                        </div>

                                                    </div>

                                                    <a class="cat_product_btn_add mt-auto" href="javascript:void(0)" data-id="{{ $product->id }}">Add To Cart <i class="fas fa-shopping-cart ms-2"></i></a>

                                                </div>

                                            </div>

                                            <!-- slide End-->

                                        @empty



                                        @endforelse

                                    </div>

                                </div>

                                <!-- Products Slider End -->

                            </div>

                            <!-- Category products End -->

							<!-- ad Start-->
                            @guest()
                                <div class="col-md-12 text-center  mt-5">
                                    <img alt="Ad image" src="{{ asset('frontend/images/980_120.jpg')}}"/>
                                </div>
                            @endguest()

                            @auth()
                                @if($expiry_date == null || $expiry_date < $dt )

                                <div class="col-md-12 text-center  mt-5">
                                    <img alt="Ad image" src="{{ asset('frontend/images/980_120.jpg')}}"/>
                                </div>
                                @endif
                            @endauth
							<!-- ad end-->

                            @empty

                            @endforelse



                        </div>

                    </div>

                    <!-- Content End -->



                </div>

            </div>

        </div>



<!-- Newsletter Start-->

<!-- @include('partials.newsletter')  -->

<!-- Newsletter End-->





</main>





@endsection

@section('scripts')

<!-- Slider Start -->

<script>

        var swiper = new Swiper(".productsSwiper", {

            watchSlidesProgress: true,

            height: 'auto',

            mousewheel: false,

            slidesPerView: 1,

            spaceBetween: 30,

            navigation: {

                    nextEl: ".productsSwiper_next",

                    prevEl: ".productsSwiper_prev",

            },

            breakpoints: {

                360: {

                    slidesPerView: 2,

                    spaceBetween: 20,

                },

                640: {

                    slidesPerView: 3,

                    spaceBetween: 20,

                },

                768: {

                    slidesPerView: 3,

                    spaceBetween: 25,

                },

                1024: {

                    slidesPerView: 4,

                    spaceBetween: 30,

                },

                1200: {

                    slidesPerView: 5,

                    spaceBetween: 30,

                },

                1700: {

                    slidesPerView: 6,

                    spaceBetween: 30,

                },

            },

        });



        function AddToCompare(id){

            $.ajax({

                type:'GET',

                url: '/add-to-cart/'.id,

                success:function(result){

                    console.log(result);

                },

                error:function(result){

                    console.log(result);

                }

            });

        }



    </script>

    <!-- Slider End -->

@endsection
