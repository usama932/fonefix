<?php
                     $store_id = session()->get('store_id');
                    $categorys = \App\Models\Category::where('store_id',$store_id)->count();
                    $stores = \App\Models\Store::where('id',$store_id)->count();
                    $products = \App\Models\Product::where('shop_id',$store_id)->count();


                ?>


@extends('admin.layouts.products')
@section('title',$title)
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Dashboard-->
		<!--begin::Row-->

		<!--end::Row-->
		<!--begin::Row-->

		<div id="cardbody" class="card card-custom">



			<div class="card-spacer mt-3">
				<!--begin::Row-->

				<div class="row m-0">
					<div class="col bg-light-warning px-6 py-8 rounded-xl mr-3 mb-7">
						<span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
							<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24" />
									<path d="M10.5,5 L20.5,5 C21.3284271,5 22,5.67157288 22,6.5 L22,9.5 C22,10.3284271 21.3284271,11 20.5,11 L10.5,11 C9.67157288,11 9,10.3284271 9,9.5 L9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,13 L20.5,13 C21.3284271,13 22,13.6715729 22,14.5 L22,17.5 C22,18.3284271 21.3284271,19 20.5,19 L10.5,19 C9.67157288,19 9,18.3284271 9,17.5 L9,14.5 C9,13.6715729 9.67157288,13 10.5,13 Z" fill="#000000" />
									<rect fill="#000000" opacity="0.3" x="2" y="5" width="5" height="14" rx="1" />
								</g>
							</svg>
							<!--end::Svg Icon-->
						</span>
						<a href="" class="text-warning font-weight-bold font-size-h6">Categories: </a>
						<a href="#" class="text-warning font-weight-bold "> {{$categorys}}</a>
						</svg>
					</div>


					<div class="col bg-light-primary px-6 py-8 rounded-xl mr-3 mb-7">
						<span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
							<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24" />
									<path d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z" fill="#000000" />
									<rect fill="#000000" opacity="0.3" x="2" y="4" width="5" height="16" rx="1" />
								</g>
							</svg>
							<!--end::Svg Icon-->
						</span>
						<a href="" class="text-primary font-weight-bold font-size-h6 mt-2">Products:</a>
						<a href="#" class="text-primary font-weight-bold  mt-2">{{$products}}</a>
					</div>

				


				</div>
				<!--end::Row-->
				<!--begin::Row-->
				<!--end::Row-->
			</div>





		</div>

		<!--end::Row-->
		<!--end::Dashboard-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
@endsection