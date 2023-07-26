<!--begin::Aside-->
<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
   <!--begin::Brand-->
   <div class="brand flex-column-auto" id="kt_brand">
      <!--begin::Logo-->
      <a href="{{ route('admin.dashboard') }}" class="brand-logo">
      @php
      $user = Auth::user();
      if($user->role == 1){
      $settings = \App\Models\BasicSetting::where('user_id', $user->id)->first();
      }elseif($user->role == 2){
      $settings = \App\Models\BasicSetting::where('user_id', $user->id)->first();
      }elseif($user->role == 3){
      $settings = \App\Models\BasicSetting::where('user_id', $user->parent_id)->first();
      }
      @endphp
      @if($settings)
      <img alt="Logo" src="{{ asset("uploads/$settings->image") }}" width="150" />
      @endif
      </a>
      <!--end::Logo-->
      <!--begin::Toggle-->
      <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
         <span class="svg-icon svg-icon svg-icon-xl">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
               <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <polygon points="0 0 24 0 24 24 0 24" />
                  <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                  <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
               </g>
            </svg>
            <!--end::Svg Icon-->
         </span>
      </button>
      <!--end::Toolbar-->
   </div>
   <!--end::Brand-->
   <!--begin::Aside Menu-->
   <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
      <!--begin::Menu Container-->
      <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
         <!--begin::Menu Nav-->
         <ul class="menu-nav">
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'dashboard'){echo 'menu-item-active'; }?>" aria-haspopup="true">
               <a href="{{ route('admin.dashboard') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <polygon points="0 0 24 0 24 24 0 24" />
                           <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
                           <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Dashboard</span>
               </a>
            </li>
            @php
            $user = Auth::user();
            if($user->role == 1){
            $view_client = 1;
            $view_staff = 1;
            $view_brand = 1;
            $view_device = 1;
            $view_product = 1;
            $view_job = 1;
            $view_invoice = 1;
            $view_enquiry = 1;
            $view_basic_setting = 1;
            $view_sms_setting = 1;
            $view_job_setting = 1;
            $view_mail_setting = 1;
            $view_other_setting = 1;
            $view_cms_setting = 1;
            }elseif($user->role == 2){
            $view_client = 1;
            $view_staff = 1;
            $view_brand = 1;
            $view_device = 1;
            $view_product = 1;
            $view_job = 1;
            $view_invoice = 1;
            $view_enquiry = 1;
            $view_basic_setting = 1;
            $view_sms_setting = 1;
            $view_job_setting = 1;
            $view_mail_setting = 1;
            $view_other_setting = 1;
            $view_cms_setting = 1;
            }elseif($user->role == 3){
            $view_client = $user->permission->user_view;
            $view_staff = 0;
            $view_brand = $user->permission->brand_view;
            $view_device = $user->permission->device_view;
            $view_product = $user->permission->product_view;
            $view_job = $user->permission->job_view;
            $view_invoice = $user->permission->invoice_view;
            $view_enquiry = $user->permission->enquiries_view;
            $view_basic_setting = $user->permission->setting_basic_view;
            $view_sms_setting = $user->permission->setting_sms_view;
            $view_job_setting = $user->permission->setting_job_view;
            $view_mail_setting = $user->permission->setting_email_view;
            $view_other_setting = $user->permission->setting_other_view;
            $view_cms_setting = $user->permission->setting_cms_view;
            }
            @endphp
            @if($view_client)
            <li class="menu-section">
               <h4 class="menu-text">Clients</h4>
               <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
            </li>
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'clients'){echo 'menu-item-active'; }?>" aria-haspopup="true">
               <a  href="{{ route('clients.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                           <path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 L7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" fill="#000000" opacity="0.3"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Manage Cilents</span>
               </a>
            </li>
            @endif
            @if($view_staff)
            <li class="menu-section">
               <h4 class="menu-text">Staff and Roles</h4>
               <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
            </li>
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'roles') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true">
               <a href="{{ route('roles.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                        viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <mask fill="white">
                              <use xlink:href="#path-1"/>
                           </mask>
                           <g/>
                           <path d="M15.6274517,4.55882251 L14.4693753,6.2959371 C13.9280401,5.51296885 13.0239252,5 12,5 C10.3431458,5 9,6.34314575 9,8 L9,10 L14,10 L17,10 L18,10 C19.1045695,10 20,10.8954305 20,12 L20,18 C20,19.1045695 19.1045695,20 18,20 L6,20 C4.8954305,20 4,19.1045695 4,18 L4,12 C4,10.8954305 4.8954305,10 6,10 L7,10 L7,8 C7,5.23857625 9.23857625,3 12,3 C13.4280904,3 14.7163444,3.59871093 15.6274517,4.55882251 Z" fill="#000000"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Manage Roles</span>
               </a>
            </li>
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'staffs') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true">
               <a href="{{ route('staffs.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                        viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3"/>
                           <path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" fill="#000000" opacity="0.3"/>
                           <path d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" fill="#000000" opacity="0.3"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Manage Staff</span>
               </a>
            </li>
            @endif
            @if(Auth::user()->role == 1)
            <li class="menu-section">
               <h4 class="menu-text">Shops</h4>
               <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
            </li>
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'shops') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true">
               <a href="{{ route('shops.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                        viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <path d="M18.1446364,11.84388 L17.4471627,16.0287218 C17.4463569,16.0335568 17.4455155,16.0383857 17.4446387,16.0432083 C17.345843,16.5865846 16.8252597,16.9469884 16.2818833,16.8481927 L4.91303792,14.7811299 C4.53842737,14.7130189 4.23500006,14.4380834 4.13039941,14.0719812 L2.30560137,7.68518803 C2.28007524,7.59584656 2.26712532,7.50338343 2.26712532,7.4104669 C2.26712532,6.85818215 2.71484057,6.4104669 3.26712532,6.4104669 L16.9929851,6.4104669 L17.606173,3.78251876 C17.7307772,3.24850086 18.2068633,2.87071314 18.7552257,2.87071314 L20.8200821,2.87071314 C21.4717328,2.87071314 22,3.39898039 22,4.05063106 C22,4.70228173 21.4717328,5.23054898 20.8200821,5.23054898 L19.6915238,5.23054898 L18.1446364,11.84388 Z" fill="#000000" opacity="0.3"/>
                           <path d="M6.5,21 C5.67157288,21 5,20.3284271 5,19.5 C5,18.6715729 5.67157288,18 6.5,18 C7.32842712,18 8,18.6715729 8,19.5 C8,20.3284271 7.32842712,21 6.5,21 Z M15.5,21 C14.6715729,21 14,20.3284271 14,19.5 C14,18.6715729 14.6715729,18 15.5,18 C16.3284271,18 17,18.6715729 17,19.5 C17,20.3284271 16.3284271,21 15.5,21 Z" fill="#000000"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Manage Shop</span>
               </a>
            </li>
            @endif
            @if($view_brand)
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'brands') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true">
               <a href="{{ route('brands.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <polygon points="0 0 24 0 24 24 0 24"/>
                           <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Manage Brands</span>
               </a>
            </li>
            @endif
            @if($view_device)
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'devices') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true">
               <a href="{{ route('devices.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <path d="M6.5,4 L6.5,20 L17.5,20 L17.5,4 L6.5,4 Z M7,2 L17,2 C18.1045695,2 19,2.8954305 19,4 L19,20 C19,21.1045695 18.1045695,22 17,22 L7,22 C5.8954305,22 5,21.1045695 5,20 L5,4 C5,2.8954305 5.8954305,2 7,2 Z" fill="#000000" fill-rule="nonzero"/>
                           <polygon fill="#000000" opacity="0.3" points="6.5 4 6.5 20 17.5 20 17.5 4"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Manage Devices</span>
               </a>
            </li>
            <li class="menu-item menu-item-submenu <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'compatibles' or Request::segment(2) == 'types') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true" data-menu-toggle="hover">
               <a href="javascript:;" class="menu-link menu-toggle">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <polygon points="0 0 24 0 24 24 0 24"/>
                           <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-360.000000) translate(-12.000000, -12.000000) " x="7" y="11" width="10" height="2" rx="1"/>
                           <path d="M13.7071045,15.7071104 C13.3165802,16.0976347 12.6834152,16.0976347 12.2928909,15.7071104 C11.9023666,15.3165861 11.9023666,14.6834211 12.2928909,14.2928968 L18.2928909,8.29289682 C18.6714699,7.91431789 19.2810563,7.90107226 19.6757223,8.26284946 L25.6757223,13.7628495 C26.0828413,14.1360419 26.1103443,14.7686092 25.7371519,15.1757282 C25.3639594,15.5828472 24.7313921,15.6103502 24.3242731,15.2371577 L19.0300735,10.3841414 L13.7071045,15.7071104 Z" fill="#000000" fill-rule="nonzero" transform="translate(19.000001, 12.000003) rotate(-270.000000) translate(-19.000001, -12.000003) "/>
                           <path d="M-0.292895505,15.7071104 C-0.683419796,16.0976347 -1.31658478,16.0976347 -1.70710907,15.7071104 C-2.09763336,15.3165861 -2.09763336,14.6834211 -1.70710907,14.2928968 L4.29289093,8.29289682 C4.67146987,7.91431789 5.28105631,7.90107226 5.67572234,8.26284946 L11.6757223,13.7628495 C12.0828413,14.1360419 12.1103443,14.7686092 11.7371519,15.1757282 C11.3639594,15.5828472 10.7313921,15.6103502 10.3242731,15.2371577 L5.03007346,10.3841414 L-0.292895505,15.7071104 Z" fill="#000000" fill-rule="nonzero" transform="translate(5.000001, 12.000003) rotate(-450.000000) translate(-5.000001, -12.000003) "/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Compatibles</span>
                  <i class="menu-arrow"></i>
               </a>
               <div class="menu-submenu">
                  <i class="menu-arrow"></i>
                  <ul class="menu-subnav">
                     <li class="menu-item menu-item-parent" aria-haspopup="true">
                        <span class="menu-link">
                        <span class="menu-text">Compatibles</span>
                        </span>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('types.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Item Types</span>
                        </a>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{ route('compatibles.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Compatibles List</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </li>
            @endif
            @if($view_product)
            <li class="menu-item menu-item-submenu <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'products' or Request::segment(2) == 'categories') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true" data-menu-toggle="hover">
               <a href="javascript:;" class="menu-link menu-toggle">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <path
                              d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z"
                              fill="#000000"/>
                           <path
                              d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z"
                              fill="#000000" opacity="0.3"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Products</span>
                  <i class="menu-arrow"></i>
               </a>
               <div class="menu-submenu">
                  <i class="menu-arrow"></i>
                  <ul class="menu-subnav">
                     <li class="menu-item menu-item-parent" aria-haspopup="true">
                        <span class="menu-link">
                        <span class="menu-text">Products</span>
                        </span>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('categories.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Manage Categories</span>
                        </a>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{ route('products.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Manage Products</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </li>
            @endif
            @if($view_job)
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'jobs') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true">
               <a href="{{ route('jobs.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <polygon points="0 0 24 0 24 24 0 24"/>
                           <path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
                           <path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Manage Jobs</span>
               </a>
            </li>
            @endif
            @if($view_invoice)
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'invoices') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true">
               <a href="{{ route('invoices.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                           <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                           <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"/>
                           <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"/>
                           <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"/>
                           <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"/>
                           <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"/>
                           <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Invoices</span>
               </a>
            </li>
            @endif
            <li class="menu-item menu-item-submenu <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'reports' or Request::segment(2) == 'reports-search' or Request::segment(2) == 'reports-job') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true" data-menu-toggle="hover">
               <a href="javascript:;" class="menu-link menu-toggle">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <polygon points="0 0 24 0 24 24 0 24"/>
                           <path d="M4.85714286,1 L11.7364114,1 C12.0910962,1 12.4343066,1.12568431 12.7051108,1.35473959 L17.4686994,5.3839416 C17.8056532,5.66894833 18,6.08787823 18,6.52920201 L18,19.0833333 C18,20.8738751 17.9795521,21 16.1428571,21 L4.85714286,21 C3.02044787,21 3,20.8738751 3,19.0833333 L3,2.91666667 C3,1.12612489 3.02044787,1 4.85714286,1 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                           <path d="M6.85714286,3 L14.7364114,3 C15.0910962,3 15.4343066,3.12568431 15.7051108,3.35473959 L20.4686994,7.3839416 C20.8056532,7.66894833 21,8.08787823 21,8.52920201 L21,21.0833333 C21,22.8738751 20.9795521,23 19.1428571,23 L6.85714286,23 C5.02044787,23 5,22.8738751 5,21.0833333 L5,4.91666667 C5,3.12612489 5.02044787,3 6.85714286,3 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fill-rule="nonzero"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Reports</span>
                  <i class="menu-arrow"></i>
               </a>
               <div class="menu-submenu">
                  <i class="menu-arrow"></i>
                  <ul class="menu-subnav">
                     <li class="menu-item menu-item-parent" aria-haspopup="true">
                        <span class="menu-link">
                        <span class="menu-text">Reports</span>
                        </span>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('reports.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Product Reports </span>
                        </a>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{ route('reports.jobs')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Job Reports</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </li>
            {{--
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'invoices') {--}}
               {{--                                    echo 'menu-item-active';--}}
               {{--                                }?>" aria-haspopup="true">
               --}}
               {{--
               <a href="{{ route('invoices.index') }}" class="menu-link">
                  --}}
                  {{--
                  <span class="svg-icon menu-icon">
                     --}}
                     {{--                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->--}}
                     {{--                                                    <svg xmlns="http://www.w3.org/2000/svg"--}}
                     {{--                                                         xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"--}}
                     {{--                                                         height="24px" viewBox="0 0 24 24" version="1.1">--}}
                     {{--
                     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        --}}
                        {{--
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        --}}
                        {{--
                        <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                        --}}
                        {{--
                        <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                        --}}
                        {{--
                        <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                        --}}
                        {{--
                     </g>
                     --}}
                     {{--                                                    </svg>--}}
                     {{--                                                    <!--end::Svg Icon-->--}}
                     {{--
                  </span>
                  --}}
                  {{--                                        <span class="menu-text">Manage Invoices</span>--}}
                  {{--
               </a>
               --}}
               {{--
            </li>
            --}}
            @if($view_enquiry)
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'enquiries') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true">
               <a href="{{ route('enquiries.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <path d="M14.8571499,13 C14.9499122,12.7223297 15,12.4263059 15,12.1190476 L15,6.88095238 C15,5.28984632 13.6568542,4 12,4 L11.7272727,4 C10.2210416,4 9,5.17258756 9,6.61904762 L10.0909091,6.61904762 C10.0909091,5.75117158 10.823534,5.04761905 11.7272727,5.04761905 L12,5.04761905 C13.0543618,5.04761905 13.9090909,5.86843034 13.9090909,6.88095238 L13.9090909,12.1190476 C13.9090909,12.4383379 13.8240964,12.7385644 13.6746497,13 L10.3253503,13 C10.1759036,12.7385644 10.0909091,12.4383379 10.0909091,12.1190476 L10.0909091,9.5 C10.0909091,9.06606198 10.4572216,8.71428571 10.9090909,8.71428571 C11.3609602,8.71428571 11.7272727,9.06606198 11.7272727,9.5 L11.7272727,11.3333333 L12.8181818,11.3333333 L12.8181818,9.5 C12.8181818,8.48747796 11.9634527,7.66666667 10.9090909,7.66666667 C9.85472911,7.66666667 9,8.48747796 9,9.5 L9,12.1190476 C9,12.4263059 9.0500878,12.7223297 9.14285008,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L14.8571499,13 Z" fill="#000000" opacity="0.3"/>
                           <path d="M9,10.3333333 L9,12.1190476 C9,13.7101537 10.3431458,15 12,15 C13.6568542,15 15,13.7101537 15,12.1190476 L15,10.3333333 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 L9,10.3333333 Z M10.0909091,11.1212121 L12,12.5 L13.9090909,11.1212121 L13.9090909,12.1190476 C13.9090909,13.1315697 13.0543618,13.952381 12,13.952381 C10.9456382,13.952381 10.0909091,13.1315697 10.0909091,12.1190476 L10.0909091,11.1212121 Z" fill="#000000"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Manage Enquiries</span>
               </a>
            </li>
            @endif
            @if(auth()->user()->role == 1)
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'informations') {
                echo 'menu-item-active';
                }?>" aria-haspopup="true">
                <a href="{{ route('informations.index') }}" class="menu-link">
                   <span class="svg-icon menu-icon">
                      <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                      <svg xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                         height="24px" viewBox="0 0 24 24" version="1.1">
                         <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M14.8571499,13 C14.9499122,12.7223297 15,12.4263059 15,12.1190476 L15,6.88095238 C15,5.28984632 13.6568542,4 12,4 L11.7272727,4 C10.2210416,4 9,5.17258756 9,6.61904762 L10.0909091,6.61904762 C10.0909091,5.75117158 10.823534,5.04761905 11.7272727,5.04761905 L12,5.04761905 C13.0543618,5.04761905 13.9090909,5.86843034 13.9090909,6.88095238 L13.9090909,12.1190476 C13.9090909,12.4383379 13.8240964,12.7385644 13.6746497,13 L10.3253503,13 C10.1759036,12.7385644 10.0909091,12.4383379 10.0909091,12.1190476 L10.0909091,9.5 C10.0909091,9.06606198 10.4572216,8.71428571 10.9090909,8.71428571 C11.3609602,8.71428571 11.7272727,9.06606198 11.7272727,9.5 L11.7272727,11.3333333 L12.8181818,11.3333333 L12.8181818,9.5 C12.8181818,8.48747796 11.9634527,7.66666667 10.9090909,7.66666667 C9.85472911,7.66666667 9,8.48747796 9,9.5 L9,12.1190476 C9,12.4263059 9.0500878,12.7223297 9.14285008,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L14.8571499,13 Z" fill="#000000" opacity="0.3"/>
                            <path d="M9,10.3333333 L9,12.1190476 C9,13.7101537 10.3431458,15 12,15 C13.6568542,15 15,13.7101537 15,12.1190476 L15,10.3333333 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 L9,10.3333333 Z M10.0909091,11.1212121 L12,12.5 L13.9090909,11.1212121 L13.9090909,12.1190476 C13.9090909,13.1315697 13.0543618,13.952381 12,13.952381 C10.9456382,13.952381 10.0909091,13.1315697 10.0909091,12.1190476 L10.0909091,11.1212121 Z" fill="#000000"/>
                         </g>
                      </svg>
                      <!--end::Svg Icon-->
                   </span>
                   <span class="menu-text">Manage Informations</span>
                </a>
            </li>
            @endif
            <li class="menu-section">
               <h4 class="menu-text">Settings</h4>
               <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
            </li>
            @if($view_basic_setting)
            <li class="menu-item menu-item-submenu <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'countries' or Request::segment(2) == 'settings' or Request::segment(2) == 'provinces'or Request::segment(2) == 'couriers'or Request::segment(2) == 'id-cards') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true" data-menu-toggle="hover">
               <a href="javascript:;" class="menu-link menu-toggle">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Basic Settings</span>
                  <i class="menu-arrow"></i>
               </a>
               <div class="menu-submenu">
                  <i class="menu-arrow"></i>
                  <ul class="menu-subnav">
                     <li class="menu-item menu-item-parent" aria-haspopup="true">
                        <span class="menu-link">
                        <span class="menu-text">Status</span>
                        </span>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('basic-setting.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Basic Settings</span>
                        </a>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('countries.create')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Manage Countries</span>
                        </a>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('provinces.create')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Manage Provinces</span>
                        </a>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('couriers.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Manage Couriers</span>
                        </a>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('id-cards.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Manage ID Card Type</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </li>
            @endif
            @php
            $user = Auth::user();
            if ($user->role == 1){ $mail_setting = 1; $sms_setting = 1; }
            elseif ($user->role == 2){ $mail_setting = $user->custom_mail; $sms_setting = $user->custom_sms; }
            elseif ($user->role == 3){ $mail_setting = $user->shop->custom_mail; $sms_setting = $user->shop->custom_sms; }
            @endphp
            {{--  @if($sms_setting)
            @if($view_sms_setting)  --}}
                <li class="menu-item menu-item-submenu <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'countries' or Request::segment(2) == 'settings' or Request::segment(2) == 'provinces'or Request::segment(2) == 'couriers'or Request::segment(2) == 'id-cards') {
                    echo 'menu-item-active';
                    }?>" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-text">SMS</span>
                    <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                    <i class="menu-arrow"></i>
                    <ul class="menu-subnav">
                        @if(auth()->user()->custom_sms == 1)
                        <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'sms-setting'){echo 'menu-item-active'; }?>" aria-haspopup="true">
                            <a  href="{{ route('sms-setting.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"/>
                                        <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="menu-text">Sms Settings</span>
                            </a>
                        </li>
                        @endif
                        <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'templates'){echo 'menu-item-active'; }?>" aria-haspopup="true">
                            <a  href="{{ route('templates.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"/>
                                        <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="menu-text">Sms Template</span>
                            </a>
                        </li>
                    </ul>
                    </div>
                </li>
                <li class="menu-item menu-item-submenu <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'countries' or Request::segment(2) == 'settings' or Request::segment(2) == 'provinces'or Request::segment(2) == 'couriers'or Request::segment(2) == 'id-cards') {
                    echo 'menu-item-active';
                    }?>" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="svg-icon menu-icon">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-text">Whatsapp</span>
                    <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                    <i class="menu-arrow"></i>
                    <ul class="menu-subnav">
                            @if(auth()->user()->custom_sms == 1)
                            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'whatsapp-setting'){echo 'menu-item-active'; }?>" aria-haspopup="true">
                            <a  href="{{ route('whatsapp-setting.index') }}" class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <polygon fill="#000000" opacity="0.3" points="5 15 3 21.5 9.5 19.5"/>
                                        <path d="M13.5,21 C8.25329488,21 4,16.7467051 4,11.5 C4,6.25329488 8.25329488,2 13.5,2 C18.7467051,2 23,6.25329488 23,11.5 C23,16.7467051 18.7467051,21 13.5,21 Z M9,8 C8.44771525,8 8,8.44771525 8,9 C8,9.55228475 8.44771525,10 9,10 L18,10 C18.5522847,10 19,9.55228475 19,9 C19,8.44771525 18.5522847,8 18,8 L9,8 Z M9,12 C8.44771525,12 8,12.4477153 8,13 C8,13.5522847 8.44771525,14 9,14 L14,14 C14.5522847,14 15,13.5522847 15,13 C15,12.4477153 14.5522847,12 14,12 L9,12 Z" fill="#000000"/>
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-text">Whatsapp Setting</span>
                            </a>
                            </li>
                            @endif
                            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'whatsapp-setting'){echo 'menu-item-active'; }?>" aria-haspopup="true">
                                <a  href="{{ route('whatsapp.index') }}" class="menu-link">
                                    <span class="svg-icon menu-icon">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <polygon fill="#000000" opacity="0.3" points="5 15 3 21.5 9.5 19.5"/>
                                            <path d="M13.5,21 C8.25329488,21 4,16.7467051 4,11.5 C4,6.25329488 8.25329488,2 13.5,2 C18.7467051,2 23,6.25329488 23,11.5 C23,16.7467051 18.7467051,21 13.5,21 Z M9,8 C8.44771525,8 8,8.44771525 8,9 C8,9.55228475 8.44771525,10 9,10 L18,10 C18.5522847,10 19,9.55228475 19,9 C19,8.44771525 18.5522847,8 18,8 L9,8 Z M9,12 C8.44771525,12 8,12.4477153 8,13 C8,13.5522847 8.44771525,14 9,14 L14,14 C14.5522847,14 15,13.5522847 15,13 C15,12.4477153 14.5522847,12 14,12 L9,12 Z" fill="#000000"/>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                    <span class="menu-text">Whatsapp Template</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            {{--  @endif
            @endif  --}}
            @if($mail_setting)
            @if($view_mail_setting)
            <li class="menu-item menu-item-submenu <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'countries' or Request::segment(2) == 'settings' or Request::segment(2) == 'provinces'or Request::segment(2) == 'couriers'or Request::segment(2) == 'id-cards') {
                echo 'menu-item-active';
                }?>" aria-haspopup="true" data-menu-toggle="hover">
                <a href="javascript:;" class="menu-link menu-toggle">
                <span class="svg-icon menu-icon">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
                <span class="menu-text">Mail</span>
                <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu">
                <i class="menu-arrow"></i>
                    <ul class="menu-subnav">
                        <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'email-setting'){echo 'menu-item-active'; }?>" aria-haspopup="true">
                            <a  href="{{ route('email-setting.index') }}" class="menu-link">
                               <span class="svg-icon menu-icon">
                                  <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                        <path d="M12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.98630124,11 4.48466491,11.0516454 4,11.1500272 L4,7 C4,5.8954305 4.8954305,5 6,5 L20,5 C21.1045695,5 22,5.8954305 22,7 L22,16 C22,17.1045695 21.1045695,18 20,18 L12.9835977,18 Z M19.1444251,6.83964668 L13,10.1481833 L6.85557487,6.83964668 C6.4908718,6.6432681 6.03602525,6.77972206 5.83964668,7.14442513 C5.6432681,7.5091282 5.77972206,7.96397475 6.14442513,8.16035332 L12.6444251,11.6603533 C12.8664074,11.7798822 13.1335926,11.7798822 13.3555749,11.6603533 L19.8555749,8.16035332 C20.2202779,7.96397475 20.3567319,7.5091282 20.1603533,7.14442513 C19.9639747,6.77972206 19.5091282,6.6432681 19.1444251,6.83964668 Z" fill="#000000"/>
                                     </g>
                                  </svg>
                                  <!--end::Svg Icon-->
                               </span>
                               <span class="menu-text">Mail Settings</span>
                            </a>
                         </li>
                    </ul>
                </div>
            </li>

            @endif
            @endif
            @if($view_job_setting)
            <li class="menu-item menu-item-submenu <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'statuses' or Request::segment(2) == 'jobs-setting') {
               echo 'menu-item-active';
               }?>" aria-haspopup="true" data-menu-toggle="hover">
               <a href="javascript:;" class="menu-link menu-toggle">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Bucket.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24"/>
                           <path d="M18.6225,9.75 L18.75,9.75 C19.9926407,9.75 21,10.7573593 21,12 C21,13.2426407 19.9926407,14.25 18.75,14.25 L18.6854912,14.249994 C18.4911876,14.250769 18.3158978,14.366855 18.2393549,14.5454486 C18.1556809,14.7351461 18.1942911,14.948087 18.3278301,15.0846699 L18.372535,15.129375 C18.7950334,15.5514036 19.03243,16.1240792 19.03243,16.72125 C19.03243,17.3184208 18.7950334,17.8910964 18.373125,18.312535 C17.9510964,18.7350334 17.3784208,18.97243 16.78125,18.97243 C16.1840792,18.97243 15.6114036,18.7350334 15.1896699,18.3128301 L15.1505513,18.2736469 C15.008087,18.1342911 14.7951461,18.0956809 14.6054486,18.1793549 C14.426855,18.2558978 14.310769,18.4311876 14.31,18.6225 L14.31,18.75 C14.31,19.9926407 13.3026407,21 12.06,21 C10.8173593,21 9.81,19.9926407 9.81,18.75 C9.80552409,18.4999185 9.67898539,18.3229986 9.44717599,18.2361469 C9.26485393,18.1556809 9.05191298,18.1942911 8.91533009,18.3278301 L8.870625,18.372535 C8.44859642,18.7950334 7.87592081,19.03243 7.27875,19.03243 C6.68157919,19.03243 6.10890358,18.7950334 5.68746499,18.373125 C5.26496665,17.9510964 5.02757002,17.3784208 5.02757002,16.78125 C5.02757002,16.1840792 5.26496665,15.6114036 5.68716991,15.1896699 L5.72635306,15.1505513 C5.86570889,15.008087 5.90431906,14.7951461 5.82064513,14.6054486 C5.74410223,14.426855 5.56881236,14.310769 5.3775,14.31 L5.25,14.31 C4.00735931,14.31 3,13.3026407 3,12.06 C3,10.8173593 4.00735931,9.81 5.25,9.81 C5.50008154,9.80552409 5.67700139,9.67898539 5.76385306,9.44717599 C5.84431906,9.26485393 5.80570889,9.05191298 5.67216991,8.91533009 L5.62746499,8.870625 C5.20496665,8.44859642 4.96757002,7.87592081 4.96757002,7.27875 C4.96757002,6.68157919 5.20496665,6.10890358 5.626875,5.68746499 C6.04890358,5.26496665 6.62157919,5.02757002 7.21875,5.02757002 C7.81592081,5.02757002 8.38859642,5.26496665 8.81033009,5.68716991 L8.84944872,5.72635306 C8.99191298,5.86570889 9.20485393,5.90431906 9.38717599,5.82385306 L9.49484664,5.80114977 C9.65041313,5.71688974 9.7492905,5.55401473 9.75,5.3775 L9.75,5.25 C9.75,4.00735931 10.7573593,3 12,3 C13.2426407,3 14.25,4.00735931 14.25,5.25 L14.249994,5.31450877 C14.250769,5.50881236 14.366855,5.68410223 14.552824,5.76385306 C14.7351461,5.84431906 14.948087,5.80570889 15.0846699,5.67216991 L15.129375,5.62746499 C15.5514036,5.20496665 16.1240792,4.96757002 16.72125,4.96757002 C17.3184208,4.96757002 17.8910964,5.20496665 18.312535,5.626875 C18.7350334,6.04890358 18.97243,6.62157919 18.97243,7.21875 C18.97243,7.81592081 18.7350334,8.38859642 18.3128301,8.81033009 L18.2736469,8.84944872 C18.1342911,8.99191298 18.0956809,9.20485393 18.1761469,9.38717599 L18.1988502,9.49484664 C18.2831103,9.65041313 18.4459853,9.7492905 18.6225,9.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                           <path d="M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Job Sheet Settings</span>
                  <i class="menu-arrow"></i>
               </a>
               <div class="menu-submenu">
                  <i class="menu-arrow"></i>
                  <ul class="menu-subnav">
                     <li class="menu-item menu-item-parent" aria-haspopup="true">
                        <span class="menu-link">
                        <span class="menu-text">Status</span>
                        </span>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('statuses.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Manage Statuses</span>
                        </a>
                     </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{route('jobs-setting.index')}}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                        </i>
                        <span class="menu-text">Repairs Setting</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </li>
            @endif
            {{--  @if($view_other_setting)
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'other-setting'){echo 'menu-item-active'; }?>" aria-haspopup="true">
               <a  href="{{ route('other-setting.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24" />
                           <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                           <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Other Settings</span>
               </a>
            </li>
            @endif  --}}
            @if($view_cms_setting)
            <li class="menu-item <?php if (Request::segment(1) == 'admin' && Request::segment(2) == 'cms-setting'){echo 'menu-item-active'; }?>" aria-haspopup="true">
               <a  href="{{ route('cms-setting.index') }}" class="menu-link">
                  <span class="svg-icon menu-icon">
                     <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                           <rect x="0" y="0" width="24" height="24" />
                           <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                           <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                        </g>
                     </svg>
                     <!--end::Svg Icon-->
                  </span>
                  <span class="menu-text">Cms Settings</span>
               </a>
            </li>
            @endif
         </ul>
         <!--end::Menu Nav-->
      </div>
      <!--end::Menu Container-->
   </div>
   <!--end::Aside Menu-->
</div>
<!--end::Aside-->
