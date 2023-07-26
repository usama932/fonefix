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
	<!-- ad Start-->
    @guest()
        <div class="container py-3">
            <div class="row">
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
        </div>
	<!-- ad end-->
    @endguest()

    @auth()
        @if($expiry_date == null || $expiry_date < $dt )
            <div class="container py-3">
                <div class="row">
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
            </div>
        @endif
    @endauth
    <div class="container-fluid">

        <div class="row">

            <!-- Content Start -->

            <div class="py-3 py-lg-4 px-3 px-lg-5">

                <div class="row">

                    <div class="col-md-12 mb-3">

                        <h3 class="cat_main_title mb-0">Products</h3>

                    </div>

                    <!-- <div class="col-md-12">

                        <a class="shop_banner" href="#" style="width: 100%;">

                            <?php $image = ($store!='')?$store->store_banner:'stopshop.png'; ?>

                            <img alt="" src='{{ asset("uploads/$image")}}' style="width: 100%;"/>

                        </a>


                    </div> -->

                    <!-- Category products Start -->

                    <div class="col-md-12 mt-5">

                        <div class="row g-3 g-md-4 row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6">

                            @forelse( $products as $product)

                                <!-- slide Start-->

                                <div class="col">

                                    <div class="block_type_product h-100 d-flex flex-column">

                                        <div class="block_type_product_wrapper position-relative">

                                            <a href="#" class="stretched-link"></a>

                                            <div class="image_wrapper mb-2">

                                                <img loading="lazy" alt="" src='{{ $product->image }}'/>

                                            </div>

                                            <div class="block_content">

                                                <h5 class="cat_product_price">${{ $product->price }}</h5>



                                                <h5 class="cat_product_title">{{ $product->name }}</h5>

                                                <span class="cat_product_weight">{{ $product->size }}</span>

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

                    <!-- Category products End -->

                </div>

            </div>

            <!-- Content End -->

        </div>

    </div>

	<!-- ad Start-->
	<div class="container py-3">
		<div class="row">
			<div class="col-md-12 text-center  mt-5">
				<img alt="Ad image" src="{{ asset('frontend/images/980_120.jpg')}}"/>
			</div>
		</div>
	</div>
	<!-- ad end-->

</div>



<!-- Newsletter Start-->

<!-- @include('partials.newsletter')  -->

<!-- Newsletter End-->



</main>





@endsection

@section('scripts')



@endsection
