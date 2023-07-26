@extends('layouts.master')
@section('content')
@auth()
    @php
        $expiry_date = auth()->user()->expiry_date;
        $dt =  Carbon\Carbon::now()->toDateString();
    @endphp
@endauth
<main class="d-flex flex-column justify-content-start flex-grow-1 ">

<!-- Hero Start-->
<div class="hero">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 p-0">
                <div class="swiper heroSwiper">
                    <div class="swiper-wrapper">

                        @forelse ($sliders as $slider)
                            <!-- Slide item -->
                            <div class="swiper-slide text-center">
                                <img alt="" src='{{ asset("uploads/$slider->image")}}'/>
                            </div>
                        @empty
                            <!-- Slide item -->
                            <div class="swiper-slide text-center">
                                <img alt="" src="{{ asset('frontend/images/hero_bg.jpg')}}"/>
                            </div>
                        @endforelse
                    </div>

                    <div class="heroSwiper_button heroSwiper_next"><i class="fas fa-chevron-right"></i></div>
                    <div class="heroSwiper_button heroSwiper_prev"><i class="fas fa-chevron-left"></i></div>
                  </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero End-->


@guest()
<div class="py-3">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<img alt="Ad image" src="{{ asset('frontend/images/980_120.jpg')}}"/>
			</div>
		</div>
	</div>
</div>
@endguest

@auth()
@if($expiry_date == null || $expiry_date < $dt )
<div class="py-3">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<img alt="Ad image" src="{{ asset('frontend/images/980_120.jpg')}}"/>
			</div>
		</div>
	</div>
</div>
@endif
@endauth
<!-- Stores Start-->
<div class="stores section_space">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="text-uppercase mb-5">Shop by Stores</h3>
            </div>
        </div>

        <div class="row g-3  row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-5 row-cols-xxl-6">
            @foreach($stores as $store)
            <!-- Store block Start-->
            <div class="col">
                <div class="block_type_1">
                    <a href='{{ route("products",[$store->slug]) }}' class="stretched-link"></a>
                    <div class="image_wrapper">
                        <img alt="sprouts farmers market" src='{{ asset("uploads/$store->image")}}' alt="{{$store->name}}"/>
                    </div>
                    <div class="block_content">
                        <h4 class="block_type_1_title text-danger">{{$store->address}}</h4>
                        <!-- <h4 href="shop_by_store.html" class="block_type_1_type">Delivery</h4> -->
                    </div>
                </div>
            </div>
            <!-- Store block end -->
            @endforeach

        </div>
    </div>
</div>
<!-- Stores End-->

@guest()
<div class="py-3">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<img alt="Ad image" src="{{ asset('frontend/images/980_120.jpg')}}"/>
			</div>
		</div>
	</div>
</div>
@endguest()

@auth()
    @if($expiry_date == null || $expiry_date < $dt )
        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img alt="Ad image" src="{{ asset('frontend/images/980_120.jpg')}}"/>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth

<!-- Categories Start-->
<!--<div class="categories section_space">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-md-12 text-center">-->
<!--                <h3 class="text-uppercase mb-3">Shop by Category</h3>-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="row">-->
<!--            <div class="col-md-12">-->
<!--                <div class="swiper categoriesSwiper pt-5">-->
<!--                    <div class="swiper-wrapper">-->
<!--                        @foreach($categories as $category)-->
                        <!-- slide Start-->
                        <!--<div class="swiper-slide">-->
                        <!--    <div class="block_type_2">-->
                        <!--        <a href='{{ url("category-products/$category->slug") }}' class="stretched-link"></a>-->
                        <!--        <div class="image_wrapper mb-2">-->
                        <!--            <img alt="sprouts farmers market" src='{{ asset("uploads/$category->image")}}'/>-->
                        <!--        </div>-->
                        <!--        <div class="block_content">-->
                        <!--            <h4 class="block_type_2_title">{{ $category->title }}</h4>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!-- slide End-->
<!--                        @endforeach-->
<!--                    </div>-->

<!--                    <div class="categorySwiper_button categorySwiper_next"><i class="fas fa-chevron-right"></i></div>-->
<!--                    <div class="categorySwiper_button categorySwiper_prev"><i class="fas fa-chevron-left"></i></div>-->

<!--                </div>-->
<!--            </div>-->

<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- Categories End-->

<!-- Deals Start-->
<!-- <div class="deals section_space">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="text-uppercase mb-5">Shop by Deals</h3>
            </div>
        </div>

        <div class="row mb-5 g-3">
            <div class="col-md-6">
                <label for="filterBy" class="form-label text-uppercase">*Filter By:</label>
                <select class="form-select" id="filterBy">
                    <option selected>Select Option</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="zipCode" class="form-label text-uppercase">*Zip Code:</label>
                <select class="form-select" id="zipCode">
                    <option selected>Select Option</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
        </div>

        <div class="row g-3">

            <div class="col-6 col-sm-6 col-md-4">
                <div class="block_type_3">
                    <div class="icon_wrapper" style="background-color: #E6CE32;">
                        <a href="#">
                            Beverages
                        </a>
                    </div>
                    <div class="block_content">
                        <a href="#" class="block_type_1_title">Deals On Beverages</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-6 col-md-4">
                <div class="block_type_3">
                    <div class="icon_wrapper" style="background-color: #E9665C;">
                        <a href="#">
                            Snacking
                        </a>
                    </div>
                    <div class="block_content">
                        <a href="#" class="block_type_1_title">Deals On Snacking</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-6 col-md-4">
                <div class="block_type_3">
                    <div class="icon_wrapper" style="background-color: #F59131;">
                        <a href="#">
                            Cofee
                        </a>
                    </div>
                    <div class="block_content">
                        <a href="#" class="block_type_1_title">Deals On Cofee</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-6 col-md-4">
                <div class="block_type_3">
                    <div class="icon_wrapper" style="background-color: #A87D6A;">
                        <a href="#">
                            Pantry
                        </a>
                    </div>
                    <div class="block_content">
                        <a href="#" class="block_type_1_title">Deals On Pantry Items</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-6 col-md-4">
                <div class="block_type_3">
                    <div class="icon_wrapper" style="background-color: #6B200D;">
                        <a href="#">
                            Wine, Beer & Liquor
                        </a>
                    </div>
                    <div class="block_content">
                        <a href="#" class="block_type_1_title">Deals On Wine, Beer & Liquor</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-6 col-md-4">
                <div class="block_type_3">
                    <div class="icon_wrapper" style="background-color: #CC0000;">
                        <a href="#">
                            All Deals
                        </a>
                    </div>
                    <div class="block_content">
                        <a href="#" class="block_type_1_title">All Deals</a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div> -->
<!-- Deals End-->

<!-- Newsletter Start-->
<!-- @include('partials.newsletter')  -->
<!-- Newsletter End-->


</main>


@endsection
@section('scripts')

@endsection
