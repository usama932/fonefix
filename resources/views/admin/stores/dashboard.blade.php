@extends('admin.layouts.master')
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
						<a href="{{route('members.index')}}" class="text-warning font-weight-bold font-size-h6">Members: </a>
						<a href="#" class="text-warning font-weight-bold "> </a>
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
						<a href="{{route('partners.index')}}" class="text-primary font-weight-bold font-size-h6 mt-2">Partners:</a>
						<a href="#" class="text-primary font-weight-bold  mt-2"></a>
					</div>

					<div class="col bg-light-danger px-6 py-8 rounded-xl mr-3 mb-7">
						<span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
							<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<polygon points="0 0 24 0 24 24 0 24" />
									<path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
									<path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
								</g></svg>
								<!--end::Svg Icon-->
						</span>
						<a href="{{route('executives.index')}}" class="text-danger font-weight-bold font-size-h6 mt-2">Executives:</a>
						<a href="#" class="text-danger font-weight-bold  mt-2"></a>
					</div>


					<div class="col bg-light-success px-6 py-8 rounded-xl mr-3 mb-7">
						<span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
							<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24" />
									<path d="M21,10 L3,10 L3,5 C3,4.44771525 3.44771525,4 4,4 L20,4 C20.5522847,4 21,4.44771525 21,5 L21,10 Z M16,6 C15.4477153,6 15,6.44771525 15,7 C15,7.55228475 15.4477153,8 16,8 C16.5522847,8 17,7.55228475 17,7 C17,6.44771525 16.5522847,6 16,6 Z M8,6 C8.55228475,6 9,6.44771525 9,7 C9,7.55228475 8.55228475,8 8,8 C7.44771525,8 7,7.55228475 7,7 C7,6.44771525 7.44771525,6 8,6 Z M21,11 L21,21 C21,21.5522847 20.5522847,22 20,22 L4,22 C3.44771525,22 3,21.5522847 3,21 L3,11 L21,11 Z M8,13 C7.44771525,13 7,13.5223345 7,14.1666667 L7,18.8333333 C7,19.4776655 7.44771525,20 8,20 L16,20 C16.5522847,20 17,19.4776655 17,18.8333333 L17,14.1666667 C17,13.5223345 16.5522847,13 16,13 L8,13 Z" fill="#000000" />
									<path d="M6,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L5,4 C5,3.44771525 5.44771525,3 6,3 Z M16,3 L18,3 C18.5522847,3 19,3.44771525 19,4 L15,4 C15,3.44771525 15.4477153,3 16,3 Z" fill="#000000" opacity="0.3" />
								</g>
							</svg>
							<!--end::Svg Icon-->
						</span>
						<a href="{{route('events.index')}}" class="text-danger font-weight-bold font-size-h6 mt-2">Events:</a>
						<a href="#" class="text-danger font-weight-bold  mt-2"></a>
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