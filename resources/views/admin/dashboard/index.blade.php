@extends('admin.layouts.master')
@section('title',$title)
@section('content')
<!--begin::Entry-->
@if(auth()->user()->role == 2)
<div class="container text-center my-3">
    @if(!empty($informations))
    @foreach ($informations->images as $image)
        <div class="row">
            <div class="col-md-12 my-3">
                <img src="{{ asset('uploads') }}/{{ $image->image_1 ?? ''}}" class="rounded float-center " style="height: 100px !important">
            </div>

            <div class="col-md-12 my-3">
                <img src="{{ asset('uploads') }}/{{ $image->image_2 ?? ''}}" class="rounded float-center" style="height: 100px !important;">
            </div>
        </div>
    @endforeach

@endif
@endif
</div>
<div class="d-flex flex-column-fluid">
   <!--begin::Container-->

   <div class="container">
      <!--begin::Dashboard-->
      <!--begin::Row-->

      <div class="row">
         @php
         $setting = \App\Models\BasicSetting::where("user_id",Auth::user()->id)->first();
         if(Auth::user()->role == 2){
         $id = Auth::user()->id;
         $users = \App\Models\User::where([["is_admin",0]])
         ->join('shop_users', function ($join) use ($id) {
         $join->on('shop_users.customer_id', '=', 'users.id')
         ->where('shop_users.user_id', '=', $id);
         })
         ->select(
         'users.*'
         )
         ->count();
         $jobs = \App\Models\Job::where("user_id",Auth::user()->id)->count();
         $statuses = \App\Models\Status::where("user_id",Auth::user()->id)->get();

         $accepted = \App\Models\Job::where([["user_id",Auth::id()],["status_id",1]])->count();
         $progressing = \App\Models\Job::where([["user_id",Auth::id()],["status_id",2]])->count();
         $completed = \App\Models\Job::where([["user_id",Auth::id()],["status_id",3]])->count();
         } elseif(Auth::user()->role == 1){
         $users = \App\Models\User::where("is_admin",0)->count();
         $jobs = \App\Models\Job::all()->count();

         $accepted = \App\Models\Job::where([["status_id",1]])->count();
         $progressing = \App\Models\Job::where([["status_id",2]])->count();
         $completed = \App\Models\Job::where([["status_id",3]])->count();
         }elseif(Auth::user()->role == 3){
         $id = Auth::user()->parent_id;
         $users = \App\Models\User::where([["is_admin",0]])
         ->join('shop_users', function ($join) use ($id) {
         $join->on('shop_users.customer_id', '=', 'users.id')
         ->where('shop_users.user_id', '=', $id);
         })
         ->select(
         'users.*'
         )
         ->count();
         $jobs = \App\Models\Job::where("user_id",Auth::user()->parent_id)->count();

         $accepted = \App\Models\Job::where([["user_id",Auth::id()],["status_id",1]])->count();
         $progressing = \App\Models\Job::where([["user_id",Auth::id()],["status_id",2]])->count();
         $completed = \App\Models\Job::where([["user_id",Auth::id()],["status_id",3]])->count();
         }
         $array = json_decode($setting->status, true);
         @endphp
         <div class="col-lg-12 col-xxl-12">
            <!--begin::Mixed Widget 1-->
            <div class="card card-custom bg-gray-100 card-stretch gutter-b">
               <!--begin::Header-->
               <div class="card-header border-0 bg-danger py-5">
                  <h3 class="card-title font-weight-bolder text-white">Stat</h3>
                  <div class="card-toolbar">
                  </div>
               </div>
               <!--end::Header-->
               <!--begin::Body-->
               <div class="card-body p-0 position-relative overflow-hidden">
                  <!--begin::Chart-->
                  <div id="kt_mixed_widget_1_chart" class="card-rounded-bottom bg-danger"
                     style="height: 200px"></div>
                  <!--end::Chart-->
                  <!--begin::Stats-->
                  <div class="card-spacer mt-n25">
                     <!--begin::Row-->
                     <div class="row m-0">
                        <div class="col bg-light-danger px-6 py-8 rounded-xl mb-7">
                           <span
                              class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                              <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
                              <svg xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                 width="24px" height="24px" viewBox="0 0 24 24"
                                 version="1.1">
                                 <g stroke="none" stroke-width="1" fill="none"
                                    fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path
                                       d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                       fill="#000000" fill-rule="nonzero"
                                       opacity="0.3"/>
                                    <path
                                       d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                       fill="#000000" fill-rule="nonzero"/>
                                 </g>
                              </svg>
                              <!--end::Svg Icon-->
                           </span>
                           <a href="#" class="text-danger font-weight-bold font-size-h6 mt-2">All Users
                           ({{$users}})</a>
                        </div>
                        <div class="col bg-light-warning  px-6 py-8 rounded-xl mb-7 ml-7">
                           <span
                              class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                              <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
                              <svg xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                 width="24px" height="24px" viewBox="0 0 24 24"
                                 version="1.1">
                                 <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    <path d="M14.5,12 C15.0522847,12 15.5,12.4477153 15.5,13 L15.5,16 C15.5,16.5522847 15.0522847,17 14.5,17 L9.5,17 C8.94771525,17 8.5,16.5522847 8.5,16 L8.5,13 C8.5,12.4477153 8.94771525,12 9.5,12 L9.5,11.5 C9.5,10.1192881 10.6192881,9 12,9 C13.3807119,9 14.5,10.1192881 14.5,11.5 L14.5,12 Z M12,10 C11.1715729,10 10.5,10.6715729 10.5,11.5 L10.5,12 L13.5,12 L13.5,11.5 C13.5,10.6715729 12.8284271,10 12,10 Z" fill="#000000"/>
                                 </g>
                              </svg>
                              <!--end::Svg Icon-->
                           </span>
                           <a href="#" class="text-warning font-weight-bold font-size-h6 mt-2">All Jobs
                           ({{$jobs}})</a>
                        </div>
                        @foreach($statuses as $status)

                            @if(!empty($setting->status))
                                @if(in_array($status->name, $array ))
                                    <div class="col bg-light-secondary  px-6 py-8 rounded-xl mb-7 ml-7" >
                                        <span  class="svg-icon svg-icon-3x status{{$status->id}} d-block my-2" style="fill: {{$status->color}} !important;">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="24px" height="24px" viewBox="0 0 24 24"
                                            version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                                                <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                        </span>
                                        @if($status->jobs)
                                        <a href="#" class="text-info font-weight-bold font-size-h6 mt-2" style="color: {{$status->color}} !important;">{{$status->name}}
                                        ({{$status->jobs->count() ?? 0}})</a>

                                        @endif
                                    </div>
                                @endif
                            @endif

                        @endforeach


                     </div>
                     @if (auth()->user()->role == 2)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive ">
                                    <div class="table-loading-message">
                                        <h3 class="font-weight-bold">
                                        Clients
                                        </h3>
                                    </div>

                                    <table class="table table-row-bordered gy-5">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800">
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Pending Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clients as $client)
                                                <tr>
                                                    <td>{{  $client->name }}</td>
                                                    <td> {{ $client->email }}</td>
                                                    <td>{{   $client->pendingInvoices->sum('total')}}</td>
                                                    <td>
                                                        @if($client->active == 1)
                                                            Active
                                                        @else
                                                            Inactive
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive ">
                                    <div class="table-loading-message">
                                      <h3 class="font-weight-bold">  Jobs</h3>
                                    </div>

                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800">
                                                <th>Job Sheet Number</th>
                                                <th>Server Type</th>
                                                <th>Expected Delievery Date</th>
                                                <th>Status</th>
                                                <th>Customer</th>
                                                <th>
                                                    Device
                                                </th>
                                                <th>Estimate Cost</th>
                                                <th>Problem</th>
                                                <th>Shop</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($works as $job)
                                                <tr>
                                                    <td>{{  $job->job_sheet_number }}</td>
                                                    <td> @if($job->service_type == 1)
                                                        Carry In
                                                    @elseif($job->service_type == 2)
                                                        Pick Up
                                                    @elseif($job->service_type == 3)
                                                        On Site

                                                    @else
                                                        Courier

                                                    @endif</td>

                                                    <td>{{ $job->expected_delivery ?? ''}}</td>
                                                    <td>{{ $job->stat->name ?? "nill" }}</td>
                                                    <td>{{ $job->customer_name ?? ''}}</td>
                                                    <td>{{ $job->cost ?? ''}}</td>
                                                    <td>{{ $job->device->name ?? ''}}</td>
                                                    <td> {{ $job->problem_by_customer ?? '' }}</td>
                                                    <td>
                                                        {{ $job->shop->name ?? ''}}
                                                    </td>

                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                     @endif
                     <div class="row m-0">
                     </div>
                     <!--end::Row-->
                  </div>
                  <!--end::Stats-->
               </div>
               <!--end::Body-->
            </div>
            <!--end::Mixed Widget 1-->
         </div>
      </div>
      <!--end::Row-->
      <!--begin::Row-->
      <!--end::Row-->
      <!--end::Dashboard-->
   </div>
   <!--end::Container-->
</div>
<div class="container text-center my-3">
    @if(!empty($informations))
    @foreach ($informations->images as $image)
    <div class="row">
        <div class="col-md-12 my-3">
        <img src="{{ asset('uploads') }}/{{ $image->image_3 ?? ' '}}" class="rounded float-center"  style="height: 100px !important; width: 600px !important;">
        </div>
        <div class="col-md-12 my-3">
        <img src="{{ asset('uploads') }}/{{ $image->image_4 ?? ''}}" class="rounded float-center" style="height: 100px !important;width: 600px !important;">
        </div>
    </div>
    @endforeach
@endif

@endsection
@section('stylesheets')
<style>
   @foreach($statuses as $status)
   .status{{$status->id}} svg g [fill] {
   -webkit-transition: fill 0.3s ease;
   transition: fill 0.3s ease;
   fill: {{$status->color}};
   }
   @endforeach
</style>
@endsection
