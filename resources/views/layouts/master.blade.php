@php
$setting = \App\Models\Setting::pluck('value','name')->toArray();
$copy_right = isset($setting['copy_right']) ? $setting['copy_right'] : '';
$favicon = isset($setting['favicon']) ? $setting['favicon'] : 'favicon.png';
$ip=$_SERVER['REMOTE_ADDR'];
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
$zipcode = 80001;
if(isset($details->postal)){
    $zipcode = $details->postal;    
}
if(\Session::get('post_code')==null){
    \Session::put(['post_code' => $zipcode]);
}else{
    $zipcode = \Session::get('post_code');
}
$stores = \App\Models\Store::join('zipcodes','zipcodes.id','=','shops.zipcode_id')
            ->where('zipcodes.zip_code',$zipcode)
            ->take(4)
            ->get();
$zipcodes = \App\Models\Zipcode::get();

@endphp
<!doctype html>
<html lang="en">

@include('partials.head')

<body class="d-flex flex-column min-vh-100 justify-content-center justify-content-md-between">


<!-- Header Start -->
@include('partials.header')
<!-- Header End -->

<main class="d-flex flex-column justify-content-start flex-grow-1 ">
<div class="ajax-loader">
  <img src="{{ asset('loader.gif') }}" class="img-responsive" />
</div>
    <!-- Hero Start-->
@yield('content')
    <!-- Colection Banners End-->

</main>

<!-- footer Start-->
@include('partials.footer')
<!-- footer End-->

 <!-- Js -->
<script src="{{ asset('frontend/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{ asset('frontend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('frontend/js/swiper-bundle.min.js')}}"></script>
<script src="{{ asset('frontend/js/select2.full.min.js')}}"></script>
<script src="{{ asset('frontend/js/main.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
/*function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}*/
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', autoDisplay: 'true', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<!-- Hero Slider Start -->
    <script>
        let check_home = `{{ Request::segment(1) }}`
        if(check_home==''){
            let store_count = `{{ count($stores) }}`
            if(store_count==0){
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: "Sorry! don't have any store in your location",
                    showConfirmButton: false,
                    timer: 3000
                }).then((result) => {
                    $("#modelStoreLocation").modal('show');;
                })
            }
        }
        

        var swiper = new Swiper(".heroSwiper", {
                mousewheel: false,
                slidesPerView: 1,
                spaceBetween: 0,
                effect: "fade",
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                loop: true,
                pagination: false,
                navigation: {
                    nextEl: ".heroSwiper_next",
                    prevEl: ".heroSwiper_prev",
                },
        });
    </script>
    <!-- Hero Slider End -->

    <!-- Categories Slider Start -->
    <script>
        
        var swiper = new Swiper(".categoriesSwiper", {
            mousewheel: true,
            slidesPerView: 1,
            spaceBetween: 30,
            autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
            },
            navigation: {
                    nextEl: ".categorySwiper_next",
                    prevEl: ".categorySwiper_prev",
            },
            breakpoints: {
                360: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 25,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
        /*function ChangeLocation(){
            $('#modelStoreLocation').show();
        }*/
		// In your Javascript (external .js resource or <script> tag)
		$(document).ready(function() {
			$('#postal_code').select2({
				dropdownParent: $('#modelStoreLocation'),
				width: 'resolve'
			});
		});
    
        function openComparisonAd(){
            let shop_id = document.getElementById('shop_id');
            if(shop_id.value==''){
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: "Please select store first",
                    showConfirmButton: true,
                    timer: 3000
                })
                return false;
            }
            $('#adsmodal').modal('show');
        }
        function onModalCloseSubmitForm(){
            $('#adsmodal').modal('hide')
            document.getElementById('compare_form').submit();
        }
    </script>
    <!-- Categories Slider End -->
@yield('scripts')
<div class="modal fade" id="modelStoreLocation" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
			<button type="button" class="btnStoreLocation_close" data-bs-dismiss="modal" aria-label="Close">
				<i class="fas fa-times"></i>
			</button>
            <div class="modal-header justify-content-center px-5">
                <h5 class="modal-title">Change Zip Code</h5>
            </div>
            <form method="post" action="{{ route('change-location') }}">
                @csrf
                <div class="modal-body">
                    <select class="form-control" id="postal_code" name="postal_code" style="width: 100%">
						  <option>Select a zip code</option>
                          @foreach($zipcodes as $code)
						  <option value="{{ $code->zip_code}}" @if($zipcode==$code->zip_code) selected @endif>{{ $code->zip_code}}</option>
                          @endforeach
						  <!-- <option value="zip2">zip 2</option>
						  <option value="zip3">zip 3</option>
						  <option value="zip4">zip 4</option>
						  <option value="zip5">zip 5</option> -->
					   </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Save Address</button>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="adsmodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
			<button type="button" class="btnStoreLocation_close" onclick="return onModalCloseSubmitForm()" aria-label="Close">
				<i class="fas fa-times"></i>
			</button>
            <div class="modal-header justify-content-center px-5">
                <h5 class="modal-title">Ads</h5>
            </div>
         
                <div class="modal-body">
                    <img alt="Ad image" src="{{ asset('frontend/images/480_320.jpg')}}"/>
                </div>
                <div class="modal-footer d-flex flex-nowrap">
                    <button type="button" class="btn btn-primary w-100" onclick="return onModalCloseSubmitForm()" aria-label="Close">Close</button>
					<a href="#" class="btn btn-primary w-100">Open</a>
                </div>

        </div>
    </div>
</div>

</body>

</html>
