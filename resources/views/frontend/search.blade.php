
<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 11 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->
<head>
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
    <meta charset="utf-8" />
    <title>{{$site_title}} | Home</title>
    <meta name="description" content="List columns 1 example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{asset("demo8/css/plugins.bundle.css?v=7.2.9")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("demo8/css/prismjs.bundle.css?v=7.2.9")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("demo8/css/style.bundle.css?v=7.2.9")}}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{$favicon}}" />
    <!-- Hotjar Tracking Code for keenthemes.com -->
    <script>(function(h,o,t,j,a,r){ h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)}; h._hjSettings={hjid:1070954,hjsv:6}; a=o.getElementsByTagName('head')[0]; r=o.createElement('script');r.async=1; r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv; a.appendChild(r); })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');</script>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed subheader-enabled page-loading">

<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Page-->
    <div class="d-flex flex-row flex-column-fluid page">
        <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            <!--begin::Header Mobile-->
            <div id="kt_header_mobile" class="header-mobile">
                <!--begin::Logo-->
                <a href="#">
                    <img alt="Logo" src="{{asset("$logo")}}" class="max-h-30px" />
                </a>
                <!--end::Logo-->
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <button class="btn p-0 burger-icon burger-icon-left ml-4" id="kt_header_mobile_toggle">
                        <span></span>
                    </button>
                    <button class="btn p-0 ml-2" id="kt_header_mobile_topbar_toggle">
								<span class="svg-icon svg-icon-xl">
									<!--begin::Svg Icon | path:/metronic/theme/html/demo8/dist/assets/media/svg/icons/General/User.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24" />
											<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
											<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
										</g>
									</svg>
                                    <!--end::Svg Icon-->
								</span>
                    </button>
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Header Mobile-->
            <!--begin::Header-->
            <div id="kt_header" class="header header-fixed">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Left-->
                    <div class="d-none d-lg-flex align-items-center mr-3">
                        <!--begin::Logo-->
                        <a href="#" class="mr-20">
                            <img alt="Logo" src="{{asset("$logo")}}" class="logo-default bg-white  max-h-75px" style="border-radius: 5px" />
                        </a>
                        <!--end::Logo-->
                    </div>
                    <!--end::Left-->
                    <!--begin::Topbar-->
                    <div class="topbar topbar-minimize">




                        <!--begin::Quick panel-->

                        <!--begin::User-->

                        <!--end::User-->
                    </div>
                    <!--end::Topbar-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Header-->
            <!--begin::Header Menu Wrapper-->

            <!--end::Header Menu Wrapper-->
            <!--begin::Container-->
            <div class="d-flex flex-row flex-column-fluid container">
                <!--begin::Content Wrapper-->
                <div class="main d-flex flex-column flex-row-fluid">
                    <!--begin::Subheader-->
                    <div class="subheader py-2 py-lg-4" id="kt_subheader">
                        <div class="w-100 d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                            <!--begin::Details-->
                            <div class="d-flex align-items-center flex-wrap mr-2">
                                <!--begin::Title-->
                                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Repair Status</h5>
                                <!--end::Title-->
                                <!--begin::Separator-->
                                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Search Form-->
                                <div class="d-flex align-items-center" id="kt_subheader_search">
{{--                                    <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Search</span>--}}
                                    <form class="ml-5" action="{{route("search-job")}}">
                                        <div class="input-group input-group-sm input-group-solid"  style="max-width: 175px">
                                            <input type="text" class="form-control" required name="keyword" id="kt_subheader_search_form" placeholder="Search..." />
                                            <div class="input-group-append">
														<button type="submit" class="input-group-text">
															<span class="svg-icon">
																<!--begin::Svg Icon | path:/metronic/theme/html/demo8/dist/assets/media/svg/icons/General/Search.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24" />
																		<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																		<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
																	</g>
																</svg>
                                                                <!--end::Svg Icon-->
															</span>
                                                            <!--<i class="flaticon2-search-1 icon-sm"></i>-->
														</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--end::Search Form-->
                                <!--begin::Group Actions-->
                                <div class="d-flex- align-items-center flex-wrap mr-2 d-none" id="kt_subheader_group_actions">
                                    <div class="text-dark-50 font-weight-bold">
                                        <span id="kt_subheader_group_selected_rows">23</span>Selected:</div>
                                    <div class="d-flex ml-6">
                                        <div class="dropdown mr-2" id="kt_subheader_group_actions_status_change">
                                            <button type="button" class="btn btn-light-primary font-weight-bolder btn-sm dropdown-toggle" data-toggle="dropdown">Update Status</button>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-sm">
                                                <ul class="navi navi-hover pt-3 pb-4">
                                                    <li class="navi-header font-weight-bolder text-uppercase text-primary font-size-lg pb-0">Change status to:</li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" data-toggle="status-change" data-status="1">
																	<span class="navi-text">
																		<span class="label label-light-success label-inline font-weight-bold">Approved</span>
																	</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" data-toggle="status-change" data-status="2">
																	<span class="navi-text">
																		<span class="label label-light-danger label-inline font-weight-bold">Rejected</span>
																	</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" data-toggle="status-change" data-status="3">
																	<span class="navi-text">
																		<span class="label label-light-warning label-inline font-weight-bold">Pending</span>
																	</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" data-toggle="status-change" data-status="4">
																	<span class="navi-text">
																		<span class="label label-light-info label-inline font-weight-bold">On Hold</span>
																	</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <button class="btn btn-light-success font-weight-bolder btn-sm mr-2" id="kt_subheader_group_actions_fetch" data-toggle="modal" data-target="#kt_datatable_records_fetch_modal">Fetch Selected</button>
                                        <button class="btn btn-light-danger font-weight-bolder btn-sm mr-2" id="kt_subheader_group_actions_delete_all">Delete All</button>
                                    </div>
                                </div>
                                <!--end::Group Actions-->
                            </div>
                            <!--end::Details-->
                            <!--begin::Toolbar-->
                            <div class="d-flex align-items-center">
                                <!--begin::Button-->

                                <!--end::Button-->
                                <!--begin::Button-->
                                @auth
                                    <a href="{{route("admin.dashboard")}}" class="btn btn-light-primary font-weight-bold ml-2">Dashboard</a>

                                @else
                                    <a href="{{route("login")}}" class="btn btn-light-primary font-weight-bold ml-2">Login</a>

                                @endauth
                                <!--end::Button-->

                            </div>
                            <!--end::Toolbar-->
                        </div>
                    </div>
                    <!--end::Subheader-->
                    <div class="content flex-column-fluid" id="kt_content">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-xl-12">
                                <!--begin::Card-->
                                <div class="card card-custom gutter-b card-stretch">
                                    <!--begin::Body-->
                                    @if($user)
                                    <div class="card-body">
                                        <!--begin::Section-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Pic-->

                                            <!--end::Pic-->
                                            <!--begin::Info-->
                                            <div class="d-flex flex-column mr-auto">
                                                <!--begin: Title-->
                                                <a href="#" class="card-title text-hover-primary font-weight-bolder font-size-h5 text-dark mb-1">Job Detail</a>
                                                <span class="text-muted font-weight-bold">{{$user->customer->name ?? ""}}</span>
                                                <!--end::Title-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Toolbar-->

                                            <!--end::Toolbar-->
                                        </div>
                                        <!--end::Section-->
                                        <!--begin::Content-->

                                        <!--end::Content-->
                                        <!--begin::Text-->
                                        <table class="table">
                                            <tr>
                                                <th>User</th>
                                                <td>{{$user->customer->name ?? ""}}</td>
                                            </tr>

                                            <tr>
                                                <th>Phone</th>
                                                <td>{{$user->customer->phone ?? ""}}</td>
                                            </tr>
                                            <tr>
                                                <th>Date:</th>
                                                <td >

                              <span style="font-weight: 100">
									{{$user->created_at->toDayDateTimeString()}}
								</span>
                                                </td>
                                            </tr>

                                            <tr>

                                                <td>
                                                    <b>
                                                        Expected Delivery Date:
                                                    </b>
                                                </td>

                                            <tr>
                                                <td><b>Job sheet number:</b></td>
                                                <td>

                                                    #{{$user->id}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Brand:</b></td>
                                                <td>
                                                    {{$user->brand->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Device:</b></td>
                                                <td>

                                                    @if($user->device->type ==1)
                                                        Mobile Phones

                                                    @else
                                                        Laptop

                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Device Model:</b></td>
                                                <td>
                                                    {{$user->device->name}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Serial Number:</b>
                                                </td>
                                                <td>
                                                    {{$user->serial_number}}
                                                </td>
                                            </tr>
{{--                                            <tr>--}}
{{--                                                <td>   <b>Password:</b></td>--}}
{{--                                                <td>--}}


{{--                                                    {{$user->password}}--}}


{{--                                                </td>--}}
{{--                                            </tr>--}}
                                            <tr>
                                                <td >
                                                    <b>
                                                        Invoice No.:
                                                    </b>
                                                </td>
                                                <td>
                                                    {{$user->id}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    <b>
                                                        Estimated Cost:
                                                    </b>
                                                </td>
                                                <td>
                                                    <span class="display_currency" data-currency_symbol="true"> {{$user->cost}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    <b>
                                                        Status:
                                                    </b>
                                                </td>
                                                <td>
                                                    @if($user->status == 1)
                                                        Accepted
                                                    @elseif($user->status == 2)
                                                        Progressing
                                                    @else
                                                        Completed
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    <b>
                                                        Location:
                                                    </b>
                                                </td>
                                                <td>
                                                    {{$user->shop->name}}
                                                </td>
                                            </tr>
                                            {{--                  <tr>--}}
                                            {{--                      <td colspan="2">--}}
                                            {{--                          <b>--}}
                                            {{--                              Technician:--}}
                                            {{--                          </b>--}}
                                            {{--                      </td>--}}
                                            {{--                      <td>--}}

                                            {{--                      </td>--}}
                                            {{--                  </tr>--}}

                                            <tr>
                                                <td>
                                                    <b>
                                                        Product Configuration:
                                                    </b>
                                                </td>
                                                <td >

                                                    {{$user->product_configuration}}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>
                                                        Condition Of The Product:
                                                    </b>
                                                </td>
                                                <td >

                                                    {{$user->condition_of_product}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @if($user->status == 1)
                                                        <label class="label label-info label-inline mr-2">Accepted</label>
                                                    @elseif($user->status == 2)
                                                        <label class="label label-primary label-inline mr-2">Progressing</label>
                                                    @elseif($user->status == 3)
                                                        <label class="label label-success label-inline mr-2">Completed</label>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                        <!--end::Blog-->
                                    </div>
                                    <!--end::Body-->
                                    <!--begin::Footer-->

                                    <!--end::Footer-->
                                        @else
                                        @if( Request::get('keyword'))
                                        <div class="alert alert-danger">
                                            Not Record Found
                                        </div>
                                        @endif
                                    @endif

                                </div>
                                <!--end::Card-->
                            </div>

                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->

                        <!--end::Row-->

                    </div>
                    <!--end::Content-->
                </div>
                <!--begin::Content Wrapper-->
            </div>
            <!--end::Container-->
            <!--begin::Footer-->
            <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                <!--begin::Container-->
                <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <!--begin::Copyright-->
                    <div class="text-dark order-2 order-md-1">
                        <span class="text-muted font-weight-bold mr-2">{{date("Y")}}©</span>
                        <a href="#" target="_blank" class="text-white text-hover-primary">{{$copy_right}}</a>
                    </div>
                    <!--end::Copyright-->
                    <!--begin::Nav-->

                    <!--end::Nav-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<!--end::Main-->
<!--begin::Quick Cart-->
<div id="kt_quick_cart" class="offcanvas offcanvas-right p-10">
    <!--begin::Header-->
    <div class="offcanvas-header d-flex align-items-center justify-content-between pb-7">
        <h4 class="font-weight-bold m-0">Shopping Cart</h4>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_cart_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>
    <!--end::Header-->
    <!--begin::Content-->
    <div class="offcanvas-content">
        <!--begin::Wrapper-->
        <div class="offcanvas-wrapper mb-5 scroll-pull">
            <!--begin::Item-->
            <div class="d-flex align-items-center justify-content-between py-8">
                <div class="d-flex flex-column mr-2">
                    <a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">iBlender</a>
                    <span class="text-muted">The best kitchen gadget in 2020</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 350</span>
                        <span class="text-muted mr-1">for</span>
                        <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">5</span>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                            <i class="ki ki-minus icon-xs"></i>
                        </a>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon">
                            <i class="ki ki-plus icon-xs"></i>
                        </a>
                    </div>
                </div>
                <a href="#" class="symbol symbol-70 flex-shrink-0">
                    <img src="/metronic/theme/html/demo8/dist/assets/media/stock-600x400/img-1.jpg" title="" alt="" />
                </a>
            </div>
            <!--end::Item-->
            <!--begin::Separator-->
            <div class="separator separator-solid"></div>
            <!--end::Separator-->
            <!--begin::Item-->
            <div class="d-flex align-items-center justify-content-between py-8">
                <div class="d-flex flex-column mr-2">
                    <a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">SmartCleaner</a>
                    <span class="text-muted">Smart tool for cooking</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 650</span>
                        <span class="text-muted mr-1">for</span>
                        <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">4</span>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                            <i class="ki ki-minus icon-xs"></i>
                        </a>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon">
                            <i class="ki ki-plus icon-xs"></i>
                        </a>
                    </div>
                </div>
                <a href="#" class="symbol symbol-70 flex-shrink-0">
                    <img src="/metronic/theme/html/demo8/dist/assets/media/stock-600x400/img-2.jpg" title="" alt="" />
                </a>
            </div>
            <!--end::Item-->
            <!--begin::Separator-->
            <div class="separator separator-solid"></div>
            <!--end::Separator-->
            <!--begin::Item-->
            <div class="d-flex align-items-center justify-content-between py-8">
                <div class="d-flex flex-column mr-2">
                    <a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">CameraMax</a>
                    <span class="text-muted">Professional camera for edge cutting shots</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 150</span>
                        <span class="text-muted mr-1">for</span>
                        <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">3</span>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                            <i class="ki ki-minus icon-xs"></i>
                        </a>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon">
                            <i class="ki ki-plus icon-xs"></i>
                        </a>
                    </div>
                </div>
                <a href="#" class="symbol symbol-70 flex-shrink-0">
                    <img src="/metronic/theme/html/demo8/dist/assets/media/stock-600x400/img-3.jpg" title="" alt="" />
                </a>
            </div>
            <!--end::Item-->
            <!--begin::Separator-->
            <div class="separator separator-solid"></div>
            <!--end::Separator-->
            <!--begin::Item-->
            <div class="d-flex align-items-center justify-content-between py-8">
                <div class="d-flex flex-column mr-2">
                    <a href="#" class="font-weight-bold text-dark text-hover-primary">4D Printer</a>
                    <span class="text-muted">Manufactoring unique objects</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 1450</span>
                        <span class="text-muted mr-1">for</span>
                        <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">7</span>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                            <i class="ki ki-minus icon-xs"></i>
                        </a>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon">
                            <i class="ki ki-plus icon-xs"></i>
                        </a>
                    </div>
                </div>
                <a href="#" class="symbol symbol-70 flex-shrink-0">
                    <img src="/metronic/theme/html/demo8/dist/assets/media/stock-600x400/img-4.jpg" title="" alt="" />
                </a>
            </div>
            <!--end::Item-->
            <!--begin::Separator-->
            <div class="separator separator-solid"></div>
            <!--end::Separator-->
            <!--begin::Item-->
            <div class="d-flex align-items-center justify-content-between py-8">
                <div class="d-flex flex-column mr-2">
                    <a href="#" class="font-weight-bold text-dark text-hover-primary">MotionWire</a>
                    <span class="text-muted">Perfect animation tool</span>
                    <div class="d-flex align-items-center mt-2">
                        <span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 650</span>
                        <span class="text-muted mr-1">for</span>
                        <span class="font-weight-bold mr-2 text-dark-75 font-size-lg">7</span>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
                            <i class="ki ki-minus icon-xs"></i>
                        </a>
                        <a href="#" class="btn btn-xs btn-light-success btn-icon">
                            <i class="ki ki-plus icon-xs"></i>
                        </a>
                    </div>
                </div>
                <a href="#" class="symbol symbol-70 flex-shrink-0">
                    <img src="/metronic/theme/html/demo8/dist/assets/media/stock-600x400/img-8.jpg" title="" alt="" />
                </a>
            </div>
            <!--end::Item-->
        </div>
        <!--end::Wrapper-->
        <!--begin::Purchase-->
        <div class="offcanvas-footer">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <span class="font-weight-bold text-muted font-size-sm mr-2">Total</span>
                <span class="font-weight-bolder text-dark-50 text-right">$1840.00</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-7">
                <span class="font-weight-bold text-muted font-size-sm mr-2">Sub total</span>
                <span class="font-weight-bolder text-primary text-right">$5640.00</span>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-primary text-weight-bold">Place Order</button>
            </div>
        </div>
        <!--end::Purchase-->
    </div>
    <!--end::Content-->
</div>
<!--end::Quick Cart-->
<!--begin::Quick Panel-->
<div id="kt_quick_panel" class="offcanvas offcanvas-right pt-5 pb-10">
    <!--begin::Header-->
    <div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-5">
        <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-primary flex-grow-1 px-10" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#kt_quick_panel_logs">Audit Logs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#kt_quick_panel_notifications">Notifications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#kt_quick_panel_settings">Settings</a>
            </li>
        </ul>
        <div class="offcanvas-close mt-n1 pr-5">
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_panel_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Content-->
    <div class="offcanvas-content px-10">
        <div class="tab-content">
            <!--begin::Tabpane-->
            <div class="tab-pane fade show pt-3 pr-5 mr-n5 active" id="kt_quick_panel_logs" role="tabpanel">
                <!--begin::Section-->
                <div class="mb-15">
                    <h5 class="font-weight-bold mb-5">System Messages</h5>
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-wrap mb-5">
                        <div class="symbol symbol-50 symbol-light mr-5">
									<span class="symbol-label">
										<img src="/metronic/theme/html/demo8/dist/assets/media/svg/misc/006-plurk.svg" class="h-50 align-self-center" alt="" />
									</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-bolder text-dark-75 text-hover-primary font-size-lg mb-1">Top Authors</a>
                            <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                        </div>
                        <span class="btn btn-sm btn-light font-weight-bolder py-1 my-lg-0 my-2 text-dark-50">+82$</span>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-wrap mb-5">
                        <div class="symbol symbol-50 symbol-light mr-5">
									<span class="symbol-label">
										<img src="/metronic/theme/html/demo8/dist/assets/media/svg/misc/015-telegram.svg" class="h-50 align-self-center" alt="" />
									</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-bolder text-dark-75 text-hover-primary font-size-lg mb-1">Popular Authors</a>
                            <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                        </div>
                        <span class="btn btn-sm btn-light font-weight-bolder my-lg-0 my-2 py-1 text-dark-50">+280$</span>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-wrap mb-5">
                        <div class="symbol symbol-50 symbol-light mr-5">
									<span class="symbol-label">
										<img src="/metronic/theme/html/demo8/dist/assets/media/svg/misc/003-puzzle.svg" class="h-50 align-self-center" alt="" />
									</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-bolder text-dark-75 text-hover-primary font-size-lg mb-1">New Users</a>
                            <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                        </div>
                        <span class="btn btn-sm btn-light font-weight-bolder my-lg-0 my-2 py-1 text-dark-50">+4500$</span>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-wrap mb-5">
                        <div class="symbol symbol-50 symbol-light mr-5">
									<span class="symbol-label">
										<img src="/metronic/theme/html/demo8/dist/assets/media/svg/misc/005-bebo.svg" class="h-50 align-self-center" alt="" />
									</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-bolder text-dark-75 text-hover-primary font-size-lg mb-1">Active Customers</a>
                            <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                        </div>
                        <span class="btn btn-sm btn-light font-weight-bolder my-lg-0 my-2 py-1 text-dark-50">+4500$</span>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="symbol symbol-50 symbol-light mr-5">
									<span class="symbol-label">
										<img src="/metronic/theme/html/demo8/dist/assets/media/svg/misc/014-kickstarter.svg" class="h-50 align-self-center" alt="" />
									</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-bolder text-dark-75 text-hover-primary font-size-lg mb-1">Bestseller Theme</a>
                            <span class="text-muted font-weight-bold">Most Successful Fellas</span>
                        </div>
                        <span class="btn btn-sm btn-light font-weight-bolder my-lg-0 my-2 py-1 text-dark-50">+4500$</span>
                    </div>
                    <!--end: Item-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-5">
                    <h5 class="font-weight-bold mb-5">Notifications</h5>
                    <!--begin: Item-->
                    <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-5">
								<span class="svg-icon svg-icon-warning mr-5">
									<span class="svg-icon svg-icon-lg">
										<!--begin::Svg Icon | path:/metronic/theme/html/demo8/dist/assets/media/svg/icons/Home/Library.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
												<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
											</g>
										</svg>
                                        <!--end::Svg Icon-->
									</span>
								</span>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Another purpose persuade</a>
                            <span class="text-muted font-size-sm">Due in 2 Days</span>
                        </div>
                        <span class="font-weight-bolder text-warning py-1 font-size-lg">+28%</span>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center bg-light-success rounded p-5 mb-5">
								<span class="svg-icon svg-icon-success mr-5">
									<span class="svg-icon svg-icon-lg">
										<!--begin::Svg Icon | path:/metronic/theme/html/demo8/dist/assets/media/svg/icons/Communication/Write.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
												<path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
											</g>
										</svg>
                                        <!--end::Svg Icon-->
									</span>
								</span>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Would be to people</a>
                            <span class="text-muted font-size-sm">Due in 2 Days</span>
                        </div>
                        <span class="font-weight-bolder text-success py-1 font-size-lg">+50%</span>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-5">
								<span class="svg-icon svg-icon-danger mr-5">
									<span class="svg-icon svg-icon-lg">
										<!--begin::Svg Icon | path:/metronic/theme/html/demo8/dist/assets/media/svg/icons/Communication/Group-chat.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000" />
												<path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3" />
											</g>
										</svg>
                                        <!--end::Svg Icon-->
									</span>
								</span>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-normel text-dark-75 text-hover-primary font-size-lg mb-1">Purpose would be to persuade</a>
                            <span class="text-muted font-size-sm">Due in 2 Days</span>
                        </div>
                        <span class="font-weight-bolder text-danger py-1 font-size-lg">-27%</span>
                    </div>
                    <!--end: Item-->
                    <!--begin: Item-->
                    <div class="d-flex align-items-center bg-light-info rounded p-5">
								<span class="svg-icon svg-icon-info mr-5">
									<span class="svg-icon svg-icon-lg">
										<!--begin::Svg Icon | path:/metronic/theme/html/demo8/dist/assets/media/svg/icons/General/Attachment2.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M11.7573593,15.2426407 L8.75735931,15.2426407 C8.20507456,15.2426407 7.75735931,15.6903559 7.75735931,16.2426407 C7.75735931,16.7949254 8.20507456,17.2426407 8.75735931,17.2426407 L11.7573593,17.2426407 L11.7573593,18.2426407 C11.7573593,19.3472102 10.8619288,20.2426407 9.75735931,20.2426407 L5.75735931,20.2426407 C4.65278981,20.2426407 3.75735931,19.3472102 3.75735931,18.2426407 L3.75735931,14.2426407 C3.75735931,13.1380712 4.65278981,12.2426407 5.75735931,12.2426407 L9.75735931,12.2426407 C10.8619288,12.2426407 11.7573593,13.1380712 11.7573593,14.2426407 L11.7573593,15.2426407 Z" fill="#000000" opacity="0.3" transform="translate(7.757359, 16.242641) rotate(-45.000000) translate(-7.757359, -16.242641)" />
												<path d="M12.2426407,8.75735931 L15.2426407,8.75735931 C15.7949254,8.75735931 16.2426407,8.30964406 16.2426407,7.75735931 C16.2426407,7.20507456 15.7949254,6.75735931 15.2426407,6.75735931 L12.2426407,6.75735931 L12.2426407,5.75735931 C12.2426407,4.65278981 13.1380712,3.75735931 14.2426407,3.75735931 L18.2426407,3.75735931 C19.3472102,3.75735931 20.2426407,4.65278981 20.2426407,5.75735931 L20.2426407,9.75735931 C20.2426407,10.8619288 19.3472102,11.7573593 18.2426407,11.7573593 L14.2426407,11.7573593 C13.1380712,11.7573593 12.2426407,10.8619288 12.2426407,9.75735931 L12.2426407,8.75735931 Z" fill="#000000" transform="translate(16.242641, 7.757359) rotate(-45.000000) translate(-16.242641, -7.757359)" />
												<path d="M5.89339828,3.42893219 C6.44568303,3.42893219 6.89339828,3.87664744 6.89339828,4.42893219 L6.89339828,6.42893219 C6.89339828,6.98121694 6.44568303,7.42893219 5.89339828,7.42893219 C5.34111353,7.42893219 4.89339828,6.98121694 4.89339828,6.42893219 L4.89339828,4.42893219 C4.89339828,3.87664744 5.34111353,3.42893219 5.89339828,3.42893219 Z M11.4289322,5.13603897 C11.8194565,5.52656326 11.8194565,6.15972824 11.4289322,6.55025253 L10.0147186,7.96446609 C9.62419433,8.35499039 8.99102936,8.35499039 8.60050506,7.96446609 C8.20998077,7.5739418 8.20998077,6.94077682 8.60050506,6.55025253 L10.0147186,5.13603897 C10.4052429,4.74551468 11.0384079,4.74551468 11.4289322,5.13603897 Z M0.600505063,5.13603897 C0.991029355,4.74551468 1.62419433,4.74551468 2.01471863,5.13603897 L3.42893219,6.55025253 C3.81945648,6.94077682 3.81945648,7.5739418 3.42893219,7.96446609 C3.0384079,8.35499039 2.40524292,8.35499039 2.01471863,7.96446609 L0.600505063,6.55025253 C0.209980772,6.15972824 0.209980772,5.52656326 0.600505063,5.13603897 Z" fill="#000000" opacity="0.3" transform="translate(6.014719, 5.843146) rotate(-45.000000) translate(-6.014719, -5.843146)" />
												<path d="M17.9142136,15.4497475 C18.4664983,15.4497475 18.9142136,15.8974627 18.9142136,16.4497475 L18.9142136,18.4497475 C18.9142136,19.0020322 18.4664983,19.4497475 17.9142136,19.4497475 C17.3619288,19.4497475 16.9142136,19.0020322 16.9142136,18.4497475 L16.9142136,16.4497475 C16.9142136,15.8974627 17.3619288,15.4497475 17.9142136,15.4497475 Z M23.4497475,17.1568542 C23.8402718,17.5473785 23.8402718,18.1805435 23.4497475,18.5710678 L22.0355339,19.9852814 C21.6450096,20.3758057 21.0118446,20.3758057 20.6213203,19.9852814 C20.2307961,19.5947571 20.2307961,18.9615921 20.6213203,18.5710678 L22.0355339,17.1568542 C22.4260582,16.76633 23.0592232,16.76633 23.4497475,17.1568542 Z M12.6213203,17.1568542 C13.0118446,16.76633 13.6450096,16.76633 14.0355339,17.1568542 L15.4497475,18.5710678 C15.8402718,18.9615921 15.8402718,19.5947571 15.4497475,19.9852814 C15.0592232,20.3758057 14.4260582,20.3758057 14.0355339,19.9852814 L12.6213203,18.5710678 C12.2307961,18.1805435 12.2307961,17.5473785 12.6213203,17.1568542 Z" fill="#000000" opacity="0.3" transform="translate(18.035534, 17.863961) scale(1, -1) rotate(45.000000) translate(-18.035534, -17.863961)" />
											</g>
										</svg>
                                        <!--end::Svg Icon-->
									</span>
								</span>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <a href="#" class="font-weight-normel text-dark-75 text-hover-primary font-size-lg mb-1">The best product</a>
                            <span class="text-muted font-size-sm">Due in 2 Days</span>
                        </div>
                        <span class="font-weight-bolder text-info py-1 font-size-lg">+8%</span>
                    </div>
                    <!--end: Item-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Tabpane-->
            <!--begin::Tabpane-->
            <div class="tab-pane fade pt-2 pr-5 mr-n5" id="kt_quick_panel_notifications" role="tabpanel">
                <!--begin::Nav-->
                <div class="navi navi-icon-circle navi-spacer-x-0">
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon-bell text-success icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">5 new user generated report</div>
                                <div class="text-muted">Reports based on sales</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon2-box text-danger icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">2 new items submited</div>
                                <div class="text-muted">by Grog John</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon-psd text-primary icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">79 PSD files generated</div>
                                <div class="text-muted">Reports based on sales</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon2-supermarket text-warning icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">$2900 worth producucts sold</div>
                                <div class="text-muted">Total 234 items</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon-paper-plane-1 text-success icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">4.5h-avarage response time</div>
                                <div class="text-muted">Fostest is Barry</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon-safe-shield-protection text-danger icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">3 Defence alerts</div>
                                <div class="text-muted">40% less alerts thar last week</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon-notepad text-primary icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">Avarage 4 blog posts per author</div>
                                <div class="text-muted">Most posted 12 time</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon-users-1 text-warning icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">16 authors joined last week</div>
                                <div class="text-muted">9 photodrapehrs, 7 designer</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon2-box text-info icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">2 new items have been submited</div>
                                <div class="text-muted">by Grog John</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon2-download text-success icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">2.8 GB-total downloads size</div>
                                <div class="text-muted">Mostly PSD end AL concepts</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon2-supermarket text-danger icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">$2900 worth producucts sold</div>
                                <div class="text-muted">Total 234 items</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon-bell text-primary icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">7 new user generated report</div>
                                <div class="text-muted">Reports based on sales</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <a href="#" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 mr-3">
                                <div class="symbol-label">
                                    <i class="flaticon-paper-plane-1 text-success icon-lg"></i>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">4.5h-avarage response time</div>
                                <div class="text-muted">Fostest is Barry</div>
                            </div>
                        </div>
                    </a>
                    <!--end::Item-->
                </div>
                <!--end::Nav-->
            </div>
            <!--end::Tabpane-->
            <!--begin::Tabpane-->
            <div class="tab-pane fade pt-3 pr-5 mr-n5" id="kt_quick_panel_settings" role="tabpanel">
                <form class="form">
                    <!--begin::Section-->
                    <div>
                        <h5 class="font-weight-bold mb-3">Customer Care</h5>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Enable Notifications:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-success switch-sm">
											<label>
												<input type="checkbox" checked="checked" name="select" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Enable Case Tracking:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-success switch-sm">
											<label>
												<input type="checkbox" name="quick_panel_notifications_2" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Support Portal:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-success switch-sm">
											<label>
												<input type="checkbox" checked="checked" name="select" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                    </div>
                    <!--end::Section-->
                    <div class="separator separator-dashed my-6"></div>
                    <!--begin::Section-->
                    <div class="pt-2">
                        <h5 class="font-weight-bold mb-3">Reports</h5>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Generate Reports:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-sm switch-danger">
											<label>
												<input type="checkbox" checked="checked" name="select" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Enable Report Export:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-sm switch-danger">
											<label>
												<input type="checkbox" name="select" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Allow Data Collection:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-sm switch-danger">
											<label>
												<input type="checkbox" checked="checked" name="select" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                    </div>
                    <!--end::Section-->
                    <div class="separator separator-dashed my-6"></div>
                    <!--begin::Section-->
                    <div class="pt-2">
                        <h5 class="font-weight-bold mb-3">Memebers</h5>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Enable Member singup:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-sm switch-primary">
											<label>
												<input type="checkbox" checked="checked" name="select" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Allow User Feedbacks:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-sm switch-primary">
											<label>
												<input type="checkbox" name="select" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                        <div class="form-group mb-0 row align-items-center">
                            <label class="col-8 col-form-label">Enable Customer Portal:</label>
                            <div class="col-4 d-flex justify-content-end">
										<span class="switch switch-sm switch-primary">
											<label>
												<input type="checkbox" checked="checked" name="select" />
												<span></span>
											</label>
										</span>
                            </div>
                        </div>
                    </div>
                    <!--end::Section-->
                </form>
            </div>
            <!--end::Tabpane-->
        </div>
    </div>
    <!--end::Content-->
</div>
<!--end::Quick Panel-->
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:/metronic/theme/html/demo8/dist/assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
                <!--end::Svg Icon-->
			</span>
</div>
<!--end::Scrolltop-->
<!--begin::Sticky Toolbar-->

<!--end::Demo Panel-->
<script>var HOST_URL = "/metronic/theme/html/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#8950FC", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#6993FF", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#EEE5FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#E1E9FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{asset("demo8/js/plugins.bundle.js?v=7.2.9")}}"></script>
<script src="{{asset("demo8/js/prismjs.bundle.js?v=7.2.9")}}"></script>
<script src="{{asset("demo8/js/scripts.bundle.js?v=7.2.9")}}"></script>
<!--end::Global Theme Bundle-->
</body>
<!--end::Body-->
</html>
